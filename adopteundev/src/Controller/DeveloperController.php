<?php

namespace App\Controller;

use App\Repository\DeveloperRepository;
use App\Repository\CategorieRepository;
use App\Form\DeveloperFilterType;
use App\Entity\Developer;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DeveloperController extends AbstractController
{
    #[Route('/devs', name: 'app_dev_list')]
    public function listFiltered(Request $request, DeveloperRepository $developerRepository, CategorieRepository $categorieRepository,  PaginatorInterface $paginator): Response
    {

        $categories = $categorieRepository->findAll();
        $categoryChoices = [];
        foreach ($categories as $category) {
            $categoryChoices[$category->getNom()] = $category->getNom();
        }

        // Créer le formulaire avec les technologies et catégories récupérées
        $form = $this->createForm(DeveloperFilterType::class, null, [
            'categories' => $categoryChoices,
        ]);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        // Récupérer les filtres du formulaire
        $filters = $form->getData() ?? [];

        // Appliquer les filtres à la requête de recherche des développeurs
        $queryBuilder = $developerRepository->createQueryBuilder('d')
            ->leftJoin('d.cat', 'c')
            ->where('1=1');

        if (!empty($filters['valeur'])) {
            $queryBuilder->andWhere(
                'd.nom LIKE :valeur OR d.prenom LIKE :valeur OR d.experience LIKE :valeur OR d.salaire LIKE :valeur'
            )
            ->setParameter('valeur', '%'.$filters['valeur'].'%');
        }

        if (!empty($filters['categorie'])) {
            $queryBuilder->andWhere('c.nom = :categorie')
                ->setParameter('categorie', $filters['categorie']);
        }

        if (!empty($filters['experience'])) {
            $queryBuilder->andWhere('d.experience >= :experience')
                ->setParameter('experience', $filters['experience']);
        }

        if (!empty($filters['salaireMin'])) {
            $queryBuilder->andWhere('d.salaire >= :salaireMin')
                ->setParameter('salaireMin', $filters['salaireMin']);
        }

        // Appliquer la pagination
        $pagination = $paginator->paginate(
            $queryBuilder, 
            $request->query->getInt('page', 1), // Page actuelle (par défaut page 1)
            5 // Nombre d'éléments par page
        );

        
        return $this->render('developer/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    
    }


    #[Route('/developer/{uuid}', name: 'app_dev_details')]
    public function details(string $uuid, DeveloperRepository $developerRepository, EntityManagerInterface $entityManager, ): Response
    {
        if (!$this->getUser()) {
            
            return $this->redirectToRoute('app_login');
        }
        $developer = $entityManager->getRepository(Developer::class)
            ->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$developer) {
            throw $this->createNotFoundException('Développeur non trouvé');
        }

        return $this->render('developer/details.html.twig', [
            'developer' => $developer,
        ]);
    }
}
