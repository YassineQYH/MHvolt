<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, ChoiceField, DateTimeField, IntegerField, AssociationField, CollectionField, MoneyField, NumberField, BooleanField
};

class PromotionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Promotion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['admin/promotion_form.html.twig']); // template twig pour injecter le JS
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre de la promotion')
                ->setHelp('Permet d’identifier facilement la promo et éventuellement l’afficher sur le site'),
            BooleanField::new('autoApply', 'Appliquer automatiquement ?'),
            TextField::new('code', 'Code promo')
                ->setRequired(false),
            ChoiceField::new('targetType', 'Type de cible')
                ->setChoices([
                    'Tout le site' => Promotion::TARGET_ALL,
                    'Catégorie accessoire' => Promotion::TARGET_CATEGORY_ACCESS,
                    'Produit' => Promotion::TARGET_PRODUCT,
                    'Liste de produits' => Promotion::TARGET_PRODUCT_LIST,
                ]),
            MoneyField::new('discountAmount', 'Montant')
                ->setCurrency('EUR')
                ->setStoredAsCents(false), // stocke directement 5.0 en BDD au lieu de 500
            NumberField::new('discountPercent', 'Pourcentage')
                ->setNumDecimals(0)
                ->setHelp('Entrez la valeur en % (ex : 25 pour 25%)')
                ->formatValue(function ($value) {
                    return $value . ' %';
                }),
            DateTimeField::new('startDate', 'Début'),
            DateTimeField::new('endDate', 'Fin'),
            IntegerField::new('quantity', 'Quantité'),
            IntegerField::new('used', 'Utilisé')->onlyOnIndex(),

            AssociationField::new('categoryAccess', 'Catégorie')->hideOnIndex(),
            AssociationField::new('product', 'Produit')->hideOnIndex(),
            AssociationField::new('products', 'Liste produits')->hideOnIndex(),
        ];
    }
}
