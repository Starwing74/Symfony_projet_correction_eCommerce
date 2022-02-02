<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(OrderLineRepository::class)]
class OrderLine extends AbstractEntity
{
    // EAGER = on récupère le produit en meme temps que la ligne
     #[ORM\ManyToOne(targetEntity: Product::class, fetch: "EAGER", inversedBy: "orderLines")]
     #[ORM\JoinColumn(nullable: false)]
    private Product $product;

     #[ORM\Column(type: "integer", nullable: false)]
    private int $quantity;

	 #[ORM\Column(type: "float", nullable: false)]
	private float $price;

     #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderLines")]
     #[ORM\JoinColumn(nullable: false)]
     private Order $order;

    public function __construct(Product $product = null) {
    	$this->setQuantity(1);
	    if ($product) {
	    	$this->setProduct($product);
	    }
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
	    $this->setPrice($product->getPrice());

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

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

    public function getTotal(): float {
    	return $this->getQuantity() * $this->getPrice();
    }
}
