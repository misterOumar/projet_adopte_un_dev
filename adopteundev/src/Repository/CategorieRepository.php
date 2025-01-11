<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
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

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
  
   public function findTopCategoriesByPostCount(int $limit = 3): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.nom, COUNT(p.id) AS post_count')
            ->leftJoin('c.postes', 'p')
            ->groupBy('c.id')
            ->orderBy('post_count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // recuperer les catégorie relier à au moins un poste
    public function findCategorieWithPosts(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.postes', 'p')
            ->getQuery()
            ->getResult();
    }
    
    public function findCategorieWithDevelopper(): array
    {
        return $this->createQueryBuilder('c')
            ->join('c.developer', 'p')
            ->getQuery()
            ->getResult();
    }
    

}
