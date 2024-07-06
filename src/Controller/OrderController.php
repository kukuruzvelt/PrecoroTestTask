<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\OrderService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
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
        private EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/', name: 'app_orders', methods: ['GET'])]
    public function index(): Response
    {
        $user = $this->getUser();
        $orders = $user->getOrders();

        return $this->render('order/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/create', name: 'app_order_create', methods: ['POST'])]
    public function create(): Response
    {
        $user = $this->getUser();
        $cart = $user->getCart();
        $cartProducts = $cart->getCartProducts();
        $order = (new Order())
            ->setStatus(Order::STATUS_NEW)
            ->setUser($user)
            ->setDate(new DateTimeImmutable())
            ->setTotalPrice(0)
        ;

        foreach ($cartProducts as $cartProduct) {
            $product = $cartProduct->getProduct();
            $amount = $cartProduct->getProductAmount();
            $this->orderService->addProduct($order, $product, $amount);
            $cart->removeProduct($product);
        }

        $this->entityManager->persist($cart);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_orders');
    }
}