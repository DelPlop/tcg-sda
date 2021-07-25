<?php

namespace App\Form;

use App\Entity\Culture;
use App\Entity\Edition;
use App\Entity\Phase;
use App\Entity\Rarity;
use App\Entity\Signet;
use App\Entity\Subtype;
use App\Entity\Tag;
use App\Entity\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            'yes' => 'card.options.yes',
            'no' => 'card.options.no'
        ];

        $builder
            ->add('name', TextType::class, [
                'label' => 'card.name',
                'required' => false,
                'attr' => [
                    'class' => 'medium-input',
                ]
            ])
            ->add('code', TextType::class, [
                'label' => 'card.code',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('edition', EntityType::class, [
                'label' => 'card.edition',
                'class' => Edition::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.isDisplayable = 1')
                        ->orderBy('e.editionNumber', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('rarity', EntityType::class, [
                'label' => 'card.rarity',
                'class' => Rarity::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.position', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('culture', EntityType::class, [
                'label' => 'card.culture',
                'class' => Culture::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.position', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('type', EntityType::class, [
                'label' => 'card.type',
                'class' => Type::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.position', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('subtype', EntityType::class, [
                'label' => 'card.subtype',
                'class' => Subtype::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.name', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('phases', EntityType::class, [
                'label' => 'card.phase',
                'class' => Phase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('cost', TextType::class, [
                'label' => 'card.cost',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('strength', TextType::class, [
                'label' => 'card.strength',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('vitality', TextType::class, [
                'label' => 'card.vitality',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('resistance', TextType::class, [
                'label' => 'card.resistance',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('signet', EntityType::class, [
                'label' => 'card.signet',
                'class' => Signet::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.characterName', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('site_number', TextType::class, [
                'label' => 'card.site_number',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('shadow_number', TextType::class, [
                'label' => 'card.shadow_number',
                'required' => false,
                'attr' => [
                    'class' => 'small-input',
                ]
            ])
            ->add('isUnique', ChoiceType::class, [
                'label' => 'card.unique',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('isRingBearer', ChoiceType::class, [
                'label' => 'card.ring_bearer',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('isTengwar', ChoiceType::class, [
                'label' => 'card.tengwar',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('isRf', ChoiceType::class, [
                'label' => 'card.rf',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('isRfa', ChoiceType::class, [
                'label' => 'card.rfa',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('hasLocalImage', ChoiceType::class, [
                'label' => 'card.local_image',
                'choices' => \array_flip($choices),
                'required' => false,
                'expanded' => true,
            ])
            ->add('text', TextType::class, [
                'label' => 'card.text',
                'required' => false,
                'attr' => [
                    'class' => 'big-input',
                ]
            ])
            ->add('quote', TextType::class, [
                'label' => 'card.quote',
                'required' => false,
                'attr' => [
                    'class' => 'big-input',
                ]
            ])
            ->add('tag', EntityType::class, [
                'label' => 'card.tag',
                'class' => Tag::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('valid_search', SubmitType::class, [
                'label' => 'form.search',
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
