<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductHistory;
use Doctrine\ORM\EntityManagerInterface;

class ProductHistoryService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Crée une entrée d'historique pour un produit donné
     * @param Product $product
     * @param bool $flush Indique si on flush immédiatement ou non
     * @return ProductHistory
     */
    public function createHistory(Product $product, bool $flush = true): ProductHistory
    {
        $history = new ProductHistory($product);

        $this->em->persist($history);

        if ($flush) {
            $this->em->flush();
        }

        return $history;
    }
}
