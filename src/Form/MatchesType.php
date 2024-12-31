<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Matches;
use App\Entity\Tournoi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matchDate', null, [
                'widget' => 'single_text',
            ])
            ->add('groupA', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'nomGroup',
            ])
            ->add('groupB', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'nomGroup',
            ])
            ->add('tournoi', EntityType::class, [
                'class' => Tournoi::class,
                'choice_label' => 'nomTournoi',
            ])
            ->add('scoreA', NumberType::class, [
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('scoreB', NumberType::class, [
                'required' => false,
                'attr' => ['min' => 0],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matches::class,
        ]);
    }
}
