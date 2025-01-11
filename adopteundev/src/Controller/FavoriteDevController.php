<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoriteDevController extends AbstractController
{
    #[Route('/favorite/add-dev/{uuid}', name: 'app_favorite_dev_add', methods:['POST'])]
    public function addFavoriteDev(string $uuid, DeveloperRepository $developerRepository, CompanyRepository $companyRepository, EntityManagerInterface $entityManager): Response
    {
       $developer = $developerRepository->findOneBy(['uuid' => $uuid]);

        if (!$developer) {
            return $this->addFlash('warning', 'Développeur introuvable, veuillez réessayer plus tard !');
            return $this->redirectToRoute('app_home');
        }

        $company = $companyRepository->findOneBy(['user' => $this->getUser()]);

        if (!$company->getDeveloperSaved()->contains($developer)) {
            $company->addDeveloperSaved($developer);
            $entityManager->flush();
            $this->addFlash('success', 'Développeur ajouté à vos favoris');
        }

      
        return $this->redirectToRoute('app_dev_list');
    }


    #[Route('/favorite/remove-dev/{uuid}', name: 'app_favorite_dev_remove', methods:['POST'])]
    public function removeFavoriteDev(string $uuid, DeveloperRepository $developerRepository, CompanyRepository $companyRepository, EntityManagerInterface $entityManager): Response
    {
       $developer = $developerRepository->findOneBy(['uuid' => $uuid]);

        if (!$developer) {
            return $this->addFlash('warning', 'Développeur introuvable, veuillez réessayer plus tard !');
            return $this->redirectToRoute('app_home');
        }

        $company = $companyRepository->findOneBy(['user' => $this->getUser()]);

        if ($company->getDeveloperSaved()->contains($developer)) {
            $company->removeDeveloperSaved($developer);
            $entityManager->flush();
            $this->addFlash('success', 'Développeur retiré à vos favoris');
        }
        return $this->redirectToRoute('app_dev_list');
    }

    
}
