<?php

namespace App\Controller\Admin;

use App\Entity\TrottinetteDescriptionSection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, TextEditorField, AssociationField, IntegerField};
use Doctrine\ORM\EntityManagerInterface;

class TrottinetteDescriptionSectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrottinetteDescriptionSection::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular("Description et section de trottinette")
            ->setEntityLabelInPlural('Descriptions et sections des trottinettes');
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

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof TrottinetteDescriptionSection) {
            $trottinette = $entityInstance->getTrottinette();
            if ($trottinette) {
                $order = $entityInstance->getSectionOrder() ?? count($trottinette->getDescriptionSections()) + 1;
                $trottinette->insertSectionAtOrder($entityInstance, $order);
            }
        }

        parent::persistEntity($em, $entityInstance);
    }

}
