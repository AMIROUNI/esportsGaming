<?php
/// src/Form/GroupType.php
namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'mapped' => false, // This field is not mapped to the entity directly
                'required' => false,
            ])
            ->add('gamer', EntityType::class, [
                'class' => User::class,           // Specifies the User entity
                'choices' => $options['gamers'], // List of users passed from the controller
                'multiple' => true,              // Allows multiple selections
                'expanded' => true,              // Displays as checkboxes
                'choice_label' => function (User $user) {
                    return $user->getNom();      // Displays the user's name
                },
                'label' => 'Joueurs',             // Field label
            ])
            ->add('admin', EntityType::class, [
                'class' => User::class,           // Specifies the User entity
                'choices' => $options['gamers'], // List of users passed from the controller
                'choice_label' => function (User $user) {
                    return $user->getNom();      // Displays the user's name
                },
                'label' => 'Admin du Groupe',     // Field label
                'placeholder' => 'Sélectionnez un admin', // Optional placeholder
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'gamers' => [],  // Default: empty array of users
        ]);

        // Mark the 'gamers' option as required
        $resolver->setRequired('gamers');
    }
}