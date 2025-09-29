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
            IdField::new('id')->hideOnForm(),
            ImageField::new('image')
                ->setUploadDir('public/uploads/illustrations')
                ->setBasePath('/uploads/illustrations'),
            AssociationField::new('trottinette')
                ->setFormTypeOption('placeholder', 'Choisir une trottinette'), // ⚡ ici
        ];
    }
}
