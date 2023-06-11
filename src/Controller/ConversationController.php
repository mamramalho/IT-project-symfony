<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route("/api/conversation", name: "conversation")]
class ConversationController extends AbstractController
{
    private ConversationRepository $conversationRepository;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private $security;

    public function __construct(Security $security,ConversationRepository $conversationRepository, UserRepository $userRepository, EntityManagerInterface $entityManager
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    #[Route("/new", name: "newConversation", methods: ["POST"])]
public function newConversation(Request $request): JsonResponse
{
    $user = $this->getUser();
    if (!$user) {
        throw new \Exception('User not authenticated');
    }

    $otherUserId = (int) $request->request->get('otherUserId');
    if (!$otherUserId) {
        throw new \Exception('Other user ID is missing');
    }

    $otherUser = $this->userRepository->find($otherUserId);
    if (!$otherUser) {
        throw new \Exception('Other user not found');
    }

    $conversation = $this->conversationRepository->findConversationByParticipants($user->getId(), $otherUser->getId());
    if ($conversation) {
        // Conversation already exists, handle accordingly
        $conversationId = $conversation[0]['conversationId'];
        $responseData = [
            'success' => true,
            'conversationId' => $conversationId,
        ];
    } else {
        $conversation = new Conversation();
        
        // Set conversation participants
        $conversation->addParticipant($user);
        $conversation->addParticipant($otherUser);
        
        // Set any other necessary data for the conversation
        
        $this->entityManager->persist($conversation);
        $this->entityManager->flush();

        $responseData = [
            'success' => true,
            'conversationId' => $conversation->getId(),
        ];
    }

    return $this->json($responseData, Response::HTTP_OK);
}


    #[Route("/", name: "getConversations", methods: ["GET"])]
    public function getConversations(): JsonResponse
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new \Exception('User not authenticated');
        }
    
        $conversations = $this->conversationRepository->findConversationsByUser($user->getId());
    
        return $this->json($conversations, Response::HTTP_OK);
    }
    
    

}