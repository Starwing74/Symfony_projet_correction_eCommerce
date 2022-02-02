<?php

namespace App\Controller;

use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/cart")]
class CartController extends AbstractController {

	private CartService $cartService;

	public function __construct(CartService $cartService) {
		$this->cartService = $cartService;
	}

    #[Route("/", name: "cart_index", methods: ["GET"])]
	public function index(): Response {
		return $this->render('cart/index.html.twig', [
			'lines' => $this->cartService->getProductsAsOrderLineInCart(),
			'total' => $this->cartService->getTotal()
		]);
	}

    #[Route("/clear", name: "cart_clear", methods: ["GET"])]
	public function clear(): Response {
		$this->cartService->clearCart();

		$this->addFlash('success', 'Panier vidé !');

		return $this->redirectToRoute('cart_index');
	}
}
