<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l’adresse',
                'attr' => ['placeholder' => 'Ex : Maison, Bureau, etc.']
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Entrez votre prénom']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Entrez votre nom']
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'required' => false,
                'attr' => ['placeholder' => '(facultatif) Entrez le nom de votre société']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Entrez votre adresse']
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postal',
                'attr' => ['placeholder' => 'Ex : 75001'],
                'constraints' => [
                    new Length(['min' => 4, 'max' => 5]),
                    new Regex([
                        'pattern' => '/^\d{4,5}$/', // accepte 4 ou 5 chiffres
                        'message' => 'Le code postal doit contenir 4 ou 5 chiffres.'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Entrez votre ville']
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'preferred_choices' => ['FR'],
                'attr' => ['placeholder' => 'Sélectionnez votre pays']
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Ex : +33612345678'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+33[67]\d{8}$/',
                        'message' => 'Le numéro doit commencer par +336 ou +337 et contenir 8 chiffres.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
