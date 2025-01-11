<?php

namespace App\Repository;

use App\Entity\Technologie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Technologie>
 */
class TechnologieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Technologie::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Technologie[] Returns an array of Technologie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Technologie
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // technologies relier à au moins un developer
    public function findTechnologiesWithDevelopers(): array
    {
        return $this->createQueryBuilder('t')
            ->join('t.developers', 'd')
            ->getQuery()
            ->getResult();
    }
}
