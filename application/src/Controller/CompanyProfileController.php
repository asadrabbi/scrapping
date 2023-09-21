<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class CompanyProfileController extends AbstractController
{
    #[Route('/company', name: 'company_list')]
    public function index(): Response
    {
        $puppeteerApiUrl = 'http://data-scrapper-service:8686/scrape';

        // Create an HTTP client
        $httpClient = HttpClient::create();

        try {
            $response = $httpClient->request('GET', $puppeteerApiUrl);
            $data = $response->toArray();
            $title = $data['title'];

            return new Response('Company title: ' . $title);
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}