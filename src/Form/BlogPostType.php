<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Entity\User; // Import the User entity
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType for user field
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert; // Import the Assert namespace for validation constraints
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;


class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'constraints' => [
                    new Assert\Length([
                        'min' => 3,
                        'minMessage' => 'The title should be at least {{ limit }} characters long',
                    ]),
                    new NotBlank([
                        'message' => 'The Title cannot be blank.',
                    ]),
                ],
            ])
            ->add('content', null, [
                'constraints' => [
                    new Assert\Length([
                        'min' => 20,
                        'minMessage' => 'The content should be at least {{ limit }} characters long',
                    ]),
                    new NotBlank([
                        'message' => 'The Content cannot be blank.',
                    ]),
                ],
            ])
            // ->add('createdAt')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '3000k',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                ],
            ])
            // ->add('video')
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'name',
            //     'placeholder' => 'Choose an author',
            // ]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}