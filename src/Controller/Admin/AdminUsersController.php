<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/users")]
class AdminUsersController extends AbstractController
{
	private UserRepository $userRepository;

	public function __construct(UserRepository $userRepository) {
		$this->userRepository = $userRepository;
	}

	#[Route("", "admin_users_list")]
	public function index(): Response {
		return $this->render('admin/users/index.html.twig', [
			'users' => $this->userRepository->findAll()
		]);
	}
}
