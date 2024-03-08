<?php

namespace App\Form;

use App\Entity\Projects;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('description')
        ->add('amount')
        ->add('StartDate', DateType::class, [
            'widget' => 'single_text', // Render as single text input
            'html5' => false, // Do not render as HTML5 date input
        ])
        ->add('EndDate', DateType::class, [
            'widget' => 'single_text', // Render as single text input
            'html5' => false, // Do not render as HTML5 date input
        ])
        ->add('Status');
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
