<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ShowHidePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',  TextType::class, [
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'max' =>30
                ]),
                'attr' => [
                    'placeholder' => 'Prénom',
                    'class' => ' form-control is-invalid'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'max' =>30
                ]),
                'attr' => [
                    'placeholder' => 'Nom de famille',
                    'class' => ' form-control is-invalid'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'constraints' => new Length([
                    'min' => 2,
                    'max' =>30
                ]),
                    'attr' => [
                        'placeholder' => 'Adresse email',
                        'class' => ' form-control is-invalid'
                ]
            ])
            ->add('password', RepeatedType::class, [
            // ->add('password', ShowHidePasswordType::class, [
                // 'type' => PasswordType::class,
                'type' => ShowHidePasswordType::class,
                'invalid_message' => 'Les mot de passes ne sont pas identiques',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => ['label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                        
                    ]
                ],
                'second_options' => ['label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmation du mot de passe'
                    ]
                    ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                   'class' => 'btn-blue-shebam'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
