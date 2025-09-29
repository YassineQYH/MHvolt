<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField, BooleanField, ImageField, CollectionField
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
            TextField::new('name'),
            TextField::new('slug'),
            TextEditorField::new('description'),
            ImageField::new('image')
                ->setUploadDir('public/uploads/accessories')
                ->setBasePath('/uploads/accessories')
                ->setRequired(false),
            BooleanField::new('isBest'),

        ];
    }
}
