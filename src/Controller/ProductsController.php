<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    #[Route('/product', name: 'user_products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
