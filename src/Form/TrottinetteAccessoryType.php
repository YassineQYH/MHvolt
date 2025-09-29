<?php

namespace App\Form;

use App\Entity\TrottinetteAccessory;
use App\Entity\Trottinette;
use App\Entity\Accessory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrottinetteAccessoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('trottinette', EntityType::class, [
                'class' => Trottinette::class,
                'choice_label' => 'name',
            ])
            ->add('accessory', EntityType::class, [
                'class' => Accessory::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => TrottinetteAccessory::class]);
    }
}
