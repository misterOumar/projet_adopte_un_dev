<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use Twig\Environment;

class EmailService
{

    public function __construct(
    
         private MailerInterface $mailer,
        private UrlGeneratorInterface $router,
        private Environment $twig,
        private array $fromEmail,
        private string $usePhpMail){}
    

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

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('coulibalyoumartc@gmail.com') // Adresse expéditrice
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }
}
