<?php

namespace App\Repository;

use App\Entity\Developer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Developer>
 */
class DeveloperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developer::class);
    }

    // public function findAllQuery(): Query
    // {
    //     return $this->createQueryBuilder('d')
    //         ->orderBy('d.experience', 'DESC')
    //         ->getQuery();
    // }


    public function findByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('d');

        // Vérification et ajout du filtre par nom
        if (!empty($filters['nom'])) {
            $qb->andWhere('d.nom LIKE :nom')
                ->setParameter('nom', '%' . $filters['nom'] . '%');
        }

        // // Vérification et ajout du filtre par technologies
        // if (!empty($filters['technologies']) && is_array($filters['technologies'])) {
        //     $qb->join('d.technologies', 't')
        //         ->andWhere('t.nom IN (:technologies)')
        //         ->setParameter('technologies', $filters['technologies']);
        // }

        // Filtre par expérience (de 1 à 5)
        if (!empty($filters['experience']) && in_array($filters['experience'], range(1, 5))) {
            $qb->andWhere('d.experience = :experience')
                ->setParameter('experience', $filters['experience']);
        }

        // Vérification et ajout du filtre par salaire minimum
        if (!empty($filters['salaireMin']) && is_numeric($filters['salaireMin'])) {
            $qb->andWhere('d.salaireMin >= :salaireMin')
                ->setParameter('salaireMin', $filters['salaireMin']);
        }

        return $qb->getQuery();
    }

    public function findByFilters2($categorie, $experience, $salaryMin, $salaryMax)
    {
        $qb = $this->createQueryBuilder('d');

        if ($categorie) {
            $qb->andWhere('d.cat.nom = :categorie')
            ->setParameter('categorie', $categorie);
        }

        if ($experience) {
            $qb->andWhere('d.experience = :experience')
            ->setParameter('experience', $experience);
        }

        // Filtrer par plage de salaire
        $qb->andWhere('d.salaireMin BETWEEN :min AND :max')
        ->setParameter('min', $salaryMin)
            ->setParameter('max', $salaryMax);

        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Developer[] Returns an array of Developer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Developer
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
