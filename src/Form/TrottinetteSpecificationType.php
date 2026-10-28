<?php

namespace App\Form;

use App\Entity\SpecificationGroup;
use App\Entity\TrottinetteSpecification;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrottinetteSpecificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $datalistHtml = '
            <datalist id="trottinette_spec_suggestions">
                <option value="Application connectée">
                <option value="Autonomie">
                <option value="Batterie">
                <option value="Charge maximale">
                <option value="Dimensions pliée">
                <option value="Éclairage">
                <option value="Étanchéité (IP)">
                <option value="Freinage">
                <option value="Matériau du châssis">
                <option value="Moteur">
                <option value="Pente maximale">
                <option value="Poids">
                <option value="Puissance maximale">
                <option value="Puissance nominale">
                <option value="Régulateur de vitesse">
                <option value="Sécurité antivol">
                <option value="Sécurité enfant">
                <option value="Suspensions">
                <option value="Taille">
                <option value="Taille des pneus">
                <option value="Temps de charge">
                <option value="Type de pneus">
                <option value="Vitesse maximale">
            </datalist>
        ';

        $builder
            ->add('group', EntityType::class, [
                'class' => SpecificationGroup::class,
                'choice_label' => 'name',
                'label' => 'Groupe / Catégorie',
                'placeholder' => '-- Choisir un groupe --',
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('g')->orderBy('g.position', 'ASC'),
                'attr' => ['class' => 'form-select'],
            ])
            ->add('name', TextType::class, [
                'label' => 'Caractéristique',
                'attr' => [
                    'list' => 'trottinette_spec_suggestions',
                    'placeholder' => 'ex. Vitesse maximale, Batterie, Taille...',
                    'autocomplete' => 'off',
                ],
                'help' => $datalistHtml,
                'help_html' => true,
            ])
            ->add('value', TextType::class, [
                'label' => 'Valeur',
                'attr' => ['placeholder' => 'ex. 45 km/h, 48 V 13 Ah, 36 kg...'],
            ])
            ->add('position', IntegerType::class, [
                'label' => 'Ordre',
                'data' => 1,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TrottinetteSpecification::class,
        ]);
    }
}
