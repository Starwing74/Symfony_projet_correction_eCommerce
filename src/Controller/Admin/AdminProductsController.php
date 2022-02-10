<?php

namespace App\Controller\Admin;

use App\DTO\ProductDto;
use App\Entity\Product;
use App\Form\ProductType;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/products")]
class AdminProductsController extends AbstractController {

	private ProductService $productService;

	public function __construct(ProductService $productService) {
		$this->productService = $productService;
	}

    #[Route("/{id}/edit", name: "admin_products_edit", methods: ["GET", "POST"])]
	public function edit(Product $product, Request $request): Response {
		$productDto = new ProductDto();

		$productDto->setFromEntity($product);

		$form = $this->createForm(ProductType::class, $productDto);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->productService->addOrUpdate($productDto, $product);

			$this->addFlash('success', 'Produit modifié !');

			return $this->redirectToRoute('products_get', [
				'id' => $product->getId()
			]);
		}

		return $this->render('admin/products/edit.html.twig', [
			'form' => $form->createView(),
			'isAdd' => false,
			'product' => $product
		]);
	}

    #[Route("/add", name: "admin_products_add", methods: ["GET", "POST"])]
	public function add(Request $request): Response {
		$productDto = new ProductDto();

		$form = $this->createForm(ProductType::class, $productDto);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$product = new Product();
			$this->productService->addOrUpdate($productDto, $product);

			$this->addFlash('success', 'Produit ajouté !');

			return $this->redirectToRoute('products_get', [
				'id' => $product->getId()
			]);
		}

		return $this->render('admin/products/edit.html.twig', [
			'form' => $form->createView(),
			'isAdd' => true
		]);
	}

    #[Route("/{id}/delete", name: "admin_products_delete", methods: ["GET"])]
	public function delete(Product $product): Response {
		$this->productService->delete($product);

		$this->addFlash('success', 'Produit supprimé !');

		return $this->redirectToRoute('products_index');
	}
}
