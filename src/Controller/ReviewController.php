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

    #[Route('/new', name: 'review_new', methods: ['POST'])]
    public function newReview(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        //get vehicles missing!!!
        $review = new Review();

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $review->setContent($decoded->content);
        $review->setUser($user);
        $review->setVehicle($vehicle);

        $em->persist($review);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Review added successfully'
        ];

        return new JsonResponse($responseData);

    }
}
