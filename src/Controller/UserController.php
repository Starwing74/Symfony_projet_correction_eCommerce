<?php

namespace App\Controller;

use App\DTO\UserDto;
use App\Entity\User;
use App\Form\UserType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route("/user")]
class UserController extends AbstractController
{
	private UserService $userService;
	private TokenStorageInterface $tokenStorage;

	public function __construct(UserService $userService, TokenStorageInterface $tokenStorage) {
		$this->userService = $userService;
		$this->tokenStorage = $tokenStorage;
	}

	#[Route("/add", name: "user_add", methods: ["GET", "POST"])]
    public function add(Request $request): Response
    {
	    /** @var User $user */
	    $user = $this->getUser();
	    if ($user) {
		    return $this->redirectToRoute('index');
	    }

	    $userDto = new UserDto();

	    $form = $this->createForm(UserType::class, $userDto, ['validation_groups' => ['Default', 'add']]);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		    $user = new User();
		    $this->userService->addOrUpdate($userDto, $user);

		    $this->addFlash('success', 'Vous êtes inscrit !');

            return $this->redirectToRoute('user_edit_index');
        }

        return $this->render('users/edit.html.twig', [
            'form' => $form->createView(),
	        'isAdd' => true
        ]);
    }

	#[Route("/", name: "user_edit_index", methods: ["GET", "POST"])]
    public function editIndex(Request $request): Response
    {
	    /** @var User $user */
    	$user = $this->getUser();
	    $userDto = new UserDto();
	    $userDto->setFromEntity($user);

        $form = $this->createForm(UserType::class, $userDto);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
	        if ($userDto->password && $userDto->password !== $userDto->passwordConfirm) {
		        $form->get('passwordConfirm')->addError(new FormError('Les mots de passes ne correspondent pas'));
	        }

			if ($form->isValid()) {
				$this->userService->addOrUpdate($userDto, $user);

				$this->addFlash('success', 'Informations modifiées !');

				return $this->redirectToRoute('user_edit_index');
			}
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'isAdd' => false
        ]);
    }

    #[Route("/delete", name: "user_delete", methods: ["GET"])]
    public function delete(): Response
    {
    	/** @var User $user */
	    $user = $this->getUser();
	    $this->userService->delete($user);

	    $this->tokenStorage->setToken();

	    $this->addFlash('success', 'Compte supprimé !');

	    return $this->redirectToRoute('security_logout');
    }

    #[Route("/orders", name: "user_orders_index", methods: ["GET"])]
    public function orders(): Response
    {
    	/** @var User $user */
	    $user = $this->getUser();

	    return $this->render('users/orders.html.twig', [
		    'orders' => $user->getOrders()
	    ]);
    }
}
