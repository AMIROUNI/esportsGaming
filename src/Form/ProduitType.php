<?php
// src/Form/ProduitType.php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduits', TextType::class, [
                'label' => 'Nom du Produit',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
            ])
            ->add('image', FileType::class, [
                
                'required' => false,
                'mapped' => false,
            ])
            ->add('rating', NumberType::class, [
                'label' => 'Note',
                'scale' => 1,  // Arrondi à 1 décimale
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
