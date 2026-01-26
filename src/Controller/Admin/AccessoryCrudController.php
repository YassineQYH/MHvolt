<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Entity\Illustrationaccess;
use App\Entity\TrottinetteAccessory;
use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Crud, Actions, Action};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, TextEditorField, BooleanField, ImageField,
    NumberField, AssociationField, CollectionField, DateTimeField
};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AccessoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Accessory::class;
    }

        public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof \App\Entity\Accessory) {
            foreach ($entityInstance->getIllustrations() as $illustration) {
                $illustration->setProduct($entityInstance); // <--- IMPORTANT
            }
            $this->handleIllustrationUploads($entityInstance->getIllustrations());
        }

        parent::persistEntity($em, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        if ($entityInstance instanceof \App\Entity\Accessory) {
            foreach ($entityInstance->getIllustrations() as $illustration) {
                $illustration->setProduct($entityInstance); // <--- IMPORTANT
            }
            $this->handleIllustrationUploads($entityInstance->getIllustrations());
        }

        parent::updateEntity($em, $entityInstance);
    }
    private function handleIllustrationUploads(Collection $illustrations)
    {
        $projectDir = $this->getParameter('kernel.project_dir');

        foreach ($illustrations as $illustration) {
            $file = $illustration->getUploadedFile();
            if (!$file instanceof UploadedFile) continue;

            $uploadDir = match($illustration->getProduct()->getType()) {
                'trottinette' => $projectDir . '/public/uploads/trottinettes/',
                'accessoire' => $projectDir . '/public/uploads/accessoires/',
                default => $projectDir . '/public/uploads/produits/',
            };

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $filename = uniqid() . '.' . $file->guessExtension();
            $file->move($uploadDir, $filename);

            $illustration->setImage($filename);
            $illustration->setUploadedFile(null);
        }
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
            CollectionField::new('illustrations', 'Images')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            IdField::new('id')->hideOnForm(),

            ImageField::new('firstIllustration', 'Image')
                ->onlyOnIndex()
                ->setBasePath(''),

            TextField::new('name', 'Nom'),
            TextField::new('slug')->setFormTypeOption('disabled', true)->hideOnIndex(),

            TextEditorField::new('description', 'Description'),

            NumberField::new('price', 'Prix (€)')->setNumDecimals(2),
            AssociationField::new('tva', 'TVA')
                ->formatValue(function ($value, $entity) {
                    if (!$value) return '';
                    return $value->getName() . ' - ' . $value->getValue() . ' %';
                }),

            NumberField::new('weight', 'Poids (kg)')
                ->setHelp('Entrez le poids exact du produit')
                ->formatValue(function ($value, $entity) {
                    // On vérifie si c'est un entier
                    if (floor($value) == $value) {
                        return $value . 'kg';
                    }
                    // Sinon on affiche avec 2 décimales
                    return number_format($value, 2, ',', '') . 'kg';
                }),


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
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Accessory) {
            parent::persistEntity($entityManager, $entityInstance);
            return;
        }

        $this->handleIllustrationsUpload($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Accessory) {
            parent::updateEntity($entityManager, $entityInstance);
            return;
        }

        $this->handleIllustrationsUpload($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }
    
    private function handleIllustrationsUpload(Accessory $accessory): void
    {
        $request = $this->getContext()->getRequest();

        if (!$request) {
            return;
        }

        $files = $request->files->all();

        // Nom du formulaire EasyAdmin (important)
        if (!isset($files['Accessory']['illustrations'])) {
            return;
        }

        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadDir = $projectDir . '/public/uploads/accessoires/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($accessory->getIllustrations() as $index => $illustration) {

            if (
                !isset($files['Accessory']['illustrations'][$index]['image']) ||
                !$files['Accessory']['illustrations'][$index]['image'] instanceof UploadedFile
            ) {
                continue;
            }

            /** @var UploadedFile $file */
            $file = $files['Accessory']['illustrations'][$index]['image'];

            // Nom unique
            $filename = uniqid('acc_', true) . '.' . $file->guessExtension();

            // Déplacement physique
            $file->move($uploadDir, $filename);

            // 🔥 AVANT le flush Doctrine
            $illustration->setImage($filename);
            $illustration->setProduct($accessory);
        }
    }

}
