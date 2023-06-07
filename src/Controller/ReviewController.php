<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/review/')]
class ReviewController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

/*     #[Route('/', name: 'review_index', methods: ['GET'])]
    public function getAllReview(ReviewRepository $reviewRepository, Request $request): JsonResponse
    {
        $review = $reviewRepository->search($request->query->get('searchText'), $request->query->get('searchCity'));
        return $this->json($review);
    } */

    #[Route('/{id}', name: 'review_find', methods: ['GET'])]
    public function getReview($vehicleId, ReviewRepository $reviewRepository): JsonResponse
    {
        $review = $reviewRepository->find($vehicleId);
        return $this->json($review);
    }

    #[Route('/new', name: 'review_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $userId = $user->getId();
        $review = new Review($userId);

        $vehicle = $this->security->getVehicle();
        $vehicleId = $vehicle->getId();
        $review = new Review($vehicleId);

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());


        if ($decoded->description != null) {
            $review->setDescription($decoded->description);
        }
        $review->setUserId($userId);
        $review->setVehicleId($vehicleId);

        $em->persist($review);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Review added successfully'
        ];

        return new JsonResponse($responseData);
    }


    #[Route('/{id}', name: 'review_show', methods: ['GET'])]
    public function show(Review $review): Response
    {
        return $this->render('review/show.html.twig', [
            'review' => $review,
        ]);
    }

    #[Route('/{id}', name: 'review_delete', methods: ['POST'])]
    public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('/');
    }
}
