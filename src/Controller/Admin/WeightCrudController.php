<?php

namespace App\Controller\Admin;

use App\Entity\Weight;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;

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
            NumberField::new('kg', 'Poids (kg)')
                ->setNumDecimals(2)
                ->setHelp('Saisissez la valeur en kilogrammes'),
            NumberField::new('price', 'Prix (€)')
                ->setNumDecimals(2)
                ->setHelp('Saisissez le prix associé au poids'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(NumericFilter::new('kg', 'Poids (kg)'))
            ->add(NumericFilter::new('price', 'Prix (€)'));
    }
}
