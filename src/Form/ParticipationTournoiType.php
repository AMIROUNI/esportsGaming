<?php

namespace App\Form;

use App\Entity\ParticipationTournoi;
use App\Entity\Tournoi;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationTournoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat')
            ->add('gamer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('tournoi', EntityType::class, [
                'class' => Tournoi::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParticipationTournoi::class,
        ]);
    }
}
