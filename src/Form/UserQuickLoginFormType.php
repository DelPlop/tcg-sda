<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserQuickLoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, [
                'label' => 'form.pseudo',
                'required' => true,
                'attr' => [
                    'class' => 'small-input',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.errors.not_blank.login'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'form.password',
                'required' => true,
                'attr' => [
                    'class' => 'small-input',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.errors.not_blank.password'
                    ])
                ]
            ])
            ->add('valid_login', SubmitType::class, [
                'label' => 'form.login',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'translation_domain' => 'cards'
        ]);
    }
}
