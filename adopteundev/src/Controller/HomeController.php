<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\CompanyRepository;
use App\Repository\DeveloperRepository;
use App\Repository\PosteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        PosteRepository $posteRepository,
        CategorieRepository $categorieRepository,
        UserRepository $userRepository,
        CompanyRepository $companyRepository,
        DeveloperRepository $developerRepository,
    ): Response
    {
        $user = $this->getUser();

        $mostViewedDevelopers = [];
        $recentDevelopers = [];
        $mostPopularPosts = [];
        if ($user && $this->isGranted('ROLE_COMPANY')) {
            // Logique spécifique pour les entreprises
            //$mostViewedDevelopers = $developerRepository->findBy([], ['views' => 'DESC'], 3);
            $recentDevelopers = $developerRepository->findBy([], ['id' => 'DESC'], 3);
        }
        $date = new \DateTimeImmutable('-2 days');
        $date=$date->format('Y-m-d H:i:s');
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'postes'=> $posteRepository->findBy( [],['id' => 'desc'],3) ,
            'topCategories' => $categorieRepository -> findTopCategoriesByPostCount(),
            'totalUsers' => $userRepository->count([]),
            'totalCompanies' => $companyRepository->count([]),
            'totalDevelopers' => $developerRepository->count([]),
            'recentPosts' => $posteRepository->count(['createdAt' => ['gte' => $date]]),
            'mostViewedDevelopers' => $mostViewedDevelopers,
            'recentDevelopers' => $recentDevelopers,
            'mostPopularPosts' => $mostPopularPosts,
            'userRole' => $user ? $user->getRoles()[0] : null, // Récupère le rôle principal de l'utilisateur
        ]);
    }
}
