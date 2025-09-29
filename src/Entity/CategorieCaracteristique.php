<?php

namespace App\Entity;

use App\Repository\CategorieCaracteristiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieCaracteristiqueRepository::class)]
class CategorieCaracteristique
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null; // Nom de la catégorie, ex: "Informations générales"

    // ⚡ Relation OneToMany vers les caractéristiques
    #[ORM\OneToMany(mappedBy: "categorie", targetEntity: TrottinetteCaracteristique::class)]
    private Collection $caracteristiques;

    public function __construct()
    {
        $this->caracteristiques = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    /** @return Collection<int, TrottinetteCaracteristique> */
    public function getCaracteristiques(): Collection { return $this->caracteristiques; }

    public function addCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if (!$this->caracteristiques->contains($tc)) {
            $this->caracteristiques[] = $tc;
            $tc->setCategorie($this);
        }
        return $this;
    }

    public function removeCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if ($this->caracteristiques->removeElement($tc)) {
            if ($tc->getCategorie() === $this) $tc->setCategorie(null);
        }
        return $this;
    }
}
