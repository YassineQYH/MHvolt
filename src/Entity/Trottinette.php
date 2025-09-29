<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TrottinetteCaracteristique;
use App\Entity\TrottinetteDescriptionSection;
use App\Entity\TrottinetteAccessory;
use App\Entity\Illustration;

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

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private ?bool $isBest = null;

    #[ORM\Column(type:"boolean")]
    private bool $isHeader = false;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerImage = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnTitle = null;

    // ------------------- Relations -------------------

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: Illustration::class, cascade: ["persist", "remove"])]
    private Collection $illustration;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteCaracteristique::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteCaracteristiques;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteAccessory::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteAccessories;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteDescriptionSection::class, cascade: ["persist", "remove"])]
    private Collection $descriptionSections;

    public function __construct()
    {
        $this->illustration = new ArrayCollection();
        $this->trottinetteCaracteristiques = new ArrayCollection();
        $this->trottinetteAccessories = new ArrayCollection();
        $this->descriptionSections = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // ------------------- GETTERS & SETTERS -------------------

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

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): ?bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    public function getIsHeader(): bool { return $this->isHeader; }
    public function setIsHeader(bool $isHeader): self { $this->isHeader = $isHeader; return $this; }

    public function getHeaderImage(): ?string { return $this->headerImage; }
    public function setHeaderImage(?string $headerImage): self { $this->headerImage = $headerImage; return $this; }

    public function getHeaderBtnTitle(): ?string { return $this->headerBtnTitle; }
    public function setHeaderBtnTitle(?string $headerBtnTitle): self { $this->headerBtnTitle = $headerBtnTitle; return $this; }

    // ------------------- Illustrations -------------------
    /** @return Collection<int, Illustration> */
    public function getIllustration(): Collection { return $this->illustration; }
    public function addIllustration(Illustration $illustration): self {
        if (!$this->illustration->contains($illustration)) {
            $this->illustration[] = $illustration;
            $illustration->setTrottinette($this);
        }
        return $this;
    }
    public function removeIllustration(Illustration $illustration): self {
        if ($this->illustration->removeElement($illustration)) {
            if ($illustration->getTrottinette() === $this) {
                $illustration->setTrottinette(null);
            }
        }
        return $this;
    }

    // ------------------- TrottinetteCaracteristiques -------------------
    /** @return Collection<int, TrottinetteCaracteristique> */
    public function getTrottinetteCaracteristiques(): Collection { return $this->trottinetteCaracteristiques; }
    public function addTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self {
        if (!$this->trottinetteCaracteristiques->contains($tc)) {
            $this->trottinetteCaracteristiques[] = $tc;
            $tc->setTrottinette($this);
        }
        return $this;
    }
    public function removeTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self {
        if ($this->trottinetteCaracteristiques->removeElement($tc)) {
            if ($tc->getTrottinette() === $this) $tc->setTrottinette(null);
        }
        return $this;
    }

    // ------------------- TrottinetteAccessories -------------------
    /** @return Collection<int, TrottinetteAccessory> */
    public function getTrottinetteAccessories(): Collection { return $this->trottinetteAccessories; }
    public function addTrottinetteAccessory(TrottinetteAccessory $ta): self {
        if (!$this->trottinetteAccessories->contains($ta)) {
            $this->trottinetteAccessories[] = $ta;
            $ta->setTrottinette($this);
        }
        return $this;
    }
    public function removeTrottinetteAccessory(TrottinetteAccessory $ta): self {
        if ($this->trottinetteAccessories->removeElement($ta)) {
            if ($ta->getTrottinette() === $this) $ta->setTrottinette(null);
        }
        return $this;
    }

    /** Alias pour Twig : permet d'accéder aux accessoires via "trottinette.accessories" */
    public function getAccessories(): Collection { return $this->getTrottinetteAccessories(); }

    // ------------------- DescriptionSections -------------------
    /** @return Collection<int, TrottinetteDescriptionSection> */
    public function getDescriptionSections(): Collection { return $this->descriptionSections; }
    public function addDescriptionSection(TrottinetteDescriptionSection $section): self {
        if (!$this->descriptionSections->contains($section)) {
            $this->descriptionSections->add($section);
            $section->setTrottinette($this);
        }
        return $this;
    }
    public function removeDescriptionSection(TrottinetteDescriptionSection $section): self {
        if ($this->descriptionSections->removeElement($section)) {
            if ($section->getTrottinette() === $this) $section->setTrottinette(null);
        }
        return $this;
    }
}
