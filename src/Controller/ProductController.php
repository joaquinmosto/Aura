<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\EncryptionService;
use App\Service\MailerService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/product")]
class ProductController extends AbstractController
{
    private EncryptionService $encryptionService; 
    private MailerService $mailService; 
    private EntityManagerInterface $entityManager;
    private UserService $userService;

    public function __construct( EncryptionService $encryptionService, MailerService $mailService, EntityManagerInterface $entityManager, UserService $userService )
    { 
        $this->encryptionService = $encryptionService;
        $this->mailService = $mailService;
        $this->entityManager = $entityManager; 
        $this->userService = $userService;
    }

    #[Route("/created", name: "created", methods: ['POST'])]
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
            $product->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Argentina/Buenos_Aires')));
        }
        
        $entityManager->persist($product);
        $entityManager->flush();

        $encryptedId = $this->encryptionService->encrypt($product->getId());

        $qrCode = Builder::create()->writer(new PngWriter())->data($encryptedId)->build();
        $qrPath = '/qr/qr_code_' . $product->getId() . '.png'; $qrCode->saveToFile($qrPath);

        $qrImageContent = base64_encode(file_get_contents($qrPath)); 
        $qrImageBase64 = 'data:image/png;base64,' . $qrImageContent; 
        $emailBody = '<html><body><p>¡Hola! Aquí tienes el código QR con el que podras ingresar.</p><img src="' . $qrImageBase64 . '"></body></html>';
        
        $this->mailService->sendHtmlEmailWithAttachment( 
            $this->userService->getUserData()->getEmail(),
            'Confirmacion De Compra', 
            $emailBody, 
            $qrPath 
        );

        return new JsonResponse(
            [
                'status'  => "Product created successfully!",
                'product' => 
                [
                    'id'          => $product->getId(),
                    'name'        => $product->getName(),
                    'description' => $product->getDescription(),
                    'image'       => $product->getImage(),
                    'is_featured' => $product->isFeatured(),
                    'created_at'  => $product->getCreatedAt() ? $product->getCreatedAt()->format('Y-m-d H:i:s') : null
                ]
            ], 
        JsonResponse::HTTP_CREATED);
    }

    #[Route("/{id}", name: "product_show", methods: ['GET'])]
    public function findById(int $id, ProductRepository $productRepository): JsonResponse
    {
        if (! $productRepository->find($id)) {
            return new JsonResponse(['error' => "Product not found"], Response::HTTP_NOT_FOUND);
        }
        // Fichar el toArray, me suena raro que no se toJson o algo por el estilo
        return new JsonResponse($productRepository->find($id)->toArray(), Response::HTTP_OK);
    }

    #[Route("/all", name: "product_all", methods: ['GET'])]
    public function findAll(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        $productsArray = array_map(fn($product) => $product->toArray(), $products);

        return new JsonResponse($productsArray, Response::HTTP_OK);
    }

    #[Route("/featured", name: "product_featured", methods: ['GET'])]
    public function findProductByFeatured(ProductRepository $productRepository): JsonResponse
    {
        $featuredProducts = $productRepository->findFeaturedProducts();

        $productsArray = array_map(fn($product) => $product->toArray(), $featuredProducts);

        return new JsonResponse($productsArray, Response::HTTP_OK);
    }

    #[Route("/edit", name: "product_edit", methods: ['PUT'])]
    public function editProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $product = $productRepository->find($data["id"]);

        if (! $product) {
            return new JsonResponse(['error' => "Product not exist"], Response::HTTP_NOT_FOUND);
        }

        try {
            $serializer->deserialize($request->getContent(), Product::class, "json", ['object_to_populate' => $product]);
        } catch (NotEncodableValueException $e) {
            return new JsonResponse(['error' => "Invalid data provided"], Response::HTTP_BAD_REQUEST);
        }
    
        $entityManager->flush();
        return new JsonResponse(['status' => "Product updated successfully!"], Response::HTTP_OK);
    }

    #[Route("/delete/{id}", name: "product_delete", methods: ['DELETE'])]
    public function deleteProduct(int $id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if (! $product) {
            return new JsonResponse(['error' => "Product not found"], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['status' => "Product deleted successfully!"], Response::HTTP_OK);
    }
}