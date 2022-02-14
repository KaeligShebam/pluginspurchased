<?php

namespace App\Form\Front\MonthsContracts;

use App\Entity\MonthsContracts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class MonthsContractsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du contrat',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('link', UrlType::class, [
                'label' => 'Lien',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('startdate', DateType::class, [
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add('enddate', DateType::class, [
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add( 'duration', TextType::class, [
                'label' => 'Durée',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('customer', TextType::class, [
                'label' => 'Client',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn-yellow-shebam']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MonthsContracts::class,
        ]);
    }
}
