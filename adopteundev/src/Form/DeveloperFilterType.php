<?php

namespace App\Form;

// use App\Repository\TechnologyRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeveloperFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valeur', SearchType::class, [
                'label' => 'Rechercher',
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher par nom, mot cles etc...']
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => $options['categories'],
                'multiple' => false,
                'expanded' => true,
                'required' => false
            ])
            // ->add('technologies', ChoiceType::class, [
            //     'label' => 'Technologies',
            //     'choices' => [
            //         'PHP' => 'php',
            //         'JavaScript' => 'javascript',
            //         'Python' => 'python',
            //         'Java' => 'java',
            //     ],
            //     'multiple' => true,
            //     'expanded' => true,
            //     'required' => false
            // ])
            ->add('experience', ChoiceType::class, [
                'label' => "Niveau d'expérience",
                'choices' => [
                    '1 an' => 1,
                    '2 ans' => 2,
                    '3 ans' => 3,
                    '4 ans' => 4,
                    '5 ans et plus' => 5,
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false
            ])
            ->add('salaireMin', IntegerType::class, [
                'label' => 'Salaire minimum',
                'required' => false,
                'attr' => ['placeholder' => 'Salaire min en €']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => ['class' => 'primry-btn-2 lg-btn text-center']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'categories' => [], 
        ]);
    }
}
