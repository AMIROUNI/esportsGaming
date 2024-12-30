<?php
// src/Form/ContenuType.php
namespace App\Form;

use App\Entity\BlogCategory;
use App\Entity\Contenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Corrected to use EntityType
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => false,
            ])

            ->add('content', TextareaType::class, [
                'required' => false, // Optional field
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'background: #444; color: #f5f5f5; border: 1px solid #ff8000;',
                    'placeholder' => 'Entrez le contenu détaillé ici'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
            ])
            // Use EntityType for the categories field
            ->add('categories', EntityType::class, [
                'class' => BlogCategory::class, // The entity class for BlogCategory
                'choices' => $options['categories'], // Passing the categories from the controller
                'label' => 'Catégorie(s)',
                'multiple' => true,  // Allow multiple categories to be selected
                'expanded' => false,  // Use a dropdown (set to true for checkboxes)
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contenu::class,
            'categories' => [], // This will be passed from the controller
        ]);
    }
}
