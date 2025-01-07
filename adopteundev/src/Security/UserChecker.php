<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // vérifier si l'utilisateur est bloqué
        if ($user->isBloqued()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException("Votre compte a été bloqué. Contactez-nous sur support.adopteundev@gmail.com pour plus d'infos. ");
        }

        // vérifier si l'utilisateur est inactif
        if (!$user->isActive()) {
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Veuillez activer votre compte. Penser à vérifier votre boîte de réception pour le lien de validation.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}