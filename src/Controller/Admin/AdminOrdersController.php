<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/orders")]
class AdminOrdersController extends AbstractController
{
	private OrderRepository $orderRepository;

	public function __construct(OrderRepository $orderRepository) {
		$this->orderRepository = $orderRepository;
	}

	#[Route("", "admin_orders_list")]
	public function index(): Response {
		return $this->render('admin/orders/index.html.twig', [
			'orders' => $this->orderRepository->findAll()
		]);
	}
}
