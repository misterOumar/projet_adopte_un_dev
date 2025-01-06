<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Form\DeveloperProfileType;
use App\Form\DevelopperSettingType;
use App\Repository\DeveloperRepository;
use App\Services\FichierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/developer', name: 'app_developer_')]
class DeveloperController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository, private FichierService $fichierService) {}

    #[Route('/profile', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
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
        
        // formulaire pour le reglage de son profile
        $formSetting = $this->createForm(DevelopperSettingType::class, $developer);
        $formSetting->handleRequest($request);

        if ($formSetting->isSubmitted() && $formSetting->isValid()) {
            // Récupérer les champs du mot de passe
            $currentPassword = $formSetting->get('currentPassword')->getData();
            $newPassword = $formSetting->get('plainPassword')->getData();


            if ($currentPassword) {
                // Vérification du mot de passe actuel
                if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                    if ($newPassword) {
                        // Hacher et mettre à jour le nouveau mot de passe
                        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                        $user->setPassword($hashedPassword);
                    }
                } else {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('app_developer_profile');
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Réglages du profil mis à jour avec succès.');
        }

        return $this->render('developer/profile_dev.html.twig', [
            'form' => $form->createView(),
            'formSetting' => $formSetting->createView(),
        ]);
    }

    // page candidature du dev
    #[Route('/candidature', name: 'candidature')]
    public function candidature(): Response
    {
        return $this->render('developer/candidature_dev.html.twig');
    }

    //postes favoris
    #[Route('/favoris', name: 'favoris')]
    public function favoris(): Response
    {
        return $this->render('developer/poste_favoris_dev.html.twig');
    }

}
