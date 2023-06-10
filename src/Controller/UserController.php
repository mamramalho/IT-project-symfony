<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/api/users')]
class UserController extends AbstractController
{

    #[Route('/', name: 'user_index', methods: ['GET'])]
    public function getAllVehicles(UserRepository $userRepository, Request $request): JsonResponse
    {
        $users = $userRepository->findAll();

        $usersData = [];
        foreach ($users as $user) {
            $data = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
            ];
            $usersData[] = $data;
        }

        return $this->json($usersData);
    }

    #[Route('/{id}', name: 'user_find', methods: ['GET'])]
    public function getUserWithId($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ];

        return $this->json($userData);
    }

}
