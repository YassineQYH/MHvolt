<?php

namespace App\Repository;

use App\Entity\Weight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weight::class);
    }

    /**
     * Retourne le palier de livraison correspondant au poids donné
     */
    public function findPriceByWeight(float $weight): ?Weight
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.kg >= :weight')
            ->setParameter('weight', $weight)
            ->orderBy('w.kg', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
