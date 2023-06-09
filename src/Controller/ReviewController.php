<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Vehicle;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/review')]
class ReviewController extends AbstractController
{
    private $security;
    private $entityManager;
    private $reviewRepository;

    public function __construct(Security $security, EntityManagerInterface $entityManager, ReviewRepository $reviewRepository)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->reviewRepository = $reviewRepository;
    }

    #[Route('/new', name: 'review_new', methods: ['POST'])]
    public function createReview(Request $request): JsonResponse
    {
        $userId = $this->security->getUser()->getId();
        $vehicleId = $request->request->get('vehicleId');
        $content = $request->request->get('content');

        $vehicle = $this->entityManager->getRepository(Vehicle::class)->find($vehicleId);

        if (!$vehicle) {
            return new JsonResponse(['error' => 'Vehicle not found.'], 404);
        }

        // Check if the user has already reviewed the vehicle
        $existingReview = $this->reviewRepository->findOneBy(['user' => $userId, 'vehicle' => $vehicleId]);

        if ($existingReview) {
            return new JsonResponse(['error' => 'User has already reviewed this vehicle.'], 400);
        }

        $review = new Review();
        $review->setUser($userId);
        $review->setVehicle($vehicle);
        $review->setContent($content);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Review created successfully.']);
    }

    #[Route('/{id}', name: 'review_edit', methods: ['PUT'])]
    public function updateReview($id, Request $request): JsonResponse
    {
        $userId = $this->security->getUser()->getId();
        $content = $request->request->get('content');

        $review = $this->reviewRepository->find($id);

        if (!$review) {
            return new JsonResponse(['error' => 'Review not found.'], 404);
        }

        // Check if the review belongs to the logged-in user
        if ($review->getUser() !== $userId) {
            return new JsonResponse(['error' => 'You are not authorized to update this review.'], 403);
        }

        $review->setContent($content);

        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Review updated successfully.']);
    }

    #[Route('/{id}', name: 'review_delete', methods: ['DELETE'])]
    public function deleteReview($id): JsonResponse
    {
        $userId = $this->security->getUser()->getId();

        $review = $this->reviewRepository->find($id);

        if (!$review) {
            return new JsonResponse(['error' => 'Review not found.'], 404);
        }

        // Check if the review belongs to the logged-in user
        if ($review->getUser() !== $userId) {
            return new JsonResponse(['error' => 'You are not authorized to delete this review.'], 403);
        }

        $this->entityManager->remove($review);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Review deleted successfully.']);
    }
}
