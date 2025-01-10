<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\DeveloperRepository;
use App\Repository\PosteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeveloperDashboardController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository) {}

    #[Route('/developer/dashboard', name: 'app_developer_dashboard')]
    public function index(PosteRepository $posteRepository, CandidatureRepository $candidatureRepository): Response
    {
        $user = $this->getUser();
        $developer = $this->developerRepository->findOneBy(['user' => $user]);

        // Récupérer les suggestions de postes
        $suggestedPosts = $posteRepository->findSuggestionsForDeveloper($developer);

        // Récupérer les candidatures associées triées par date décroissante
        $candidatures = $candidatureRepository->findBy(
            ['developer' => $developer],
            ['date' => 'DESC']
        );

        return $this->render('developer/dashboard_dev.html.twig', [
            'developer' => $developer,
            'suggestedPosts' => $suggestedPosts,
            'candidatures' => $candidatures, // Liste des candidatures associées
        ]);
    }
}
