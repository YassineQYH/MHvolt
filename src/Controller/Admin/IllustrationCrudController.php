<?php

namespace App\Controller\Admin;

use App\Entity\Illustration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, ImageField, AssociationField};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class IllustrationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Illustration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            AssociationField::new('product', 'Produit associé')
                ->setFormTypeOption('placeholder', 'Sélectionner un produit'),

            ImageField::new('image', 'Illustration')
                ->setUploadDir('public/uploads/tmp')   // dossier pour uploader la nouvelle image
                ->setBasePath('')                        // EasyAdmin va utiliser getImagePath() pour l'affichage
                ->formatValue(fn($value, $entity) => $entity ? $entity->getImagePath() : null),
        ];
    }

    public function persistEntity(EntityManagerInterface $em, $entityInstance): void
    {
        parent::persistEntity($em, $entityInstance);
        $this->moveImageIfUploaded($entityInstance);
    }

    public function updateEntity(EntityManagerInterface $em, $entityInstance): void
    {
        parent::updateEntity($em, $entityInstance);
        $this->moveImageIfUploaded($entityInstance);
    }

    private function moveImageIfUploaded(Illustration $illustration): void
    {
        $image = $illustration->getImage();
        $product = $illustration->getProduct();

        if (!$image || !$product) {
            return;
        }

        $publicDir = $this->getParameter('kernel.project_dir') . '/public';
        $source = $publicDir . '/uploads/tmp/' . $image;

        // seulement si c'est un fichier UploadedFile (nouveau upload)
        if (!file_exists($source)) {
            return;
        }

        switch ($product->getType()) {
            case 'trottinette':
                $targetDir = $publicDir . '/uploads/trottinettes/';
                break;
            case 'accessoire':
                $targetDir = $publicDir . '/uploads/accessoires/';
                break;
            default:
                $targetDir = $publicDir . '/uploads/produits/';
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        rename($source, $targetDir . $image);
    }
}
