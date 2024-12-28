<?php

namespace App\Controller;

use App\Entity\Developer;
use App\Form\DeveloperRegistrationType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/register')]
class DeveloperRegistrationController extends AbstractController
{
    #[Route('', name: 'app_register')]
    public function register()
    {
        return $this->render('security/pre-register.html.twig');
    }
    #[Route('/developer', name: 'app_developer_registration')]

    public function registerDeveloper(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, EmailService $emailService): Response
    {
        $dev = new Developer();
        $form = $this->createForm(DeveloperRegistrationType::class, $dev);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $user = $dev->getUser();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_DEV']);

            $entityManager->persist($dev);
            $entityManager->flush();

            $emailService->sendConfirmationEmail($user);

            $this->addFlash('success', 'Votre inscription a été effectuée avec succès. Veuillez vérifier votre boîte de réception pour activer votre compte.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('developer_registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
