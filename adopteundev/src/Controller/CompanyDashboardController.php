<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyDashboardController extends AbstractController
{
   

    #[IsGranted('ROLE_COMPANY')]
    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function index(): Response
    {
        return $this->render('company/dashboard.html.twig', [
            'controller_name' => 'CompanyDashboardController',
        ]);
    }
}
