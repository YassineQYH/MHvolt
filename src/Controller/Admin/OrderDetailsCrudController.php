<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use App\Controller\Admin\ProductCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

public function configureFields(string $pageName): iterable
{
    return [

        // 🔗 Commande associée
        AssociationField::new('myOrder', 'Commande')
            ->setCrudController(OrderCrudController::class)
            ->setSortable(true)
            ->formatValue(fn($value, $entity) => $entity->getMyOrder()?->getReference()),

        // 📦 Nom du produit enregistré le jour de la commande (texte figé, non relié)
        TextField::new('product', 'Produit'),

        // 🎯 Produit réel (relation vers Product) → utile en back-office uniquement
        AssociationField::new('productEntity', 'Produit lié')
            ->setCrudController(ProductCrudController::class)
            ->hideOnIndex() // évite le doublon sur la vue liste
            ->hideOnDetail(), // garde la version texte sur la vue détail

        // ⚖️ Poids choisi
        TextField::new('weight', 'Poids'),

        // 🔢 Quantité
        IntegerField::new('quantity', 'Quantité'),

        // 💶 Prix unitaire HT
        MoneyField::new('price', 'Prix HT')
            ->setCurrency('EUR'),

        // 💶 TVA appliquée
        MoneyField::new('tva', 'TVA')
            ->setCurrency('EUR')
            ->onlyOnDetail(),

        // 💶 Prix TTC calculé
        MoneyField::new('priceTTC', 'Prix TTC')
            ->setCurrency('EUR')
            ->onlyOnDetail(),

        // 🧮 Total TTC
        MoneyField::new('total', 'Total TTC')
            ->setCurrency('EUR')
            ->onlyOnDetail(),
    ];
}

}
