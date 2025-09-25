<?php

namespace App\Repository;

use App\Entity\Accessory;
use App\Entity\ModelTrottinette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Accessory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Accessory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Accessory[]    findAll()
 * @method Accessory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Accessory::class);
    }

    /**
     * @return Accessory[] Returns an array of Accessory objects for a given ModelTrottinette
     */
    public function findAllByModelTrottinette(ModelTrottinette $modelTrottinette)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.modelTrottinette = :model')
            ->setParameter('model', $modelTrottinette)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
