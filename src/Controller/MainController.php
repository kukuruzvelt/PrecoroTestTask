<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class MainController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    #[Route('/', name: 'app_main', methods: ['GET'])]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('main/main.html.twig', [
            'products' => $products
        ]);
    }
}