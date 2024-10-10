<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DocumentTypeController extends AbstractController
{
    #[Route('/document/type', name: 'app_document_type')]
    public function index(): Response
    {
        return $this->render('document_type/index.html.twig', [
            'controller_name' => 'DocumentTypeController',
        ]);
    }
}
