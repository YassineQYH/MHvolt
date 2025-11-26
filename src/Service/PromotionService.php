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
            $linePrice = $product->getPrice() * $quantity;

            switch ($promo->getTargetType()) {
                case Promotion::TARGET_ALL:
                    // S'applique sur tout le panier
                    $reduction = $promo->getDiscountAmount() ?? $linePrice * ($promo->getDiscountPercent() / 100 ?? 0);
                    $totalReduction += $reduction;
                    break;

                case Promotion::TARGET_CATEGORY_ACCESS:
                    // Vérifie type "accessoire" + bonne catégorie
                    if (
                        $product->getType() === 'accessoire'
                        && $product->getCategory() === $promo->getCategoryAccess()
                    ) {
                        // 💡 Appliquer la réduction pour CHAQUE quantité
                        $totalReduction += $promo->getDiscountAmount() * $item['quantity'];
                    }
                    break;


                case Promotion::TARGET_PRODUCT:
                    if ($product === $promo->getProduct()) {
                        $reduction = $promo->getDiscountAmount() ?? $linePrice * ($promo->getDiscountPercent() / 100 ?? 0);
                        $totalReduction += $reduction;
                    }
                    break;

                case Promotion::TARGET_PRODUCT_LIST:
                    if ($promo->getProducts()->contains($product)) {
                        $reduction = $promo->getDiscountAmount() ?? $linePrice * ($promo->getDiscountPercent() / 100 ?? 0);
                        $totalReduction += $reduction;
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
}
