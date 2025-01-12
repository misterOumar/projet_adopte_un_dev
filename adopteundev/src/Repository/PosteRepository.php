<?php

namespace App\Repository;

use App\Entity\Developer;
use App\Entity\Poste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Poste>
 */
class PosteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Poste::class);
    }

    //    /**
    //     * @return Poste[] Returns an array of Poste objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Poste
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // suggestion de poste pour un développeur
    public function findSuggestionsForDeveloper(Developer $developer): array
    {
        $qb = $this->createQueryBuilder('p');

        return $qb
            ->where('p.experienceRequis <= :experience')
            ->andWhere('p.ville = :ville OR p.ville IS NULL') // Les postes sans ville spécifique sont inclus
            ->andWhere('p.categorie = :categorie')
            ->andWhere(':technologie MEMBER OF p.technologie') // Correspondance technologique
            ->setParameter('experience',
                $developer->getExperience()
            )
            ->setParameter('ville', $developer->getVille())
            ->setParameter('categorie', $developer->getCat())
            ->setParameter('technologie', $developer->getTechnologie()->toArray())
            ->orderBy('p.salaireMin', 'DESC') // Prioriser par salaire minimum
            ->getQuery()
            ->getResult();
    }

    /**
     * recuperer les postes les plus consulté
     * @param int $limite
     * @return array POST
     */
    public function findMostViewedPosts(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.views', 'v')
            ->groupBy('p.id')
            ->orderBy('COUNT(v.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    // recuperer les types de poste unique
    public function findDistinctTypes(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.type')
            ->getQuery()
            ->getResult();
    }


    // recuperer les postes similaires
    public function findSimilarPosts(Poste $poste): array
    {
        $qb = $this->createQueryBuilder('p');
        return $qb
        ->where('p.id != :id')
        ->setParameter('id', $poste->getId())
        ->andWhere('p.ville = :ville OR p.ville IS NULL')
        ->setParameter('ville', $poste->getVille())
        ->andWhere('p.categorie = :categorie')
        ->setParameter('categorie', $poste->getCategorie())
        ->andWhere(':technologie MEMBER OF p.technologie')
        ->setParameter('technologie', $poste->getTechnologie()->toArray())
        ->getQuery()
        ->getResult();
    }
        


}
