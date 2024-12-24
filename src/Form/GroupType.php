<?php
namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGroup')
            ->add('logo', FileType::class, [
                'required' => false, // Set to false if logo is optional
                'mapped' => false,   // Do not map this field to the entity directly
                'attr' => ['accept' => 'image/*']
            ])
            ->add('gamer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',  // Assuming User entity has a 'nom' property
                'multiple' => true,  // Allow multiple gamers to be selected
                'expanded' => true,  // Display checkboxes instead of a dropdown
                'choices' => $options['gamers'],  // Pass the list of gamers
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
            'gamers' => [],  // Default empty array for gamers
        ]);
    }
}
