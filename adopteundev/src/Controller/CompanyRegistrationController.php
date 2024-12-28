<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyRegistrationType;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/register')]
class CompanyRegistrationController extends AbstractController
{
    #[Route('', name: 'app_register')]
    public function register(){
        return $this->render('security/pre-register.html.twig');
    }

    #[Route('/company', name: 'app_company_registration')]
    public function registerCompany(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, EmailService $emailService): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyRegistrationType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hacher le mot de passe
            $user = $company->getUser();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_COMPANY']);

            // Sauvegarder l'entité
            $entityManager->persist($company);
            $entityManager->flush();

            // envoyer un email de confirmation
            $emailService->sendConfirmationEmail($user);

            $this->addFlash('success', 'Votre inscription a été effectuée avec succès. Veuillez vérifier votre boîte de réception pour confirmer votre adresse email.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('company/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
