<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Appartement;
use App\Entity\Arrondissement;
use App\Entity\Client;
use App\Entity\Image;
use App\Entity\Proprietaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;





class AppartementFixtures extends Fixture
{
    
    
    public function __construct(UserPasswordEncoderInterface $encoder)
        {
            $this->encoder = $encoder;
        }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
       


        for ($i=0; $i < 10 ; $i++) { 

           $user = new Proprietaire();
           $user->setNom($faker->lastName)
                ->setPrenom($faker->firstName())
                ->setTelephone("0".$faker->numberBetween(611111111,699999999)) 
                ->setAdresse($faker->streetAddress)
                ->setEmail($faker->freeEmail)   
                ->setVille($faker->city)
                ->setCp($faker->numberBetween(90000, 99999))
                ->setMdp($this->encoder->encodePassword($user, '11111'))
                ->setRoles(array(0=>"ROLE_PROPRIETAIRE"))
                                ;
            $manager->persist($user);

           for ($j=0; $j < mt_rand(1,4) ; $j++) { 
            $appartement = new Appartement();
            $appartement->setRue($faker->streetAddress)
                        ->setArrondissement($faker->numberBetween(1,43))
                        ->setEtage($faker->numberBetween(1,15))
                        ->setPrixLocation($faker->numberBetween(400, 1200))
                        ->setPrixCharge($faker->numberBetween(100, 200))
                        ->setTypeAppart("F".$faker->numberBetween(1, 5))
                        ->setPreavis($faker->numberBetween(0, 1))
                        ->setAscenseur($faker->numberBetween(0, 1))
                        ->setPrixTotal($appartement->getPrixLocation()+$appartement->getPrixCharge())
                        ->setMontantCotisation($appartement->getPrixLocation()*0.07)
                        ->setProprietaire($user);
                        ;

            $manager->persist($appartement);
        }

        }

       
        

        $manager->flush();
    }
}
