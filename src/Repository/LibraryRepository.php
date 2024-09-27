<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    /**
    * @return Library[] Returns an array of Library objects
    */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Library
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // /**
    //  * Find all books having a value above the specified one with SQL.
    //  *
    //  * @return [][] Returns an array of arrays (i.e. a raw data set)
    //  */
    // public function findByMinimumValue2($value): array
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //         SELECT * FROM library AS l
    //         WHERE l.value >= :value
    //         ORDER BY l.value ASC
    //     ';

    //     $resultSet = $conn->executeQuery($sql, ['value' => $value]);

    //     return $resultSet->fetchAllAssociative();
    // }
}
