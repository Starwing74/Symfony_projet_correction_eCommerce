<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;

class OrderService {

	private CartService $cartService;

	private OrderRepository $orderRepository;

	public function __construct(CartService $cartService, OrderRepository $orderRepository) {
		$this->cartService = $cartService;
		$this->orderRepository = $orderRepository;
	}

	public function createFromCarteToUser(User $user): Order {
		$order = new Order($this->cartService->getProductsAsOrderLineInCart(), $user);
		$this->orderRepository->save($order);
		$this->cartService->clearCart();
		return $order;
	}
}
