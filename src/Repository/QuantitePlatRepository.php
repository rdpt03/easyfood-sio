<?php

namespace App\Repository;

use App\Entity\QuantitePlat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuantitePlat>
 *
 * @method QuantitePlat|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantitePlat|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantitePlat[]    findAll()
 * @method QuantitePlat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantitePlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantitePlat::class);
    }

//    /**
//     * @return QuantitePlat[] Returns an array of QuantitePlat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuantitePlat
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
