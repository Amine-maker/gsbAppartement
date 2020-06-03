<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Client;
use App\Entity\Image;
use App\Entity\Locataire;
use App\Entity\Utilisateur;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Routing\Annotation\Route;

class LocataireController extends AbstractController
{


    /**
     * @Route ("/locataire/showAppart", name = "myAppart")
     */
    public function showAppartLocataire(){

        $locataire = $this->getUser();
        $appartLoc =$this->getDoctrine()->getRepository(Appartement::class)
                         ->findBy(array('id' => $locataire->getAppartement()->getId()));
        
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


    /**
     * @Route("/locataire/endRegist", name = "endRegistr")
     */
    public function endRegistr(Request $request){
        $rep = $this->getDoctrine()->getRepository(Utilisateur::class);
        $user = $this->getUser();
        $locataire = new Locataire();
        
        $form = $this->createFormBuilder($locataire);
        $form->add('telBanque', TelType::class)
             ->add('rib');
             
             $form = $form->getForm();

             $form->handleRequest($request);

             if($form->isSubmitted() && $form->isValid()){

                $rep->updateUserClass($locataire, $user->getId());
                return $this->redirectToRoute('accueil');               
             }

             return $this->render('locataire/locRegistr.html.twig',
             [
                 'form' => $form->createView()
             ]);
    }



    /**
     * @Route ("/locataire/restore/{id}", name = "restoreAppart")
     */
    public function restoreAppart(ObjectManager $manager){

        $loc = $this->getUser();
        $loc->setNom($loc->getNom())
            ->setRoles(array('0'=>'ROLE_CLIENT'))
            ->setTelBanque(null)
            ->setRib(null)
            ->setAppartement(null);

            $this->getDoctrine()->getRepository(Locataire::class)->updateClass($loc);

            $manager->persist($loc);
            $manager->flush();
        



        //return $this->render("locataire/index.html.twig", ['controller_name' => 'LocataireController']);
        return $this->redirectToRoute("deconnexion");
    }
}
