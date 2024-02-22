<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as assert;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', null, [
            'constraints' => [
                new NotBlank(['message' => 'L\'Nom ne peut pas être vide.']),
            ],
        ])            ->add('prenom')
            ->add('email')
            ->add('age')
            ->add('motorise', ChoiceType::class, [
                'label' => 'Motorisé',
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true, // Renders as radio buttons
                'multiple' => false, // Allows only one option to be selected
            ]) ;
            //->add('relation')        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
