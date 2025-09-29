<?php

namespace App\Entity;

use App\Repository\TrottinetteCaracteristiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrottinetteCaracteristiqueRepository::class)]
class TrottinetteCaracteristique
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    // ⚡ Relation vers la trottinette
    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "trottinetteCaracteristiques")]
    private ?Trottinette $trottinette = null;

    // ⚡ Relation vers la caractéristique (ex: "Vitesse maximale")
    #[ORM\ManyToOne(targetEntity: Caracteristique::class, inversedBy: "trottinetteCaracteristiques")]
    private ?Caracteristique $caracteristique = null;

    // ⚡ Titre personnalisé (optionnel) si on veut override le nom de la caractéristique
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $title = null;

    // ⚡ Valeur de la caractéristique (ex: "45 km/h")
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $value = null;

    // ⚡ Relation vers la catégorie (ex: "Informations générales")
    #[ORM\ManyToOne(targetEntity: CategorieCaracteristique::class, inversedBy: "caracteristiques")]
    private ?CategorieCaracteristique $categorie = null;

    // -------------------------------
    // Getters / Setters
    // -------------------------------

    public function getId(): ?int { return $this->id; }

    public function getTrottinette(): ?Trottinette { return $this->trottinette; }
    public function setTrottinette(?Trottinette $trottinette): self { $this->trottinette = $trottinette; return $this; }

    public function getCaracteristique(): ?Caracteristique { return $this->caracteristique; }
    public function setCaracteristique(?Caracteristique $caracteristique): self { $this->caracteristique = $caracteristique; return $this; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(?string $title): self { $this->title = $title; return $this; }

    public function getValue(): ?string { return $this->value; }
    public function setValue(?string $value): self { $this->value = $value; return $this; }

    public function getCategorie(): ?CategorieCaracteristique { return $this->categorie; }
    public function setCategorie(?CategorieCaracteristique $categorie): self { $this->categorie = $categorie; return $this; }
}
