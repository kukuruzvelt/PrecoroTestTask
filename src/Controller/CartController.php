<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
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
        private CartService $cartService,
        private ProductRepository $productRepository,
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

        if ($product) {
            $this->cartService->addProduct($cart, $this->productRepository->find($productId), $amount);

            return new Response();
        }

        return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }

    #[Route('/remove', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(Request $request): Response
    {
        $cart = $this->getUser()->getCart();
        $productId = $request->request->get('productId');
        $product = $this->productRepository->find($productId);

        if ($product) {
            $this->cartService->removeProduct($cart, $this->productRepository->find($productId));

            return new Response();
        }

        return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }
}