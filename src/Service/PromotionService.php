<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Entity\Product;

class PromotionService
{
    /**
     * Applique une promotion sur un prix donné
     */
    public function applyPromotion(Promotion $promo, float $price, Product $product = null): float
    {
        if (!$promo->canBeUsed()) {
            throw new \LogicException("Promotion '{$promo->getCode()}' is not active or available.");
        }

        // Vérifie que le produit correspond au targetType
        switch ($promo->getTargetType()) {
            case Promotion::TARGET_ALL:
                break;

            case Promotion::TARGET_CATEGORY:
                if (!$product || $product->getCategory() !== $promo->getCategory()) {
                    throw new \LogicException("Promotion '{$promo->getCode()}' is not valid for this category.");
                }
                break;

            case Promotion::TARGET_PRODUCT:
                if (!$product || $product !== $promo->getProduct()) {
                    throw new \LogicException("Promotion '{$promo->getCode()}' is not valid for this product.");
                }
                break;

            case Promotion::TARGET_PRODUCT_LIST:
                if (!$product || !$promo->getProducts()->contains($product)) {
                    throw new \LogicException("Promotion '{$promo->getCode()}' is not valid for this product.");
                }
                break;
        }

        // Applique la remise
        if ($promo->getDiscountAmount() !== null) {
            $price -= $promo->getDiscountAmount();
        } elseif ($promo->getDiscountPercent() !== null) {
            $price -= $price * ($promo->getDiscountPercent() / 100);
        }

        // Incrémente le compteur d'utilisation
        $promo->incrementUsed();

        return max(0, $price); // le prix ne peut pas être négatif
    }
}
