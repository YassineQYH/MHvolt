<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField,
    TextField,
    TextEditorField,
    NumberField,
    IntegerField,
    BooleanField,
    AssociationField,
    DateTimeField,
    CollectionField,
    FormField,
    ImageField,
};

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE) // interdit création, modification, suppression
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // ajoute l'action "Voir"
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action
                    ->setIcon('fa fa-eye')   // icône œil
                    ->setCssClass('btn btn-info'); // style bouton visible
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit')
            ->setEntityLabelInPlural('Produits')
            ->setDefaultSort(['id' => 'DESC'])
            ->showEntityActionsInlined(true); //
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            // ----------- IDENTIFIANT -----------
            IdField::new('id')->hideOnForm(),

            // ----------- TYPE DE PRODUIT -----------

            ImageField::new('firstIllustration', 'Image principale')
                ->onlyOnIndex()
                ->formatValue(function ($value, $entity) {
                    /** @var \App\Entity\Product $entity */
                    $illustration = $entity->getIllustrations()->first();
                    if (!$illustration) return null;

                    // Retourne le chemin correct selon le type
                    switch ($entity->getType()) {
                        case 'trottinette':
                            return '/uploads/trottinettes/' . $illustration->getImage();
                        case 'accessoire':
                            return '/uploads/accessoires/' . $illustration->getImage();
                        default:
                            return '/uploads/produits/' . $illustration->getImage();
                    }
                }),

            // ----------- INFO PRODUIT -----------
            FormField::addPanel('Informations générales'),

            TextField::new('name', 'Nom du produit'),
            TextField::new('slug')->hideOnIndex(),

            TextEditorField::new('description', 'Description'),

            // ----------- PRIX / STOCK -----------
            FormField::addPanel('Prix & Stock'),

            // Index : affichage avec €
            NumberField::new('price', 'Prix HT')
                ->setNumDecimals(2)
                ->formatValue(fn ($value) => number_format($value, 2, ',', ' ') . ' €')
                ->onlyOnIndex(),

            // Formulaire : édition propre
            NumberField::new('price', 'Prix HT')
                ->setNumDecimals(2)
                ->hideOnIndex(),

            IntegerField::new('stock', 'Stock'),

            BooleanField::new('isBest', 'Accueil'),

            // ----------- RELATIONS -----------
            FormField::addPanel('Données liées'),

            NumberField::new('weight', 'Poids (kg)')
                ->formatValue(function ($value) {
                    if (floor($value) == $value) {
                        return $value . 'kg';
                    }
                    return number_format($value, 2, ',', '') . 'kg';
                }),

            AssociationField::new('tva', 'TVA'),

            TextField::new('type', 'Type')
                ->onlyOnIndex() // optionnel : visible seulement dans la liste
                ->formatValue(function ($value, $entity) {
                    /** @var \App\Entity\Product $entity */
                    return ucfirst($entity->getType()); // Trottinette / Accessoire / Product
                }),

            // ----------- ILLUSTRATIONS : affichées mais non modifiées ici -----------
            FormField::addPanel('Illustrations'),

            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail() // visible seulement sur la page détail
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            // ----------- DATES -----------
            FormField::addPanel('Métadonnées'),

            DateTimeField::new('createdAt', 'Créé le')
                ->hideOnForm(),

            DateTimeField::new('updatedAt', 'Mis à jour le')
                ->hideOnForm(),
        ];
    }
}
