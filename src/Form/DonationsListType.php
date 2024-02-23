<?php

namespace App\Form;

use App\Entity\DonationsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Donator;


class DonationsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('montant')
            ->add('donator_id_id', EntityType::class, [
                'class' => Donator::class,
                'choice_label' => 'Nom', // Remplacez 'Nom' par le nom du champ que vous voulez afficher dans le champ de formulaire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonationsList::class,
        ]);
    }
}
