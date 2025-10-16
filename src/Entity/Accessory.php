<?php

namespace App\Entity;

use App\Repository\AccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Illustrationaccess;
use App\Entity\TrottinetteAccessory;
use App\Entity\Weight;
use App\Entity\CategoryAccessory;

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

    #[ORM\Column(type:"integer")]
    private ?int $stock = 0;

    #[ORM\Column(type:"float", nullable:true)]
    private ?float $price = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private bool $isBest = false;

    // ------------------- Relations -------------------

    // Pivot TrottinetteAccessory
    #[ORM\OneToMany(mappedBy: "accessory", targetEntity: TrottinetteAccessory::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteAccessories;

    // Illustrations
    #[ORM\OneToMany(mappedBy:"accessory", targetEntity: Illustrationaccess::class, cascade: ["persist", "remove"])]
    private Collection $illustrationaccess;

    // Poids
    #[ORM\ManyToOne(targetEntity: Weight::class, inversedBy: "accessories")]
    private ?Weight $weight = null;

    // Catégorie
    #[ORM\ManyToOne(targetEntity: CategoryAccessory::class, inversedBy: "accessories")]
    private ?CategoryAccessory $category = null;

    public function __construct()
    {
        $this->illustrationaccess = new ArrayCollection();
        $this->trottinetteAccessories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // ------------------- GETTERS & SETTERS -------------------
    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getStock(): ?int { return $this->stock; }
    public function setStock(int $stock): self { $this->stock = $stock; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    // ------------------- Illustrations -------------------
    /** @return Collection<int, Illustrationaccess> */
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

    // ------------------- TrottinetteAccessories -------------------
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

    // ------------------- Weight -------------------
    public function getWeight(): ?Weight { return $this->weight; }
    public function setWeight(?Weight $weight): self { $this->weight = $weight; return $this; }

    // ------------------- Category -------------------
    public function getCategory(): ?CategoryAccessory { return $this->category; }
    public function setCategory(?CategoryAccessory $category): self { $this->category = $category; return $this; }
}
