<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    AssociationField,
    TextField
};

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            AssociationField::new('user', 'Utilisateur'),

            TextField::new('name', 'Nom de l’adresse'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            TextField::new('company', 'Société')->hideOnIndex(), // facultatif
            TextField::new('address', 'Adresse'),
            TextField::new('postal', 'Code postal'),
            TextField::new('city', 'Ville'),
            TextField::new('country', 'Pays'),
            TextField::new('phone', 'Téléphone'),
        ];
    }
}
