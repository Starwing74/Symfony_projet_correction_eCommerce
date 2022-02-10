<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDto extends AbstractDto  {

	#[Assert\NotBlank]
	#[Assert\Length(max: 250)]
	public string $name;

	#[Required]
	public Category $category;

	public ?string $description = null;

	#[Assert\NotBlank]
	#[Assert\Range(min: 0.01)]
	public int $price;

	public ?File $photo = null;

	/**
	 * @param Product $product
	 */
	public function setFromEntity(AbstractEntity $product): void {
		$this->category = $product->getCategory();
		$this->description = $product->getDescription();
		$this->price = $product->getPrice();
		$this->name = $product->getName();
	}
}
