<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    BooleanField,
    CollectionField,
    DateTimeField,
    FormField,
    IdField,
    ImageField,
    IntegerField,
    MoneyField,
    NumberField,
    TextEditorField,
    TextField
};

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Tous les produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined(true);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            ImageField::new('firstIllustration', 'Image')
                ->onlyOnIndex()
                ->setBasePath('/'),

            FormField::addPanel('Informations générales'),
            TextField::new('name', 'Nom du produit'),
            TextField::new('slug')->hideOnIndex(),
            TextEditorField::new('description', 'Description'),

            FormField::addPanel('Prix & Stock'),
            MoneyField::new('price', 'Prix de vente TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            MoneyField::new('promoPrice', 'Prix promo TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn ($value) => $value > 0 ? number_format((float)$value, 2, ',', ' ') . ' €' : '-'),

            IntegerField::new('stock', 'Stock disponible'),
            BooleanField::new('isBest', 'En avant sur l’accueil'),

            FormField::addPanel('Données techniques & fiscales'),
            NumberField::new('weight', 'Poids (kg)')
                ->formatValue(fn ($value) => $value !== null ? number_format((float)$value, 2, ',', ' ') . ' kg' : '-'),

            AssociationField::new('tva', 'TVA')
                ->onlyOnDetail()
                ->formatValue(fn ($value) =>$value ? $value->getName() . ' (' .$value->getValue() . ' %)' : '-'),

            TextField::new('type', 'Type de produit')
                ->onlyOnIndex()
                ->formatValue(fn ($value, $entity) => ucfirst($entity->getType())),

            FormField::addPanel('Illustrations'),
            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            FormField::addPanel('Métadonnées'),
            DateTimeField::new('createdAt', 'Créé le')->onlyOnDetail(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->onlyOnDetail(),
        ];
    }
}
