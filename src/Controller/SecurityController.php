<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Client;
use App\Entity\Locataire;
use App\Entity\Proprietaire;
use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class SecurityController extends AbstractController
{


    /**
     * @Route ("/inscription", name = "inscription")
     * @Route ("/user/edit/{id}", name = "editUser")
     */
public function regist(Request $request,
                      ObjectManager $manager,
                      UserPasswordEncoderInterface $encoder,
                      Client $client = null,
                      Utilisateur $user = null,
                      Locataire $locataire = null,
                      Proprietaire $proprietaire = null
                    )
            {
        
        if(!$client){$client = new Client();}
        if(!$user) {$user = new Utilisateur();}
        if(!$locataire) {$locataire = new Locataire();}
        if(!$proprietaire){$proprietaire = new Proprietaire();} 
        

        $form = $this->createForm(RegistrationType::class, $user);
        $formC = $this->createForm(RegistrationType::class, $client);
        $formL = $this->createForm(RegistrationType::class, $locataire);
        $formP = $this->createForm(RegistrationType::class, $proprietaire);
        

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
           
            $role = [0=>$form->get('roles')->getData()];
            $user->setRoles($role);
            
            switch ($user->getRoles()[0])
            {
                case 'ROLE_CLIENT' : {
                    $formC->handleRequest($request);
                    $hash = $encoder->encodePassword($client, $client->getPassword());
                    $client->setRoles($role);
                    $client->setMdp($hash);
                    $manager->persist($client);
                    break;           }

                case 'ROLE_PROPRIETAIRE' : {

                    $formP->handleRequest($request);
                    $proprietaire->setRoles($role);
                    $hash = $encoder->encodePassword($proprietaire, $proprietaire->getPassword());
                    $proprietaire->setMdp($hash);
                    $manager->persist($proprietaire);

                     break;}
    
                case 'ROLE_LOCATAIRE' : {
                    $formL->handleRequest($request);
                    $hash = $encoder->encodePassword($locataire, $locataire->getPassword());
                    $locataire->setMdp($hash);
                    $locataire->setRib($formL->get('rib')->getData());
                    $locataire->setTelBanque($formL->get('telBanque')->getData());
                    $locataire->setAppartement($formL->get('appartement')->getData());
                    $locataire->setRoles($role);
                    $manager->persist($locataire);
                    break;}
            }

            $manager->flush();
            dump($user);
            return $this->redirectToRoute('connexion');
        }

            if($this->getUser() !== null){
                $editMode = true;
            }
            else{$editMode = false;}


        return $this->render('security/registr.html.twig',
        [
            'form' => $form->createView(), 'editMode'=> $editMode
        ]);
    }



    /**
     * @Route ("/user/show", name = "showUsers")
     */
    public function showUsers(PaginatorInterface $paginator, Request $request){

        $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        
        foreach ($users as $user) {
            if (method_exists($user, 'getAppartement'))
            {
                $id = $user->getAppartement()->getId();
                $appart = $this->getDoctrine()->getRepository(Appartement::class)->findBy(array('id' => $id));
                dump($appart);
                dump($user->getAppartement()->getId());
                $user->setAppartement($appart[0]);

            }
        }

        $appointments = $paginator->paginate(
            // Doctrine Query, not results
            $users,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('security/showUsers.html.twig', [
 
            'users' => $appointments
        ]);
    }

        /**
         * @Route("/", name="connexion")
         */
    public function login(AuthenticationUtils $authenticationUtils, EntityManagerInterface $em) : Response{

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('security/login.html.twig', [
            'error' => $error
        ]);
    }


    /**
     * @Route("/deconnexion", name = "deconnexion")
     */
    public function logout(){}




    /**
     * @Route("/user/supprimer/{id}", name="delUser")
     */
    public function delete(Utilisateur $utilisateur, ObjectManager $manager){
 
        //dump($utilisateur);

        $manager->remove($utilisateur);
        $manager->flush();
        
        return $this->redirectToRoute("showUsers");
    }
 
}


