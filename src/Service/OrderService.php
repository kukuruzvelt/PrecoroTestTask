<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function addProductsFromCart(Cart $cart): void
    {
        $cartProducts = $cart->getCartProducts();
        $order = (new Order())
            ->setStatus(Order::STATUS_NEW)
            ->setUser($cart->getUser())
            ->setDate(new DateTimeImmutable())
            ->setTotalPrice(0)
        ;

        foreach ($cartProducts as $cartProduct) {
            $product = $cartProduct->getProduct();
            $amount = $cartProduct->getProductAmount();
            $this->addProduct($order, $product, $amount);
            $cart->removeCartProduct($cartProduct);
        }

        $this->entityManager->persist($cart);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    private function addProduct(Order $order, Product $product, int $amount): void
    {
        $orderProduct = $this->getOrderProduct($order, $product);

        if ($orderProduct === null) {
            $orderProduct = $this->createOrderProduct($order, $product);
            $order->addOrderProduct($orderProduct);
        }

        $orderProduct->setProductAmount($orderProduct->getProductAmount() + $amount);
        $orderProduct->setFixedPrice($product->getPrice());

        $allProductsPrice = $product->getPrice() * $orderProduct->getProductAmount();
        $orderProduct->setAllProductsPrice($allProductsPrice);

        $order->setTotalPrice(
            $order->getTotalPrice() +
            $orderProduct->getProduct()->getPrice() * $orderProduct->getProductAmount()
        );
    }

    private function getOrderProduct(Order $order, Product $product): ?OrderProduct
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('product', $product));
        $orderProducts = $order->getOrderProducts()->matching($criteria);

        return $orderProducts->isEmpty() ? null : $orderProducts->first();
    }

    private function createOrderProduct(Order $order, Product $product): OrderProduct
    {
        return (new OrderProduct())
            ->setProduct($product)
            ->setOrder($order)
            ->setProductAmount(0);
    }
}