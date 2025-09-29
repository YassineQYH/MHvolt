<?php

namespace App\Controller\Admin;

use App\Entity\TrottinetteCaracteristique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, AssociationField
};

class TrottinetteCaracteristiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrottinetteCaracteristique::class;
    }

    public function configureFields(string $pageName): iterable
    {
    return [
        AssociationField::new('trottinette'),
        AssociationField::new('categorie'),
        AssociationField::new('caracteristique'),
        TextField::new('value')
    ];
    }
}
