<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController {

	#[Route("/category/{id}", name: "categories_get_products", methods: ["GET"])]
	public function getProductsForCategory(Category $category): Response {
		return $this->render('categories/products.html.twig', [
			'categorie' => $category,
			'products' => $category->getProducts()
		]);
	}
}
