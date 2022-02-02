<?php

namespace App\Services;

use App\Entity\OrderLine;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

	private RequestStack $requestStack;

	private ProductRepository $productRepository;

	public function __construct(RequestStack $requestStack, ProductRepository $productRepository) {
		$this->requestStack      = $requestStack;
		$this->productRepository = $productRepository;
	}

	public function addProductToCart(Product $product): void {
		$cart = $this->getCart();
		$cart[] = $product->getId();
		$this->saveCart($cart);
	}

	public function removeProductFromCart(Product $product): void {
		$cart = $this->getCart();
		$index = array_search($product->getId(), $cart, true);
		if ($index === false) {
			throw new Exception('Produit introuvable dans le panier');
		}

		unset($cart[$index]);
		$this->saveCart($cart);
	}

	public function clearCart(): void {
		$this->saveCart([]);
	}

	/**
	 * @return OrderLine[]
	 */
	public function getProductsAsOrderLineInCart(): array {
		$cart = $this->getCart();
		$products = $this->productRepository->getProductsFromIds($cart);

		/** @var OrderLine[] $lines */
		$lines = [];

		foreach ($cart as $cartProductId) {
			if (isset($lines[$cartProductId])) {
				$lines[$cartProductId]->setQuantity($lines[$cartProductId]->getQuantity() + 1);
			} else {
				$lines[$cartProductId] = new OrderLine(array_values(array_filter($products, static function(Product $product) use ($cartProductId) {
					return $product->getId() === $cartProductId;
				}))[0]);
			}
		}

		return $lines;
	}

	public function getTotal(): float {
		$total = 0;
		$lines = $this->getProductsAsOrderLineInCart();

		foreach ($lines as $line) {
			$total += $line->getTotal();
		}

		return $total;
	}

	/**
	 * @return int[]
	 */
	private function getCart(): array {
		return $this->requestStack->getSession()->get('cart', []);
	}

	private function saveCart(array $cart): void {
		$this->requestStack->getSession()->set('cart', $cart);
	}
}
