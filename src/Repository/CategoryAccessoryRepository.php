<?php

namespace App\Repository;

use App\Entity\CategoryAccessory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryAccessory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryAccessory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryAccessory[]    findAll()
 * @method CategoryAccessory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryAccessoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryAccessory::class);
    }

    // /**
    //  * @return CategoryAccessory[] Returns an array of CategoryAccessory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryAccessory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
