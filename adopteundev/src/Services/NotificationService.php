<?php

namespace App\Services;

use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class NotificationService
{
    private EntityManagerInterface $entityManager;
    private $hub;

    public function __construct(EntityManagerInterface $entityManager, HubInterface $hub)
    {
        $this->entityManager = $entityManager;
        $this->hub = $hub;
    }

    /**
     * Créer une notification pour un utilisateur
     */
    public function createNotification(User $user, string $message, string $type)
    {
        $notification = new Notification();
        $notification->setUser($user);
        $notification->setMessage($message);
        $notification->setType($type);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        // Publier la notification via Mercure
        $update = new Update(
            "notifications/{$user->getId()}",
            json_encode([
                'message' => $message,
                'type' => $type,
                'createdAt' => (new \DateTime())->format('Y-m-d H:i:s'),
            ])
        );

        // $this->hub->publish($update);

        // return $notification;

        

    }
}
