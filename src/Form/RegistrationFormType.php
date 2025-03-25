<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', null, [
                "label" => "Nom",
                "attr" => [
                    "placeholder" => "Votre Nom",
                    "position" => "center"
                ],
                "required" => false,
                "constraints" => [
                    new Length([
                        "min" => 3,
                        "max" => 25,
                        "minMessage" => "{{ limit }} caractères minimum.",
                        "maxMessage" => "{{ limit }} caractères maximum."
                    ]),
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire.',
                    ]),
                ]
            ])
            ->add('email', null, [
                "label" => "Email",
                "required" => false,
                "attr" => ["placeholder" => "Votre@email.com"],
                "constraints" => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire.',
                    ]),
                    new Email([
                        'message' => 'L\'email "{{ value }}" n\'est pas valide.',
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les conditions d'utilisation",
                'mapped' => false,
                "required" => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
                "toggle" => true,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                // 'toggle' => true,
                "required" => false,
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'new-password',
                    "placeholder" => "Votre mot de passe"
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@.$!%*#?&])[A-Za-z\d@$!%*#?&]{12,}$/',
                        'message' => 'Le mot de passe doit contenir au moins une lettre, un chiffre et un caractère spécial.',
                    ]),
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'le nombre minimal de caractère est de{{ limit }}',
                        'maxMessage' => 'le nombre maximal de caractère est de{{ limit }}',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label_attr' => ['class' => 'label has-text-weight-normal'],
            'help_attr' => ['class' => 'help']
        ]);
    }
}
