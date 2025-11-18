<?php

namespace App\Entity;

use App\Repository\TrottinetteAccessoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrottinetteAccessoryRepository::class)]
class TrottinetteAccessory
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "trottinetteAccessories")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trottinette $trottinette = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $accessory = null;

    public function getId(): ?int { return $this->id; }

    public function getTrottinette(): ?Trottinette { return $this->trottinette; }
    public function setTrottinette(?Trottinette $trottinette): self { $this->trottinette = $trottinette; return $this; }

    public function getAccessory(): ?Product { return $this->accessory; }
    public function setAccessory(?Product $accessory): self { $this->accessory = $accessory; return $this; }
}
