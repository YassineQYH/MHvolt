<?php

namespace App\Controller\Admin;

use App\Entity\TrottinetteAccessory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TrottinetteAccessoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrottinetteAccessory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('trottinette'),
            AssociationField::new('accessory'),
        ];
    }
}
