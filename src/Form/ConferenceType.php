<?php

namespace App\Form;

use App\Entity\Conference;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une date.']),
                ],
                'required' => true,
            ])
            ->add('lieu', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un lieu.']),
                ],
                'required' => true,
            ])
            ->add('association', EntityType::class, [
                'class' => Association::class,
                'choice_label' => 'nom', // Remplacer 'nom' par le nom de la propriété à afficher
                'label' => 'Association', // Libellé du champ dans le formulaire
                'required' => true,
                'placeholder' => 'Choisir une association', // Texte affiché par défaut dans le champ
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conference::class,
        ]);
    }
}
