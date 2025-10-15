<?php

namespace App\Entity;

use App\Repository\WeightRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trottinette;

#[ORM\Entity(repositoryClass: WeightRepository::class)]
class Weight
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "float")]
    private ?float $kg = null;

    #[ORM\Column(type: "float")]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: "weight", targetEntity: Trottinette::class)]
    private Collection $trottinettes;

    public function __construct()
    {
        $this->trottinettes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->kg;
    }

    public function getId(): ?int { return $this->id; }
    public function getKg(): ?float { return $this->kg; }
    public function setKg(float $kg): self { $this->kg = $kg; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    /** @return Collection<int, Trottinette> */
    public function getTrottinettes(): Collection { return $this->trottinettes; }

    public function addTrottinette(Trottinette $trottinette): self
    {
        if (!$this->trottinettes->contains($trottinette)) {
            $this->trottinettes[] = $trottinette;
            $trottinette->setWeight($this);
        }
        return $this;
    }

    public function removeTrottinette(Trottinette $trottinette): self
    {
        if ($this->trottinettes->removeElement($trottinette)) {
            if ($trottinette->getWeight() === $this) {
                $trottinette->setWeight(null);
            }
        }
        return $this;
    }
}
