<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;

#[ORM\Entity]
class ProductHistory
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable:false)]
    private Product $product;

    #[ORM\Column(type:"string", length:255)]
    private string $name;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $slug = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $description = null;

    #[ORM\Column(type:"integer")]
    private int $stock = 0;

    #[ORM\Column(type:"float", nullable:true)]
    private ?float $price = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $mainImage = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $modifiedAt;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->name = $product->getName() ?? '';
        $this->slug = $product->getSlug();
        $this->description = $product->getDescription();
        $this->stock = $product->getStock() ?? 0;
        $this->price = $product->getPrice();

        // Première illustration si elle existe
        $this->mainImage = $product->getIllustrations()->isEmpty() ? null : $product->getIllustrations()->first()->getImage();

        // On récupère updatedAt du produit pour la date de modification
        $this->modifiedAt = $product->getUpdatedAt();
    }

    // ------------------- GETTERS -------------------

    public function getId(): ?int { return $this->id; }
    public function getProduct(): Product { return $this->product; }
    public function getName(): string { return $this->name; }
    public function getSlug(): ?string { return $this->slug; }
    public function getDescription(): ?string { return $this->description; }
    public function getStock(): int { return $this->stock; }
    public function getPrice(): ?float { return $this->price; }
    public function getMainImage(): ?string { return $this->mainImage; }
    public function getModifiedAt(): \DateTimeInterface { return $this->modifiedAt; }
}
