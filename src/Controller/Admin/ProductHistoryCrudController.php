<?php

namespace App\Controller\Admin;

use App\Entity\ProductHistory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Action, Actions};
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextEditorField,
    IntegerField,
    NumberField,
    ImageField,
    DateTimeField,
    AssociationField
};

class ProductHistoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductHistory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Historique produit")
            ->setEntityLabelInPlural("Historiques produits")
            ->showEntityActionsInlined()
            ->setSearchFields(['name', 'slug', 'product.name'])
            ->setDefaultSort(['modifiedAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        // On désactive la création, édition et suppression : historique = lecture seule
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            IdField::new('id')->hideOnForm(),

            // Produit lié
            AssociationField::new('product', 'Produit d’origine')
                ->setFormTypeOption('disabled', true),

            // Snapshot du nom à la date du changement
            TextField::new('name', 'Nom enregistré')
                ->setFormTypeOption('disabled', true),

            TextField::new('slug', 'Slug')
                ->setFormTypeOption('disabled', true),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', true),

            IntegerField::new('stock', 'Stock')
                ->setFormTypeOption('disabled', true),

            NumberField::new('price', 'Prix (€)')
                ->formatValue(fn($v) => number_format($v, 2, ',', ' ') . ' €')
                ->setFormTypeOption('disabled', true),

            // Image à l'instant T
            ImageField::new('mainImage', 'Image principale')
                ->setBasePath('/uploads/illustrations')
                ->hideOnForm(),

            DateTimeField::new('modifiedAt', 'Date de modification')
                ->setFormTypeOption('disabled', true),
        ];
    }
}
