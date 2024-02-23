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
                    new Assert\NotBlank(),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo',
                'required' => true, // Ensure logo is required
                'constraints' => [
                    new Assert\NotBlank(), // Ensure logo is not blank
                ],
            ])
                        ->add('siteweb', null, [
                'constraints' => [
                    new Assert\Url(),
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
