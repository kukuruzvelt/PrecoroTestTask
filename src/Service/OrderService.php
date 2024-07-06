<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use Doctrine\Common\Collections\Criteria;

class OrderService
{
    public function addProduct(Order $order, Product $product, int $amount): void
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

        $totalPrice = 0;
        foreach ($order->getOrderProducts() as $orderProduct) {
            $totalPrice += $orderProduct->getProduct()->getPrice() * $orderProduct->getProductAmount();
        }
        $order->setTotalPrice($totalPrice);
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