<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    public const PAYMENT_UNPAID = 0;
    public const PAYMENT_PAID = 1;

    public const STATE_WAITING = 0;
    public const STATE_PREPARATION = 1;
    public const STATE_SHIPPING = 2;
    public const STATE_DELIVERED = 3;
    public const STATE_CANCELED = 4;

    public const DELIVERY_STATES = [
        self::STATE_WAITING      => 'Commande en attente',
        self::STATE_PREPARATION  => 'Préparation en cours',
        self::STATE_SHIPPING     => 'Livraison en cours',
        self::STATE_DELIVERED    => 'Livraison terminée',
        self::STATE_CANCELED     => 'Annulée',
    ];

    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: 'float')]
    private float $carrierPrice = 0.0;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $carrier = 'bpost';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $trackingNumber = null;

    #[ORM\Column(type: 'text')]
    private ?string $delivery = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $deliveryCountry = 'BE';

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $stripeSessionId = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $promoCode = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $promoTitre = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $promoReduction = 0.0;

    #[ORM\Column(type: 'integer')]
    private int $paymentState = self::PAYMENT_UNPAID;

    #[ORM\Column(type: 'integer')]
    private int $deliveryState = self::STATE_WAITING;

    #[ORM\OneToMany(mappedBy: 'myOrder', targetEntity: OrderDetails::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->reference ?? 'Commande n°' . $this->id;
    }

    /** Total TTC des produits seuls (après réductions) */
    public function getSubtotalTtc(): float
    {
        $subtotal = 0.0;
        foreach ($this->orderDetails as $detail) {
            $subtotal += $detail->getTotalTtc();
        }
        return $subtotal;
    }

    /** Total TTC global final (produits après promo + frais de port) */
    public function getTotalTtc(): float
    {
        return $this->getSubtotalTtc() + $this->carrierPrice;
    }

    /** Pour rétrocompatibilité avec tes anciens templates */
    public function getTotal(): float
    {
        return $this->getTotalTtc();
    }

    public function getWeightTotal(): float
    {
        $totalWeight = 0.0;
        foreach ($this->orderDetails as $detail) {
            $totalWeight += $detail->getWeight() * $detail->getQuantity();
        }
        return $totalWeight;
    }

    public function getDeliveryStateLabel(): string
    {
        return self::DELIVERY_STATES[$this->deliveryState] ?? 'Statut inconnu';
    }

    public function getOrderStatusLabel(): string
    {
        if ($this->paymentState === self::PAYMENT_UNPAID) {
            return 'Non payée';
        }
        return 'Payée - ' . $this->getDeliveryStateLabel();
    }

    // ------------------- GETTERS & SETTERS -------------------

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getReference(): ?string { return $this->reference; }
    public function setReference(string $reference): self { $this->reference = $reference; return $this; }

    public function getCarrierPrice(): float { return $this->carrierPrice; }
    public function setCarrierPrice(float $carrierPrice): self { $this->carrierPrice = $carrierPrice; return $this; }

    public function getCarrier(): ?string { return $this->carrier; }
    public function setCarrier(?string $carrier): self { $this->carrier = $carrier; return $this; }

    public function getTrackingNumber(): ?string { return $this->trackingNumber; }
    public function setTrackingNumber(?string $trackingNumber): self { $this->trackingNumber = $trackingNumber; return $this; }

    public function getDelivery(): ?string { return $this->delivery; }
    public function setDelivery(string $delivery): self { $this->delivery = $delivery; return $this; }

    public function getDeliveryCountry(): ?string { return $this->deliveryCountry; }
    public function setDeliveryCountry(?string $country): self { $this->deliveryCountry = $country; return $this; }

    public function getStripeSessionId(): ?string { return $this->stripeSessionId; }
    public function setStripeSessionId(?string $stripeSessionId): self { $this->stripeSessionId = $stripeSessionId; return $this; }

    public function getPromoCode(): ?string { return $this->promoCode; }
    public function setPromoCode(?string $promoCode): self { $this->promoCode = $promoCode; return $this; }

    public function getPromoTitre(): ?string { return $this->promoTitre; }
    public function setPromoTitre(?string $promoTitre): self { $this->promoTitre = $promoTitre; return $this; }

    public function getPromoReduction(): ?float { return $this->promoReduction; }
    public function setPromoReduction(?float $promoReduction): self { $this->promoReduction = $promoReduction; return $this; }

    public function getPaymentState(): int { return $this->paymentState; }
    public function setPaymentState(int $paymentState): self { $this->paymentState = $paymentState; return $this; }

    public function getDeliveryState(): int { return $this->deliveryState; }
    public function setDeliveryState(int $deliveryState): self { $this->deliveryState = $deliveryState; return $this; }

    /** @return Collection<int, OrderDetails> */
    public function getOrderDetails(): Collection { return $this->orderDetails; }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setMyOrder($this);
        }
        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            if ($orderDetail->getMyOrder() === $this) {
                $orderDetail->setMyOrder(null);
            }
        }
        return $this;
    }
}
