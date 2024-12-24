<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Mot de passe',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_GAMER' => 'ROLE_GAMER',
                    'ROLE_COACHE' => 'ROLE_COACHE',
                ],
                'multiple' => true,  // Allow multiple selection
                'expanded' => false, // Use a select dropdown instead of checkboxes
                'label' => 'Roles', // Optional: You can add a label for the select
                'attr' => ['class' => 'form-control'], // Optional: Add Bootstrap class for styling
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Description',
            ])
            ->add('niveau', IntegerType::class, [
                'required' => false,
                'label' => 'Niveau',
            ])
            ->add('surNom', TextType::class, [
                'required' => false,
                'label' => 'Surnom',
            ])
            ->add('badge', TextType::class, [
                'required' => false,
                'label' => 'Badge',
            ]);
    }
    
}
