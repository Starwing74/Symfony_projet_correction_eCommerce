<?php

namespace App\Services;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

class CategoryService extends AbstractEntityService {

	public function __construct(CategoryRepository $categoryRepository) {
		parent::__construct($categoryRepository);
	}

	/**
	 * @param Category $entity
	 */
	public function delete(AbstractEntity $entity): void {
		if (!$entity->getProducts()->isEmpty()) {
			throw new Exception('Des produits sont encore dans cette catégorie');
		}

		parent::delete($entity);
	}
}
