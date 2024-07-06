<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, CartProduct>
     */
    #[ORM\OneToMany(targetEntity: CartProduct::class, mappedBy: 'cart', orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct()
    {
        $this->cartProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, CartProduct>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addProduct(Product $product, int $amount): static
    {
        $cartProduct = $this->getCartProduct($product);

        if ($cartProduct === null) {
            $cartProduct = $this->createCartProduct($product);
            $this->cartProducts->add($cartProduct);
        }

        $cartProduct->setProductAmount($cartProduct->getProductAmount() + $amount);

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $cartProduct = $this->getCartProduct($product);

        if ($this->cartProducts->removeElement($cartProduct)) {
            if ($cartProduct->getCart() === $this) {
                $cartProduct->setCart(null);
            }
        }

        return $this;
    }

    private function getCartProduct(Product $product): ?CartProduct
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('product', $product));
        $cartProducts = $this->cartProducts->matching($criteria);

        return $cartProducts->isEmpty() ? null : $cartProducts->first();
    }

    private function createCartProduct(Product $product): CartProduct
    {
        return (new CartProduct())
            ->setProduct($product)
            ->setCart($this)
            ->setProductAmount(0);
    }
}
