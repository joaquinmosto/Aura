<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Document;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/person")]
class PersonController extends AbstractController
{
    // esto todavia no funciona porque pensaba que iba a poder pasar solo ids en en la 
    // request pero claramente no, no tenia sentido pero lo hice drogado, asique tenemos que cambiar este metodo 
    // teniendo en cuenta que vamos a recibir el json de persona  con otros jsons andntro para documentos y address.
    #[Route("/created", name: "person_created", methods: ['POST'])]
    public function createdProduct(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $jsonData = $request->getContent();

        $person = $serializer->deserialize($jsonData, Person::class, 'json', 
            [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
            ]
        );

        $entityManager->persist($person);
        $entityManager->flush();

        return new JsonResponse(
            [
                'status'  => "Person created successfully!",
            ], 
        JsonResponse::HTTP_CREATED);
    }
}
