<?php

namespace App\Repository;

use App\Entity\TrottinetteCaracteristique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrottinetteCaracteristique>
 *
 * @method TrottinetteCaracteristique|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrottinetteCaracteristique|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrottinetteCaracteristique[]    findAll()
 * @method TrottinetteCaracteristique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrottinetteCaracteristiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrottinetteCaracteristique::class);
    }

    // Exemple de méthode personnalisée (optionnel)
    /*
    public function findByTrottinetteId(int $trottinetteId): array
    {
        return $this->createQueryBuilder('tc')
            ->andWhere('tc.trottinette = :id')
            ->setParameter('id', $trottinetteId)
            ->orderBy('tc.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    */
}
