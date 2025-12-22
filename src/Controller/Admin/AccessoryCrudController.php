<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Entity\Illustrationaccess;
use App\Entity\TrottinetteAccessory;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Actions, Action};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField, BooleanField, ImageField,
    NumberField, AssociationField, CollectionField, DateTimeField
};

class AccessoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Accessory::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Accessoire')
            ->setEntityLabelInPlural('Accessoires')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            ImageField::new('firstIllustration', 'Image')
                ->setBasePath('/uploads/accessoires')
                ->onlyOnIndex(),

            TextField::new('name', 'Nom'),
            TextField::new('slug')->setFormTypeOption('disabled', true)->hideOnIndex(),
            TextEditorField::new('description', 'Description'),

            NumberField::new('price', 'Prix (€)')->setNumDecimals(2),
            AssociationField::new('tva', 'TVA'),

            AssociationField::new('weight', 'Poids'),
            NumberField::new('stock', 'Stock'),

            BooleanField::new('isBest', 'Accueil'),

            // ---------------------- Relations ----------------------

            AssociationField::new('category', 'Catégorie'),

            CollectionField::new('illustrations', 'Illustrations')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\IllustrationType::class)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(), // visible uniquement dans le formulaire

            AssociationField::new('trottinetteAccessories', 'Trottinettes associées')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul>';
                    foreach ($entity->getTrottinetteAccessories() as $ta) {
                        $html .= '<li>' . $ta->getTrottinette()?->getName() . '</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->renderAsHtml(),


            // ======================
            // DATE (facultatif)
            // ======================
            /* DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(), */
        ];
    }
}
