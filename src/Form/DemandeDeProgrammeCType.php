<?php

namespace App\Form;

use App\Entity\DemandeDeProgrammeC;
use App\Entity\ProgrammeCoaching;
use App\Entity\User;
use App\Enum\EtatDeParticipation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class DemandeDeProgrammeCType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'Pending' => EtatDeParticipation::Pending,
                    'Approved' => EtatDeParticipation::Approved,
                    'Rejected' => EtatDeParticipation::Rejected,
                ],
                'choice_label' => fn ($choice) => match ($choice) {
                    EtatDeParticipation::Pending => 'Pending',
                    EtatDeParticipation::Approved => 'Approved',
                    EtatDeParticipation::Rejected => 'Rejected',
                },
                'choice_value' => fn ($choice) => $choice?->value,
                'placeholder' => 'Select a status',
            ])
            ->add('gamer', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_GAMER%');
                },
                'placeholder' => 'Select a Gamer',
            ])
            ->add('coach', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_COACH%');
                },
                'placeholder' => 'Select a Coach',
            ])
            ->add('programmeCoaching', EntityType::class, [
                'class' => ProgrammeCoaching::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DemandeDeProgrammeC::class,
        ]);
    }
}
