<?php

namespace App\Form\Back\User;

use App\Entity\User;
use App\Form\ShowHidePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AdminUserAddType extends AbstractType
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
                    'attr' => [
                        'placeholder' => 'Adresse email',
                        'class' => ' form-control is-invalid'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => ShowHidePasswordType::class,
                'invalid_message' => 'Les mot de passes ne sont pas identiques',
                'label' => false,
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmation du mot de passe',
                    ]
                    ],
            ])
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ),
                'label' => false,
                'attr' => [
                    'placeholder' => 'Role',
                ]
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn-submit-admin-shebam'],
            ])
        ;
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                // transform the array to a string
                return count($rolesArray)? $rolesArray[0]: null;
            },
            function ($rolesString) {
                // transform the string back to an array
                return [$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
