<?php

namespace App\Entity;

use App\Repository\AccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TrottinetteAccessory;
use App\Entity\CategoryAccessory;

#[ORM\Entity(repositoryClass: AccessoryRepository::class)]
class Accessory extends Product
{
    #[ORM\ManyToOne(targetEntity: CategoryAccessory::class, inversedBy: "accessories")]
    private ?CategoryAccessory $category = null;

    #[ORM\OneToMany(mappedBy: "accessory", targetEntity: TrottinetteAccessory::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteAccessories;

    public function __construct()
    {
        parent::__construct();
        $this->trottinetteAccessories = new ArrayCollection();
    }

    public function __toString(): string { return $this->name ?? ''; }

    public function getCategory(): ?CategoryAccessory { return $this->category; }
    public function setCategory(?CategoryAccessory $category): self { $this->category = $category; return $this; }

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
            $ta->setAccessory(null);
        }
        return $this;
    }
}
