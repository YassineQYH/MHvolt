<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, NumberField, MoneyField, AssociationField
};
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Détail de commande')
            ->setEntityLabelInPlural('Détails de commande')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            AssociationField::new('myOrder', 'Commande')
                ->setCrudController(OrderCrudController::class)
                ->setRequired(true),

            TextField::new('product', 'Produit'),

            NumberField::new('quantity', 'Quantité'),

            MoneyField::new('price', 'Prix unitaire')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            MoneyField::new('total', 'Total')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->onlyOnDetail(),

            TextField::new('weight', 'Taille'),
        ];
    }
}
