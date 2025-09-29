<?php

namespace App\Controller\Admin;

use App\Entity\CategorieCaracteristique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, CollectionField
};

class CategorieCaracteristiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategorieCaracteristique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom de la catégorie'),
            CollectionField::new('caracteristiques')
                ->setEntryType(\App\Form\TrottinetteCaracteristiqueType::class)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOption('by_reference', false)
                ->onlyOnDetail(), // Affiché seulement dans la page de détail
        ];
    }
}
