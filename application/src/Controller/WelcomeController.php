<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/',
    name: 'welcome_'
)]
class WelcomeController extends AbstractController
{
    #[Route(
        '',
        name: 'index',
    )]
    public function index(): Response
    {
        $val = random_int(0, 100);

        return $this->render('index.html.twig', compact('val'));
    }

    #[Route(
        '/api',
        name: 'api',
    )]
    public function show(): Response
    {
        return $this->json(['name' => 'ampm', 'age' => 35]);
    }
}
