<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/review')]
class ReviewController extends AbstractController
{
    private $security;

    private function serializeReview(Review $review): array {
        return [
            'id' => $review->getId(),
            'user_id' => $review->getUser()->getId(),
            'vehicle_id' => $review->getVehicle()->getId(),
            'content' => $review->getContent(),
        ];
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/{id}', name: 'review_find', methods: ['GET'])]
    public function getReview($id, ReviewRepository $reviewRepository): JsonResponse
    {
        $review = $reviewRepository->find($id);
        $reviewData = $this->serializeReview($review);

        return $this->json($reviewData);
    }

    #[Route('/new', name: 'review_new', methods: ['POST'])]
    public function newReview(ManagerRegistry $doctrine, Request $request, Review $review): JsonResponse
    {
        $user = $this->security->getUser();
        //get vehicles missing!!!
        $vehicle = $review->getVehicle();
        $review = new Review();
        

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $review->setUser($user);
        $review->setVehicle($vehicle->getId());
        $review->setContent($decoded->content);
        dd($review);
        $em->persist($review);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Review added successfully'
        ];

        return new JsonResponse($responseData);

    }
}
