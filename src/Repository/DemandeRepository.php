<?php

namespace App\Repository;

use App\Entity\Demande;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Demande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Demande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Demande[]    findAll()
 * @method Demande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Demande::class);
    }

    // /**
    //  * @return Demande[] Returns an array of Demande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ; }

    */

    
    public function joinDemandes($id): array
    {
        $conn = $this->getEntityManager()->getConnection();
    
        $sql = "
        select arrondissement_id 
        from demande_arrondissement 
        where demande_id = $id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // returns an array of arrays (i.e. a raw data set)
        $dem = $stmt->fetchAll();
        $demandes = array();
        foreach ($dem as $d)
        {
            $demandes[] = $d["arrondissement_id"] ;
        }
        return $demandes;
    }

    public function updateRole(Utilisateur $utilisateur, $idapp = null){
        $newR = '[
            "ROLE_LOCATAIRE"
        ]';
        $conn = $this->getEntityManager()->getConnection();
        $nom = $utilisateur->getNom();
        $prenom = $utilisateur->getPrenom();
        $adresse = $utilisateur->getAdresse();
        $ville = $utilisateur->getVille();
        $cp = $utilisateur->getVille();
        $tel = $utilisateur->getTelephone();
        $email = $utilisateur->getEmail();
        $id = $utilisateur->getId();
        $sql = "update utilisateur set nom = '$nom',
                prenom = '$prenom',
                adresse = '$adresse' ,
                ville = '$ville',
                cp = '$cp',
                telephone = '$tel',
                email = '$email',
                appartement_id = '$idapp',
                roles = '$newR'
                where id = $id";

                $stmt = $conn->prepare($sql);
                $stmt->execute();


            

    }


}
