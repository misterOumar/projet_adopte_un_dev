<?php

namespace App\Controller;

use App\Form\CompanyProfileType;
use App\Form\CompanySettingType;
use App\Repository\CompanyRepository;
use App\Services\FichierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class CompanyProfileController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository, private FichierService $fichierService) {}
    #[Route('/company/profile', name: 'app_company_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            throw $this->createNotFoundException('Aucun profil de company associé à cet utilisateur.');
        }
        $form = $this->createForm(CompanyProfileType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $company->getUser();
            // avatar
            $avatarFile = $form->get('avatar')->getData();
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

        return $this->render('company/profile_company.html.twig', [
            'form' => $form->createView(),
            'company' => $company
        ]);
    }

    #[Route('/company/reglage', name: 'app_company_reglage')]
    public function reglage(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            throw $this->createNotFoundException('Aucun profil de company associé à cet utilisateur.');
        }
        $form = $this->createForm(CompanyProfileType::class, $company);
        $form->handleRequest($request);
        $user = $company->getUser();

        // formulaire pour le reglage de son profile
        $formSetting = $this->createForm(CompanySettingType::class, $company);
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
                    return $this->redirectToRoute('app_company_reglage');
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Réglages du profil mis à jour avec succès.');
        }

        return $this->render('company/reglage_company.html.twig', [
            'formSetting' => $formSetting->createView()
        ]);
    }
}
