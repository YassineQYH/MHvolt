<?php

namespace App\Controller\Admin;

use App\Entity\Trottinette;
use App\Entity\TrottinetteCaracteristique;
use App\Entity\TrottinetteDescriptionSection;
use App\Form\TrottinetteCaracteristiqueType;
use App\Form\TrottinetteDescriptionSectionType;
use App\Form\TrottinetteAccessoryType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField, BooleanField, ImageField, CollectionField, AssociationField
};

class TrottinetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trottinette::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name'),
            TextField::new('nameShort'),
            TextField::new('slug'),

            TextEditorField::new('description'),
            TextEditorField::new('descriptionShort'),

            ImageField::new('image')
                ->setUploadDir('public/uploads/trottinettes')
                ->setBasePath('/uploads/trottinettes')
                ->setRequired(false),

            BooleanField::new('isBest'),
            BooleanField::new('isHeader'),

            ImageField::new('headerImage')
                ->setUploadDir('public/uploads/trottinettes')
                ->setBasePath('/uploads/trottinettes')
                ->setRequired(false),

            TextField::new('headerBtnTitle'),
            TextField::new('headerBtnUrl'),

            // ----------------------
            // Relations
            // ----------------------

            // Caractéristiques pivot
            CollectionField::new('trottinetteCaracteristiques')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(TrottinetteCaracteristiqueType::class)
                ->setFormTypeOption('by_reference', false),

            // Sections description
            CollectionField::new('descriptionSections')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(TrottinetteDescriptionSectionType::class)
                ->setFormTypeOption('by_reference', false),

            // Accessoires via pivot
            CollectionField::new('trottinetteAccessories')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(TrottinetteAccessoryType::class)
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
