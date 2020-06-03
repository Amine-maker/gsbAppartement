<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class VisiteController extends AbstractController
{
    /**
     * @Route("/visit", name="visite")
     */
    public function index()
    {
        return $this->render('visite/index.html.twig', [
            'controller_name' => 'VisiteController',
        ]);
    }


   /**
     * @Route("/visite/show", name = "showVisites")
     */
    public function showVisites(){
        $visites = $this->getDoctrine()->getRepository(Client::class)->JoinVisites();


        return($this->render('visite/showVisites.html.twig', ['visites' => $visites]));
    }


     /**
     * @Route("/visite/{id}", name="visiteApp")
     */
    public function enregistrerVisite($id)
    {

        $config = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'dbname' => 'gsbs2',
            'user' => 'root',
            'password' => 'test',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

        $repo = $this->getDoctrine()->getRepository(Appartement::class);
        $appartement = $repo->find($id);
       
        $idC = $this->getUser()->getId();
        $visite = $conn->fetchAll("select client_id , appartement_id 
                            from client_appartement c
                            where c.client_id = $idC and c.appartement_id = ".$appartement->getId());

            if(count($visite) > 0){
                return $this->render('visite/erreur.html.twig');
            }
            else 
            {
                $conn->insert('client_appartement', array('client_id' => $idC,
                                                   'appartement_id' => $appartement->getId(),
                                                   'date_visite'=> date('y-m-d')));
            }
        
        
        return $this->render('visite/enregistrement.html.twig', ['client' => $this->getUser()]);
    }
     

}
