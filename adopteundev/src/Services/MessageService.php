<?php

namespace App\Services;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    public function __construct(

        private EntityManagerInterface $entityManager
    ) {}

    /**
     *  envoyer un nouveau message    
     * @param User $sender
     * @param User $receiver
     * @param string $content
     * @return message    
     */
    public function sendNewMessage(User $sender, User $receiver, string $content): Message
    {
        $message = new Message();
        $message->setSender($sender);
        $message->setReceiver($receiver);
        $message->setContent($content);
        $message->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
}
