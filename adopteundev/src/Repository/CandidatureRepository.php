<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidature>
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    //    /**
    //     * @return Candidature[] Returns an array of Candidature objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Candidature
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // recuperer le nombre de candidature pour une company
    public function getNombreCandidatureParCompany($company)
    {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.company = :company')
            ->setParameter('company', $company)
            ->getQuery();
        return $query->getSingleScalarResult();
    }

    public function findByCompany($company): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.poste', 'p')
            ->where('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
    }

    public function countCandidaturesByCompany($company): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.poste', 'p')
            ->where('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAcceptedByCompany($company): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.poste', 'p')
            ->where('p.company = :company')
            ->andWhere('c.statut = :status')
            ->setParameter('company', $company)
            ->setParameter('status', 'acceptée')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findPendingByCompany($companyId): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.poste', 'p')
            ->where('p.company = :company')
            ->andWhere('c.statut = :status')
            ->setParameter('company', $companyId)
            ->setParameter('status', 'En cours')
            ->getQuery()
            ->getResult();
    }

    // nombre candidature rejetée en fonction du poste
    public function countRejectedByPoste($posteId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.poste = :poste')
            ->andWhere('c.statut = :status')
            ->setParameter('poste', $posteId)
            ->setParameter('status', 'rejetée')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAcceptedByPoste($posteId): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.poste = :poste')
            ->andWhere('c.statut = :status')
            ->setParameter('poste', $posteId)
            ->setParameter('status', 'acceptée')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
