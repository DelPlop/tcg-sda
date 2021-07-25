<?php

namespace App\Form;

use App\Entity\ApplicationUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'class' => 'big-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.errors.not_blank.password'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'form.email',
                'required' => true,
                'attr' => [
                    'class' => 'big-input'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'form.errors.not_blank.email'
                    ])
                ]
            ])
            ->add('valid_form', SubmitType::class, [
                'label' => 'form.edit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ApplicationUser::class,
            'translation_domain' => 'cards'
        ]);
    }
}