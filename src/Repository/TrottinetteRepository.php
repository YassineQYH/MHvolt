<?php

namespace App\Repository;

use App\Entity\Trottinette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trottinette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trottinette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trottinette[]    findAll()
 * @method Trottinette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrottinetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trottinette::class);
    }

    /**
     * Exemple pour récupérer toutes les trottinettes
     * avec un ordre spécifique
     */
    public function findAllOrdered(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
