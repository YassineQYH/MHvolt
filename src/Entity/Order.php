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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'float')]
    private ?float $carrierPrice = null;

    #[ORM\Column(type: 'text')]
    private ?string $delivery = null;

    #[ORM\OneToMany(mappedBy: 'myOrder', targetEntity: OrderDetails::class)]
    private Collection $orderDetails;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $stripeSessionId = null;

    #[ORM\Column(type: 'integer')]
    private ?int $paymentState = 0;

    #[ORM\Column(type: 'integer')]
    private ?int $deliveryState = 0;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $trackingNumber = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $carrier = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $secondaryCarrierTrackingNumber = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

        public function __toString(): string
    {
        return $this->reference ?? 'Commande n°' . $this->id;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->getOrderDetails() as $product) {
            $total += $product->getPrice() * $product->getQuantity();
        }
        return $total;
    }

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getCarrierPrice(): ?float { return $this->carrierPrice; }
    public function setCarrierPrice(float $carrierPrice): self { $this->carrierPrice = $carrierPrice; return $this; }

    public function getDelivery(): ?string { return $this->delivery; }
    public function setDelivery(string $delivery): self { $this->delivery = $delivery; return $this; }

    /**
     * @return Collection|OrderDetails[]
     */
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

    // 🟢 --- GETTERS & SETTERS ---

    public function getReference(): ?string { return $this->reference; }
    public function setReference(string $reference): self { $this->reference = $reference; return $this; }

    public function getStripeSessionId(): ?string { return $this->stripeSessionId; }
    public function setStripeSessionId(?string $stripeSessionId): self { $this->stripeSessionId = $stripeSessionId; return $this; }

    // ✅ Paiement (0 = Non payée, 1 = Payée)
    public function getPaymentState(): ?int { return $this->paymentState; }
    public function setPaymentState(int $paymentState): self { $this->paymentState = $paymentState; return $this; }

    // ✅ Livraison (0 = Préparation en cours, 1 = Livraison en cours)
    public function getDeliveryState(): ?int { return $this->deliveryState; }
    public function setDeliveryState(int $deliveryState): self { $this->deliveryState = $deliveryState; return $this; }

    public function getTrackingNumber(): ?string { return $this->trackingNumber; }
    public function setTrackingNumber(?string $trackingNumber): self { $this->trackingNumber = $trackingNumber; return $this; }

    public function getCarrier(): ?string { return $this->carrier; }
    public function setCarrier(?string $carrier): self { $this->carrier = $carrier; return $this; }

    public function getSecondaryCarrierTrackingNumber(): ?string {return $this->secondaryCarrierTrackingNumber; }
    public function setSecondaryCarrierTrackingNumber(?string $trackingNumber): self {$this->secondaryCarrierTrackingNumber = $trackingNumber;return $this; }

}
