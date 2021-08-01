<?php

namespace App\Form;

use App\Entity\ApplicationUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'form.errors.password_not_blank',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'form.errors.password_min_length',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'form.password',
                    'attr' => [
                        'class' => 'big-input'
                    ],
                    'label_attr' => [
                        'class' => 'inline'
                    ]
                ],
                'second_options' => [
                    'label' => 'form.confirm',
                    'attr' => [
                        'class' => 'big-input'
                    ],
                    'label_attr' => [
                        'class' => 'inline'
                    ]
                ],
                'invalid_message' => 'form.errors.passwords_must_match',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
            ->add('email', RepeatedType::class, [
                'type' => EmailType::class,
                'required' => false,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'form.errors.email_not_blank',
                        ]),
                    ],
                    'attr' => [
                        'class' => 'big-input'
                    ],
                    'label_attr' => [
                        'class' => 'inline'
                    ],
                    'label' => 'form.email'
                ],
                'second_options' => [
                    'label' => 'form.confirm',
                    'attr' => [
                        'class' => 'big-input'
                    ],
                    'label_attr' => [
                        'class' => 'inline'
                    ]
                ],
                'invalid_message' => 'form.errors.emails_must_match',
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
