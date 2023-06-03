<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

#[Route('/api/vehicle')]
class VehicleController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'vehicle_index', methods: ['GET'])]
    public function index(VehicleRepository $vehicleRepository): JsonResponse{
        /*$user = $this->security->getUser();
        $vehicles = $vehicleRepository->findBy(['user' => $user]);
    
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);*/

        $vehicles = $vehicleRepository->findAll();
        return new JsonResponse($vehicles);
    }

    #[Route('/new', name: 'vehicle_new', methods: ['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
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

        $em->persist($vehicle);
        $em->flush();

        $responseData = [
            'success' => true,
            'message' => 'Vehicle added successfully'
        ];

        return new JsonResponse($responseData);
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
