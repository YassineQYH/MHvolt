<?php

namespace App\Entity;

use App\Repository\IllustrationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

#[ORM\Entity(repositoryClass: IllustrationRepository::class)]
class Illustration
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "illustrations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function __toString(): string
    {
        return $this->image ?? 'Illustration';
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }
    public function getImagePath(): string
    {
        if ($this->product) {
            switch ($this->product->getType()) {
                case 'trottinette':
                    return '/uploads/trottinettes/' . $this->image;
                case 'accessoire':
                    return '/uploads/accessoires/' . $this->image;
                default:
                    return '/uploads/produits/' . $this->image;
            }
        }

        return '/uploads/produits/' . $this->image;
    }

}
