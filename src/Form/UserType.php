<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('plainPassword', PasswordType::class, [ // Add plainPassword field
                'mapped' => false, // Do not map this field to the User entity
                'required' => true,
                'label' => 'Password',
            ])
            ->add('roles', ChoiceType::class, [ // Champ pour sélectionner un rôle
                'choices' => [
                    'ROLE_GAMER' => 'ROLE_GAMER',   // Affiche "ROLE_GAMER"
                    'ROLE_COACHE' => 'ROLE_COACHE', // Affiche "ROLE_COACHE"
                ],
                'multiple' => true, // Permet de sélectionner plusieurs rôles si nécessaire
                'expanded' => true, // Affiche les choix sous forme de cases à cocher
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
