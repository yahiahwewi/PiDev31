<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\User; // Import the User entity
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType for user field

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('createdAt')
            ->add('image')
            ->add('video')
            // Configure the 'user' field as an EntityType
            ->add('user', EntityType::class, [
                'class' => User::class, // Specify the associated User entity class
                'choice_label' => 'name', // Specify the property of User entity to use as label (e.g., 'name')
                'placeholder' => 'Choose an author', // Optional placeholder text for the dropdown
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
