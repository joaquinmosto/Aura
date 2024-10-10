<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StateController extends AbstractController
{
    #[Route('/state', name: 'app_state')]
    public function index(): Response
    {
        return $this->render('state/index.html.twig', [
            'controller_name' => 'StateController',
        ]);
    }
}
