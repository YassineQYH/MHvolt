<?php

namespace App\Service;

use App\Entity\Accessory;
use App\Entity\Product;
use App\Entity\Promotion;
use App\Entity\Trottinette;
use Doctrine\ORM\EntityManagerInterface;

class PromotionFinderService
{
    private EntityManagerInterface $em;
    private PromotionService $calculator;

    public function __construct(EntityManagerInterface $em, PromotionService $calculator)
    {
        $this->em = $em;
        $this->calculator = $calculator;
    }

    /**
     * Trouve la meilleure promotion automatique applicable à un produit
     */
    public function findBestForProduct(Product $product): ?Promotion
    {
        $promos = $this->em->getRepository(Promotion::class)->findBy(['autoApply' => true]);

        $applicable = [];

        foreach ($promos as $promo) {
            if (!$promo->isActive()) {
                continue;
            }

            if (!$this->isPromoForProduct($promo, $product)) {
                continue;
            }

            $reduction = $this->calculator->calculateReduction([
                ['product' => $product, 'quantity' => 1]
            ], $promo);

            if ($reduction > 0) {
                $applicable[] = ['promo' => $promo, 'reduction' => $reduction];
            }
        }

        if (empty($applicable)) {
            return null;
        }

        usort($applicable, fn($a, $b) => $b['reduction'] <=> $a['reduction']);

        return $applicable[0]['promo'];
    }

    /**
     * Vérifie si une promo est compatible avec un produit selon targetType
     */
    private function isPromoForProduct(Promotion $promo, Product $product): bool
    {
        return match ($promo->getTargetType()) {
            Promotion::TARGET_ALL => true,

            Promotion::TARGET_ALL_TROTTINETTES =>
                $product instanceof Trottinette || $product->getType() === 'trottinette',

            Promotion::TARGET_ALL_ACCESSORIES =>
                $product instanceof Accessory || $product->getType() === 'accessoire',

            Promotion::TARGET_CATEGORY_ACCESS =>
                ($product instanceof Accessory || $product->getType() === 'accessoire')
                && method_exists($product, 'getCategory')
                && $product->getCategory() === $promo->getCategoryAccess(),

            Promotion::TARGET_PRODUCT =>
                $promo->getProduct() === $product,

            Promotion::TARGET_PRODUCT_LIST =>
                $promo->getProducts()->contains($product),

            default => false,
        };
    }

    /**
     * Calcule le prix réduit d’un produit selon une promotion donnée
     */
    public function calculateDiscountedPrice(Product $product, Promotion $promo): float
    {
        $reduction = $this->calculator->calculateReduction([
            ['product' => $product, 'quantity' => 1]
        ], $promo);

        $final = $product->getPrice() - $reduction;

        return max(0, $final);
    }

    /**
     * Trouve la meilleure promotion automatique globale (sans produit)
     */
    public function findBestAutoPromotion(): ?Promotion
    {
        $promos = $this->em->getRepository(Promotion::class)->findBy(['autoApply' => true]);

        $valids = [];

        foreach ($promos as $promo) {
            if (!$promo->isActive()) {
                continue;
            }

            if ($promo->getTargetType() !== Promotion::TARGET_ALL) {
                continue;
            }

            $force = 0;
            if ($promo->getDiscountPercent()) {
                $force = $promo->getDiscountPercent();
            } elseif ($promo->getDiscountAmount()) {
                $force = $promo->getDiscountAmount();
            }

            $valids[] = [
                'promo' => $promo,
                'force' => $force
            ];
        }

        if (empty($valids)) {
            return null;
        }

        usort($valids, fn($a, $b) => $b['force'] <=> $a['force']);

        return $valids[0]['promo'];
    }

    /**
     * Récupère TOUTES les promos actives à afficher en bannière sur le site
     * @return Promotion[]
     */
    public function findHomepagePromos(): array
    {
        $promos = $this->em->getRepository(Promotion::class)->findAll();

        $activePromos = array_filter(
            $promos,
            fn(Promotion $p) => $p->isActive() && $p->isDiscountValid() && $p->isAutoApply()
        );

        if (empty($activePromos)) {
            return [];
        }

        usort($activePromos, function(Promotion $a, Promotion $b) {
            return $b->getStartDate() <=> $a->getStartDate();
        });

        return array_values($activePromos);
    }
}
