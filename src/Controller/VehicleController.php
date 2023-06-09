<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/vehicle')]
class VehicleController extends AbstractController
{
    private $security;

    private function serializeVehicle(Vehicle $vehicle): array {
        return [
            'id' => $vehicle->getId(),
            'name' => $vehicle->getName(),
            'company' => $vehicle->getCompany(),
            'type' => $vehicle->getType(),
            'model' => $vehicle->getModel(),
            'year' => $vehicle->getYear(),
            'registerYear' => $vehicle->getRegisterYear(),
            'price' => $vehicle->getPrice(),
            'description' => $vehicle->getDescription(),
            'color' => $vehicle->getColor(),
            'fuel' => $vehicle->getFuel(),
            'plate' => $vehicle->getPlate(),
            'kms' => $vehicle->getKms(),
            'images' => $vehicle->getImages(),
            'city' => $vehicle->getCity(),
            'userId' => $vehicle->getUser()->getId(),
        ];
    }

    private function serializeReview(Review $review): array {
        return [
            'id' => $review->getId(),
            'userEmail' => $review->getUser()->getEmail(),
            'vehicle_id' => $review->getVehicle()->getId(),
            'content' => $review->getContent(),
        ];
    }

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'vehicle_index', methods: ['GET'])]
    public function getAllVehicles(VehicleRepository $vehicleRepository, Request $request): JsonResponse
    {
        $vehicles = $vehicleRepository->search($request->query->get('searchText'), $request->query->get('searchCity'));
        $vehiclesData = [];

        foreach ($vehicles as $vehicle) {
            $data = $this->serializeVehicle($vehicle);
            $vehiclesData[] = $data;
        }

        return $this->json($vehiclesData);
    }

    #[Route('/my', name: 'vehicle_my', methods: ['GET'])]
    public function getMyVehicles(VehicleRepository $vehicleRepository, Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        $vehicles = $vehicleRepository->search(null, null, $user);
        $vehiclesData = [];

        foreach ($vehicles as $vehicle) {
            $data = $this->serializeVehicle($vehicle);
            $vehiclesData[] = $data;
        }

        return $this->json($vehiclesData);
    }

    #[Route('/{id}', name: 'vehicle_find', methods: ['GET'])]
    public function getVehicle($id, VehicleRepository $vehicleRepository): JsonResponse
    {
        $vehicle = $vehicleRepository->find($id);
        $vehicleData = $this->serializeVehicle($vehicle);

        return $this->json($vehicleData);
    }


    #[Route('/new', name: 'vehicle_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        $vehicle = new Vehicle();

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $vehicle->setName($decoded->name);
        $vehicle->setCompany($decoded->company);
        $vehicle->setType($decoded->type);
        $vehicle->setModel($decoded->model);
        $vehicle->setYear($decoded->year);
        $vehicle->setRegisterYear($decoded->registerYear);
        $vehicle->setPrice($decoded->price);
        if ($decoded->description != null) {
            $vehicle->setDescription($decoded->description);
        }
        if ($decoded->color != null) {
            $vehicle->setColor($decoded->color);
        }
        $vehicle->setFuel($decoded->fuel);
        $vehicle->setPlate($decoded->plate);
        $vehicle->setKms($decoded->kms);
        $vehicle->setImages($decoded->images);
        $vehicle->setCity($decoded->city);
        $vehicle->setUser($user);

        $em->persist($vehicle);

        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Vehicle added successfully'
        ];

        return new JsonResponse($responseData);
    }

    

    #[Route('/{id}/reviews/new', name: 'vehicle_add_review', methods: ['POST'])]
    public function newVehicleReview($id, ManagerRegistry $doctrine, VehicleRepository $vehicleRepository, Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        $vehicle = $vehicleRepository->find($id);

        $review = new Review();

        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $review->setUser($user);
        $review->setVehicle($vehicle);
        $review->setContent($decoded->content);

        $em->persist($review);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Review added successfully'
        ];

        return new JsonResponse($responseData);
    }

    #[Route('/{id}/reviews', name: 'vehicle_reviews', methods: ['GET'])]
    public function getVehicleReviews($id, VehicleRepository $vehicleRepository): JsonResponse
    {
        $vehicle = $vehicleRepository->find($id);
        $reviews = $vehicle->getReviews();

        $reviewsSerialized = [];
        foreach ($reviews as $review) {
            $data = $this->serializeReview($review);
            $reviewsSerialized[] = $data;
        }

        return $this->json($reviewsSerialized);
    }

    #[Route('/{id}', name: 'vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    #[Route('/{id}/edit', name: 'vehicle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('vehicle_index');
        }

        return $this->render('vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'vehicle_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicle $vehicle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vehicle_index');
    }
}
