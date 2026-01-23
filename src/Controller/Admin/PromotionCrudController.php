<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, ChoiceField, DateTimeField, IntegerField, AssociationField, CollectionField, MoneyField, NumberField, BooleanField, FormField
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
            ->setFormThemes(['admin/promotion_form.html.twig']); // JS pour cacher/afficher les champs
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

            FormField::addPanel('Réduction'),

            MoneyField::new('discountAmount', 'Montant')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            NumberField::new('discountPercent', 'Pourcentage')
                ->setNumDecimals(0)
                ->setHelp('Entrez la valeur en % (ex : 25 pour 25%)')
                ->formatValue(fn($value) => $value . ' %'),

            DateTimeField::new('startDate', 'Début'),
            DateTimeField::new('endDate', 'Fin'),
            IntegerField::new('quantity', 'Quantité'),
            IntegerField::new('used', 'Utilisé')->onlyOnIndex(),

            FormField::addPanel('Cibles'),

            AssociationField::new('categoryAccess', 'Catégorie')->hideOnIndex(),
            AssociationField::new('product', 'Produit')->hideOnIndex(),
            AssociationField::new('products', 'Liste produits')->hideOnIndex(),
        ];
    }
}
