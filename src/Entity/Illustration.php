<?php

namespace App\Entity;

use App\Repository\IllustrationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trottinette;

#[ORM\Entity(repositoryClass: IllustrationRepository::class)]
class Illustration
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "illustration")]
    #[ORM\JoinColumn(nullable:false)]
    private ?Trottinette $trottinette = null;

    public function __toString(): string
    {
        return $this->image ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getTrottinette(): ?Trottinette
    {
        return $this->trottinette;
    }

    public function setTrottinette(?Trottinette $trottinette): self
    {
        $this->trottinette = $trottinette;
        return $this;
    }
}
