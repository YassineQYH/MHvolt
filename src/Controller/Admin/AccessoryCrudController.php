<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Entity\Product;
use App\Entity\Tva;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    AssociationField,
    BooleanField,
    CollectionField,
    IdField,
    ImageField,
    MoneyField,
    NumberField,
    TextEditorField,
    TextField
};
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Product) {
            // 1. TVA standard par défaut si non renseignée
            if ($entityInstance->getTva() === null) {
                $defaultTva = $entityManager->getRepository(Tva::class)->findOneBy([])
                    ?? (new Tva())->setName('Taux standard')->setValue(21.0);
                $entityInstance->setTva($defaultTva);
            }

            // 2. Traitement des photos avant enregistrement en base
            if ($entityInstance instanceof Accessory) {
                $this->handleIllustrationsUpload($entityInstance);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Accessory) {
            $this->handleIllustrationsUpload($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handleIllustrationsUpload(Accessory $accessory): void
    {
        $request = $this->getContext()?->getRequest();
        if (!$request) {
            return;
        }

        $files = $request->files->all();
        $formKey = 'Accessory';

        if (!isset($files[$formKey]['illustrations'])) {
            return;
        }

        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadDir = $projectDir . '/public/uploads/accessoires/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($accessory->getIllustrations() as $index => $illustration) {
            if (!isset($files[$formKey]['illustrations'][$index]['image'])) {
                continue;
            }

            $file = $files[$formKey]['illustrations'][$index]['image'];

            if ($file instanceof UploadedFile) {
                $filename = uniqid('acc_', true) . '.' . $file->guessExtension();
                $file->move($uploadDir, $filename);

                $illustration->setImage($filename);
                $illustration->setProduct($accessory);
            }
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            ImageField::new('firstIllustration', 'Image')
                ->onlyOnIndex()
                ->setBasePath('/'),

            TextField::new('name', 'Nom'),
            TextField::new('slug')->setFormTypeOption('disabled', true)->hideOnIndex(),

            TextEditorField::new('description', 'Description')
                ->hideOnIndex(),

            MoneyField::new('price', 'Prix de vente TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setHelp('Prix public TTC affiché sur la boutique.'),

            MoneyField::new('promoPrice', 'Prix promo TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setRequired(false)
                ->setHelp('Laisser vide si aucune promotion directe.'),

            // TVA masquée de l'index pour alléger le tableau, visible uniquement au détail
            AssociationField::new('tva', 'TVA')
                ->onlyOnDetail()
                ->formatValue(fn ($value) => $value ? $value->getName() . ' (' . $value->getValue() . ' %)' : '-'),

            NumberField::new('weight', 'Poids (kg)')
                ->setNumDecimals(2)
                ->setHelp('Entrez le poids en kg')
                ->formatValue(fn($value) => $value !== null ? number_format($value, 2, ',', ' ') . ' kg' : '-'),

            NumberField::new('stock', 'Stock'),

            BooleanField::new('isBest', 'En avant sur l’accueil'),

            AssociationField::new('category', 'Catégorie'),

            // Galerie d'illustrations
            CollectionField::new('illustrations', 'Illustrations')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\IllustrationType::class)
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),

            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            // Association avec Trottinette : modifiable sur formulaire et lisible au détail
            AssociationField::new('trottinettes', 'Trottinettes compatibles')
                ->onlyOnForms()
                ->setFormTypeOption('by_reference', false)
                ->setHelp('Sélectionnez les modèles de trottinettes compatibles avec cet accessoire.'),

            AssociationField::new('trottinettes', 'Trottinettes compatibles')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul class="mb-0">';
                    foreach ($entity->getTrottinettes() as $trott) {
                        $html .= '<li>' . htmlspecialchars($trott->getName()) . '</li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),
        ];
    }
}
