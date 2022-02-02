<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto extends AbstractDto {

	#[Assert\NotBlank]
	#[Assert\Length(max: 250)]
	public string $name;

	/**
	 * @param Category $category
	 */
	public function setFromEntity(AbstractEntity $category): void {
		$this->name = $category->getName();
	}
}
