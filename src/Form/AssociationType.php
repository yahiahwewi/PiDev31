<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir un nom.']),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez saisir une description.']),
                ],
            ])
            ->add('logo', FileType::class,['mapped'=>false, array('data_class' => null)])
            ->add('logo', FileType::class, array('data_class' => null,'required' => false))
            
            ->add('siteweb', null, [
                'constraints' => [
                    new Assert\Url(['message' => 'Veuillez saisir une URL valide.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
