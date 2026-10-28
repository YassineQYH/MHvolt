<?php

namespace App\Entity;

use App\Repository\IllustrationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IllustrationRepository::class)]
class Illustration
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "illustrations")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Product $product = null;

    public function __toString(): string
    {
        return $this->image ?? 'Illustration';
    }

    public function getId(): ?int { return $this->id; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }

    public function getImagePath(): string
    {
        if (!$this->image) {
            return '/images/default.jpg';
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        $dir = $this->product ? $this->product->getUploadDirectory() : 'produits';
        return '/uploads/' . $dir . '/' . $this->image;
    }
}
