<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\CaracteristiqueRepository")]
class Caracteristique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    /**
     * @ORM\OneToMany(targetEntity=TrottinetteCaracteristique::class, mappedBy="caracteristique", cascade={"persist"})
     */
    private Collection $trottinetteCaracteristiques;

    public function __construct()
    {
        $this->trottinetteCaracteristiques = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    /** @return Collection|TrottinetteCaracteristique[] */
    public function getTrottinetteCaracteristiques(): Collection { return $this->trottinetteCaracteristiques; }
}
