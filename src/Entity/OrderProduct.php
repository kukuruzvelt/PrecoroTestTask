<?php

namespace App\Entity;

use App\Repository\OrderProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderProductRepository::class)]
class OrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $productAmount = null;

    #[ORM\Column]
    private ?int $fixedPrice = null;

    #[ORM\Column]
    private ?int $allProductsPrice = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'orderProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductAmount(): ?int
    {
        return $this->productAmount;
    }

    public function setProductAmount(int $productAmount): static
    {
        $this->productAmount = $productAmount;

        return $this;
    }

    public function getFixedPrice(): ?int
    {
        return $this->fixedPrice;
    }

    public function setFixedPrice(int $fixedPrice): static
    {
        $this->fixedPrice = $fixedPrice;

        return $this;
    }

    public function getAllProductsPrice(): ?int
    {
        return $this->allProductsPrice;
    }

    public function setAllProductsPrice(int $allProductsPrice): static
    {
        $this->allProductsPrice = $allProductsPrice;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
