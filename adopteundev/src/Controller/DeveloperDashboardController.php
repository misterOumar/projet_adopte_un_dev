<?php

namespace App\Controller;

use App\Repository\DeveloperRepository;
use App\Repository\PosteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeveloperDashboardController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository){}
    
    #[Route('/developer/dashboard', name: 'app_developer_dashboard')]
    public function index(PosteRepository $posteRepository): Response
    {
        $user = $this->getUser();
        $developer = $this->developerRepository->findOneBy(['user' => $user]);
        $suggestedPosts = $posteRepository->findSuggestionsForDeveloper($developer);
        
        return $this->render('developer/dashboard_dev.html.twig', [
            'developer' => $developer,
            'suggestedPosts' => $suggestedPosts,
        ]);
    }
}
