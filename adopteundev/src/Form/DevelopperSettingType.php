<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Developer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevelopperSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isDisponible', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input', // Classe CSS pour le style
                ],
                'required' => false,
            ])
            ->add('mobileVisible', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input', // Classe CSS pour le style
                ],
                'required' => false,
            ])
            ->add('salaireVisible', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input', // Classe CSS pour le style
                ],
                'required' => false,
            ])
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false, // Ce champ n'est pas lié à l'entité
                'required' => true,
                'label' => 'Mot de passe actuel',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false, // Ce champ n'est pas lié à l'entité
                'required' => false,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmer le nouveau mot de passe'],
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
