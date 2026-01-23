<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Product;
use App\Entity\CategoryAccessory;
use App\Validator\UniqueAutoPromo;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueAutoPromo]
class Promotion
{
    public const TARGET_ALL = 'all';
    public const TARGET_CATEGORY_ACCESS = 'category_access';
    public const TARGET_PRODUCT = 'product';
    public const TARGET_PRODUCT_LIST = 'product_list';

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $titre = null;

    #[ORM\Column(type:"string", length:100, nullable:true)]
    private ?string $code = null;

    #[ORM\Column(type:"string", length:30)]
    private string $targetType;

    #[ORM\Column(type:"float", nullable:true)]
    private ?float $discountAmount = null;

    #[ORM\Column(type:"float", nullable:true)]
    private ?float $discountPercent = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type:"datetime", nullable:true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type:"integer")]
    private int $quantity = 1;

    #[ORM\Column(type:"integer")]
    private int $used = 0;

    #[ORM\Column(type:"boolean")]
    private bool $autoApply = false;

    // --- RELATIONS ---
    #[ORM\ManyToOne(targetEntity: CategoryAccessory::class)]
    private ?CategoryAccessory $categoryAccess = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    private ?Product $product = null;

    #[ORM\ManyToMany(targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->startDate = new \DateTimeImmutable();
    }

    // ----------------- GETTERS / SETTERS ------------------
    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(?string $titre): self { $this->titre = $titre; return $this; }

    public function getCode(): ?string { return $this->code; }
    public function setCode(?string $code): self { $this->code = $code; return $this; }

    public function getTargetType(): string { return $this->targetType; }
    public function setTargetType(string $type): self
    {
        $allowed = [
            self::TARGET_ALL,
            self::TARGET_CATEGORY_ACCESS,
            self::TARGET_PRODUCT,
            self::TARGET_PRODUCT_LIST
        ];

        if (!in_array($type, $allowed, true)) {
            throw new \InvalidArgumentException("Invalid targetType '$type'");
        }

        $this->targetType = $type;
        return $this;
    }

    public function getDiscountAmount(): ?float { return $this->discountAmount; }
    public function setDiscountAmount(?float $discountAmount): self { $this->discountAmount = $discountAmount; return $this; }

    public function getDiscountPercent(): ?float { return $this->discountPercent; }
    public function setDiscountPercent(?float $discountPercent): self { $this->discountPercent = $discountPercent; return $this; }

    public function getStartDate(): \DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $start): self { $this->startDate = $start; return $this; }

    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $end): self { $this->endDate = $end; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getUsed(): int { return $this->used; }
    public function incrementUsed(): self { $this->used++; return $this; }

    public function isAutoApply(): bool { return $this->autoApply; }
    public function setAutoApply(bool $auto): self { $this->autoApply = $auto; return $this; }

    public function getCategoryAccess(): ?CategoryAccessory { return $this->categoryAccess; }
    public function setCategoryAccess(?CategoryAccessory $category): self
    {
        if ($this->targetType !== self::TARGET_CATEGORY_ACCESS && $category !== null) {
            throw new \LogicException("Cannot set categoryAccess when targetType is '{$this->targetType}'");
        }
        $this->categoryAccess = $category;
        return $this;
    }

    public function getProduct(): ?Product { return $this->product; }
    public function setProduct(?Product $product): self
    {
        if ($this->targetType !== self::TARGET_PRODUCT && $product !== null) {
            throw new \LogicException("Cannot set product when targetType is '{$this->targetType}'");
        }
        $this->product = $product;
        return $this;
    }

    public function getProducts(): Collection { return $this->products; }
    public function addProduct(Product $product): self
    {
        if ($this->targetType !== self::TARGET_PRODUCT_LIST) {
            throw new \LogicException("Cannot add products when targetType is '{$this->targetType}'");
        }
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }
    public function removeProduct(Product $product): self { $this->products->removeElement($product); return $this; }

    // ----------------- LOGIQUE ------------------
    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->startDate <= $now && ($this->endDate === null || $now <= $this->endDate);
    }

    public function isAvailable(): bool { return $this->used < $this->quantity; }

    public function canBeUsed(): bool { return $this->isActive() && $this->isAvailable(); }

    public function isDiscountValid(): bool { return $this->discountAmount !== null || $this->discountPercent !== null; }

    public function isValidForTarget(): bool
    {
        return match($this->targetType) {
            self::TARGET_ALL => $this->categoryAccess === null && $this->product === null && $this->products->isEmpty(),
            self::TARGET_CATEGORY_ACCESS => $this->categoryAccess !== null && $this->product === null && $this->products->isEmpty(),
            self::TARGET_PRODUCT => $this->categoryAccess === null && $this->product !== null && $this->products->isEmpty(),
            self::TARGET_PRODUCT_LIST => $this->categoryAccess === null && $this->product === null && !$this->products->isEmpty(),
            default => false,
        };
    }

    public function isExpired(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->endDate !== null && $this->endDate < $now;
    }

    // -------------------------
    // VALIDATION CUSTOM
    // -------------------------
    #[Assert\Callback]
    public function validateDiscount(ExecutionContextInterface $context): void
    {
        if ($this->discountAmount !== null && $this->discountPercent !== null) {
            $context->buildViolation('Vous ne pouvez remplir qu’un seul champ : montant ou pourcentage.')
                ->atPath('discountAmount')
                ->addViolation();

            $context->buildViolation('Vous ne pouvez remplir qu’un seul champ : montant ou pourcentage.')
                ->atPath('discountPercent')
                ->addViolation();
        }

        if ($this->discountAmount === null && $this->discountPercent === null) {
            $context->buildViolation('Vous devez remplir soit le montant, soit le pourcentage.')
                ->atPath('discountAmount')
                ->addViolation();
        }
    }
}
