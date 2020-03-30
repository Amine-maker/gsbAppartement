<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Image;
use App\Entity\Proprietaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LocataireController extends AbstractController
{
    /**
     * @Route("/proprietaire", name="proprietaire")
     */
    public function index()
    {
        return $this->render('proprietaire/index.html.twig', [
            'controller_name' => 'ProprietaireController',
        ]);
    }

    /**
     * @Route ("/locataire/showAppart", name = "myAppart")
     */
    public function showAppartLocataire(){

        $locataire = $this->getUser();
        $appartLoc =$this->getDoctrine()->getRepository(Appartement::class)
                         ->findBy(array('id' => $locataire->getAppartement()->getId()));
        dump($appartLoc);
        $q = $this->getDoctrine()->getRepository(Image::class);
        $i=0;
        $route = array();
 
                $idI = rand(1,count($q->findAll())); // id d'une image pour le nb d'images dans la bdd
                if(!in_array($idI,$route))// pour savoir s'il existe deja 
                {
                    $routes[] = $q->find($idI)->getRoute();// trouver la route d'une image
                    $i++;
                }
                
            
        return $this->redirectToRoute('show_one',[
            'id' => $locataire->getAppartement()->getId()
        ]);
    }
}
