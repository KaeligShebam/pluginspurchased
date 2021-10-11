<?php

namespace App\Form\Back\User;

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
            'label' => 'Adresse mail',
            'label_attr' => ['class' => 'label-custom'],
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'label_attr' => ['class' => 'label-custom'],
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom de Famille',
            'label_attr' => ['class' => 'label-custom'],
        ])
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ),
                'label' => 'Rôle',
                'label_attr' => ['class' => 'label-custom'],
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
