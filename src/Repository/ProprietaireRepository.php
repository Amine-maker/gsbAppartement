<?php

namespace App\Repository;

use App\Entity\Proprietaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Proprietaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proprietaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proprietaire[]    findAll()
 * @method Proprietaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProprietaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proprietaire::class);
    }

    // /**
    //  * @return Proprietaire[] Returns an array of Proprietaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findCotisations()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql ="
        select count(nom), nom, prenom, adresse, ville, cp, sum(montant_cotisation) as cotisation
        from utilisateur join 
        appartement on appartement.proprietaire_id = utilisateur.id 
        group by utilisateur.id
";
        
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();

        ;
    }

    public function findCotisationByAppart()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql ="select appartement.id as idAppart, nom, prenom, prix_location, montant_cotisation as cotisationByAppart
        from appartement join 
        utilisateur on utilisateur.id = appartement.proprietaire_id";
        
    
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();

        ;
    }
    
    
}
