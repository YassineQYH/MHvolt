<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Illustration;
use App\Entity\Accessory;

#[ORM\Entity(repositoryClass: TrottinetteRepository::class)]
class Trottinette
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $slug = null;

    #[ORM\Column(type:"text")]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy:"trottinette", targetEntity: Illustration::class)]
    private Collection $illustrations;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private ?bool $isBest = null;

    #[ORM\ManyToMany(targetEntity: Accessory::class, inversedBy: "trottinettes")]
    #[ORM\JoinTable(name: "trottinette_accessory")]
    private Collection $accessories;

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->accessories = new ArrayCollection();
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
            $accessory->addTrottinette($this); // cohérence inverse
        }
        return $this;
    }

    public function removeAccessory(Accessory $accessory): self
    {
        if ($this->accessories->removeElement($accessory)) {
            $accessory->removeTrottinette($this); // cohérence inverse
        }
        return $this;
    }
}
