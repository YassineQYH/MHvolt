<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TrottinetteSpecification
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "specifications")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Trottinette $trottinette = null;

    #[ORM\ManyToOne(targetEntity: SpecificationGroup::class, inversedBy: "specifications")]
    #[ORM\JoinColumn(nullable: false, onDelete: "RESTRICT")]
    private ?SpecificationGroup $group = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $value = null;

    #[ORM\Column(type: "integer")]
    private int $position = 0;

    public function __toString(): string
    {
        return sprintf('[%s] %s : %s', $this->group?->getName() ?? '-', $this->name, $this->value);
    }

    public function getId(): ?int { return $this->id; }

    public function getTrottinette(): ?Trottinette { return $this->trottinette; }
    public function setTrottinette(?Trottinette $trottinette): self { $this->trottinette = $trottinette; return $this; }

    public function getGroup(): ?SpecificationGroup { return $this->group; }
    public function setGroup(?SpecificationGroup $group): self { $this->group = $group; return $this; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getValue(): ?string { return $this->value; }
    public function setValue(string $value): self { $this->value = $value; return $this; }

    public function getPosition(): int { return $this->position; }
    public function setPosition(int $position): self { $this->position = $position; return $this; }
}
