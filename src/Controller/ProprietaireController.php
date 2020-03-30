<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Image;
use App\Entity\Proprietaire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ProprietaireController extends AbstractController
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
     * @Route ("/proprietaire/showAppart", name = "myApparts")
     */
    public function showAppartProprietaire(){

        $appartProprio = $this->getDoctrine()->getRepository(Appartement::class)->findBy(
            array('proprietaire' => $this->getUser()->getId())
        );
        $q= $this->getDoctrine()->getRepository(Image::class);
        $i=0;
        $route = array();
        while($i<=count($appartProprio))
            {
                $idI = rand(1,count($q->findAll())); // id d'une image pour le nb d'images dans la bdd
                if(!in_array($idI,$route))// pour savoir s'il existe deja 
                {
                    $routes[] = $q->find($idI)->getRoute();// trouver la route d'une image
                    $i++;
                }
                
            }
        return $this->render('proprietaire/showMyAppart.html.twig',[
            'appartements' => $appartProprio, 'routes' => $routes
        ]);
    }

    /**
     * @Route("/proprietaire/cotisations",name = "cotisationByProprietaire")
     */
    public function getCotisatisation(Request $request, PaginatorInterface $paginator)
    {
        $cotisations = $this->getDoctrine()->getRepository(Proprietaire::class)->findCotisations();
        $cotisationByAppart = $this->getDoctrine()->getRepository(Proprietaire::class)->findCotisationByAppart();           
                           
                          //dump($cotisationByAppart);
                          //dump($cotisations);  

                $appointments = $paginator->paginate(
                $cotisations,
                $request->query->getInt('page', 1),
                6
                );


        return $this->render("proprietaire/cotisation.html.twig", [
            "cotisations" => $appointments,
            "cotisationByAppart" => $cotisationByAppart ]);
    }


}
