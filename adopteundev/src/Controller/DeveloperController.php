<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Form\DeveloperProfileType;
use App\Repository\DeveloperRepository;
use App\Services\FichierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/developer', name: 'app_developer_')]
class DeveloperController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository, private FichierService $fichierService) {}

    #[Route('/profile', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $developer = $this->developerRepository->findOneBy(['user' => $user]);
        if (!$developer) {
            throw $this->createNotFoundException('Aucun profil de développeur associé à cet utilisateur.');
        }
        $email = $developer->getUser()->getEmail();
        // dd($email);
        $form = $this->createForm(DeveloperProfileType::class, $developer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $user = $developer->getUser();

            // avatar
            $avatarFile = $form->get('avatar')->getData();
            // dd($form);
            if ($avatarFile) {

                // Suppression de l'ancien avatar s'il existe
                $oldAvatarReference = $user->getAvatar()?->getReference();
                if (!empty($oldAvatarReference)) {
                    $this->fichierService->deleteUserAvatar($oldAvatarReference);
                }

                // Sauvegarde du nouveau avatar
                $fichier = $this->fichierService->saveUserAvatar($avatarFile);
                $entityManager->persist($fichier);
                $user->setAvatar($fichier);
            }

            // Mise à jour du profil
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
        }  

        return $this->render('developer/profile_dev.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
