<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Order $myOrder = null;

    // Snapshot du nom du produit au moment de l'achat
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $product = null;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Product $productEntity = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = 1;

    // Prix unitaire HT avant réduction
    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    // Prix unitaire HT effectif après réduction
    #[ORM\Column(type: 'float')]
    private ?float $priceAfterReduc = null;

    // Taux de TVA (ex: 21.0 ou 20.0)
    #[ORM\Column(type: 'float')]
    private ?float $tva = 20.0;

    // Prix unitaire TTC effectif
    #[ORM\Column(type: 'float')]
    private ?float $priceTtc = null;

    // Total de la ligne TTC (quantité * priceTtc)
    #[ORM\Column(type: 'float')]
    private ?float $totalTtc = null;

    #[ORM\Column(type: 'float')]
    private float $weight = 0.0;

    public function __toString(): string
    {
        return sprintf('%s (x%d)', $this->product, $this->quantity);
    }

    public function getId(): ?int { return $this->id; }

    public function getMyOrder(): ?Order { return $this->myOrder; }
    public function setMyOrder(?Order $myOrder): self { $this->myOrder = $myOrder; return $this; }

    public function getProduct(): ?string { return $this->product; }
    public function setProduct(string $product): self { $this->product = $product; return $this; }

    public function getProductEntity(): ?Product { return $this->productEntity; }
    public function setProductEntity(?Product $productEntity): self { $this->productEntity = $productEntity; return $this; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getPriceAfterReduc(): ?float { return $this->priceAfterReduc; }
    public function setPriceAfterReduc(float $priceAfterReduc): self { $this->priceAfterReduc = $priceAfterReduc; return $this; }

    public function getTva(): ?float { return $this->tva; }
    public function setTva(float $tva): self { $this->tva = $tva; return $this; }

    public function getPriceTtc(): ?float { return $this->priceTtc; }
    public function setPriceTtc(float $priceTtc): self { $this->priceTtc = $priceTtc; return $this; }

    public function getTotalTtc(): ?float { return $this->totalTtc; }
    public function setTotalTtc(float $totalTtc): self { $this->totalTtc = $totalTtc; return $this; }

    public function getWeight(): float { return $this->weight; }
    public function setWeight(float $weight): self { $this->weight = $weight; return $this; }
}
