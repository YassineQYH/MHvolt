<?php

namespace App\Controller\Admin;

use App\Entity\ProductHistory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Action, Actions};
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    DateTimeField,
    IdField,
    ImageField,
    IntegerField,
    MoneyField,
    TextEditorField,
    TextField
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
            ->showEntityActionsInlined(true)
            ->setSearchFields(['name', 'slug', 'product.name'])
            ->setDefaultSort(['modifiedAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            AssociationField::new('product', 'Produit d’origine'),

            TextField::new('name', 'Nom enregistré'),

            TextField::new('slug', 'Slug')->hideOnIndex(),

            TextEditorField::new('description', 'Description')
                ->onlyOnDetail(),

            IntegerField::new('stock', 'Stock'),

            MoneyField::new('price', 'Prix enregistré')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            ImageField::new('mainImage', 'Image principale')
                ->setBasePath('/')
                ->onlyOnIndex(),

            DateTimeField::new('modifiedAt', 'Date de modification'),
        ];
    }
}
