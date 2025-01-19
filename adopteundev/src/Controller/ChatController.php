<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ChatController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}


  

    /**
     * envoyer un nouveau message
     */
    #[Route('/chat/send', name: 'chat_send', methods: ['POST'])]
    public function sendMessage(Request $request, MessageService $messageService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // recipient id string to int 
        $recipientId = (int) $data['recipientId'];

        $recipient = $this->userRepository->find($recipientId);
        if (!$recipient) {
            return new JsonResponse(['error' => 'Recipient not found'], 404);
        }

        $message = $messageService->sendNewMessage($this->getUser(), $recipient, $data['content']);

        return new JsonResponse([
            'success' => true,
            'message' => [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'sender' => $message->getSender()->getId(),
                'receiver' => $message->getReceiver()->getId(),
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    #[Route('/chat/{id}', name: 'chat')]
    public function chat(
        int $id,
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

        // // Enregistrer un nouveau message
        // if ($request->isMethod('POST')) {
        //     $messageContent = $request->request->get('message');
        //     if ($messageContent) {
        //         $message = new Message();
        //         $message->setSender($currentUser)
        //             ->setReceiver($receiver)
        //             ->setContent($messageContent);

        //         $em->persist($message);
        //         $em->flush();

        //         return $this->redirectToRoute('chat', ['id' => $id]);
        //     }
        // }

        return $this->render('chat/index.html.twig', [
            'receiver' => $receiver,
            'messages' => $messages,
        ]);
    }
}
