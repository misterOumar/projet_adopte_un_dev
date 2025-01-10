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
use App\Form\DeveloperRatingType;
use Knp\Component\Pager\PaginatorInterface;


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
    public function listDevelopers(Request $request, DeveloperRepository $developerRepository)
    {
        $developers = $developerRepository->findAll();
        return $this->render('developer/list.html.twig', [
            'devs' => $developers,
        ]);
    }



    #[Route('/devs/detail/{uuid}', name: 'app_dev_details')]
    public function details(string $uuid,Request $request, DeveloperRepository $developerRepository, EntityManagerInterface $entityManager,): Response
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
// -------------------------------

        


        return $this->render('developer/details.html.twig', [
            'developer' => $developer,
            'ratingDeveloper' => $ratingDeveloper,
            'form' => $form ? $form->createView() : null,

        ]);
    }
}
