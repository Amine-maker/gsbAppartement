<?php

namespace App\Controller;

use App\Entity\Arrondissement;
use App\Entity\Demande;
use App\Entity\Appartement;
use App\Entity\AppartementVide;
use App\Entity\Client;
use App\Entity\Image;
use App\Entity\Utilisateur;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;



class DemandeController extends AbstractController
{
    /**
     * @Route("/demande/voir", name="voirDemande")
     */
    public function read()
    {  
        $repo = $this->getDoctrine()->getRepository(Demande::class);
        $repoo = $this->getDoctrine()->getRepository(Client::class);
        $bdemandes = $repo->findAll();
        $arr = new Arrondissement();
        foreach ($bdemandes as $demande)
            {     
            $repo->joinDemandes($demande->getId());
            $demande->addArrondissement($arr);

            $util = $repoo->findBy(array('id' => $demande->getClient()->getId()));
                if ($util == null){
                    continue;
                }
                
            $demande->setClient($util[0]);
             
            }
            
           // dump($bdemandes);
           
        return $this->render('demande/showDemande.html.twig', [
            'controller_name' => 'DemandeController', 'demandes' => $bdemandes
        ]);
    }

    /**
     * @Route("/demande/supprimer/{id}", name="delDemande")
     */
        public function delete($id, ObjectManager $manager){
            $repo = $this->getDoctrine()->getRepository(Demande::class);

            $demande = $repo->find($id);
            $demande->setClient(null);
            dump($demande);

            $manager->remove($demande);
            $manager->flush();
            
            return $this->redirectToRoute("voirDemande");
        }



    /**
     * @Route("/demande/valider/{id}/appartement/{app}", name="validerDemande")
     */
    public function validerDemande($id,$app, EntityManagerInterface $em)
    {  
        
        $repo = $this->getDoctrine()->getRepository(Utilisateur::class);
        $repoo = $this->getDoctrine()->getRepository(Demande::class);
        $utilisateur = $repo->find($id);
           
            
            $repoo->updateRole($utilisateur, $app);
            $demandes = $repoo->findBy(array('client'=> $id));
            dump($demandes);
            foreach ($demandes as $demande)
                {
                    $em->remove($demande);
                }
            $em->flush();
 
        return $this->render('demande/validationDemande.html.twig', ['utilisateur' => $utilisateur]);
    }

    /**
     * @Route("/demandeApp", name="demandeApp")
     * @Route("/demande/edit/{id}", name = "editDemande")
     */
    public function demande(Demande $demande = null, Request $request, ObjectManager $manager, EntityManagerInterface $em, PaginatorInterface $paginator){

        if(!$demande){
             $demande = new Demande();
        }
       

        $form = $this->createFormBuilder($demande);
        $form->add('typeAppart', ChoiceType::class,[
            'choices' => 
            [
                'F1' => 'F1',
                'F2' => 'F2',
                'F3' => 'F3',
                'F4' => 'F4',
                'F5' => 'F5',
            ]
        ])
             ->add('prixLocation')
             ->add('arrondissements',EntityType::class,[
                 'class' => Arrondissement::class,
                 'multiple' => true,
                 'choice_label' => 'arrondissementDemande'
                
                ]);

            $form = $form->getForm();
            $form->handleRequest($request); 
            

             if($form->isSubmitted() && $form->isValid()){
                if ($em->getMetadataFactory()
                       ->getMetadataFor(get_class($this->getUser()))
                       ->getName() == 'App\Entity\Client')
                    {
                        $demande->setClient($this->getUser());
                    }
                    
                if($demande->getClient()){
                    $demande->setClient($demande->getClient());
                }
               

                $manager->persist($demande);
                $manager->flush();

                foreach ($demande->getArrondissements() as $arrondissement)
                {
                   $tab_arr[] = $arrondissement->getArrondissementDemande();
                }
                
               $apps = $this->getDoctrine()
                               ->getRepository(AppartementVide::class)
                               ->getAppartementsVide($demande->getTypeAppart(),$demande->getPrixLocation(),$tab_arr);


                

                $route = array();
                $i=1;
                $q = $this->getDoctrine()->getRepository(Image::class);
                while($i<=count($apps))
                {
                    $idI = rand(1,count($q->findAll())); // id d'une image pour le nb d'images dans la bdd
                    if(!in_array($idI,$route))// pour savoir s'il existe deja 
                    {
                        $routes[] = $q->find($idI)->getRoute(); // trouver la route d'une image
                        $i++;
                    }
                    
                }
                if ($request->get('_route') == "editDemande")
                {
                    return $this->redirectToRoute("voirDemande");
                }
               
                if(count($apps)>0){

                    $appointments = $paginator->paginate(
                        $apps,
                        $request->query->getInt('page', 1),
                        6
                        );

                    return $this->render('appartement/show.html.twig',
                     ['appartements' => $appointments, 'routes'=> $routes]);
                }
                else {
                    return $this->render('demande/vide.html.twig');
                }
                
             }

        return $this->render('demande/form_demande.html.twig',[
            "formD" => $form->createView(), 'editMode'=> $demande->getId() !== null
        ]);
    }
}
