<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TrottinetteCaracteristique;
use App\Entity\TrottinetteDescriptionSection;
use App\Entity\TrottinetteAccessory;

#[ORM\Entity(repositoryClass: TrottinetteRepository::class)]
class Trottinette extends Product
{
    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $nameShort = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $descriptionShort = null;

/*     #[ORM\Column(type:"boolean")]
    private bool $isHeader = false;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerImage = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnTitle = null; */

    // ------------------- Relations -------------------

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteCaracteristique::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteCaracteristiques;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteAccessory::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteAccessories;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteDescriptionSection::class, cascade: ["persist", "remove"])]
    private Collection $descriptionSections;

    // Spécifique aux trottinettes
    public function getUploadDirectory(): string
    {
        return 'trottinettes';
    }

    public function __construct()
    {
        parent::__construct();
        $this->trottinetteCaracteristiques = new ArrayCollection();
        $this->trottinetteAccessories = new ArrayCollection();
        $this->descriptionSections = new ArrayCollection();
    }

    public function __toString(): string { return $this->name ?? ''; }

    // ------------------- GETTERS & SETTERS -------------------

    public function getNameShort(): ?string { return $this->nameShort; }
    public function setNameShort(?string $nameShort): self { $this->nameShort = $nameShort; return $this; }

    public function getDescriptionShort(): ?string { return $this->descriptionShort; }
    public function setDescriptionShort(?string $descriptionShort): self { $this->descriptionShort = $descriptionShort; return $this; }

/*     public function getIsHeader(): bool { return $this->isHeader; }
    public function setIsHeader(bool $isHeader): self { $this->isHeader = $isHeader; return $this; }

    public function getHeaderImage(): ?string { return $this->headerImage; }
    public function setHeaderImage(?string $headerImage): self { $this->headerImage = $headerImage; return $this; }

    public function getHeaderBtnTitle(): ?string { return $this->headerBtnTitle; }
    public function setHeaderBtnTitle(?string $headerBtnTitle): self { $this->headerBtnTitle = $headerBtnTitle; return $this; } */

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
            $tc->setTrottinette(null);
        }
        return $this;
    }

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
            $ta->setTrottinette(null);
        }
        return $this;
    }

    /** @return Collection<int, TrottinetteDescriptionSection> */
    public function getDescriptionSections(): Collection
    {
        $sections = $this->descriptionSections->toArray();
        usort($sections, fn($a, $b) => $a->getSectionOrder() <=> $b->getSectionOrder());
        return new \Doctrine\Common\Collections\ArrayCollection($sections);
    }

    public function addDescriptionSection(TrottinetteDescriptionSection $section): self {
        if (!$this->descriptionSections->contains($section)) {
            $this->descriptionSections->add($section);
            $section->setTrottinette($this);
        }
        return $this;
    }

    public function addDescriptionSectionWithOrder(TrottinetteDescriptionSection $newSection, int $order, \Doctrine\ORM\EntityManagerInterface $em): self
    {
        // Décale toutes les sections à partir de cet ordre
        foreach ($this->descriptionSections as $section) {
            if ($section->getSectionOrder() >= $order) {
                $section->setSectionOrder($section->getSectionOrder() + 1);
                $em->persist($section); // <-- Important
            }
        }

        // Définit l'ordre de la nouvelle section et l'ajoute
        $newSection->setSectionOrder($order);
        $this->addDescriptionSection($newSection);
        $em->persist($newSection);

        return $this;
    }


    public function removeDescriptionSection(TrottinetteDescriptionSection $section): self {
        if ($this->descriptionSections->removeElement($section)) {
            $section->setTrottinette(null);
        }
        return $this;
    }

        public function insertSectionAtOrder(TrottinetteDescriptionSection $newSection, int $order): self
    {
        // Décale toutes les sections existantes à partir de cet ordre
        foreach ($this->descriptionSections as $section) {
            if ($section->getSectionOrder() >= $order) {
                $section->setSectionOrder($section->getSectionOrder() + 1);
            }
        }

        // Définit l'ordre de la nouvelle section et l'ajoute
        $newSection->setSectionOrder($order);
        $this->addDescriptionSection($newSection);

        return $this;
    }

    public function getFirstIllustration(): ?string
    {
        $illustration = $this->getIllustrations()->first();
        return $illustration ? $illustration->getImagePath() : null;
    }

}
