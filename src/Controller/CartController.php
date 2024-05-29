<?php
namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'cart_index')]
    public function index(SessionInterface $session, ProductsRepository $productsRepository)
    {
        $panier = $session->get('panier', []);

        $data = [];
        $total = 0;

        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }

        return $this->render('cart/index.html.twig', compact('data', 'total'));
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function add(Products $product, SessionInterface $session, Security $security)
    {

        if (!$security->isGranted('ROLE_USER')) {
            // Redirect the user to the sign-in page
            return new RedirectResponse($this->generateUrl('/signin'));
        }

        $id = $product->getId();
        $panier = $session->get('panier', []);

        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(Products $product, SessionInterface $session)
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function delete(Products $product, SessionInterface $session)
    {
        $id = $product->getId();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/empty', name: 'cart_empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/checkout', name: 'cart_checkout', methods: ['POST'])]
    public function checkout(Request $request, SessionInterface $session, ProductsRepository $productsRepository): JsonResponse
    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            return $this->json(['error' => 'Your cart is empty.'], 400);
        }

        $total = 0;

        // Calculate total of cart products
        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);
            if ($product) {
                $total += $product->getPrice() * $quantity;
            }
        }
        
        // Simulate successful payment
        $session->remove('panier'); // Clear the cart after successful payment

        return $this->json(['success' => true, 'total' => $total]);
    }
}
?>