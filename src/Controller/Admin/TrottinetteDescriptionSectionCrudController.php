<?php

namespace App\Controller\Admin;

use App\Entity\TrottinetteDescriptionSection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, TextEditorField, AssociationField, IntegerField};

class TrottinetteDescriptionSectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrottinetteDescriptionSection::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('trottinette'),
            TextField::new('title'),
            TextEditorField::new('content'),
            IntegerField::new('sectionOrder', 'Ordre')
                ->setHelp('Détermine l’ordre d’affichage des sections')
                ->setSortable(true),
        ];
    }
}
