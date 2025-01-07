<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyDashboardController extends AbstractController
{
   public function __construct(private CompanyRepository $companyRepository){}
    #[IsGranted('ROLE_COMPANY')]
    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        return $this->render('company/dashboard.html.twig', [
            'company' => $company,
        ]);
    }
}
