<?php

namespace App\Classe;

use App\Entity\Accessory;
use App\Entity\Promotion;
use App\Entity\Trottinette;
use App\Service\PromotionService;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private EntityManagerInterface $entityManager;
    private WeightRepository $weightRepository;
    private ?SessionInterface $session;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, WeightRepository$weightRepository)
    {
        $this->entityManager =$entityManager;
        $this->weightRepository =$weightRepository;
        $this->session =$requestStack->getSession();

        if ($this->session && !$this->session->isStarted()) {$this->session->start();
        }
    }

    // ------------------- Code promo -------------------
    public function setPromoCode(?string $code): void
    {
        if ($this->session) {
            $this->session->set('promo_code',$code);
        }
    }

    public function getPromoCode(): ?string
    {
        return $this->session ? $this->session->get('promo_code') : null;
    }

    public function clearPromos(): void
    {
        if ($this->session) {$this->session->remove('promo_code');
        }
    }

    // ------------------- Gestion des articles -------------------
    public function add(int $id, string$type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart =$this->session->get('cart', []);
        $cart[$type][$id] = ($cart[$type][$id] ?? 0) + 1;
        $this->session->set('cart',$cart);
    }

    public function get(): array
    {
        return $this->session ? $this->session->get('cart', []) : [];
    }

    public function remove(): void
    {
        if ($this->session) {$this->session->remove('cart');
        }
    }

    public function delete(int $id, string$type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart =$this->session->get('cart', []);
        unset($cart[$type][$id]);
        $this->session->set('cart',$cart);
    }

    public function decrease(int $id, string$type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart =$this->session->get('cart', []);
        if (!empty($cart[$type][$id])) {
            if ($cart[$type][$id] > 1) {$cart[$type][$id]--;
            } else {
                unset($cart[$type][$id]);
            }
            $this->session->set('cart',$cart);
        }
    }

    // ------------------- Panier complet avec entités -------------------
    public function getFull(): array
    {
        $cartComplete = [];
        if (!$this->session) return$cartComplete;

        $cart =$this->get();

        // Trottinettes
        if (!empty($cart['trottinette'])) {
            foreach ($cart['trottinette'] as$id => $quantity) {$product = $this->entityManager->getRepository(Trottinette::class)->find($id);
                if (!$product) {
                    $this->delete($id, 'trottinette');
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => 'trottinette'
                ];
            }
        }

        // Accessoires
        if (!empty($cart['accessory'])) {
            foreach ($cart['accessory'] as$id => $quantity) {$product = $this->entityManager->getRepository(Accessory::class)->find($id);
                if (!$product) {
                    $this->delete($id, 'accessory');
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => 'accessory'
                ];
            }
        }

        return $cartComplete;
    }

    // ------------------- Poids & Frais de port -------------------
    public function getTotalWeight(): float
    {
        $totalWeight = 0.0;
        foreach ($this->getFull() as $element) {$weight = (float)($element['product']->getWeight() ?? 0.0);$totalWeight += $weight * (int)$element['quantity'];
        }
        return $totalWeight;
    }

    public function getShippingPrice(): float
    {
        $totalWeight = $this->getTotalWeight();$weightEntity = $this->weightRepository->findPriceByWeight($totalWeight);

        return $weightEntity ? (float)$weightEntity->getPrice() : 0.0;
    }

    public function getLivraisonPrice(WeightRepository $weightRepository): float
    {
        $weightEntity = $weightRepository->findPriceByWeight($this->getTotalWeight());
        return $weightEntity ? (float)$weightEntity->getPrice() : 0.0;
    }

    public function getTotalQuantity(): int
    {
        $total = 0;
        foreach ($this->getFull() as$element) {
            $total += (int)$element['quantity'];
        }
        return $total;
    }

    // ------------------- Sous-total TTC catalogue brut -------------------
    public function getSubtotalTtc(): float
    {
        $total = 0.0;
        foreach ($this->getFull() as$element) {
            $product =$element['product'];
            $price = (float)($product->getPromoPrice() ?? $product->getPrice());$total += $price * (int)$element['quantity'];
        }
        return $total;
    }

    // ------------------- Promotions & Réductions -------------------
    public function getReduction(PromotionService $promoService, ?Promotion $promo = null): float
    {
        $cartFull =$this->getFull();

        if (!$promo) {
            $promoCode =$this->getPromoCode();
            if (!$promoCode) return 0.0;

            $promo =$this->entityManager->getRepository(Promotion::class)
                ->findOneBy(['code' => $promoCode]);

            if (!$promo) return 0.0;
        }

        return $promoService->calculateReduction($cartFull,$promo);
    }

    public function getDiscountTTC(PromotionService $promoService, array$allPromotions): float
    {
        $cartFull =$this->getFull();
        if (empty($cartFull)) return 0.0;

        $promo =$this->getBestPromotion($promoService,$allPromotions);
        if (!$promo) return 0.0;

        // Le prix du produit étant déjà en TTC, calculateReduction renvoie directement le montant TTC exact
        return $promoService->calculateReduction($cartFull,$promo);
    }

    public function getBestPromotion(PromotionService $promoService, array$allPromotions): ?Promotion
    {
        $cartFull =$this->getFull();
        if (empty($cartFull)) return null;

        $promoCode =$this->getPromoCode();
        if ($promoCode) {
            $manualPromo =$this->entityManager->getRepository(Promotion::class)
                ->findOneBy(['code' => $promoCode]);

            if ($manualPromo &&$promoService->calculateReduction($cartFull,$manualPromo) > 0) {
                return $manualPromo;
            }

            $this->clearPromos();
            return null;
        }

        return $promoService->getAutomaticPromotion($cartFull,$allPromotions);
    }

    public function getDiscountName(PromotionService $promoService, array$allPromotions): ?string
    {
        $promo =$this->getBestPromotion($promoService,$allPromotions);
        return $promo ? $promo->getTitre() : null;
    }
}
