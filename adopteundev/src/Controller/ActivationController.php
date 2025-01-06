<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ActivationController extends AbstractController
{
    #[Route('activate/{token}', name: 'app_verify_email')]
    public function activate(string $token, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $email = base64_decode($token);
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('error', 'Lien invalide.');
            return $this->redirectToRoute('app_home');
        }

        $user->setActive(true);
        $em->flush();

        $this->addFlash('success', 'Votre compte a été activé !');
        return $this->redirectToRoute('app_login');
    }
}
