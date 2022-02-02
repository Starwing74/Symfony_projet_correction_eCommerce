<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/products")]
class ProductsController extends AbstractController {

	private ProductRepository $productRepository;

	private CartService $cartService;

	public function __construct(ProductRepository $productRepository, CartService $cartService) {
		$this->productRepository = $productRepository;
		$this->cartService = $cartService;
	}

	#[Route("/", name: "products_index", methods: ["GET"])]
	public function index(): Response {
		return $this->render('products/index.html.twig', [
			'products' => $this->productRepository->findAll()
		]);
	}

	#[Route("/{id}", name: "products_get", methods: ["GET"])]
	public function getById(Product $product): Response {
		return $this->render('products/get.html.twig', [
			'product' => $product
		]);
	}

	#[Route("/{id}/cart/add", name: "products_add_to_cart", methods: ["GET"])]
	public function addToCartById(Product $product): Response {
		$this->cartService->addProductToCart($product);

		$this->addFlash('success', 'Produit ' . $product->getName() . ' ajouté au panier !');

		return $this->redirectToRoute('cart_index');
	}

    #[Route("/{id}/cart/delete", name: "products_delete_from_cart", methods: ["GET"])]
	public function deleteFromCartById(Product $product): Response {
		$this->cartService->removeProductFromCart($product);

		$this->addFlash('success', 'Un produit ' . $product->getName() . ' enlevé du panier !');

		return $this->redirectToRoute('cart_index');
	}
}
