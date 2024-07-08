<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class CartService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }


    public function addProduct(Cart $cart, Product $product, int $amount): void
    {
        $cartProduct = $this->getCartProduct($cart, $product);

        if ($cartProduct === null) {
            $cartProduct = $this->createCartProduct($cart, $product);
            $cart->addCartProduct($cartProduct);
        }

        $cartProduct->setProductAmount($cartProduct->getProductAmount() + $amount);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    public function removeProduct(Cart $cart, Product $product): void
    {
        $cartProduct = $this->getCartProduct($cart, $product);

        $cart->removeCartProduct($cartProduct);
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
    }

    private function getCartProduct(Cart $cart, Product $product): ?CartProduct
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('product', $product));
        $cartProducts = $cart->getCartProducts()->matching($criteria);

        return $cartProducts->isEmpty() ? null : $cartProducts->first();
    }

    private function createCartProduct(Cart $cart, Product $product): CartProduct
    {
        return (new CartProduct())
            ->setProduct($product)
            ->setCart($cart)
            ->setProductAmount(0);
    }
}