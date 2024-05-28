<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function getCommandesByDate($unUser) {
        $dql = "SELECT c FROM App\Entity\Commande c " .
                "JOIN c.lUtilisateur u ".
                "WHERE u = :unUser ".
                "ORDER BY c.dateCommande DESC ";
        
        
        $req = $this->getEntityManager()->createQuery($dql);
        $req->setParameter(":unUser",$unUser);
        return $req->getResult();
    }
    public function calculerPrixCommande(){
$dql = "SELECT SUM(p.prixClientPlat * qp.quantite) AS prix_totaux
        FROM App\Entity\Commande c
        JOIN c.lesQuantitesPlats qp
        JOIN qp.lePlat p
        GROUP BY c.id
        ORDER BY c.dateCommande DESC";

        $req = $this->getEntityManager()->createQuery($dql);
        return $req->getResult();
    }

//    /**
//     * @return Commande[] Returns an array of Commande objects
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

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
