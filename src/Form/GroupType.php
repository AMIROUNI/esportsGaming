<?php
// src/Form/GroupType.php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomGroup', TextType::class, [
                'label' => 'Nom du Groupe',
            ])
            ->add('logo', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('gamer', EntityType::class, [
                'class' => User::class,           // Spécifie que c'est une entité User
                'choices' => $options['gamers'], // Liste des utilisateurs passée depuis le contrôleur
                'multiple' => true,              // Permet plusieurs sélections
                'expanded' => true,              // Transforme en cases à cocher
                'choice_label' => function (User $user) {
                    return $user->getNom();      // Nom de l'utilisateur affiché
                },
                'label' => 'Joueurs',             // Label du champ
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'gamers' => [],  // Défaut : tableau vide de joueurs
        ]);
    }
}
