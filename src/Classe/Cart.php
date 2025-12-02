<?php

namespace App\Classe;

use App\Entity\Accessory;
use App\Entity\Promotion;
use App\Entity\Trottinette;
use App\Service\PromotionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private EntityManagerInterface $entityManager;
    private ?SessionInterface $session;
    private float $reduction = 0.0;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;

        // Récupère la session si elle existe
        $this->session = $requestStack->getSession();

        // Démarre la session si elle existe mais n'est pas encore active
        if ($this->session && !$this->session->isStarted()) {
            $this->session->start();
        }
    }

    // ------------------- Code promo -------------------
    public function setPromoCode(?string $code): void
    {
        if ($this->session) {
            $this->session->set('promo_code', $code);
        }
    }

    public function getPromoCode(): ?string
    {
        return $this->session ? $this->session->get('promo_code') : null;
    }

    // ------------------- Ajout au panier -------------------
    public function add(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        $cart[$type][$id] = ($cart[$type][$id] ?? 0) + 1;
        $this->session->set('cart', $cart);
    }

    // ------------------- Récupère le panier -------------------
    public function get(): array
    {
        return $this->session ? $this->session->get('cart', []) : [];
    }

    // ------------------- Supprime tout le panier -------------------
    public function remove(): void
    {
        if ($this->session) {
            $this->session->remove('cart');
        }
    }

    // ------------------- Supprime un élément -------------------
    public function delete(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        unset($cart[$type][$id]);
        $this->session->set('cart', $cart);
    }

    // ------------------- Diminue la quantité -------------------
    public function decrease(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        if (!empty($cart[$type][$id])) {
            if ($cart[$type][$id] > 1) {
                $cart[$type][$id]--;
            } else {
                unset($cart[$type][$id]);
            }
            $this->session->set('cart', $cart);
        }
    }

    // ------------------- Récupère le panier complet -------------------
    public function getFull(): array
    {
        $cartComplete = [];
        if (!$this->session) return $cartComplete;

        $cart = $this->get();

        // ---- Trottinettes ----
        if (!empty($cart['trottinette'])) {
            foreach ($cart['trottinette'] as $id => $quantity) {
                $product = $this->entityManager->getRepository(Trottinette::class)->find($id);
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

        // ---- Accessoires ----
        if (!empty($cart['accessory'])) {
            foreach ($cart['accessory'] as $id => $quantity) {
                $product = $this->entityManager->getRepository(Accessory::class)->find($id);
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

    // ------------------- Poids total du panier -------------------
    public function getTotalWeight(): float
    {
        $totalWeight = 0.0;
        foreach ($this->getFull() as $element) {
            $weightObj = $element['product']->getWeight();
            if ($weightObj) {
                $totalWeight += $weightObj->getKg() * $element['quantity'];
            }
        }
        return $totalWeight;
    }

    // ------------------- Réduction ------------------- Plus besoin de setReduction(), la valeur est toujours recalculée
    /* public function setReduction(float $montant): void
    {
        $this->reduction = $montant;

        // 👉 On stocke aussi en session pour ne pas perdre la valeur
        if ($this->session) {
            $this->session->set('promo_reduction', $montant);
        }
    } */

    // Calcule la réduction actuelle selon le panier et la promo en session
    public function getReduction(PromotionService $promoService, ?Promotion $promo = null): float
    {
        $cartFull = $this->getFull();

        // Si aucun promo fourni, on regarde le code en session
        if (!$promo) {
            $promoCode = $this->getPromoCode();
            if (!$promoCode) return 0;

            $promo = $this->entityManager->getRepository(Promotion::class)
                ->findOneBy(['code' => $promoCode]);

            if (!$promo) return 0;
        }

        return $promoService->calculateReduction($cartFull, $promo);
    }

    // ------------------- Réinitialiser code promo et réduction -------------------
    public function clearPromos(): void
    {
        if ($this->session) {
            $this->session->remove('promo_code');
        }
    }

    // ------------------- Quantité totale -------------------
    public function getTotalQuantity(): int
    {
        $total = 0;
        foreach ($this->getFull() as $element) {
            $total += $element['quantity'];
        }
        return $total;
    }

    // ------------------- Prix livraison -------------------
    public function getLivraisonPrice(\App\Repository\WeightRepository $weightRepository): float
    {
        $poids = $this->getTotalWeight();
        $weightEntity = $weightRepository->findByKgPrice($poids);
        return $weightEntity ? $weightEntity->getPrice() : 0;
    }

    // ------------------- Réduction TTC (pour affichage) -------------------
    public function getDiscountTTC(PromotionService $promoService, array $allPromotions): float
    {
        $cartFull = $this->getFull();
        if (empty($cartFull)) return 0;

        $promo = $this->getBestPromotion($promoService, $allPromotions);
        if (!$promo) return 0;

        $discountTTC = 0;
        foreach ($cartFull as $item) {
            $product  = $item['product'];
            $quantity = $item['quantity'];

            if ($promo->getDiscountAmount() !== null) {
                $unitDiscountHT = $promo->getDiscountAmount();
            } elseif ($promo->getDiscountPercent() !== null) {
                $unitDiscountHT = $product->getPrice() * ($promo->getDiscountPercent() / 100);
            } else {
                $unitDiscountHT = 0;
            }

            $tvaRate = $product->getTva()?->getValue() / 100 ?? 0;
            $unitDiscountTTC = $unitDiscountHT * (1 + $tvaRate);
            $discountTTC += $unitDiscountTTC * $quantity;
        }

        return $discountTTC;
    }


    // ------------------- Récupère la meilleure promotion applicable -------------------
    public function getBestPromotion(PromotionService $promoService, array $allPromotions): ?Promotion
    {
        $cartFull = $this->getFull();
        if (empty($cartFull)) return null;

        // 1️⃣ Promo manuelle via code promo
        $promoCode = $this->getPromoCode();
        if ($promoCode) {
            $manualPromo = $this->entityManager->getRepository(Promotion::class)
                ->findOneBy(['code' => $promoCode]);

            if ($manualPromo && $promoService->calculateReduction($cartFull, $manualPromo) > 0) {
                // Si code promo valide, on l'applique directement
                return $manualPromo;
            }

            // Si le code promo est invalide, on peut le nettoyer
            $this->clearPromos();
            return null;
        }

        // 2️⃣ Sinon, promo automatique
        return $promoService->getAutomaticPromotion($cartFull, $allPromotions);
    }

}
