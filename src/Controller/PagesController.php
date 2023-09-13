<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


class PagesController extends AbstractController
{
    #[Route(
        '/pages',
        name: 'page_index',
    )]
    public function index(): Response
    {
        $val = 3;
        return $this->render('index.html.twig', compact('val'));
    }

    #[Route(
        '/{page}',
        name: 'page_show',
    )]
    public function show($page, Environment $twig): Response
    {
        if (!$twig->getLoader()->exists("pages/{$page}.html.twig")) {
            throw $this->createNotFoundException('Page Not Found');
        }
        return $this->render("pages/{$page}.html.twig");
    }
}
