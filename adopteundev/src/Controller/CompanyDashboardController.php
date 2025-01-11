<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Repository\CandidatureRepository;
use App\Repository\CompanyRepository;
use App\Repository\PostViewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyDashboardController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository) {}
    #[IsGranted('ROLE_COMPANY')]
    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function index(EntityManagerInterface $entityManager, CandidatureRepository $candidatureRepository, PostViewRepository $postViewRepository): Response
    {
        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);

        // Récupérer les candidatures liées aux postes de la société
        $candidatures = $candidatureRepository->findByCompany($company);

            $totalCandidature = $candidatureRepository->countCandidaturesByCompany($company);
            $totalCandidatureAccepted = $candidatureRepository->findAcceptedByCompany($company);
            $totalView = $postViewRepository->countViewsByCompany($company);
        return $this->render('company/dashboard.html.twig', [
            'company' => $company,'candidatures' => $candidatures,
            'totalCandidature' => $totalCandidature,
            'totalView' => $totalView,
            'totalCandidatureAccepted' => $totalCandidatureAccepted,

        ]);
    }
}
