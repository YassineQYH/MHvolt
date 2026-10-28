<?php

namespace App\Entity;

use App\Repository\CategoryAccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: CategoryAccessoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CategoryAccessory
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $illustration = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: "category", targetEntity: Accessory::class)]
    private Collection $accessories;

    public function __construct()
    {
        $this->accessories = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setSlugValue(): void
    {
        if ($this->name) {
            $slugger = new AsciiSlugger();
            if (!$this->slug || strtolower((string)$slugger->slug($this->name)) !== $this->slug) {
                $this->slug = strtolower((string)$slugger->slug($this->name));
            }
        }
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(?string $slug): self { $this->slug = $slug; return $this; }

    public function getIllustration(): ?string { return $this->illustration; }
    public function setIllustration(?string $illustration): self { $this->illustration = $illustration; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    /** @return Collection<int, Accessory> */
    public function getAccessories(): Collection { return $this->accessories; }

    public function addAccessory(Accessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories[] = $accessory;
            $accessory->setCategory($this);
        }
        return $this;
    }

    public function removeAccessory(Accessory $accessory): self
    {
        if ($this->accessories->removeElement($accessory)) {
            if ($accessory->getCategory() === $this) {
                $accessory->setCategory(null);
            }
        }
        return $this;
    }
}
