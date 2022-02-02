<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController {
	private ProductRepository $productRepository;

	public function __construct(ProductRepository $productRepository) {
		$this->productRepository = $productRepository;
	}

    #[Route("/", name: "index", methods: ["GET"])]
	public function index(): Response {
		return $this->render('index/index.html.twig', [
			'products' => $this->productRepository->getLast(3)
		]);
	}
}
