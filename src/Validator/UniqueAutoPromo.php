<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute] // ← Obligatoire pour PHP 8 Attributes
class UniqueAutoPromo extends Constraint
{
    public string $message = 'Une autre promotion automatique est déjà active. Vous ne pouvez en activer qu’une seule à la fois.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT; // valide sur l’objet entier
    }
}
