<?php

namespace App\Form;

use App\Form\HoneyPotType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => ' ',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre nom et prénom']),
                    new Length(['min' => 2, 'max' => 50, 'minMessage' => 'Nom trop court', 'maxMessage' => 'Nom trop long'])
                ],
                'attr' => [
                    'class' => 'form-element',
                    'id' => 'name',
                    'placeholder' => 'Nom & Prénom'
                ]
            ])
            ->add('tel', TextType::class, [
                'label' => ' ',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre numéro de téléphone']),
                    new Regex([
                        'pattern' => '/^(?:\+33[67]\d{8}|\+32[4-7]\d{8})$/',
                        'message' => 'Le numéro doit commencer par +33 (France) ou +32 (Belgique) et être correct.'
                    ])
                ],
                'attr' => [
                    'class' => 'form-element',
                    'id' => 'tel',
                    'placeholder' => '+33612345678',
                    'pattern' => "^(\\+33[67]\\d{8}|\\+32[4-7]\\d{8})$",
                    'title' => 'France : +33 6 ou 7, Belgique : +32 4 à 7',
                    'required' => true
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => ' ',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre adresse e-mail']),
                    new Email(['message' => 'Veuillez saisir une adresse e-mail valide'])
                ],
                'attr' => [
                    'class' => 'form-element',
                    'id' => 'email',
                    'placeholder' => 'admin@mhvolt.com',
                    'required' => true
                ]
            ])
            ->add('company', TextType::class, [
                'label' => ' ',
                'required' => false,
                'attr' => [
                    'class' => 'form-element',
                    'id' => 'company',
                    'placeholder' => 'Https://mhvolt.com'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => ' ',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre message']),
                    new Length(['min' => 5, 'max' => 1000, 'minMessage' => 'Message trop court', 'maxMessage' => 'Message trop long'])
                ],
                'attr' => [
                    'class' => 'form-element',
                    'id' => 'message',
                    'placeholder' => "J'aimerais des informations sur des trottinettes svp..."
                ]
            ])
            ->add('honeypot', HoneyPotType::class, [
                'mapped' => false, // IMPORTANT
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn-contact']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // pas de data_class nécessaire ici
        ]);
    }
}
