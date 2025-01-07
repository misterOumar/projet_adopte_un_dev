<?php

namespace App\Controller;

use App\Repository\DeveloperRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeveloperDashboardController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository){}
    
    #[Route('/developer/dashboard', name: 'app_developer_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        $developer = $this->developerRepository->findOneBy(['user' => $user]);
        return $this->render('developer/dashboard_dev.html.twig', [
            'developer' => $developer,
        ]);
    }
}
