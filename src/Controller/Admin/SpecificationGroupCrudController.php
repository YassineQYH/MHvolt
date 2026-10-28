<?php

namespace App\Controller\Admin;

use App\Entity\SpecificationGroup;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpecificationGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpecificationGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Groupe de spécifications')
            ->setEntityLabelInPlural('Groupes de spécifications')
            ->setDefaultSort(['position' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom du groupe (ex. Motorisation, Dimensions...)'),
            IntegerField::new('position', 'Ordre d’affichage')
                ->setHelp('Un chiffre plus petit s’affiche en premier'),
        ];
    }
}
