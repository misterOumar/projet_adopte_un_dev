<?php

namespace App\Repository;

use App\Entity\SavedDeveloper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedDeveloper>
 */
class SavedDeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedDeveloper::class);
    }

    /**
     * Sauvegarde un SavedDeveloper en base de données
     */
    public function save(SavedDeveloper $savedDeveloper, bool $flush = true): void
    {
        $this->getEntityManager()->persist($savedDeveloper);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime un SavedDeveloper en base de données
     */
    public function remove(SavedDeveloper $savedDeveloper, bool $flush = true): void
    {
        $this->getEntityManager()->remove($savedDeveloper);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère tous les développeurs sauvegardés par une entreprise
     */
    public function findByCompany(int $companyId): array
    {
        return $this->createQueryBuilder('sd')
            ->join('sd.company', 'c')
            ->addSelect('c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)
            ->getQuery()
            ->getResult();
    }
}
