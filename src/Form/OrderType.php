<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        // ✅ On ajoute le champ uniquement si l'utilisateur a au moins une adresse
        if ($user && count($user->getAddresses()) > 0) {
            $builder->add('addresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses()->toArray(), // <-- convertit la collection en tableau
                'multiple' => false,
                'expanded' => true,
                'choice_label' => function (Address $address) {
                    return $address->getAddress() . ' - ' . $address->getCity();
                },
                'choice_value' => 'id', // <-- transforme l'objet en son ID pour le formulaire
            ]);
        }

        $builder->add('submit', SubmitType::class, [
            'label' => 'Valider ma commande',
            'attr' => [
                'class' => 'btn btn-success btn-block'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null,
        ]);
    }
}
