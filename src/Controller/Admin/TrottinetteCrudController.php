<?php

namespace App\Controller\Admin;

use App\Entity\Trottinette;
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

class TrottinetteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trottinette::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Trottinette')
            ->setEntityLabelInPlural('Trottinettes')
            ->setDefaultSort(['id' => 'DESC']);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Trottinette) {
            // 1. Assigner la TVA standard par défaut si non définie
            if ($entityInstance->getTva() === null) {
                $defaultTva = $entityManager->getRepository(\App\Entity\Tva::class)->findOneBy([])
                    ?? (new \App\Entity\Tva())->setName('Taux standard')->setValue(21.0);
                $entityInstance->setTva($defaultTva);
            }

            // 2. Traiter l'upload des images avant l'insertion en BDD
            $this->handleIllustrationsUpload($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Trottinette) {
            $this->handleIllustrationsUpload($entityInstance);
        }

        parent::updateEntity($entityManager, $entityInstance);
    }

    private function handleIllustrationsUpload(Trottinette $trottinette): void
    {
        $request = $this->getContext()?->getRequest();
        if (!$request) {
            return;
        }

        $files = $request->files->all();

        // Le formulaire EasyAdmin nomme le tableau de requête d'après l'entité
        $formKey = 'Trottinette';
        if (!isset($files[$formKey]['illustrations'])) {
            return;
        }

        $projectDir = $this->getParameter('kernel.project_dir');
        $uploadDir = $projectDir . '/public/uploads/trottinettes/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($trottinette->getIllustrations() as $index => $illustration) {
            // Si l'illustration a déjà un nom d'image en base et aucun nouveau fichier, on passe
            if (!isset($files[$formKey]['illustrations'][$index]['image'])) {
                continue;
            }

            $file = $files[$formKey]['illustrations'][$index]['image'];

            if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
                $filename = uniqid('trott_', true) . '.' . $file->guessExtension();
                $file->move($uploadDir, $filename);

                $illustration->setImage($filename);
                $illustration->setProduct($trottinette);
            }
        }
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            ImageField::new('firstIllustration', 'Image')
                ->onlyOnIndex()
                ->setBasePath(''),

            TextField::new('name', 'Nom'),
            TextField::new('nameShort', 'Nom court')->hideOnIndex(),
            TextField::new('slug')->setFormTypeOption('disabled', true)->hideOnIndex(),

            TextEditorField::new('description', 'Description'),
            TextEditorField::new('descriptionShort', 'Courte desc.')->hideOnIndex(),

            MoneyField::new('price', 'Prix de vente TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setHelp('Prix public TTC affiché sur la boutique.'),

            MoneyField::new('promoPrice', 'Prix promo TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->setRequired(false)
                ->setHelp('Laisser vide si aucune promotion directe.'),

            //AssociationField::new('tva', 'TVA')
                //->hideOnForm()
                //->formatValue(fn ($value) => $value ? $value->getName() . ' (' . $value->getValue() . ' %)' : '-'),

            NumberField::new('weight', 'Poids (kg)')
                ->setNumDecimals(2)
                ->setHelp('Entrez le poids en kg')
                ->formatValue(fn ($value) => $value !== null ? number_format((float)$value, 2, ',', ' ') . ' kg' : '-'),

            NumberField::new('stock', 'Stock'),
            BooleanField::new('isBest', 'En avant sur l’accueil'),

            // ---------------- Relations & Formulaires imbriqués ----------------

            // Galerie d'images
            CollectionField::new('illustrations', 'Illustrations')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\IllustrationType::class)
                ->setFormTypeOption('by_reference', false),

            CollectionField::new('illustrations', 'Galerie photos')
                ->onlyOnDetail()
                ->setTemplatePath('admin/fields/illustrations.html.twig'),

            // Sections descriptives
            CollectionField::new('descriptionSections', 'Sections de présentation')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteDescriptionSectionType::class)
                ->setFormTypeOption('by_reference', false),

            // 🔥 Relation ManyToMany DIRECTE avec les accessoires
            AssociationField::new('accessories', 'Accessoires compatibles')
                ->onlyOnForms()
                ->setFormTypeOption('by_reference', false),

            AssociationField::new('accessories', 'Accessoires compatibles')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {$html = '<ul class="mb-0">';
                    foreach ($entity->getAccessories() as $acc) {$html .= '<li>' . htmlspecialchars($acc->getName()) . ' (' . number_format($acc->getPrice(), 2, ',', ' ') . ' € HT)</li>';
                    }
                    $html .= '</ul>';
                    return $html;
                })
                ->renderAsHtml(),

                // Spécifications techniques
            CollectionField::new('specifications', 'Spécifications techniques')
                ->onlyOnForms()
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(\App\Form\TrottinetteSpecificationType::class)
                ->setFormTypeOption('by_reference', false),

            AssociationField::new('specifications', 'Spécifications techniques')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {
                    $html = '<ul class="mb-0">';
                    foreach ($entity->getSpecifications() as $spec) {
                        $html .= '<li><strong>' . htmlspecialchars($spec->getName()) . '</strong> : ' . htmlspecialchars($spec->getValue()) . '</li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),

            AssociationField::new('descriptionSections', 'Sections')
                ->onlyOnDetail()
                ->formatValue(function ($v, $entity) {$html = '<ul>';
                    foreach ($entity->getDescriptionSections() as $section) {$html .= '<li><strong>' . htmlspecialchars($section->getTitle()) . '</strong><br>' . nl2br(htmlspecialchars($section->getContent())) . '</li>';
                    }
                    return $html . '</ul>';
                })
                ->renderAsHtml(),
        ];
    }
}
