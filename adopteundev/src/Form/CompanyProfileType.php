<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('phone')
            ->add('ville')
            ->add('adresse')
            ->add('localisation')
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'id' => 'upload-avatar-input', // Classe CSS ajoutée pour le style
                    'hidden' => true, // Classe CSS ajoutée pour le style
                ],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
