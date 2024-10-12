<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route("/created_product", name: "product", methods: ['POST'])]
    public function createdProduct(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $jsonData = $request->getContent();
        // REVISAR!!!!
        //cuando deserializa para setear la entidad e pierde el booleano de is_featured entonces siempre lo guarda como null 
        $product = $serializer->deserialize($jsonData, Product::class, 'json', 
            [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );

        $requestData = json_decode($jsonData, true);

        if (!isset($requestData['created_at'])) {
            $product->setCreatedAt(new \DateTimeImmutable());
        }
        
        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(
            [
                'status' => 'Product created successfully!',
                'product' => 
                [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'description' => $product->getDescription(),
                    'image' => $product->getImage(),
                    'is_featured' => $product->isFeatured(),
                    'created_at' => $product->getCreatedAt() ? $product->getCreatedAt()->format('Y-m-d H:i:s') : null
                ]
            ], 
        JsonResponse::HTTP_CREATED);
    }

    // #[Route("/login", name: "login", methods: ['POST'])]
    // public function login(Request $request, UserPasswordHasherInterface $passwordHasher, UserProviderInterface $userProvider): Response
    // {
    //     $data = json_decode($request->getContent(), true);

    //     $username = $data['email'] ?? null;
    //     $password = $data['password'] ?? null;

    //     if (!$username || !$password) {
    //         return new JsonResponse(['error' => "Invalid credentials"], Response::HTTP_BAD_REQUEST);
    //     }

    //     try {
    //         $user = $userProvider->loadUserByIdentifier($username);

    //         if (!$user instanceof PasswordAuthenticatedUserInterface) {
    //             throw new \LogicException('El usuario no implementa PasswordAuthenticatedUserInterface.');
    //         }

    //         if ($passwordHasher->isPasswordValid($user, $password)) {
    //             return new JsonResponse(
    //                 [
    //                     'message' => "User logged in successfully",
    //                     'username' => $username
    //                 ],
    //                 Response::HTTP_OK
    //             );
    //         } else {
    //             return new JsonResponse(
    //                 ['error' => "Invalid credentials"],
    //                 Response::HTTP_UNAUTHORIZED
    //             );
    //         }
            
    //     } catch (UserNotFoundException $e) {
    //         return new JsonResponse(
    //             ['error' => "Invalid credentials"],
    //             Response::HTTP_UNAUTHORIZED
    //         );
    //     }
    // }

    #[Route("/product/{id}", name: "product_show", methods: ['GET'])]
    public function findById(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->findById($id);

        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($product->toArray(), Response::HTTP_OK);
    }

    #[Route("/products", name: "product_all", methods: ['GET'])]
    public function findAll(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAllProducts();

        $productsArray = array_map(fn($product) => $product->toArray(), $products);

        return new JsonResponse($productsArray, Response::HTTP_OK);
    }

    #[Route("/products/featured", name: "product_featured", methods: ['GET'])]
    public function findFeatured(ProductRepository $productRepository): JsonResponse
    {
        $featuredProducts = $productRepository->findFeaturedProducts();

        $productsArray = array_map(fn($product) => $product->toArray(), $featuredProducts);

        return new JsonResponse($productsArray, Response::HTTP_OK);
    }

    #[Route("/product/edit", name: "product_edit", methods: ['PUT'])]
    public function editProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($data["id"]);

        if (! $product) {
            return new JsonResponse(['error' => 'Product not exist0'], Response::HTTP_NOT_FOUND);
        }

        try {
            $serializer->deserialize($request->getContent(), Product::class, 'json', ['object_to_populate' => $product]);
        } catch (NotEncodableValueException $e) {
            return new JsonResponse(['error' => 'Invalid data provided'], Response::HTTP_BAD_REQUEST);
        }
    
        $entityManager->flush();
        return new JsonResponse(['status' => 'Product updated successfully!'], Response::HTTP_OK);
    }

    #[Route("/product/delete/{id}", name: "product_delete", methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
    $product = $productRepository->findById($id);

    if (!$product) {
        return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
    }

    $entityManager->remove($product);
    $entityManager->flush();

    return new JsonResponse(['status' => 'Product deleted successfully!'], Response::HTTP_OK);
    }

}
