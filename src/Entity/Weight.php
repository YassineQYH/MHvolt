<?php

namespace App\Entity;

use App\Repository\WeightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

#[ORM\Entity(repositoryClass: WeightRepository::class)]
class Weight
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "float")]
    private float $kg;

    #[ORM\Column(type: "float")]
    private float $price;

    #[ORM\OneToMany(mappedBy: "weight", targetEntity: Product::class)]
    private Collection $products;

    public function __construct(float $kg, float $price)
    {
        $this->kg = $kg;
        $this->price = $price;
        $this->products = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->kg . ' kg';
    }


    public function getId(): ?int { return $this->id; }

    public function getKg(): float { return $this->kg; }
    public function setKg(float $kg): self { $this->kg = $kg; return $this; }

    public function getPrice(): float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    /** @return Collection<int, Product> */
    public function getProducts(): Collection { return $this->products; }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setWeight($this);
        }
        return $this;
    }

    public function removeProduct(Product $product): self
    {
        // On supprime la relation côté collection, mais on ne passe jamais null
        $this->products->removeElement($product);
        return $this;
    }
}
