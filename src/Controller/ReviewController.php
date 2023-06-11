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

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'review_index', methods: ['GET'])]
    public function getAllReviews(ReviewRepository $reviewRepository): JsonResponse
    {
        $reviews = $reviewRepository->findAll();

        $reviewsData = [];
        foreach ($reviews as $review) {
            $data = $this->serializeReview($review);
            $reviewsData[] = $data;
        }

        return $this->json($reviewsData);
    }

    #[Route('/{id}', name: 'review_find', methods: ['GET'])]
    public function getReview($id, ReviewRepository $reviewRepository): JsonResponse
    {
        $review = $reviewRepository->find($id);
        $reviewData = $this->serializeReview($review);

        return $this->json($reviewData);
    }
}
