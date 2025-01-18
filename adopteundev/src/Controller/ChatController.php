<?php

namespace App\Controller;

use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    #[Route('/chat/{id}', name: 'chat', methods: ['GET', 'POST'])]
    public function chat(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        MessageRepository $messageRepository,
        UserRepository $userRepository
    ): Response {
        $currentUser = $this->getUser();
        $receiver = $userRepository->find($id);

        if (!$receiver) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        // Récupérer les messages
        $messages = $messageRepository->findByChat($currentUser, $receiver);

        // Enregistrer un nouveau message
        if ($request->isMethod('POST')) {
            $messageContent = $request->request->get('message');
            if ($messageContent) {
                $message = new Message();
                $message->setSender($currentUser)
                    ->setReceiver($receiver)
                    ->setContent($messageContent);

                $em->persist($message);
                $em->flush();

                return $this->redirectToRoute('chat', ['id' => $id]);
            }
        }

        return $this->render('chat/index.html.twig', [
            'receiver' => $receiver,
            'messages' => $messages,
        ]);
    }
}
