<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    Field,
    IdField,
    NumberField,
    TextField
};

class TvaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tva::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Taux de TVA')
            ->setEntityLabelInPlural('Taux de TVA')
            ->showEntityActionsInlined(true);
    }

    public function configureActions(Actions $actions): Actions
    {
        // Empêche la suppression accidentelle d'un taux de TVA utilisé par la boutique
        return $actions
            ->disable(Action::DELETE);
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
                ->setStoredAsString(false)
                ->formatValue(fn ($value) => number_format((float)$value, 2, ',', ' ') . ' %'),

            Field::new('products', 'Produits associés')
                ->formatValue(function ($value, $entity) {
                    $count = $entity->getProducts() ? $entity->getProducts()->count() : 0;
                    return $count . ' produit(s)';
                })
                ->onlyOnIndex(),
        ];
    }
}
