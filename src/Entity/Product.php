<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Illustration;
use App\Entity\Weight;
use App\Entity\Tva;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap([
    "trottinette" => Trottinette::class,
    "accessory" => Accessory::class
])]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    protected ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    protected ?string $name = null;

    #[ORM\Column(type:"string", length:255, unique:true)]
    protected ?string $slug = null;

    #[ORM\Column(type:"text", nullable:true)]
    protected ?string $description = null;

    #[ORM\Column(type:"float")]
    protected ?float $price = null;

    #[ORM\Column(type:"integer")]
    protected ?int $stock = null;

    #[ORM\Column(type:"boolean")]
    protected bool $isBest = false;

    #[ORM\ManyToOne(targetEntity: Weight::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Weight $weight = null;

    #[ORM\ManyToOne(targetEntity: Tva::class)]
    #[ORM\JoinColumn(nullable: false)]
    protected ?Tva $tva = null;

    #[ORM\OneToMany(mappedBy: "product", targetEntity: Illustration::class, cascade: ["persist", "remove"])]
    protected Collection $illustrations;

    #[ORM\Column(type:"datetime")]
    protected \DateTimeInterface $createdAt;

    #[ORM\Column(type:"datetime")]
    protected \DateTimeInterface $updatedAt;

    // Permet de récupérer automatiquement le bon dossier uploads
    public function getUploadDirectory(): string
    {
        // valeur par défaut (si jamais un produit n'est ni trottinette ni accessoire)
        return 'produits';
    }

    public function __construct()
    {
        $this->illustrations = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    // ------------------------ GETTERS / SETTERS ------------------------

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getStock(): ?int { return $this->stock; }
    public function setStock(int $stock): self { $this->stock = $stock; return $this; }

    public function getIsBest(): bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    public function getWeight(): ?Weight { return $this->weight; }
    public function setWeight(Weight $weight): self { $this->weight = $weight; return $this; }

    public function getTva(): ?Tva { return $this->tva; }
    public function setTva(Tva $tva): self { $this->tva = $tva; return $this; }

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
            $illustration->setProduct(null);
        }
        return $this;
    }

    // ------------------------ CREATED / UPDATED ------------------------

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeInterface { return $this->updatedAt; }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
