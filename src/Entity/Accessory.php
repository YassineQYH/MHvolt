<?php

namespace App\Entity;

use App\Repository\AccessoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessoryRepository::class)]
class Accessory extends Product
{
    #[ORM\ManyToOne(targetEntity: CategoryAccessory::class, inversedBy: "accessories")]
    private ?CategoryAccessory $category = null;

    #[ORM\ManyToMany(targetEntity: Trottinette::class, mappedBy: "accessories")]
    private Collection $trottinettes;

    public function getUploadDirectory(): string
    {
        return 'accessoires';
    }

    public function __construct()
    {
        parent::__construct();
        $this->trottinettes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getCategory(): ?CategoryAccessory { return $this->category; }
    public function setCategory(?CategoryAccessory $category): self { $this->category = $category; return $this; }

    /** @return Collection<int, Trottinette> */
    public function getTrottinettes(): Collection { return $this->trottinettes; }

    public function addTrottinette(Trottinette $trottinette): self
    {
        if (!$this->trottinettes->contains($trottinette)) {
            $this->trottinettes->add($trottinette);
            $trottinette->addAccessory($this);
        }
        return $this;
    }

    public function removeTrottinette(Trottinette $trottinette): self
    {
        if ($this->trottinettes->removeElement($trottinette)) {
            $trottinette->removeAccessory($this);
        }
        return $this;
    }
}
