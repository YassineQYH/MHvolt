<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Entity\Illustrationaccess;
use App\Entity\TrottinetteAccessory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField, BooleanField, ImageField,
    NumberField, AssociationField, CollectionField
};

class AccessoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Accessory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Nom'),
            TextField::new('slug', 'Slug'),
            TextEditorField::new('description', 'Description'),

            NumberField::new('price', 'Prix (€)')->setNumDecimals(2),
            NumberField::new('stock', 'Stock'),

            ImageField::new('image', 'Image')
                ->setUploadDir('public/uploads/accessories')
                ->setBasePath('/uploads/accessories')
                ->setRequired(false),

            BooleanField::new('isBest', 'Meilleur'),

            // ---------------------- Relations ----------------------
            AssociationField::new('weight', 'Poids'),
            AssociationField::new('category', 'Catégorie'),

            CollectionField::new('trottinetteAccessories', 'Trottinettes associées')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(TrottinetteAccessory::class)
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
