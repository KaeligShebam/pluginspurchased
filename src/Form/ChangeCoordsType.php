<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangeCoordsType extends AbstractType
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', null, array('disabled' => true, 'label' => false)
        )
            ->add('old_password', ShowHidePasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ancien mot de passe'],
                'mapped' => false,
            ])
            ->add('new_password', RepeatedType::class, [

                'type' => ShowHidePasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mot de passes ne sont pas identiques',
                'label' => false,
                'attr' => ['placeholder' => 'Nouveau mot de passe'],
                'required' => true,
                'first_options' => ['label' => false,
                'attr' => ['placeholder' => 'Nouveau mot de passe'],
                ],
                'second_options' => ['label' => false,
                'attr' => ['placeholder' => 'Confirmer le nouveau mot de passe'],
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn-yellow-shebam']
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
