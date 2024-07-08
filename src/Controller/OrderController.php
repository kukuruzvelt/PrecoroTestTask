<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/order')]
class OrderController extends AbstractController
{
    public function __construct(
        private OrderService $orderService,
    )
    {
    }

    #[Route('/', name: 'app_orders', methods: ['GET'])]
    public function index(): Response
    {
        $orders = $this->getUser()->getOrders();

        return $this->render('order/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/create', name: 'app_order_create', methods: ['POST'])]
    public function create(): Response
    {
        $cart = $this->getUser()->getCart();
        $this->orderService->addProductsFromCart($cart);

        return $this->redirectToRoute('app_orders');
    }
}