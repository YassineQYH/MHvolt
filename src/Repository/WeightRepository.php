<?php

namespace App\Repository;

use App\Entity\Weight;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Weight|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weight|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weight[]    findAll()
 * @method Weight[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weight::class);
    }


    public function findByKgPrice($poid)
    {
        try{
            return $this->createQueryBuilder('w')
            /* ->select('w.price') */
            ->andWhere('w.kg <= :poid')
            ->setParameter('poid', $poid)
            ->orderBy('w.kg', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
        }catch(\Exception $e){
            return new Weight(0, 0); // ou des valeurs par défaut que tu juges appropriées
        }

    }
    /* select price from weight where kg < 5.7 order by kg DESC limit 1;  */

    /*
    public function findOneBySomeField($value): ?Weight
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
