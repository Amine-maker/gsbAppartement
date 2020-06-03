<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Arrondissement;
use App\Entity\Image;
use App\Entity\Locataire;
use App\Entity\Proprietaire;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class AppartementController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(EntityManagerInterface $em)
    {

        if($this->getUser() != null){

            if ($this->getUser()->getRoles()[0] == 'ROLE_LOCATAIRE' && $em->getMetadataFactory()
                                                                          ->getMetadataFor(get_class($this->getUser()))
                                                                          ->getName() == 'App\Entity\Client')
                {
                  return $this->redirectToRoute('endRegistr');
                }
       }

        return $this->render('appartement/index.html.twig', [
            'controller_name' => 'AppartementController',
        ]);
    }

    /**
     * @Route("/appartements", name="showAppart")
     */
    public function show(PaginatorInterface $paginator, Request $request){
        $route = array();
        $repo = $this->getDoctrine()->getRepository(Appartement::class);
        $q= $this->getDoctrine()->getRepository(Image::class);
        $i=1;
        $appartements = $repo->findAll();


        $appointments = $paginator->paginate(
            $appartements,
            $request->query->getInt('page', 1),
            6
        );

        $max = count($q->findAll());

        while($i<=count($appartements))
            {
                $idI = rand(1,$max); // id d'une image pour le nb d'images dans la bdd
                if(!in_array($idI,$route))// pour savoir s'il existe deja 
                {
                    $routes[] = $q->find($idI)->getRoute();// trouver la route d'une image
                    $i++;
                }
                
            }
           
        return $this->render('appartement/show.html.twig', [
            'appartements' => $appointments, 'routes' => $routes
        ]);
    }

    /**
     * @Route("/appartements/supprimer/{id}", name="delAppart")
     */
    public function delete($id, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Appartement::class);
        $appartement = $repo->find($id);
        $appartement->setProprietaire(null);
        $locataire = $this->getDoctrine()->getRepository(Locataire::class)->findBy(array('appartement'=> $id));

        if($locataire != null){
             $manager->remove($locataire[0]); // censer avoir qu'un locataire
        }
       
        $manager->remove($appartement);
        
        $manager->flush();

        return $this->redirectToRoute('showAppart');
    }


    /**
     * @Route("/appartements/{id}", name="show_one")
     */
    public function show_one($id){
        
       // id d'une image pour x images
        $repo = $this->getDoctrine()->getRepository(Appartement::class);
        $q = $this->getDoctrine()->getRepository(Image::class);
        $appartement = $repo->find($id);
        
        $idImage = rand(1,count($q->findAll())-1); 
        $routes[0] = $q->find($idImage)->getRoute();
        $routes[1] = $q->find($idImage+1)->getRoute();// trouver la route d'une image
        $proprietaire = $this->getDoctrine()
                             ->getRepository(Proprietaire::class)
                             ->findBy(array('id'=>$appartement
                             ->getProprietaire()
                             ->getId()));

        $appartement->setProprietaire($proprietaire[0]);
        

        return $this->render('appartement/show_one.html.twig', 
        [
            'appartement' => $appartement, 'routes' => $routes
        ]);
    }

    /**
     * @Route("/appartement/add", name="addAppart")
     * @Route("/appartement/edit/{id}", name = "editAppart")
     */
    public function add(Appartement $appartement = null, Request $request, ObjectManager $manager){

        if(!$appartement)
        {
             $appartement = new Appartement(); 
        }
        

        $form = $this->createFormBuilder($appartement);
        $form->add('rue',TextType::class, [
            'label' => 'Adresse'
        ])
             ->add('arrondissement', EntityType::class,[
                 'class'=> Arrondissement::class,
                 'choice_label' => 'arrondissementDemande',
                 'mapped'=>false
             ])
             ->add('etage')
             ->add('typeAppart', ChoiceType::class,[
                 'choices' => [
                     'F1' => 'F1',
                     'F2' => 'F2',
                     'F3' => 'F3',
                     'F4' => 'F4',
                     'F5' => 'F5',
                 ]
             ])
             ->add('prixLocation')
             ->add('prixCharge')
             ->add('ascenseur')
             ->add('preavis')
             ->add('proprietaire', EntityType::class, [
                 'class' => Proprietaire::class,
                 'choice_label' => 'nom',
                 'label' => 'Appartement du proprietaire'
             ]);
             
             $form = $form->getForm();

             $form->handleRequest($request);
                
             if($form->isSubmitted() && $form->isValid()){
                
                $appartement->setArrondissement($form->get('arrondissement')->getData()->getArrondissementDemande());
                $prix = $appartement->getPrixLocation();
                $appartement->setMontantCotisation($prix*0.07);
                $appartement->setPrixTotal($appartement->getPrixCharge()+$appartement->getPrixLocation());
                $manager->persist($appartement);
                $manager->flush();

                return $this->redirectToRoute('show_one', ['id' => $appartement->getId()]);
             }


             

        return $this->render('appartement/add.html.twig',[
            "formA" => $form->createView()
        ]);
    }

}
