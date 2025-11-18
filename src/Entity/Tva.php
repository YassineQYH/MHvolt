<?php

namespace App\Entity;

use App\Repository\TvaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

#[ORM\Entity(repositoryClass: TvaRepository::class)]
class Tva
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(type: "float")]
    private float $value;

    #[ORM\OneToMany(mappedBy: "tva", targetEntity: Product::class)]
    private Collection $products;

    public function __construct(string $name, float $value)
    {
        $this->name = $name;
        $this->value = $value;
        $this->products = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getValue(): float { return $this->value; }
    public function setValue(float $value): self { $this->value = $value; return $this; }

    /** @return Collection<int, Product> */
    public function getProducts(): Collection { return $this->products; }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setTva($this);
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
