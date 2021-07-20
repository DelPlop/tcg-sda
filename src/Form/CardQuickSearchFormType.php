<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CardQuickSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'size' => '10',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'card_search.errors.not_blank'
                    ])
                ]
            ])
            ->add('valid_search', SubmitType::class, [
                'label' => 'form.ok',
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
