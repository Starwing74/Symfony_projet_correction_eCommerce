<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Security\OrderVoter;
use App\Services\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/order")]
class OrderController extends AbstractController {

	private OrderService $orderService;

	public function __construct(OrderService $orderService) {
		$this->orderService = $orderService;
	}

	#[Route("/add", name: "orders_add", methods: ["GET"])]
	public function addOrder(): Response
	{
		/** @var User $user */
		$user = $this->getUser();
		$order = $this->orderService->createFromCarteToUser($user);

		$this->addFlash('success', 'Commande passée !');

		return $this->redirectToRoute('orders_get', [
			'id' => $order->getId()
		]);
	}

	#[Route("/{id}", name: "orders_get", methods: ["GET"])]
	public function getById(Order $order): Response {
		$this->denyAccessUnlessGranted(OrderVoter::VIEW, $order);

		return $this->render('orders/get.html.twig', [
			'order' => $order
		]);
	}
}
