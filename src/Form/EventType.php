<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Theme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('status')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('updated_at', null, [
                'widget' => 'single_text',
            ])

            ->add('theme', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'name',
                'label' => 'Theme*',
                'placeholder' => 'Saisir une un theme',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une theme'
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
