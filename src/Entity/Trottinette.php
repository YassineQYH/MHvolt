<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrottinetteRepository::class)]
class Trottinette extends Product
{
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nameShort = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descriptionShort = null;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteDescriptionSection::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[ORM\OrderBy(["sectionOrder" => "ASC"])]
    private Collection $descriptionSections;

    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteSpecification::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[ORM\OrderBy(["position" => "ASC"])]
    private Collection $specifications;

    #[ORM\ManyToMany(targetEntity: Accessory::class, inversedBy: "trottinettes")]
    #[ORM\JoinTable(name: "trottinette_accessories_pivot")]
    private Collection $accessories;

    public function __construct()
    {
        parent::__construct();
        $this->descriptionSections = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->accessories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName() ?? '';
    }

    public function getType(): string
    {
        return 'trottinette';
    }

    public function getUploadDirectory(): string
    {
        return 'trottinettes';
    }

    // ------------------- GETTERS / SETTERS -------------------

    public function getNameShort(): ?string { return $this->nameShort; }
    public function setNameShort(?string $nameShort): self { $this->nameShort = $nameShort; return $this; }

    public function getDescriptionShort(): ?string { return $this->descriptionShort; }
    public function setDescriptionShort(?string $descriptionShort): self { $this->descriptionShort = $descriptionShort; return $this; }

    /** @return Collection<int, TrottinetteDescriptionSection> */
    public function getDescriptionSections(): Collection
    {
        return $this->descriptionSections;
    }

    public function addDescriptionSection(TrottinetteDescriptionSection $section): self
    {
        if (!$this->descriptionSections->contains($section)) {
            $this->descriptionSections->add($section);
            $section->setTrottinette($this);
        }
        return $this;
    }

    public function removeDescriptionSection(TrottinetteDescriptionSection $section): self
    {
        if ($this->descriptionSections->removeElement($section)) {
            if ($section->getTrottinette() === $this) {
                $section->setTrottinette(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, TrottinetteSpecification> */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(TrottinetteSpecification $specification): self
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications->add($specification);
            $specification->setTrottinette($this);
        }
        return $this;
    }

    public function removeSpecification(TrottinetteSpecification $specification): self
    {
        if ($this->specifications->removeElement($specification)) {
            if ($specification->getTrottinette() === $this) {
                $specification->setTrottinette(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Accessory> */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(Accessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
        }
        return $this;
    }

    public function removeAccessory(Accessory $accessory): self
    {
        $this->accessories->removeElement($accessory);
        return $this;
    }
}
