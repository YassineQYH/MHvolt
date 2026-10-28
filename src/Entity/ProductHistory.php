<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ProductHistory
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Product $product;

    #[ORM\Column(type: "string", length: 255)]
    private string $name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: "integer")]
    private int $stock = 0;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $price = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $promoPrice = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $modifiedAt;

    public function __construct(Product $product)
    {
        $this->product = $product;
        $this->name = $product->getName() ?? '';
        $this->slug = $product->getSlug();
        $this->description = $product->getDescription();
        $this->stock = $product->getStock() ?? 0;
        $this->price = $product->getPrice();
        $this->promoPrice = $product->getPromoPrice();
        $this->mainImage = $product->getFirstIllustration();
        $this->modifiedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getProduct(): Product { return $this->product; }
    public function getName(): string { return $this->name; }
    public function getSlug(): ?string { return $this->slug; }
    public function getDescription(): ?string { return $this->description; }
    public function getStock(): int { return $this->stock; }
    public function getPrice(): ?float { return $this->price; }
    public function getPromoPrice(): ?float { return $this->promoPrice; }
    public function getMainImage(): ?string { return $this->mainImage; }
    public function getModifiedAt(): \DateTimeImmutable { return $this->modifiedAt; }
}
