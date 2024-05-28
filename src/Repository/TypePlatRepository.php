<?php

namespace App\Repository;

use App\Entity\TypePlat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypePlat>
 *
 * @method TypePlat|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePlat|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePlat[]    findAll()
 * @method TypePlat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePlatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypePlat::class);
    }

//    /**
//     * @return TypePlat[] Returns an array of TypePlat objects
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

//    public function findOneBySomeField($value): ?TypePlat
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
