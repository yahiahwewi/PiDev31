<?php

namespace App\Form;

use App\Entity\Donator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
<<<<<<< HEAD
=======
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // Ajout de cette ligne
use Symfony\Component\Validator\Constraints as Assert;
>>>>>>> main

class DonatorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('Nom')
            ->add('Email')
            ->add('Password')
        ;
    }
=======
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('Prenom', TextType::class, [
                'label' => 'Prenom',
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('Email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['maxlength' => 255],
            ])
            ->add('Password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => true,
                'attr' => ['maxlength' => 255],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez saisir un mot de passe',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('Montant', IntegerType::class, [ // Ajout de ce champ
                'label' => 'Montant',
                'required' => true,
            ]);
    }
    
>>>>>>> main

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donator::class,
        ]);
    }
}
