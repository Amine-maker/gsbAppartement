<?php

namespace App\Repository;

use App\Entity\Appartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Appartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartement[]    findAll()
 * @method Appartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartement::class);
    }

    // /**
    //  * @return Appartement[] Returns an array of Appartement objects
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
/**
     * @return Appartement[] Returns an array of appart objects
     */
    public function TestDeM($demande)
    {

        foreach ($demande->getArrondissements() as $arrondissement)
        {
           $tab_arr[] = $arrondissement->getArrondissementDemande();
           $tab_arr = array(6,8);
        }
        dump($tab_arr);
         return $this->createQueryBuilder('a')
                     ->Where('a.typeAppart = :type_appart')
                     ->andWhere('a.prixLocation = :prix_location')
                     ->orWhere('a.arrondissement = :arrondissement')
                     ->setParameter('type_appart', /*$demande->getTypeAppart()*/'F5')
                     ->setParameter('prix_location',/* $demande->getPrixLocation()*/300)
                     ->getQuery()
                     ->getResult();
    }
    /*
    public function findOneBySomeField($value): ?Appartement
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
