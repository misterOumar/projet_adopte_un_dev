<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Developer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeveloperProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mobile')
            ->add('salaireMin')
            // // ->add('isDisponible')
            ->add('ville')
            ->add('adresse')
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false, // Masque le label
                'attr' => [
                    'id' => 'upload-avatar-input', // Classe CSS ajoutée pour le style
                    'hidden' => true, // Classe CSS ajoutée pour le style
                ],

            ])
            ->add('cat', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez une catégorie',
                'required' => true,
                'attr' => [
                    'class' => 'select1', // Classe CSS ajoutée pour le style
                ],
                'label' => false, // Masque le label
            ])
            ->add(
                'experience',
                ChoiceType::class,
                [
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
                    'label' => false,
                ]
            )
            ->add('biographie', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Décrivez-vous ici...',
                    'rows' => 8,
                    'id' => 'summernote1',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Developer::class,
        ]);
    }
}
