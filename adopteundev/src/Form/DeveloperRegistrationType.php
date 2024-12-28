<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Developer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeveloperRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mobile')
            // ->add('salaireMin')
            // ->add('isDisponible')
            // ->add('experience')
            ->add('ville')
            ->add('adresse')
            // ->add('biographie')
            ->add('user', UserType::class, [
                'label' => false,
                // 'choice_label' => 'id',
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Developer::class,
        ]);
    }
}
