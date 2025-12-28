<?php

namespace App\Repository;

use App\Entity\TrottinetteDescriptionSection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrottinetteDescriptionSectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrottinetteDescriptionSection::class);
    }
}
