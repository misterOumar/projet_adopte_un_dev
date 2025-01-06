<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\CompanyRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PosteController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository, private PosteRepository $posteRepository) {}

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/new', name: 'app_add_poste')]
    public function addPoste(Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteFormType::class, $poste);
        $form->handleRequest($request);

        // Récupérer la `Company` actuellement connectée ou liée
        $user = $this->getUser();
        $company = $companyRepository->findOneBy(['user' => $user]);

        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setCompany($company);

            // Enregistrer dans la base de données
            $entityManager->persist($poste);
            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été créé avec succès.');

            return $this->redirectToRoute('app_company_poste_list');
        }
        return $this->render('company/add_poste.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/list', name: 'app_company_poste_list')]
    public function companyPosteList()
    {
        // Récupérer la `Company` actuellement connectée ou liée
        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        //recuperer tous postes de la company connectée
        $postes = $company->getPostes();
        return $this->render('company/poste_list.html.twig', ['postes' => $postes, 'company' => $company]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/details/{uuid}', name: 'app_company_poste_details')]
    public function companyPosteDetail(string $uuid)
    {
        $poste = $this->posteRepository->findOneBy(['uuid' => $uuid]);

        if (!$poste) {
            throw $this->createNotFoundException('Poste introuvable');
        }
        // dd($poste);
        return $this->render('company/poste_details.html.twig', ['poste' => $poste]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/edit/{uuid}', name: 'app_company_poste_edit')]
    public function posteEdit(string $uuid, Request $request, EntityManagerInterface $entityManager): Response
    {
        $poste = $this->posteRepository->findOneBy(['uuid' => $uuid]);

        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);

        if ($poste->getCompany() !== $company) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce poste.');
        }

        $form = $this->createForm(PosteFormType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setModifiedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été mis à jour avec succès.');

            return $this->redirectToRoute('app_company_poste_list');
        }

        return $this->render('company/edit_poste.html.twig', [
            'form' => $form->createView(),
            'poste' => $poste,
        ]);
    }

    #[Route('/postes', name: 'app_poste_list')]
    public function posteList(Request $request, EntityManagerInterface $entityManager): Response
    {
        //recupération des catégories associés à un poste
        $categories = $entityManager->createQuery(
            'SELECT c.id, c.nom, COUNT(p.id) AS nbPostes FROM App\Entity\Categorie c LEFT JOIN c.postes p GROUP BY c.id'
        )->getResult();

        // récupération des type de poste
        $types = $entityManager->createQuery(
            'SELECT p.type, COUNT(p.id) AS nbPostes
             FROM App\Entity\Poste p
             GROUP BY p.type'
        )->getResult();
        // Récupérer la sélection de la période via les paramètres GET
        $selectedDateFilter = $request->query->get('dateFilter', null);

        // Calculer la plage de dates en fonction de la sélection
        $now = new \DateTimeImmutable();
        $startDate = null;

        switch ($selectedDateFilter) {
            case 'today':
                $startDate = $now->setTime(0, 0, 0); // Début de la journée
                break;
            case 'yesterday':
                $startDate = $now->modify('-1 day')->setTime(0, 0, 0); // Début d'hier
                break;
            case 'week':
                $startDate = $now->modify('-1 week'); // Début de la semaine dernière
                break;
            case 'month':
                $startDate = $now->modify('-1 month'); // Début du mois dernier
                break;
        }

        // Requête pour récupérer les postes
        $queryBuilder = $entityManager->getRepository(Poste::class)->createQueryBuilder('p');

        if ($startDate) {
            $queryBuilder
                ->andWhere('p.createdAt >= :startDate')
                ->setParameter('startDate', $startDate);
        }

        // $postes = $queryBuilder->getQuery()->getResult();

        // Compter les postes pour chaque période
        $countByDate = [
            'today' => $entityManager->getRepository(Poste::class)->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.createdAt >= :startOfDay')
                ->setParameter('startOfDay', $now->setTime(0, 0, 0))
                ->getQuery()
                ->getSingleScalarResult(),
            'yesterday' => $entityManager->getRepository(Poste::class)->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.createdAt >= :startOfYesterday')
                ->andWhere('p.createdAt < :startOfToday')
                ->setParameter('startOfYesterday', $now->modify('-1 day')->setTime(0, 0, 0))
                ->setParameter('startOfToday', $now->setTime(0, 0, 0))
                ->getQuery()
                ->getSingleScalarResult(),
            'week' => $entityManager->getRepository(Poste::class)->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.createdAt >= :startOfWeek')
                ->setParameter('startOfWeek', $now->modify('-1 week'))
                ->getQuery()
                ->getSingleScalarResult(),
            'month' => $entityManager->getRepository(Poste::class)->createQueryBuilder('p')
                ->select('COUNT(p.id)')
                ->where('p.createdAt >= :startOfMonth')
                ->setParameter('startOfMonth', $now->modify('-1 month'))
                ->getQuery()
                ->getSingleScalarResult(),
        ];
        $postes = $this->posteRepository->findAll();
        return $this->render('poste/poste_liste.html.twig', ['postes' => $postes, 'categories' => $categories, 'types' => $types, 'countByDate' => $countByDate,]);
    }

    // #[IsGranted('ROLE_DEV')]
    #[Route('/poste/details/{uuid}', name: 'app_poste_details')]
    public function posteDetail(string $uuid)
    {
        $poste = $this->posteRepository->findOneBy(['uuid' => $uuid]);
        if (!$this->getUser()) {
            // Redirige vers la page de connexion si non connecté
            return $this->redirectToRoute('app_login');
        }
        if (!$poste) {
            throw $this->createNotFoundException('Poste introuvable');
        }
        return $this->render('poste/poste_details.html.twig', ['poste' => $poste]);
    }
}
