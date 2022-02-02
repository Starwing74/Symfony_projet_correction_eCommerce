<?php

namespace App\Entity;

use App\DTO\AbstractDto;
use App\DTO\ProductDto;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(ProductRepository::class)]
class Product extends AbstractEntity
{
	#[ORM\Column(type: "string", length: 255)]
	private string $name;

	#[ORM\Column(type: "string", length: 255, nullable: true)]
	private ?string $photo = null;

	#[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "products")]
	#[ORM\JoinColumn(nullable: false)]
	private Category $category;

	#[ORM\Column(type: "float")]
	private float $price;

	#[ORM\Column(type: "text", nullable: true)]
	private ?string $description = null;

	#[ORM\OneToMany(mappedBy: "product", targetEntity: OrderLine::class)]
	private Collection $orderLines;

	public function __construct() {
		$this->orderLines = new ArrayCollection();
	}

	/**
	 * @param ProductDto $dto
	 */
	public function setFromDto(AbstractDto $dto): void {
		$this->setName($dto->name);
		$this->setDescription($dto->description);
		$this->setPrice($dto->price);
		$this->setCategory($dto->category);
		if ($dto->photo) {
			$this->setPhoto($dto->photo->getPath() . '/' . $dto->photo->getBasename());
		}
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getPhoto(): ?string
	{
		return $this->photo;
	}

	public function setPhoto(?string $photo): self
	{
		$this->photo = $photo;

		return $this;
	}

	public function getCategory(): Category
	{
		return $this->category;
	}

	public function setCategory(Category $category): self
	{
		$this->category = $category;

		return $this;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function setPrice(float $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * @return Collection|OrderLine[]
	 */
	public function getOrderLines(): Collection
	{
		return $this->orderLines;
	}

	public function addOrderLine(OrderLine $orderLine): self
	{
		if (!$this->orderLines->contains($orderLine)) {
			$this->orderLines[] = $orderLine;
			$orderLine->setProduct($this);
		}

		return $this;
	}

	public function removeOrderLine(OrderLine $orderLine): self
	{
		if ($this->orderLines->contains($orderLine)) {
			$this->orderLines->removeElement($orderLine);
			// set the owning side to null (unless already changed)
			if ($orderLine->getProduct() === $this) {
				$orderLine->setProduct(null);
			}
		}

		return $this;
	}
}
