<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => ShowHidePasswordType::class,
                'first_options' => [
                    'attr' => [
                    'placeholder' => 'Mon nouveau mot de passe'
                ],
                    'label' => false,
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmation du mot de passe'
                    ],

                ],
                'invalid_message' => 'Les deux mots de passe ne sont pas identifiques',
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Changer le mot de passe',
                'attr' => [
                   'class' => 'btn-blue-shebam'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }}