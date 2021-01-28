<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="orders")
 */
class Order extends AbstractEntity
{
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * EAGER = on récupère les lignes produits en meme temps que la commande
     * @ORM\OneToMany(targetEntity="App\Entity\OrderLine", mappedBy="order", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private $orderLines;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $address;

    public function __construct(?array $lines = null, ?User $user = null)
    {
        $this->orderLines = new ArrayCollection();
        $this->setDate(new DateTime());
        if ($lines) {
        	foreach ($lines as $line) {
        		$this->addOrderLine($line);
	        }
        }
        if ($user) {
        	$this->setUser($user);
        }
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
	    $this->setAddress($user->getAddress());

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
            $orderLine->setOrder($this);
        }

        return $this;
    }

    public function removeOrderLine(OrderLine $orderLine): self
    {
        if ($this->orderLines->contains($orderLine)) {
            $this->orderLines->removeElement($orderLine);
            // set the owning side to null (unless already changed)
            if ($orderLine->getOrder() === $this) {
                $orderLine->setOrder(null);
            }
        }

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTotal(): float {
    	return array_reduce(array_map(static function(OrderLine $line) {
    		return $line->getTotal();
	    }, $this->orderLines->getValues()), static function(float $a, float $b) {
    		return $a + $b;
	    }, 0);
    }
}
