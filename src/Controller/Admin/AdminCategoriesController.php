<?php

namespace App\Controller\Admin;

use App\DTO\CategoryDto;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Services\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/categories")]
class AdminCategoriesController extends AbstractController
{
	private CategoryService $categoryService;

	public function __construct(CategoryService $categoryService) {
		$this->categoryService = $categoryService;
	}

    #[Route("/add", name: "admin_categories_add", methods: ["GET", "POST"])]
    public function add(Request $request): Response
    {
	    $categorytDto = new CategoryDto();

	    $form = $this->createForm(CategoryType::class, $categorytDto);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	    	$category = new Category();
		    $this->categoryService->addOrUpdate($categorytDto, $category);

		    $this->addFlash('success', 'Categorie ajoutée !');

		    return $this->redirectToRoute('categories_get_products', [
			    'id' => $category->getId()
		    ]);
	    }

	    return $this->render('admin/categories/edit.html.twig', [
		    'form' => $form->createView(),
		    'isAdd' => true
	    ]);
    }

    #[Route("/{id}/edit", name: "admin_categories_edit", methods: ["GET", "POST"])]
    public function edit(Request $request, Category $category): Response
    {
	    $categoryDto = new CategoryDto();

	    $categoryDto->setFromEntity($category);

	    $form = $this->createForm(CategoryType::class, $categoryDto);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
		    $this->categoryService->addOrUpdate($categoryDto, $category);

		    $this->addFlash('success', 'Catégorie modifiée !');

		    return $this->redirectToRoute('categories_get_products', [
			    'id' => $category->getId()
		    ]);
	    }

	    return $this->render('admin/categories/edit.html.twig', [
		    'form' => $form->createView(),
		    'isAdd' => false,
		    'category' => $category
	    ]);
    }

    #[Route("/{id}", name: "admin_categories_delete", methods: ["GET"])]
    public function delete(Category $category): Response
    {
	    $this->categoryService->delete($category);

	    $this->addFlash('success', 'Catégorie supprimée !');

	    return $this->redirectToRoute('index');
    }
}
