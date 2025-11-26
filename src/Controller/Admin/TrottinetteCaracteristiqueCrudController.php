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
            IdField::new('id')->hideOnForm(),

            AssociationField::new('trottinette', 'Trottinette')
                ->setRequired(true)       // obligatoire
                ->hideOnIndex(),          // visible uniquement dans le formulaire

            AssociationField::new('categorie', 'Catégorie')
                ->hideOnIndex(),          // visible uniquement dans le formulaire

            AssociationField::new('caracteristique', 'Caractéristique')
                ->setRequired(true),      // obligatoire

            TextField::new('title', 'Titre personnalisé')
                ->hideOnIndex(),          // optionnel, seulement dans le formulaire

            TextField::new('value', 'Valeur')
                ->setRequired(true),      // obligatoire
        ];
    }
}
