<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Entity\Product;

class PromotionService
{
    /**
     * Calcule la réduction totale applicable sur un panier complet
     *
     * @param array $cartFull Tableau du panier complet (comme retourné par Cart::getFull())
     * @param Promotion|null $promo La promotion à appliquer
     * @return float Montant total de la réduction
     */
    public function calculateReduction(array $cartFull, ?Promotion $promo = null): float
    {
        if (!$promo || !$promo->canBeUsed() || !$promo->isDiscountValid()) {
            return 0;
        }

        $totalReduction = 0;

        foreach ($cartFull as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];
            $unitPriceHT = $product->getPrice(); // toujours HT

            switch ($promo->getTargetType()) {
                case Promotion::TARGET_ALL:
                    // Réduction sur le total HT du panier
                    $totalHT = 0;
                    foreach ($cartFull as $p) {
                        $totalHT += $p['product']->getPrice() * $p['quantity'];
                    }

                    $totalReduction = $promo->getDiscountAmount() ?? ($totalHT * ($promo->getDiscountPercent() / 100));
                    break;

                case Promotion::TARGET_CATEGORY_ACCESS:
                    if ($product->getType() === 'accessoire' && $product->getCategory() === $promo->getCategoryAccess()) {
                        if ($promo->getDiscountAmount() !== null) {
                            // Promo en € (montant fixe)
                            $totalReduction += $promo->getDiscountAmount() * $quantity;
                        } elseif ($promo->getDiscountPercent() !== null) {
                            // Promo en % sur HT
                            $totalReduction += ($unitPriceHT * ($promo->getDiscountPercent() / 100)) * $quantity;
                        }
                    }
                    break;

                case Promotion::TARGET_PRODUCT:
                    if ($product === $promo->getProduct()) {
                        if ($promo->getDiscountAmount() !== null) {
                            $totalReduction += $promo->getDiscountAmount() * $quantity;
                        } elseif ($promo->getDiscountPercent() !== null) {
                            $totalReduction += ($unitPriceHT * ($promo->getDiscountPercent() / 100)) * $quantity;
                        }
                    }
                    break;

                case Promotion::TARGET_PRODUCT_LIST:
                    if ($promo->getProducts()->contains($product)) {
                        if ($promo->getDiscountAmount() !== null) {
                            $totalReduction += $promo->getDiscountAmount() * $quantity;
                        } elseif ($promo->getDiscountPercent() !== null) {
                            $totalReduction += ($unitPriceHT * ($promo->getDiscountPercent() / 100)) * $quantity;
                        }
                    }
                    break;
            }
        }

        return max(0, $totalReduction);
    }


    /**
     * Applique une promotion sur un prix unitaire d'un produit
     * (utile pour recalculer le prix affiché d'une ligne)
     */
    public function applyPromotion(Promotion $promo, float $price, Product $product): float
    {
        return max(0, $price - $this->calculateReduction([['product' => $product, 'quantity' => 1]], $promo));
    }

    /**
     * Retourne une promotion automatique applicable au panier (ou null)
     */
    public function getAutomaticPromotion(array $cartFull, iterable $allPromos = []): ?Promotion
    {
        // On filtre uniquement les promotions autoApply
        foreach ($allPromos as $promo) {

            // On ignore les promos non automatiques
            if (!$promo->isAutoApply()) {
                continue;
            }

            // On ignore celles qui ne peuvent pas être utilisées
            if (!$promo->canBeUsed() || !$promo->isDiscountValid()) {
                continue;
            }

            // Test : est-ce que cette promo donne réellement une réduction ?
            $reduction = $this->calculateReduction($cartFull, $promo);

            if ($reduction > 0) {
                return $promo; // ✅ Promo auto applicable trouvée
            }
        }

        return null; // ❌ Aucune promo auto applicable
    }

}
