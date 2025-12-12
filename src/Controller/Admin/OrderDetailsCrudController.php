<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use App\Controller\Admin\ProductCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IntegerField,
    TextField,
    AssociationField,
    NumberField
};

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityLabelInSingular('Order Detail')
            ->setEntityLabelInPlural('Order Details')
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::EDIT, Action::DELETE) // pas d'édition ni suppression
            ->add(Crud::PAGE_INDEX, Action::DETAIL); // juste "Afficher"
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // 🆔 ID
            IntegerField::new('id', 'ID')
                ->onlyOnIndex(),

            // 🔗 Commande
            AssociationField::new('myOrder', 'Commande')
                ->setCrudController(OrderCrudController::class)
                ->setSortable(true)
                ->formatValue(fn($value, $entity) => $entity->getMyOrder()?->getReference()),

            // 📦 Nom du produit
            TextField::new('product', 'Produit'),

            // 🎯 Produit réel lié — pas affiché
            AssociationField::new('productEntity', 'Produit lié')
                ->setCrudController(ProductCrudController::class)
                ->hideOnIndex()
                ->hideOnDetail(),

            // ⚖️ Poids
            TextField::new('weight', 'Poids')
                ->formatValue(fn($value) => $value . ' kg'),

            // 🔢 Quantité
            IntegerField::new('quantity', 'Quantité'),

            // 💶 Prix HT
            IntegerField::new('price', 'Prix HT')
                ->formatValue(fn($value) => $value . ' €'),

            // 💶 Prix HT après réduction — affiché dans liste si différent
            IntegerField::new('priceAfterReduc', 'Prix HT après promo')
                ->formatValue(fn($value, $entity) =>
                    $entity->getPriceAfterReduc() !== $entity->getPrice() ? $value . ' €' : '-'
                )
                ->onlyOnIndex(),

            // 💶 TVA (%)
            NumberField::new('tva', 'TVA')
                ->formatValue(fn($value) => $value . ' %')
                ->onlyOnIndex(),

            // 💶 Prix TTC
            IntegerField::new('priceTTC', 'Prix TTC')
                ->formatValue(fn($value) => $value . ' €')
                ->onlyOnIndex(),

            // 💶 Prix TTC après réduction
            IntegerField::new('priceTTCAfterReduc', 'Prix TTC après promo')
                ->formatValue(fn($value, $entity) =>
                    $entity->getPriceTTCAfterReduc() !== $entity->getPriceTTC()
                        ? number_format($entity->getPriceTTCAfterReduc(), 2, ',', ' ') . ' €'
                        : '-'
                )
                ->onlyOnIndex(),

            // 🏷️ Promo
            TextField::new('promoInfo', 'Promo')
                ->formatValue(fn($value, $entity) => $entity->getMyOrder()?->getPromoInfo() ?: '-')
                ->onlyOnIndex(),

            // Champs pour le détail uniquement
            IntegerField::new('priceAfterReduc', 'Prix HT après réduc')
                ->formatValue(fn($value, $entity) => $value !== $entity->getPrice() ? $value . ' €' : '')
                ->onlyOnDetail(),

            NumberField::new('tva', 'TVA')
                ->formatValue(fn($value) => $value . ' %')
                ->onlyOnDetail(),

            IntegerField::new('priceTTC', 'Prix TTC')
                ->formatValue(fn($value) => $value . ' €')
                ->onlyOnDetail(),

            IntegerField::new('priceTTCAfterReduc', 'Prix TTC après réduc')
                ->formatValue(fn($value, $entity) =>
                    $value !== $entity->getPriceTTC()
                        ? number_format($value, 2, ',', ' ') . ' €'
                        : ''
                )
                ->onlyOnDetail(),

            IntegerField::new('total', 'Total TTC')
                ->formatValue(fn($value, $entity) =>
                    number_format($entity->getPriceTTC() * $entity->getQuantity(), 2, ',', ' ') . ' €'
                )
                ->onlyOnDetail(),

            IntegerField::new('totalAfterReduc', 'Total TTC après réduc')
                ->formatValue(fn($value, $entity) =>
                    $entity->getPriceTTCAfterReduc() !== null && $entity->getPriceTTCAfterReduc() != $entity->getPriceTTC()
                        ? number_format($entity->getPriceTTCAfterReduc() * $entity->getQuantity(), 2, ',', ' ') . ' €'
                        : ''
                )
                ->onlyOnDetail(),

            TextField::new('promoInfo', 'Promo')
                ->formatValue(fn($value, $entity) => $entity->getMyOrder()?->getPromoInfo() ?: '-')
                ->onlyOnDetail(),
        ];
    }
}
