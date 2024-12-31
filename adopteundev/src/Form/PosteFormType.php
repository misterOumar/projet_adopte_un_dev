<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Company;
use App\Entity\Poste;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('salaireMin')
            ->add('experienceRequis', ChoiceType::class, [
            'choices' => [
                '1 an' => 1,
                '2 ans' => 2,
                '3 ans' => 3,
                '4 ans' => 4,
                '5 ans ou plus' => 5,
            ],
            'placeholder' => 'Choisissez le nombre d\'expérience',
            'required' => true,
            'attr' => [
                'class' => 'select1', // Classe CSS pour le style
            ],
            'label' => false,]                
            )
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Temps plein' => 'Temps plein',
                    'Temps partiel' => 'Temps partiel',
                    'Freelance' => 'Freelance',
                ],
                'placeholder' => 'Choisissez le type',
                'required' => true,
                'attr' => [
                    'class' => 'select1', // Classe CSS pour le style
                ],
                'label' => false,]                
            )
            ->add('ville')
            ->add('adresse')
            ->add('dateLimite')
            ->add('salaireMin')
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Décrivez le poste ici...',
                    'rows' => 8,
                    'id' => 'summernote1',
                ],
                'required' => true,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez une catégorie',
                'required' => true,
                'attr' => [
                    'class' => 'select1', // Classe CSS ajoutée pour le style
                ],
                'label' => false, // Masque le label
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
        ]);
    }
}
