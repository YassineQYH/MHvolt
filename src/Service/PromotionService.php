<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Entity\Product;
use App\Entity\Accessory;
use App\Entity\Trottinette;

class PromotionService
{
    /**
     * Calcule la réduction totale TTC applicable sur le panier
     */
    public function calculateReduction(array $cartFull, ?Promotion $promo = null): float
    {
        if (!$promo || !$promo->canBeUsed() || !$promo->isDiscountValid() || empty($cartFull)) {
            return 0.0;
        }

        // Cas 1 : Réduction globale sur tout le panier
        if ($promo->getTargetType() === Promotion::TARGET_ALL) {
            $total = 0.0;
            foreach ($cartFull as $item) {
                $total += (float)$item['product']->getPrice() * (int)$item['quantity'];
            }

            if ($promo->getDiscountAmount() !== null) {
                return min($total, (float)$promo->getDiscountAmount());
            }

            if ($promo->getDiscountPercent() !== null) {
                return round($total * ((float)$promo->getDiscountPercent() / 100), 2);
            }

            return 0.0;
        }

        // Cas 2 : Réduction ciblée par type, catégorie ou produit
        $totalReduction = 0.0;

        foreach ($cartFull as $item) {
            /** @var Product $product */
            $product = $item['product'];
            $quantity = (int)$item['quantity'];
            $unitPrice = (float)$product->getPrice();

            $isEligible = match ($promo->getTargetType()) {
                Promotion::TARGET_ALL_TROTTINETTES =>
                    $product instanceof Trottinette || $product->getType() === 'trottinette',

                Promotion::TARGET_ALL_ACCESSORIES =>
                    $product instanceof Accessory || $product->getType() === 'accessoire',

                Promotion::TARGET_CATEGORY_ACCESS =>
                    ($product instanceof Accessory || $product->getType() === 'accessoire')
                    && method_exists($product, 'getCategory')
                    && $product->getCategory() === $promo->getCategoryAccess(),

                Promotion::TARGET_PRODUCT =>
                    $product === $promo->getProduct(),

                Promotion::TARGET_PRODUCT_LIST =>
                    $promo->getProducts()->contains($product),

                default => false,
            };

            if ($isEligible) {
                if ($promo->getDiscountAmount() !== null) {
                    $discountPerUnit = min($unitPrice, (float)$promo->getDiscountAmount());
                    $totalReduction += $discountPerUnit * $quantity;
                } elseif ($promo->getDiscountPercent() !== null) {
                    $totalReduction += round(($unitPrice * ((float)$promo->getDiscountPercent() / 100)) * $quantity, 2);
                }
            }
        }

        return max(0.0, $totalReduction);
    }

    public function applyPromotion(Promotion $promo, float $price, Product $product): float
    {
        $reduction = $this->calculateReduction([['product' => $product, 'quantity' => 1]], $promo);
        return max(0.0, $price - $reduction);
    }

    public function getAutomaticPromotion(array $cartFull, iterable $allPromos = []): ?Promotion
    {
        foreach ($allPromos as $promo) {
            if (!$promo->isAutoApply() || !$promo->canBeUsed() || !$promo->isDiscountValid()) {
                continue;
            }

            if ($this->calculateReduction($cartFull, $promo) > 0) {
                return $promo;
            }
        }

        return null;
    }
}
