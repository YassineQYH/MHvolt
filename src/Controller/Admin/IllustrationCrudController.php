<?php

namespace App\Controller\Admin;

use App\Entity\Illustration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, ImageField, AssociationField};

class IllustrationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Illustration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            // ID (caché en création/édition)
            IdField::new('id')->hideOnForm(),

            // Champ image
            ImageField::new('image', 'Illustration')
                ->setUploadDir('public/uploads/illustrations')
                ->setBasePath('/uploads/illustrations'),

            // Association product OU accessory selon ton entité Illustration
            AssociationField::new('product', 'Produit associé')
                ->setFormTypeOption('placeholder', 'Sélectionner un produit'),
        ];
    }


}
