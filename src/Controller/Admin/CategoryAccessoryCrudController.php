<?php

namespace App\Controller\Admin;

use App\Entity\CategoryAccessory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextEditorField,
    ImageField,
    CollectionField
};

class CategoryAccessoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CategoryAccessory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Nom'),

            ImageField::new('illustration', 'Illustration')
                ->setUploadDir('public/uploads/categories')
                ->setBasePath('/uploads/categories')
                ->setRequired(false),

            TextEditorField::new('description', 'Description'),

            CollectionField::new('accessories', 'Accessoires associés')
                ->onlyOnDetail()
                ->formatValue(function ($value, $entity) {
                    // $value est la collection
                    return implode(', ', $value->map(fn($a) => $a->__toString())->toArray());
                }),
        ];
    }
}
