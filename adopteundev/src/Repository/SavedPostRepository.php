<?php

namespace App\Repository;

use App\Entity\SavedPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SavedPost>
 */
class SavedPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SavedPost::class);
    }

    /**
     * Sauvegarde un SavedPost en base de données
     */
    public function save(SavedPost $savedPost, bool $flush = true): void
    {
        $this->getEntityManager()->persist($savedPost);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Supprime un SavedPost en base de données
     */
    public function remove(SavedPost $savedPost, bool $flush = true): void
    {
        $this->getEntityManager()->remove($savedPost);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère tous les postes sauvegardés par un développeur
     */
    public function findByDeveloper(int $developerId): array
    {
        return $this->createQueryBuilder('sp')
            ->join('sp.developer', 'd')
            ->addSelect('d')
            ->where('d.id = :developerId')
            ->setParameter('developerId', $developerId)
            ->getQuery()
            ->getResult();
    }
}
