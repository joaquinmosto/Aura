<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route("/register", name: "register", methods: ['POST'])]
    public function createdUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $email    = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if (!$email || !$password) {
                return new JsonResponse(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
            }

            $user = new User();
            $user->setEmail($email);

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(['message' => "User created successfully"], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route("/login", name: "login", methods: ['POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return new JsonResponse(['error' => "Invalid credentials"], Response::HTTP_BAD_REQUEST);
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            return new JsonResponse([
                'error' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse
        (
            [
                'message' => "User logged in successfully",
                'username' => $lastUsername
            ], 
        Response::HTTP_OK);
    }

    #[Route("/logout", name: "logout")]
    public function logout(): void
    {
    }
}
