<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Promotion;
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
        // Récupère seulement les promos autoApply
        $promos = $this->em->getRepository(Promotion::class)->findBy(['autoApply' => true]);

        $applicable = [];

        foreach ($promos as $promo) {

            // Vérifie date début/fin
            if (!$promo->isActive()) {
                continue;
            }

            // Vérifie si promo applicable selon targetType
            if (!$this->isPromoForProduct($promo, $product)) {
                continue;
            }

            // On calcule combien elle retirerait sur 1 unité du produit
            $reduction = $this->calculator->calculateReduction([
                ['product' => $product, 'quantity' => 1]
            ], $promo);

            if ($reduction > 0) {
                $applicable[] = ['promo' => $promo, 'reduction' => $reduction];
            }
        }

        // Rien trouvé ?
        if (empty($applicable)) {
            return null;
        }

        // On retourne celle avec la plus forte réduction
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

            Promotion::TARGET_CATEGORY_ACCESS =>
                $product instanceof \App\Entity\Accessory
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
        // On calcule la réduction pour 1 unité
        $reduction = $this->calculator->calculateReduction([
            ['product' => $product, 'quantity' => 1]
        ], $promo);

        // Prix final = prix de base - réduction
        $final = $product->getPrice() - $reduction;

        // On évite les prix négatifs
        return max(0, $final);
    }

    /**
     * Trouve la meilleure promotion automatique globale (sans produit)
     */
    public function findBestAutoPromotion(): ?Promotion
    {
        // Récupère les promos autoApply + actives
        $promos = $this->em->getRepository(Promotion::class)->findBy(['autoApply' => true]);

        $valids = [];

        foreach ($promos as $promo) {

            // Vérifie date début/fin
            if (!$promo->isActive()) {
                continue;
            }

            // Seules les promotions applicables à tous les produits sont universelles
            if ($promo->getTargetType() !== Promotion::TARGET_ALL) {
                continue;
            }

            // On calcule sa "force" : pourcentage prioritaire, sinon montant
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

        // On trie par la promo la plus forte
        usort($valids, fn($a, $b) => $b['force'] <=> $a['force']);

        return $valids[0]['promo'];
    }

    public function findHomepagePromo(): ?Promotion
    {
        // Récupère toutes les promos actives
        $promos = $this->em->getRepository(Promotion::class)->findAll();

        $activePromos = array_filter($promos, fn($promo) => $promo->isActive());

        if (empty($activePromos)) {
            return null;
        }

        // Ici tu peux choisir la logique pour laquelle afficher :
        // exemple : priorité à autoApply, sinon la plus récente
        usort($activePromos, function($a, $b) {
            // d'abord autoApply
            if ($a->isAutoApply() && !$b->isAutoApply()) return -1;
            if (!$a->isAutoApply() && $b->isAutoApply()) return 1;
            // ensuite date de début (plus récente en premier)
            return $b->getStartDate() <=> $a->getStartDate();
        });

        return $activePromos[0];
    }


}
