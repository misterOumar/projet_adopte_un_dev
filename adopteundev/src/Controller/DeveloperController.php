<?php

namespace App\Controller;


use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\CategorieRepository;
use App\Form\DeveloperFilterType;
use App\Entity\Developer;
use App\Entity\DeveloperRating;
use App\Entity\DeveloperView;
use App\Form\DeveloperRatingType;
use App\Repository\CompanyRepository;
use App\Repository\DeveloperViewRepository;
use App\Repository\TechnologieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeveloperController extends AbstractController
{


    //    #[Route('/devs', name: 'app_dev_list')]
    //     public function listFiltered(Request $request, DeveloperRepository $developerRepository, CategorieRepository $categorieRepository,  PaginatorInterface $paginator): Response
    //     {

    //         $categories = $categorieRepository->findAll();
    //         $categoryChoices = [];
    //         foreach ($categories as $category) {
    //             $categoryChoices[$category->getNom()] = $category->getNom();
    //         }

    //         // Créer le formulaire avec les technologies et catégories récupérées
    //         $form = $this->createForm(DeveloperFilterType::class, null, [
    //             'categories' => $categoryChoices,
    //         ]);

    //         // Traiter la soumission du formulaire
    //         $form->handleRequest($request);

    //         // Récupérer les filtres du formulaire
    //         $filters = $form->getData() ?? [];

    //         // Appliquer les filtres à la requête de recherche des développeurs
    //         $queryBuilder = $developerRepository->createQueryBuilder('d')
    //             ->leftJoin('d.cat', 'c')
    //             ->where('1=1');

    //         if (!empty($filters['valeur'])) {
    //             $queryBuilder->andWhere(
    //                 'd.nom LIKE :valeur OR d.prenom LIKE :valeur OR d.experience LIKE :valeur OR d.salaire LIKE :valeur'
    //             )
    //             ->setParameter('valeur', '%'.$filters['valeur'].'%');
    //         }

    //         if (!empty($filters['categorie'])) {
    //             $queryBuilder->andWhere('c.nom = :categorie')
    //                 ->setParameter('categorie', $filters['categorie']);
    //         }

    //         if (!empty($filters['experience'])) {
    //             $queryBuilder->andWhere('d.experience >= :experience')
    //                 ->setParameter('experience', $filters['experience']);
    //         }

    //         if (!empty($filters['salaireMin'])) {
    //             $queryBuilder->andWhere('d.salaire >= :salaireMin')
    //                 ->setParameter('salaireMin', $filters['salaireMin']);
    //         }

    //         // Appliquer la pagination
    //         $pagination = $paginator->paginate(
    //             $queryBuilder, 
    //             $request->query->getInt('page', 1), // Page actuelle (par défaut page 1)
    //             10 // Nombre d'éléments par page
    //         );


    //         return $this->render('developer/list.html.twig', [
    //             'developers' => $pagination->getItems(),
    //             'form' => $form->createView(),
    //             'pagination' => $pagination,
    //         ]);

    //     }

    #[Route('/devs', name: 'app_dev_list')]
    public function listDevelopers(Request $request, DeveloperRepository $developerRepository, CategorieRepository $categorieRepository, TechnologieRepository $technologieRepository, PaginatorInterface $paginator): Response
    {
        // recuperer les filtres
        // Récupérer les paramètres de la requête
        $categorie_filtre = $request->query->get('category');
        $technos_filtre = $request->query->get('technos');
        $experience_filtre = $request->query->get('experience',);
        $salaryMin_filtre = $request->query->get('salaire');

        // construire la querybuilder
        $queryBuilder = $developerRepository->createQueryBuilder('d');

        if ($categorie_filtre) {
            $queryBuilder->andWhere('d.cat = :category')
            ->setParameter('category', $categorie_filtre);
        }

        // filtrer un dev en fonction de son experience
        if ($experience_filtre) {
            $queryBuilder->andWhere('d.experience >= :experience')
            ->setParameter('experience', $experience_filtre);
        }

        // filtrer un dev en fonction de sa collection de technologies
        if ($technos_filtre) {
            $queryBuilder->join('d.technologie', 't')
            ->andWhere('t.id IN (:techno)')
            ->setParameter('techno', $technos_filtre);
        }

        // filtrer un dev en fonction de son salaire minimum
        if ($salaryMin_filtre) {
            $queryBuilder->andWhere('d.salaireMin >= :salaireMin')
            ->setParameter('salaireMin', $salaryMin_filtre);
        }


        // pour le filtre
        //recupération des catégories associés à un poste
        $categories = $categorieRepository->findCategorieWithPosts();
        $technos = $technologieRepository->findTechnologiesWithDevelopers();



        $developers = $queryBuilder->getQuery()->getResult();
        return $this->render('developer/list.html.twig', [
            'devs' => $developers,
            'categories' => $categories,
            'technos' => $technos,
        ]);
    }



    #[Route('/devs/detail/{uuid}', name: 'app_dev_details')]
    public function details(string $uuid,Request $request,CompanyRepository $companyRepository, DeveloperRepository $developerRepository, EntityManagerInterface $entityManager, DeveloperViewRepository $developerViewRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour accéder au detail du dev');
            return $this->redirectToRoute('app_login');
        }

        $developer = $developerRepository->findOneBy(['uuid' => $uuid]);            

        if (!$developer) {
            $this->addFlash('error', 'Développeur non trouvé');
            return $this->redirectToRoute('app_dev_list');
        }

        $ratedDeveloper = $developer; // developpeur noté

        $ratingDeveloper = $developerRepository->findOneBy(['user' => $user]); // developpeur noté par l'utilisateur
        // Empêcher un développeur de s'auto-évaluer
        if ($ratingDeveloper === $developer) {
            $form = null;
        } else {
            $existingRating = $entityManager->getRepository(DeveloperRating::class)
                ->findOneBy(['rateDeveloper' => $developer, 'ratingDeveloper' => $ratingDeveloper]);

            $developerRating = $existingRating ?? new DeveloperRating();
            $developerRating->setRateDeveloper($developer);
            $developerRating->setRatingDeveloper($ratingDeveloper);

            $form = $this->createForm(DeveloperRatingType::class, $developerRating);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($developerRating);
                $entityManager->flush();

                $this->addFlash('success', 'Votre évaluation a été enregistrée.');
                return $this->redirectToRoute('app_dev_details', ['uuid' => $ratedDeveloper->getUuid()]);
            }
        }
        
        // Vu de profile 
        // Vérifier si l'utilisateur a déjà vu ce poste
        $existingView = $developerViewRepository->findOneBy([
            'developer' => $developer,
            'user' => $user,
        ]);
        
        // vérificier que l'utilisateur n'a pas encore vu le profile et qu'il s'agit pas du dev lui même
        if (!$existingView && $developer !== $ratingDeveloper ) {
            $developerView = new DeveloperView();
            $developerView->setDeveloper($developer);
            $developerView->setUser($user);
            
            $entityManager->persist($developerView);
            $entityManager->flush();

        }
        
        $company = $companyRepository->findOneBy(['user' => $user]); // developpeur noté par l'utilisateur
        // dd($company);

        
        return $this->render('developer/details.html.twig', [
            'company' => $company,
            'developer' => $developer,
            'ratingDeveloper' => $ratingDeveloper,
            'form' => $form ? $form->createView() : null,

        ]);
    }

    #[Route('/filter-developers', name: 'filter_developers', methods: ['GET'])]
    public function filterDevelopers(Request $request, DeveloperRepository $developerRepository): JsonResponse
    {
        // Récupérer les paramètres de la requête
        $categorie = $request->query->get('categorie');
        $experience = $request->query->get('experience',);
        $salaryMin = $request->query->get('salary_min', 0);
        $salaryMax = $request->query->get('salary_max', 100000);

        // Construire la requête avec le repository
        $developers = $developerRepository->findByFilters2($categorie, $experience, $salaryMin, $salaryMax);

        // Rendu d'une vue partielle ou retour JSON des résultats
        return $this->json([
            'html' => $this->renderView('developer/dev_list_partial.html.twig', [
                'devs' => $developers,
            ]),
        ]);
    }
}
