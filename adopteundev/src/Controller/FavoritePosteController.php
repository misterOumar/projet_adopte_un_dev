<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Repository\DeveloperRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoritePosteController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository) {}

    #[Route('/favorites/add-poste/{uuid}', name: 'app_favorite_poste_add', methods: ['POST'])]
    public function addFavoritePoste(string $uuid, PosteRepository $posteRepository, EntityManagerInterface $entityManager): Response
    {
        $poste = $posteRepository->findOneBy(['uuid' => $uuid]);

        if (!$poste) {
            throw $this->createNotFoundException('Poste non trouvé.');
        }

        $user = $this->getUser();

        $developer = $this->developerRepository->findOneBy(['user' => $user]);

        if (!$developer->getFavorites()->contains($poste)) {
            $developer->addFavorite($poste);
            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été ajouté à vos favoris.');
        }

        return $this->redirectToRoute('app_developer_dashboard');
    }


    #[Route('/favorites/remove-poste/{uuid}', name: 'app_favorite_poste_remove', methods: ['POST'])]
    public function removeFavoritePoste(string $uuid, PosteRepository $posteRepository, EntityManagerInterface $entityManager): Response
    {
        $poste = $posteRepository->findOneBy(['uuid' => $uuid]);

        if (!$poste) {
            throw $this->createNotFoundException('Poste non trouvé.');
        }

        $user = $this->getUser();

        $developer = $this->developerRepository->findOneBy(['user' => $user]);

        if ($developer->getFavorites()->contains($poste)) {
            $developer->removeFavorite($poste);
            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été retiré de vos favoris.');
        }

        return $this->redirectToRoute('app_developer_dashboard');
    }
}
