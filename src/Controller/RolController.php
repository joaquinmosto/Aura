<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RolController extends AbstractController
{
    #[Route('/rol', name: 'app_rol')]
    public function index(): Response
    {
        return $this->render('rol/index.html.twig', [
            'controller_name' => 'RolController',
        ]);
    }
}
