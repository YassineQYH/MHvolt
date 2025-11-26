<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, NumberField
};
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class TvaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tva::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Nom de la TVA')
                ->setRequired(true),

            NumberField::new('value', 'Valeur (%)')
                ->setRequired(true)
                ->setNumDecimals(2)
                ->setStoredAsString(false),

            // Champ personnalisé pour afficher le nombre de produits liés
            Field::new('products', 'Produits associés')
                ->formatValue(function ($value, $entity) {
                    return $entity->getProducts()->count();
                })
                ->onlyOnIndex(), // visible uniquement dans la liste
        ];
    }
}
