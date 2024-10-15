<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

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
    public function login(Request $request, UserPasswordHasherInterface $passwordHasher, UserProviderInterface $userProvider, JWTTokenManagerInterface $JWTManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return new JsonResponse(['error' => "Invalid credentials"], Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $userProvider->loadUserByIdentifier($username);

            if (!$user instanceof PasswordAuthenticatedUserInterface) {
                throw new \LogicException('El usuario no implementa PasswordAuthenticatedUserInterface.');
            }

            if ($passwordHasher->isPasswordValid($user, $password)) {
                
                return new JsonResponse(
                    [
                        'message'   => "User logged in successfully",
                        'username'  => $username,
                        'token'     => $JWTManager->create($user)
                    ],
                    Response::HTTP_OK
                );
            } else {
                return new JsonResponse(
                    ['error' => "Invalid credentials"],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            
        } catch (UserNotFoundException $e) {
            return new JsonResponse(
                ['error' => "Invalid credentials"],
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
    
    #[Route("/logout", name: "logout")]
    public function logout(): void
    {
    }
}
