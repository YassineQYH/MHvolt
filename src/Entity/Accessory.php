<?php

namespace App\Entity;

use App\Repository\AccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Illustrationaccess;

#[ORM\Entity(repositoryClass: AccessoryRepository::class)]
class Accessory
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $slug = null;

    #[ORM\Column(type:"text")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy:"accessory", targetEntity: Illustrationaccess::class)]
    private Collection $illustrationaccess;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private bool $isBest = false;

    // ⚡ Relation OneToMany vers la pivot TrottinetteAccessory
    #[ORM\OneToMany(mappedBy: "accessory", targetEntity: TrottinetteAccessory::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteAccessories;

    public function __construct()
    {
        $this->illustrationaccess = new ArrayCollection();
        $this->trottinetteAccessories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    /** @return Collection|Illustrationaccess[] */
    public function getIllustrationaccess(): Collection { return $this->illustrationaccess; }
    public function addIllustrationaccess(Illustrationaccess $illustrationaccess): self {
        if (!$this->illustrationaccess->contains($illustrationaccess)) {
            $this->illustrationaccess->add($illustrationaccess);
            $illustrationaccess->setAccessory($this);
        }
        return $this;
    }
    public function removeIllustrationaccess(Illustrationaccess $illustrationaccess): self {
        if ($this->illustrationaccess->removeElement($illustrationaccess)) {
            if ($illustrationaccess->getAccessory() === $this) {
                $illustrationaccess->setAccessory(null);
            }
        }
        return $this;
    }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    /** @return Collection<int, TrottinetteAccessory> */
    public function getTrottinetteAccessories(): Collection { return $this->trottinetteAccessories; }
    public function addTrottinetteAccessory(TrottinetteAccessory $ta): self {
        if (!$this->trottinetteAccessories->contains($ta)) {
            $this->trottinetteAccessories[] = $ta;
            $ta->setAccessory($this);
        }
        return $this;
    }
    public function removeTrottinetteAccessory(TrottinetteAccessory $ta): self {
        if ($this->trottinetteAccessories->removeElement($ta)) {
            if ($ta->getAccessory() === $this) $ta->setAccessory(null);
        }
        return $this;
    }
}

