<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "trottinette" => Trottinette::class,
    "accessoire" => Accessory::class
])]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    protected ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    protected ?string $name = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    protected ?string $slug = null;

    #[ORM\Column(type: "text", nullable: true)]
    protected ?string $description = null;

    #[ORM\Column(type: "float")]
    protected ?float $price = null; // Prix HT

    #[ORM\Column(type: "float", nullable: true)]
    protected ?float $promoPrice = null; // Prix promo HT optionnel

    #[ORM\Column(type: "integer")]
    protected ?int $stock = 0;

    #[ORM\Column(type: "boolean")]
    protected bool $isBest = false;

    #[ORM\Column(type: "float")]
    protected float $weight = 1.0;

    #[ORM\ManyToOne(targetEntity: Tva::class)]
    #[ORM\JoinColumn(nullable: true)]
    protected ?Tva $tva = null;

    #[ORM\OneToMany(mappedBy: "product", targetEntity: Illustration::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    protected Collection $illustrations;

    #[ORM\Column(type: "datetime_immutable")]
    protected \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime_immutable")]
    protected \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setSlugValue(): void
    {
        if ($this->name) {
            $slugger = new AsciiSlugger();
            if (!$this->slug || strtolower((string)$slugger->slug($this->name)) !== $this->slug) {
                $this->slug = strtolower((string)$slugger->slug($this->name));
            }
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getUploadDirectory(): string
    {
        return 'produits';
    }

    public function getFirstIllustration(): ?string
    {
        $first = $this->illustrations->first();
        return $first ? $first->getImagePath() : null;
    }

    public function getType(): string
    {
        if ($this instanceof Trottinette) {
            return 'trottinette';
        }
        if ($this instanceof Accessory) {
            return 'accessoire';
        }
        return 'product';
    }

    public function getPriceTtc(): float
    {
        $rate = $this->tva ? ($this->tva->getValue() / 100) : 0.20;
        return $this->price * (1 + $rate);
    }

    public function getPromoPriceTtc(): ?float
    {
        if (!$this->promoPrice || $this->promoPrice <= 0) {
            return null;
        }
        $rate = $this->tva ? ($this->tva->getValue() / 100) : 0.20;
        return $this->promoPrice * (1 + $rate);
    }

    // ------------------- GETTERS & SETTERS -------------------

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getPromoPrice(): ?float { return $this->promoPrice; }
    public function setPromoPrice(?float $promoPrice): self { $this->promoPrice = $promoPrice; return $this; }

    public function getStock(): ?int { return $this->stock; }
    public function setStock(int $stock): self { $this->stock = $stock; return $this; }

    public function getIsBest(): bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    public function getWeight(): float { return $this->weight; }
    public function setWeight(float $weight): self
    {
        if ($weight <= 0) {
            throw new \InvalidArgumentException('Le poids doit être supérieur à 0');
        }
        $this->weight = $weight;
        return $this;
    }

    public function getTva(): ?Tva { return $this->tva; }
    public function setTva(?Tva $tva): self { $this->tva = $tva; return $this; }

    /** @return Collection<int, Illustration> */
    public function getIllustrations(): Collection { return $this->illustrations; }

    public function addIllustration(Illustration $illustration): self
    {
        if (!$this->illustrations->contains($illustration)) {
            $this->illustrations[] = $illustration;
            $illustration->setProduct($this);
        }
        return $this;
    }

    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustrations->removeElement($illustration)) {
            if ($illustration->getProduct() === $this) {
                $illustration->setProduct(null);
            }
        }
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
