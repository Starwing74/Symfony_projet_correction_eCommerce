<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto extends AbstractDto {

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(max="250")
	 */
	public $name;

	/**
	 * @param Category $category
	 */
	public function setFromEntity(AbstractEntity $category): void {
		$this->name = $category->getName();
	}
}
