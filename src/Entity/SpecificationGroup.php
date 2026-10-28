<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SpecificationGroup
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: "integer")]
    private int $position = 0;

    #[ORM\OneToMany(mappedBy: "group", targetEntity: TrottinetteSpecification::class)]
    private Collection $specifications;

    public function __construct()
    {
        $this->specifications = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getPosition(): int { return $this->position; }
    public function setPosition(int $position): self { $this->position = $position; return $this; }

    /** @return Collection<int, TrottinetteSpecification> */
    public function getSpecifications(): Collection { return $this->specifications; }
}
