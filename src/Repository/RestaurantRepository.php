<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Restaurant>
 *
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }
    
    public function getRestoByR($recherche){
        $dql="SELECT r FROM App\Entity\Restaurant r ".
             "JOIN r.lesPlats p ".
             "JOIN p.leTypePlat tp ".
             "WHERE 1=1 "  ;
        foreach ($recherche as $critere){
            $dql=$dql.$critere;
        }
        $req=$this->getEntityManager()->createQuery($dql);
        return $req->getResult();        
    }
    
    public function getRestoByName($nomR){
        $dql="SELECT r FROM App\Entity\Restaurant r ".
             "WHERE r.nomRestaurant LIKE %:nomR% ";
        $req=$this->getEntityManager()->createQuery($dql);
        $req->setParameter(":nomR",$nomR);
        return $req->getResult();    
    }

//    /**
//     * @return Restaurant[] Returns an array of Restaurant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Restaurant
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
