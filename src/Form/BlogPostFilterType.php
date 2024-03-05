<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BlogPostFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filterBy', ChoiceType::class, [
                'choices' => [
                    'Date de création' => 'createdAt',
                    'Utilisateur' => 'user',
                ],
                'label' => 'Filtrer par :',
            ]);
    }
}
