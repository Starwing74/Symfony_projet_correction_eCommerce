<?php

namespace App\Form;

use App\DTO\UserDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('mail', EmailType::class, [
		        'label' => 'Adresse mail'
	        ])
            ->add('password', PasswordType::class, [
	            'label' => 'Mot de passe'
            ])
            ->add('passwordConfirm', PasswordType::class, [
	            'label' => 'Confirmation du mot de passe'
            ])
            ->add('name', TextType::class, [
	            'label' => 'Nom'
            ])
            ->add('address', TextType::class, [
	            'label' => 'Adresse postale'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
            'required' => false
        ]);
    }
}
