<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use Twig\Environment;

class EmailService
{

    public function __construct(
    
         private MailerInterface $mailer,
        private UrlGeneratorInterface $router,
        private Environment $twig){}
    

    /**
     * Permet de réinitialiser le mot de passe
     * 
     * @param User               $user
     * @param ResetPasswordToken $resetToken
     */
    public function sendPasswordResettingEmail(User $user, ResetPasswordToken $resetToken): void
    {
        $template = 'email/password_resetting.email.html.twig';

        $url = $this->router->generate('app_reset_password', ['token' => $resetToken->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);

        $context = ['userEmail' => $user->getEmail(), 'confirmationUrl' => $url, 'expiredAt' => $resetToken->getExpiresAt()->format('d/m/Y H:i:s')];

        $to = $user->getEmail();

        $subject = 'Réinitialisation du mot de passe!';

        $this->sendEmail($to, $subject, $template, $context);
    }

    /**
     * envoyer un email de confirmation après inscription
     * 
     * @param User $user
     */
    public function sendConfirmationEmail(User $user): void
    {
        $template = 'email/confirmation.email.html.twig';
        
        $url = $this->router->generate('app_verify_email', ['token' =>base64_encode($user->getEmail()),], UrlGeneratorInterface::ABSOLUTE_URL);
        
        $context = ['userEmail' => $user->getEmail(), 'activationLink' => $url];
        
        $to = $user->getEmail();
        
        $subject = 'Confirmation de votre adresse email!';
        
        $this->sendEmail($to, $subject, $template, $context);
    }

    public function sendEmail(string $to, string $subject, string $template, $context): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('adopteundeveloppeur@gmail.com', 'AdopteUnDev Bot'))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($email);
    }
}
