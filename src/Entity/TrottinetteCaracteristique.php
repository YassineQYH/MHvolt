<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trottinette;
use App\Entity\Caracteristique;

#[ORM\Entity(repositoryClass: "App\Repository\TrottinetteCaracteristiqueRepository")]
class TrottinetteCaracteristique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "trottinetteCaracteristiques")]
    private ?Trottinette $trottinette = null;

    #[ORM\ManyToOne(targetEntity: Caracteristique::class, inversedBy: "trottinetteCaracteristiques")]
    private ?Caracteristique $caracteristique = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $value = null;

    public function getId(): ?int { return $this->id; }
    public function getTrottinette(): ?Trottinette { return $this->trottinette; }
    public function setTrottinette(?Trottinette $trottinette): self { $this->trottinette = $trottinette; return $this; }

    public function getCaracteristique(): ?Caracteristique { return $this->caracteristique; }
    public function setCaracteristique(?Caracteristique $caracteristique): self { $this->caracteristique = $caracteristique; return $this; }

    public function getValue(): ?string { return $this->value; }
    public function setValue(?string $value): self { $this->value = $value; return $this; }
}
