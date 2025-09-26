<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Illustration;
use App\Entity\Accessory;
use App\Entity\Caracteristique;

#[ORM\Entity(repositoryClass: TrottinetteRepository::class)]
class Trottinette
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $nameShort = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $slug = null;

    #[ORM\Column(type:"text")]
    private ?string $description = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $descriptionShort = null;

    // Relation avec les images secondaires
    #[ORM\OneToMany(mappedBy:"trottinette", targetEntity: Illustration::class)]
    private Collection $illustrations;

    // Image principale de la trottinette
    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    // Indique si c'est un "best" produit
    #[ORM\Column(type:"boolean")]
    private ?bool $isBest = null;

    // ---- NOUVEAUX CHAMPS POUR LE CARROUSEL (ancien Header) ----

    // Active ou non la trottinette dans le carrousel
    #[ORM\Column(type:"boolean")]
    private bool $isHeader = false;

    // Image spécifique pour le carrousel (différente de l'image principale)
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerImage = null;

    // Texte du bouton affiché dans le carrousel
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnTitle = null;

    // Lien du bouton affiché dans le carrousel
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnUrl = null;

    // Relation ManyToMany avec les accessoires
    #[ORM\ManyToMany(targetEntity: Accessory::class, inversedBy: "trottinettes")]
    #[ORM\JoinTable(name: "trottinette_accessory")]
    private Collection $accessories;

    // Relation ManyToMany avec les caractéristiques
    #[ORM\ManyToMany(targetEntity: Caracteristique::class, inversedBy: "trottinettes")]
    #[ORM\JoinTable(name: "trottinette_caracteristique")]
    private Collection $caracteristiques;

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->accessories = new ArrayCollection();
        $this->caracteristiques = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // ---- GETTERS & SETTERS ----

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getNameShort(): ?string { return $this->nameShort; }
    public function setNameShort(?string $nameShort): self { $this->nameShort = $nameShort; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDescriptionShort(): ?string { return $this->descriptionShort; }
    public function setDescriptionShort(?string $descriptionShort): self { $this->descriptionShort = $descriptionShort; return $this; }

    /** @return Collection|Illustration[] */
    public function getIllustrations(): Collection { return $this->illustrations; }
    public function addIllustration(Illustration $illustration): self
    {
        if (!$this->illustrations->contains($illustration)) {
            $this->illustrations[] = $illustration;
            $illustration->setTrottinette($this);
        }
        return $this;
    }
    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustrations->removeElement($illustration)) {
            if ($illustration->getTrottinette() === $this) {
                $illustration->setTrottinette(null);
            }
        }
        return $this;
    }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): ?bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    /** @return Collection<int, Accessory> */
    public function getAccessories(): Collection { return $this->accessories; }
    public function addAccessory(Accessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
            $accessory->addTrottinette($this);
        }
        return $this;
    }
    public function removeAccessory(Accessory $accessory): self
    {
        if ($this->accessories->removeElement($accessory)) {
            $accessory->removeTrottinette($this);
        }
        return $this;
    }

    // ---- NOUVEAUX GETTERS & SETTERS POUR LE CARROUSEL ----

    public function getIsHeader(): bool { return $this->isHeader; }
    public function setIsHeader(bool $isHeader): self { $this->isHeader = $isHeader; return $this; }

    public function getHeaderImage(): ?string { return $this->headerImage; }
    public function setHeaderImage(?string $headerImage): self { $this->headerImage = $headerImage; return $this; }

    public function getHeaderBtnTitle(): ?string { return $this->headerBtnTitle; }
    public function setHeaderBtnTitle(?string $headerBtnTitle): self { $this->headerBtnTitle = $headerBtnTitle; return $this; }

    public function getHeaderBtnUrl(): ?string { return $this->headerBtnUrl; }
    public function setHeaderBtnUrl(?string $headerBtnUrl): self { $this->headerBtnUrl = $headerBtnUrl; return $this; }

    /** @return Collection|Caracteristique[] */
    public function getCaracteristiques(): Collection { return $this->caracteristiques; }
    public function addCaracteristique(Caracteristique $caracteristique): self
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques[] = $caracteristique;
        }
        return $this;
    }
    public function removeCaracteristique(Caracteristique $caracteristique): self
    {
        $this->caracteristiques->removeElement($caracteristique);
        return $this;
    }
}
