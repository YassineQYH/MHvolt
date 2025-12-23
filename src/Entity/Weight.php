<?php

namespace App\Entity;

use App\Repository\WeightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeightRepository::class)]
class Weight
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    // Poids max ou seuil (ex: 1, 2, 5, 10)
    #[ORM\Column(type: "float")]
    private float $kg;

    // Prix de livraison correspondant
    #[ORM\Column(type: "float")]
    private float $price;

    public function __toString(): string
    {
        return $this->kg . ' kg';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKg(): float
    {
        return $this->kg;
    }

    public function setKg(float $kg): self
    {
        $this->kg = $kg;
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
}
