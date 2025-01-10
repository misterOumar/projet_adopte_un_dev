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
    public function __construct(private DeveloperRepository $developerRepository) {}
    #[Route('/devs', name: 'app_dev_list')]
    public function listFiltered(Request $request, DeveloperRepository $developerRepository, CategorieRepository $categorieRepository, PaginatorInterface $paginator): Response
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

        $form->handleRequest($request);

        $filters = $form->getData() ?? [];

        // Récupérer les paramètres de tri
        $sortField = $request->query->get('sortField', 'd.nom'); // Par défaut, trier par nom
        $sortDirection = $request->query->get('sortDirection', 'ASC'); // Par défaut, ordre croissant

        // Appliquer les filtres à la requête de recherche des développeurs
        $queryBuilder = $developerRepository->createQueryBuilder('d')
            ->leftJoin('d.cat', 'c')
            ->where('1=1');

        if (!empty($filters['valeur'])) {
            $queryBuilder->andWhere(
                'd.nom LIKE :valeur OR d.prenom LIKE :valeur OR d.experience LIKE :valeur OR d.salaire LIKE :valeur'
            )
                ->setParameter('valeur', '%' . $filters['valeur'] . '%');
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
            $queryBuilder->andWhere('d.salaireMin >= :salaireMin')
                ->setParameter('salaireMin', $filters['salaireMin']);
        }

        $queryBuilder->orderBy($sortField, $sortDirection);

        // Appliquer la pagination
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1), // Page actuelle (par défaut page 1)
            5 // Nombre d'éléments par page
        );

        return $this->render('developer/list.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'filters' => $filters,
        ]);
    }



    #[Route('/devs/details/{uuid}', name: 'app_dev_details')]
    public function details(string $uuid, DeveloperRepository $developerRepository, EntityManagerInterface $entityManager,): Response
    {
        $developer = $developerRepository->findOneBy(['uuid' => $uuid]);

        if (!$developer) {
            throw $this->createNotFoundException('Developer introuvable');
        }

        if (!$this->getUser()) {

            return $this->redirectToRoute('app_login');
        }

        return $this->render('developer/details.html.twig', [
            'developer' => $developer,
        ]);
    }
}
