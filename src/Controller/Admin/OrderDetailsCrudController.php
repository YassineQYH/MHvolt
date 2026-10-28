<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    IntegerField,
    MoneyField,
    NumberField,
    TextField
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
            ->setEntityLabelInSingular('Ligne de commande')
            ->setEntityLabelInPlural('Lignes de commandes')
            ->showEntityActionsInlined(true);
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
            IntegerField::new('id', 'ID')->onlyOnIndex(),

            AssociationField::new('myOrder', 'Commande')
                ->setCrudController(OrderCrudController::class)
                ->formatValue(fn($value, $entity) =>$entity->getMyOrder()?->getReference() ?? '-'),

            TextField::new('product', 'Produit'),

            NumberField::new('weight', 'Poids')
                ->formatValue(fn($value) => $value !== null ? number_format((float)$value, 2, ',', ' ') . ' kg' : '-'),

            IntegerField::new('quantity', 'Quantité'),

            MoneyField::new('price', 'Prix unit. HT')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            MoneyField::new('priceAfterReduc', 'Prix HT remisé')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(function ($value, $entity) {$basePrice = (float)($entity->getPrice() ?? 0);$reducPrice = (float)($entity->getPriceAfterReduc() ?? $basePrice);
                    return abs($reducPrice -$basePrice) > 0.01
                        ? number_format($reducPrice, 2, ',', ' ') . ' €'
                        : '-';
                })
                ->onlyOnIndex(),

            NumberField::new('tva', 'TVA')
                ->formatValue(fn($value) => number_format((float)$value, 1, ',', ' ') . ' %'),

            MoneyField::new('priceTtc', 'Prix unit. TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            MoneyField::new('totalTtc', 'Total TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            TextField::new('promoInfo', 'Promo')
                ->formatValue(fn($value, $entity) =>$entity->getMyOrder()?->getPromoCode()
                    ?: ($entity->getMyOrder()?->getPromoTitre() ?? '-')
                ),
        ];
    }
}
