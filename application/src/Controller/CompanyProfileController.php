<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class CompanyProfileController extends AbstractController
{
    #[Route('/company', name: 'company_list')]
    public function index(): Response
    {
        $puppeteerServiceUrl = 'http://data-scrapper-service:8686';

        // Create an HTTP client
        $client = HttpClient::create();

        // Send a GET request to the Puppeteer service
        $response = $client->request('GET', $puppeteerServiceUrl);

        // Get the response content
        $content = $response->getContent();

        dd($content);
    }

}