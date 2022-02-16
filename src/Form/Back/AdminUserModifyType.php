<?php

namespace App\Form\Back;

use App\Entity\User;
use App\Form\ShowHidePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AdminUserModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => false,
                'label_attr' => ['class' => 'label-custom','placeholder'=> 'Adresse mail'],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'label_attr' => ['class' =>'label-custom', 'placeholder' => 'Prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'label_attr' => ['class' => 'label-custom', 'placeholder' => 'Prénom'],
            ])
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ),
                'label' => false,
                'label_attr' => ['class' => 'label-custom'],
            ))
            ->add('password', RepeatedType::class, [
                // ->add('password', ShowHidePasswordType::class, [
                // 'type' => PasswordType::class,
                'type' => ShowHidePasswordType::class,
                'invalid_message' => 'Les mot de passes ne sont pas identiques',
                'label' => 'Mot de passe',
                'required' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe',

                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmation du mot de passe'
                    ]
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn-blue-shebam'],
            ]);
            $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) ? $rolesArray[0] : null;
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