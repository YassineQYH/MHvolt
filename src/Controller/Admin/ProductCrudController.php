<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextEditorField,
    MoneyField,
    IntegerField,
    BooleanField,
    AssociationField,
    DateTimeField,
    CollectionField,
    FormField,
};

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            // ----------- IDENTIFIANT -----------
            IdField::new('id')->hideOnForm(),

            // ----------- INFO PRODUIT -----------
            FormField::addPanel('Informations générales'),

            TextField::new('name', 'Nom du produit'),
            TextField::new('slug')->hideOnIndex(),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(),

            // ----------- PRIX / STOCK -----------
            FormField::addPanel('Prix & Stock'),

            MoneyField::new('price', 'Prix HT')
                ->setCurrency('EUR'),

            IntegerField::new('stock', 'Stock'),

            BooleanField::new('isBest', 'Meilleure vente'),

            // ----------- RELATIONS -----------
            FormField::addPanel('Données liées'),

            AssociationField::new('weight', 'Poids'),

            AssociationField::new('tva', 'TVA'),

            // ----------- ILLUSTRATIONS : affichées mais non modifiées ici -----------
            FormField::addPanel('Illustrations'),

            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail() // visible seulement sur la page détail
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            // ----------- DATES -----------
            FormField::addPanel('Métadonnées'),

            DateTimeField::new('createdAt', 'Créé le')
                ->hideOnForm(),

            DateTimeField::new('updatedAt', 'Mis à jour le')
                ->hideOnForm(),
        ];
    }
}
