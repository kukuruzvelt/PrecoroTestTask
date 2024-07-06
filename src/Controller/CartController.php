<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/cart')]
class CartController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_cart', methods: ['GET'])]
    public function index(): Response
    {
        $cart = $this->getUser()->getCart();
        $cartProducts = $cart->getCartProducts();

        return $this->render('cart/cart.html.twig', [
            'cartProducts' => $cartProducts
        ]);
    }

    #[Route('/add', name: 'app_cart_add', methods: ['POST'])]
    public function add(Request $request): Response
    {
        $cart = $this->getUser()->getCart();
        $productId = $request->request->get('productId');
        $amount = $request->request->get('amount');

        $product = $this->productRepository->find($productId);

        if($product) {
            $cart->addProduct($this->productRepository->find($productId), $amount);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/remove', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(Request $request): Response
    {
        $cart = $this->getUser()->getCart();
        $productId = $request->request->get('productId');
        $product = $this->productRepository->find($productId);

        if($product){
            $cart->removeProduct($this->productRepository->find($productId));
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }
}