<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;

#[Route("/api/messages", name: "messages.")]
class MessageController extends AbstractController
{
    private const ATTRIBUTES_TO_SERIALIZE = ['id', 'content', 'createdAt', 'mine'];

    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;
    private Security $security;

    public function __construct(Security $security, EntityManagerInterface $entityManager, MessageRepository $messageRepository) {
        $this->entityManager = $entityManager;
        $this->messageRepository = $messageRepository;
        $this->security = $security;
    }

    #[Route("/{id}", name: "getMessages", methods: ['GET'])]
    public function index(Conversation $conversation): JsonResponse
    {
        /* $this->denyAccessUnlessGranted('view', $conversation); */

        $messages = $this->messageRepository->findMessageByConversationId($conversation->getId());

        array_walk($messages, function ($message) {
            $message->setMine(
                $message->getUser()->getId() === $this->security->getUser()
            );
        });

        return $this->json($messages, Response::HTTP_OK, [], [
            'attributes' => self::ATTRIBUTES_TO_SERIALIZE,
        ]);
    }

    #[Route("/{id}", name: "newMessage", methods: ['POST'])]
    public function newMessage(Request $request, ManagerRegistry $doctrine, ConversationRepository $conversationRepository, $id): JsonResponse
    {
        $user = $this->security->getUser();

        $message = new Message();

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        if (!isset($decoded->content)) {
            throw new \Exception('Message content is missing');
        }

        $message->setContent($decoded->content);
        $message->setUser($user);
        $message->setMine(true);
        $message->prePersist(new \DateTime());

        $conversation = $conversationRepository->find($id);

        if (!$conversation) {
            throw new \Exception('Conversation not found');
        }

        $conversation->addMessage($message);
        $conversation->setLastMessage($message);

        $em->persist($message);
        $em->persist($conversation);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'New message added successfully'
        ];

        return new JsonResponse($responseData);
    }




}
