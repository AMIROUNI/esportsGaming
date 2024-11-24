<?php

namespace App\Form;

use App\Entity\Coach;
use App\Entity\DemandeDeProgrammeC;
use App\Entity\Gamer;
use App\Entity\ProgrammeCoaching;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeDeProgrammeCType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat')
            ->add('gamer', EntityType::class, [
                'class' => Gamer::class,
                'choice_label' => 'id',
            ])
            ->add('coach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'id',
            ])
            ->add('programmeCoaching', EntityType::class, [
                'class' => ProgrammeCoaching::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeDeProgrammeC::class,
        ]);
    }
}
