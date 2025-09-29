<?php

namespace App\Repository;

use App\Entity\CategorieCaracteristique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieCaracteristique>
 *
 * @method CategorieCaracteristique|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieCaracteristique|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieCaracteristique[]    findAll()
 * @method CategorieCaracteristique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieCaracteristiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieCaracteristique::class);
    }

    public function save(CategorieCaracteristique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategorieCaracteristique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // Exemple de méthode personnalisée
    /*
    public function findByName(string $name): ?CategorieCaracteristique
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
    */
}
