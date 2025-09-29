<?php

namespace App\Repository;

use App\Entity\TrottinetteAccessory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrottinetteAccessoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrottinetteAccessory::class);
    }
}
