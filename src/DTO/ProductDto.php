<?php

namespace App\DTO;

use App\Entity\AbstractEntity;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDto extends AbstractDto  {

	/**
	 * @var string
	 * @Assert\NotBlank()
	 * @Assert\Length(max="250")
	 */
	public $name;

	/**
	 * @var Category
	 * @Required()
	 */
	public $category;

	/**
	 * @var string | null
	 */
	public $description;

	/**
	 * @var int
	 * @Assert\Range(min="1")
	 */
	public $price;

	/**
	 * @var UploadedFile | null
	 */
	public $photo;

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
