<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EarningsController extends AbstractController
{
    #[Route('/earnings', name: 'app_earnings')]
    public function index(): Response
    {
        return $this->render('earnings/index.html.twig', [
            'controller_name' => 'EarningsController',
        ]);
    }
}
