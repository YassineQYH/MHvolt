<?php

namespace App\Controller\Admin;

use App\Entity\Weight;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, NumberField
};

class WeightCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Weight::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Poids')
            ->setEntityLabelInPlural('Poids')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPageTitle('index', 'Gestion des poids');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name', 'Nom'),
            NumberField::new('value', 'Valeur (kg)')
                ->setNumDecimals(2)
                ->setHelp('Saisissez la valeur en kilogrammes'),
        ];
    }
}
