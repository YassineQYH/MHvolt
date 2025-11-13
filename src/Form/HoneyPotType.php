<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class HoneyPotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Champ invisible
        $builder->add('honeypot_field', TextType::class, [
            'mapped' => false,
            'required' => false,
            'attr' => [
                'class' => 'honeypot-field',
                'autocomplete' => 'off',
                'tabindex' => '-1',
            ],
            'constraints' => [
                new Length(['max' => 0, 'maxMessage' => 'Spam détecté !']),
            ],
        ]);

        // Vérification lors de la soumission
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $data = $event->getForm()->get('honeypot_field')->getData();
            if (!empty($data)) {
                $event->getForm()->addError(new FormError('Spam détecté !'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true, // Important : permet d’avoir des enfants
        ]);
    }
}
