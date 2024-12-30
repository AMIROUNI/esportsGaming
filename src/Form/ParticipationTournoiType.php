<?php
// src/Form/ParticipationTournoiType.php

namespace App\Form;

use App\Entity\ParticipationTournoi;
use App\Entity\Group;
use App\Enum\EtatDeParticipationTournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType as DoctrineEntityType;

class ParticipationTournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $tournoi = $options['tournoi'] ?? null;  // Access the passed tournoi option
        $groups = $options['groups'] ?? [];      // Access the passed groups option
        
        $builder
            // Add the "etat" field for the tournament participation status
            ->add('etat', ChoiceType::class, [
                'choices' => EtatDeParticipationTournoi::cases(),
                'choice_label' => fn(EtatDeParticipationTournoi $etat) => $etat->getLabel(),  // Use getLabel() or value here
                'choice_value' => fn(?EtatDeParticipationTournoi $etat) => $etat?->value,  // Store the enum value (string)
                'label' => 'État de participation',
                'placeholder' => 'Choisir un état',
            ])
            // Add the "group" field with EntityType for selecting the group
            ->add('group', DoctrineEntityType::class, [
                'class' => Group::class,    // Reference to the Group entity
                'choices' => $groups,       // Pass the groups dynamically
                'choice_label' => 'nomGroup',   // Assuming the property in the Group entity is 'name'
                'placeholder' => 'Sélectionner un groupe',
                'label' => 'Groupe',
            ])
            // Add the "tournoi" field (you might not want to map it)
            ->add('tournoi', ChoiceType::class, [
                'choices' => [$tournoi->getNomTournoi() => $tournoi->getId()], // Assuming your Tournoi entity has getNomTournoi() and getId() methods
                'data' => $tournoi->getId(),
                'mapped' => false, // Don't map this to the entity directly
                'label' => 'Tournoi',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipationTournoi::class,
        ]);

        // Define custom options
        $resolver->setDefined(['tournoi', 'groups']);
    }
}
