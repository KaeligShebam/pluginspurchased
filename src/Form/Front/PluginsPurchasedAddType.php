<?php

namespace App\Form\Front;

use App\Entity\Plugins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class PluginsPurchasedAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du plugin',
                'attr' => [
                    'placeholder' => 'Nom du plugin',
                    'class' => 'label-custom'
                ]
            ])
            ->add('link', TextType::class, [
                'label' => 'Lien',
                'attr' => [
                    'placeholder' => 'Lien',
                    'class' => 'label-custom'
                ]
            ])
            ->add('purchaseddate', DateType::class, [
                'label' => 'Date d\'achat',
                'attr' => [
                    'placeholder' => 'Date d\'achat',
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add( 'duration', DateType::class, [
                'label' => 'Durée',
                'attr' => [
                    'placeholder' => 'Durée',
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add('customer', TextType::class, [
                'label' => 'Client',
                'attr' => [
                    'placeholder' => 'Client',
                    'class' => 'label-custom'
                ]
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'attr' => [
                    'placeholder' => 'Prix',
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
            'data_class' => Plugins::class,
        ]);
    }
}
