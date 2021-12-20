<?php

namespace App\Form\Back;

use App\Entity\Plugins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class PluginsPurchasedModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du plugin',
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
            ->add('purchaseddate', DateType::class, [
                'label' => 'Date d\'achat',
                'attr' => [
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add('expirationdate', DateType::class, [
                'label' => 'Date d\'expiration',
                'attr' => [
                    'class' => 'label-custom'
                ],
                'widget' => 'single_text'
            ])
            ->add('duration', TextType::class, [
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
            ->add('price', TextType::class, [
                'label' => 'Prix',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('cms', TextType::class, [
                'label' => 'CMS',
                'attr' => [
                    'class' => 'label-custom'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn-yellow-shebam']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plugins::class,
        ]);
    }
}
