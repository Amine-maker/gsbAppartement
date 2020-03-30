<?php

namespace App\Repository;

use App\Entity\AppartementVide;
use App\Entity\Arrondissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AppartementVide|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppartementVide|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppartementVide[]    findAll()
 * @method AppartementVide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementVideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppartementVide::class);
    }

    // /**
    //  * @return AppartementVide[] Returns an array of AppartementVide objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getAppartementsVide($typeAppart, $prixT,$arrondissements)
    {
        $bmi = $prixT -50;
        $bma = $prixT +50;
        $arr = implode("','",$arrondissements);
        $conn = $this->getEntityManager()->getConnection();
        $sql="SELECT * FROM appartementvide 
            WHERE typeAppart = '$typeAppart'
            AND prix_total between $bmi and $bma 
            AND arrondissement IN ('$arr')";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?AppartementVide
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
