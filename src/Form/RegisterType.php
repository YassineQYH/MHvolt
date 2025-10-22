<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AddressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                    new Length(['min' => 2, 'max' => 30]),
                ],
                'attr' => ['placeholder' => 'Saisissez votre prénom'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [
                    new Length(['min' => 2, 'max' => 30]),
                ],
                'attr' => ['placeholder' => 'Saisissez votre nom'],
            ])
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'required' => false,
            ])

            ->add('tel', TelType::class, [
                'label' => 'Votre téléphone',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?:\+33[67]\d{8}|\+32[4-7]\d{8})$/',
                        'message' => 'Le numéro doit commencer par +33 (France) ou +32 (Belgique) et être correct.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '+33612345678',
                    'pattern' => "^(\\+33[67]\\d{8}|\\+32[4-7]\\d{8})$",
                    'title' => 'France : +33 6 ou 7, Belgique : +32 4 à 7',
                    'required' => true
                ],
            ])


            ->add('email', EmailType::class, [
                'label' => 'Votre e-mail',
                'constraints' => [
                    new Length(['min' => 5, 'max' => 55]),
                    new Regex([
                        'pattern' => '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}(\.[a-z]{2,})?$/i',
                        'message' => 'Veuillez saisir une adresse email valide (ex: xxx@xxx.xx ou xxx@xxx.xx.xx)',
                    ])

                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre e-mail',
                    'pattern' => '^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$',
                    'title' => 'Format attendu: xx@xxxx.xx',
                    'required' => true
                ],
            ])


            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et sa confirmation doivent correspondre.',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisissez votre mot de passe',
                        // ⚠️ même correction ici : doubler les antislashs
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{10,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 10 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial.',
                        'title' => 'Min 10 caractères, 1 maj, 1 min, 1 chiffre, 1 caractère spécial',
                        'required' => true
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                        'required' => true
                    ],
                ],
                // Validation côté serveur
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un mot de passe']),
                    new Regex([
                        // ici aussi, on garde les antislashs simples, car c'est du PHP pur
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{10,}$/',
                        'message' => 'Min 10 caractères, 1 maj, 1 min, 1 chiffre, 1 caractère spécial.',
                    ])
                ]
            ])


            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => ['class' => 'submit'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
