<?php

// src/Form/RegistrationType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', null, [
            'constraints' => [
                new Email(['message' => 'Adresse e-mail invalide.']),
                new NotBlank(['message' => 'L\'e-mail ne peut pas être vide.']),
               
            ],
        ])
            ->add('password', PasswordType::class, [
            'constraints' => [
                new Length(['min' => 5, 'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères.']),
                new NotBlank(['message' => 'Le mot de passe ne peut pas être vide.']),
            ],
        ])
     
        ->add('name', null, [
            'constraints' => [
                new Length(['min' => 5, 'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.']),
                new NotBlank(['message' => 'Le nom ne peut pas être vide.']),
            ],
        ])
        ->add('phone', null, [
            'constraints' => [
                new Length(['min' => 8, 'minMessage' => 'Le téléphone doit comporter au moins {{ limit }} chiffres.']),
                new NotBlank(['message' => 'Le téléphone ne peut pas être vide.']),
            ],
        ])
        ->add('city', null, [
            'constraints' => [
                new Length(['min' => 4, 'minMessage' => 'La ville doit comporter au moins {{ limit }} caractères.']),
                new NotBlank(['message' => 'La ville ne peut pas être vide.']),
            ],
        ])
        ->add('country', null, [
            'constraints' => [
                new Length(['min' => 3, 'minMessage' => 'Le pays doit comporter au moins {{ limit }} caractères.']),
                new NotBlank(['message' => 'Le pays ne peut pas être vide.']),
            ],
        ])
        ->add('registration_date', HiddenType::class, [
            'data' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);        



        
            }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
