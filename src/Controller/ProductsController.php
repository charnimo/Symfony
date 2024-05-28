<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProductsController extends AbstractController
{
    #[Route('/products', name: 'user_products')]
    public function index(ProductsRepository $productsRepository): Response
    {
        // Check if the user has the 'ROLE_ADMIN'
        if ($this->isGranted('ROLE_ADMIN')) {
            // Redirect to another page if the user is an admin
            return $this->redirectToRoute('admin_home');
        }

        // Fetch all products if the user is not an admin
        $products = $productsRepository->findAll();

        // Render the products page
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
