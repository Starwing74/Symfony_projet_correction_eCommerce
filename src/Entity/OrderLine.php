<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderLineRepository")
 */
class OrderLine extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="orderLines", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

	/**
	 * @ORM\Column(type="float", nullable=false)
	 */
	private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="orderLines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    public function __construct(Product $product = null) {
    	$this->setQuantity(1);
	    if ($product) {
	    	$this->setProduct($product);
	    }
    }

	public function getId(): ?int
    {
        return $this->id;
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

	public function getPrice(): ?float
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
