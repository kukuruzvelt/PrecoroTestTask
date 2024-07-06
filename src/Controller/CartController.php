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
        private CartRepository $cartRepository,
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_cart')]
    public function index(): Response
    {

    }

    #[Route('/add', name: 'app_cart_add')]
    public function add(Request $request): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $productId = $request->request->get('productId');
        $amount = $request->request->get('amount');

        $cart->addProduct($this->productRepository->find($productId), $amount);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        return new Response();
    }

    #[Route('/remove', name: 'app_cart_remove')]
    public function remove(): Response
    {

    }
}