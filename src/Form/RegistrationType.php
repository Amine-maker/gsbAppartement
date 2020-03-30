<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('ville')
            ->add('cp')
            ->add('telephone', TelType::class)
            ->add('email', EmailType::class)
            ->add('rib',TextType::class,['mapped' => false , 'required' => false])
            ->add('telBanque',NumberType::class,['mapped' => false, 'required' => false])
            ->add('mdp', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    "Client" => "ROLE_CLIENT",
                    "Locataire" => "ROLE_LOCATAIRE",
                    "Proprietaire" => "ROLE_PROPRIETAIRE",
                ],
                'label' => 'Qui êtes vous', 
                'mapped'=> false,
                'expanded'=>true , 
                'label_attr' => ['class' => 'col']

                ])
            ->add('appartement',EntityType::class,[
                'class' => Appartement::class,
                'choice_label' => 'rue',
                'label' => 'Appartement du locataire',
                'mapped'=>false
                ]);       
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
