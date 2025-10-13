<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AddressType; // ✅ important : import du AddressType pour les adresses
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            // --- Prénom ---
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre prénom',
                ],
            ])

            // --- Nom ---
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre nom',
                ],
            ])

            // --- Téléphone ---
            ->add('tel', TextType::class, [
                'label' => 'Votre n° de téléphone',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+33[67]\d{8}$/',
                        'message' => 'Le numéro doit commencer par +336 ou +337 et comporter 8 chiffres après.',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Ex : +33612345678',
                ],
            ])

            // --- Email ---
            ->add('email', EmailType::class, [
                'label' => 'Votre e-mail',
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'max' => 55,
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Saisissez votre e-mail',
                ],
            ])

            // --- Adresses (CollectionType) ---
            // ⚠️ NE PAS inclure le champ user dans AddressType sinon boucle infinie !
            ->add('addresses', CollectionType::class, [
                'entry_type' => AddressType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false, // 🔥 indispensable pour Doctrine
                'label' => false,
                'prototype' => true,
                'required' => false, // facultatif à l’inscription
            ])

            // --- Mot de passe ---
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Saisissez votre mot de passe',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe',
                    ],
                ],
            ])

            // --- Bouton d’envoi ---
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire",
                'attr' => [
                    'class' => 'submit',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
