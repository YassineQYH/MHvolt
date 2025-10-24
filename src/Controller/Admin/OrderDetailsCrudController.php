<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('myOrder', 'Commande')
                ->setCrudController(OrderCrudController::class)
                ->setSortable(true)
                ->formatValue(fn($value, $entity) => $entity->getMyOrder()?->getReference()),
            TextField::new('product', 'Produit'),
            TextField::new('weight', 'Poids'),
            IntegerField::new('quantity', 'Quantité'),
            MoneyField::new('price', 'Prix unitaire')->setCurrency('EUR'),

            // Champ total en lecture seule
            MoneyField::new('total', 'Total')
                ->setCurrency('EUR')
                ->onlyOnDetail(), // visible uniquement sur la page de détail
        ];
    }
}
