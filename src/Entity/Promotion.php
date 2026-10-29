<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class Promotion
{
    public const TARGET_ALL = 'all';
    public const TARGET_ALL_TROTTINETTES = 'all_trottinettes';
    public const TARGET_ALL_ACCESSORIES = 'all_accessories';
    public const TARGET_CATEGORY_ACCESS = 'category_access';
    public const TARGET_PRODUCT = 'product';
    public const TARGET_PRODUCT_LIST = 'product_list';

    public const TYPE_PERCENTAGE = 'PERCENTAGE';
    public const TYPE_FIXED_AMOUNT = 'FIXED_AMOUNT';

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: "string", length: 100, unique: true, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(type: "string", length: 30)]
    private string $targetType = self::TARGET_ALL;

    #[ORM\Column(type: "string", length: 20)]
    private string $discountType = self::TYPE_PERCENTAGE;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $discountAmount = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $discountPercent = null;

    #[ORM\Column(type: "datetime_immutable")]
    private \DateTimeImmutable $startDate;

    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(type: "integer")]
    private int $quantity = 100;

    #[ORM\Column(type: "integer")]
    private int $used = 0;

    #[ORM\Column(type: "boolean")]
    private bool $autoApply = false;

    #[ORM\ManyToOne(targetEntity: CategoryAccessory::class)]
    private ?CategoryAccessory $categoryAccess = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Product $product = null;

    #[ORM\ManyToMany(targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->startDate = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): self { $this->titre = $titre; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(?string $code): self
    {
        $this->code = $code ? strtoupper(trim($code)) : null;
        return $this;
    }

    public function getTargetType(): string { return $this->targetType; }
    public function setTargetType(string $type): self { $this->targetType = $type; return $this; }

    public function getDiscountType(): string { return $this->discountType; }
    public function setDiscountType(string $discountType): self { $this->discountType = $discountType; return $this; }

    public function getDiscountAmount(): ?float { return $this->discountAmount; }
    public function setDiscountAmount(?float $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        if ($discountAmount !== null) {
            $this->discountType = self::TYPE_FIXED_AMOUNT;
        }
        return $this;
    }

    public function getDiscountPercent(): ?float { return $this->discountPercent; }
    public function setDiscountPercent(?float $discountPercent): self
    {
        $this->discountPercent = $discountPercent;
        if ($discountPercent !== null) {
            $this->discountType = self::TYPE_PERCENTAGE;
        }
        return $this;
    }

    public function getStartDate(): \DateTimeImmutable { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $start): self
    {
        $this->startDate = $start instanceof \DateTimeImmutable ? $start : \DateTimeImmutable::createFromInterface($start);
        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $end): self
    {
        $this->endDate = $end ? ($end instanceof \DateTimeImmutable ? $end : \DateTimeImmutable::createFromInterface($end)) : null;
        return $this;
    }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getUsed(): int { return $this->used; }
    public function incrementUsed(): self { $this->used++; return $this; }

    public function isAutoApply(): bool { return $this->autoApply; }
    public function setAutoApply(bool $auto): self { $this->autoApply = $auto; return $this; }

    public function getCategoryAccess(): ?CategoryAccessory { return $this->categoryAccess; }
    public function setCategoryAccess(?CategoryAccessory $category): self { $this->categoryAccess = $category; return $this; }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self { $this->product = $product; return $this; }

    /** @return Collection<int, Product> */
    public function getProducts(): Collection { return $this->products; }
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }
    public function removeProduct(Product $product): self { $this->products->removeElement($product); return $this; }

    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->startDate <= $now && ($this->endDate === null || $now <= $this->endDate);
    }

    public function isAvailable(): bool { return $this->used < $this->quantity; }

    public function canBeUsed(): bool { return $this->isActive() && $this->isAvailable(); }

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->endDate !== null && $this->endDate < $now;
    }

    public function isDiscountValid(): bool
    {
        return $this->discountAmount !== null || $this->discountPercent !== null;
    }

    #[Assert\Callback]
    public function validateDiscount(ExecutionContextInterface $context): void
    {
        if ($this->discountAmount === null && $this->discountPercent === null) {
            $context->buildViolation('Vous devez définir au moins une valeur de réduction (% ou €).')
                ->atPath('discountType')
                ->addViolation();
        }
    }
}
