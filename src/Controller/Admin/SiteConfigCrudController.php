<?php

namespace App\Controller\Admin;

use App\Entity\SiteConfig;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SiteConfigCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SiteConfig::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            TextField::new('name', 'Clé de configuration')
                ->setDisabled(true),

            ChoiceField::new('value', 'Mode maintenance')
                ->setChoices([
                    'Désactivée (Site accessible)' => '0',
                    'Activée (Site en maintenance)' => '1',
                ])
                ->renderExpanded(true),
        ];
    }
}
