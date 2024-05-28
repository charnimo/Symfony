<?php
namespace App\Controller;
use App\Entity\Users;
use App\Entity\Facture;
use App\Entity\Item;
use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function add(Products $product, SessionInterface $session)
    {
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
    public function checkout(Request $request, SessionInterface $session, ProductsRepository $productsRepository,EntityManagerInterface $entityManager): JsonResponse
    {
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            return $this->json(['error' => 'Your cart is empty.'], 400);
        }

        $total = 0;
        $user=$this->getUser();
        $data = json_decode($request->getContent(), true);

        $facture=new Facture();
        $facture->setSubmissionDate(new \DateTime($data['expiry_date']));
        
        $facture->setUser($user);
        
        $facture->setCardNumber((int)$data['card_number']);
        
        $facture->setExpiryDate(new \DateTime('2025-01-01'));
        $facture->setCvv((int)$data['cvv']);
        $description=" ";
        
        
        // Calculate total of cart products
        foreach ($panier as $id => $quantity) {
            $product = $productsRepository->find($id);
            

            if ($product) {
                $total += $product->getPrice() * $quantity;

                $description=$description . " " . $product->getName() . ":" . $quantity;
                /*
                $item=new Item();
                $item->setFactureId($facture->getId());
                $item->setQuantite($quantity);
                $item->setProductId($product->getId());
                $entityManager->persist($item);
                */
            }
            
        }
        
        $facture->setDescription($description);
        $facture->setTotal((int)$total);
        $entityManager->persist($facture);
        $entityManager->flush();
        

        // Simulate successful payment
        $session->remove('panier'); // Clear the cart after successful payment

        return $this->json(['success' => true, 'total' => $total]);
    }
}
?>