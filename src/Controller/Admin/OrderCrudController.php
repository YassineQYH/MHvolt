<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, ArrayField, MoneyField, ChoiceField, DateTimeField, TextEditorField, FormField, NumberField
};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

// Endroid QR Code
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevel;

class OrderCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator,
        private readonly PdfService $pdfService,
    ) {}

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')
            ->linkToCrudAction('updatePreparation')
            ->displayIf(fn($order) => $order->getDeliveryState() === 0);

        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            ->linkToCrudAction('updateDelivery')
            ->displayIf(fn($order) => $order->getDeliveryState() === 1);

        $internalLabelWeb = Action::new('internalLabelWeb', 'Voir Étiquette Web', 'fas fa-eye')
            ->linkToCrudAction('internalLabelWeb')
            ->setHtmlAttributes(['target' => '_blank']);

        $internalLabelPdf = Action::new('generateInternalLabel', 'Télécharger Étiquette', 'fas fa-file-pdf')
            ->linkToCrudAction('generateInternalLabel')
            ->setHtmlAttributes(['target' => '_blank']);

        $bpostLabelWeb = Action::new('bpostLabelWeb', 'Voir Bordereau Web', 'fas fa-eye')
            ->linkToCrudAction('bpostLabelWeb')
            ->setHtmlAttributes(['target' => '_blank']);

        $bpostLabelPdf = Action::new('generateBpostLabel', 'Télécharger Bordereau', 'fas fa-truck-fast')
            ->linkToCrudAction('generateBpostLabel')
            ->setHtmlAttributes(['target' => '_blank']);

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_DETAIL, $updatePreparation)
            ->add(Crud::PAGE_DETAIL, $updateDelivery)
            ->add(Crud::PAGE_DETAIL, $internalLabelWeb)
            ->add(Crud::PAGE_DETAIL, $internalLabelPdf)
            ->add(Crud::PAGE_DETAIL, $bpostLabelWeb)
            ->add(Crud::PAGE_DETAIL, $bpostLabelPdf)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    // 🔹 Rendu web
    public function internalLabelWeb(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        return $this->render('admin/order/internal_label_web.html.twig', [
            'order' => $order
        ]);
    }

    public function bpostLabelWeb(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        return $this->render('admin/order/bpost_label_web.html.twig', [
            'order' => $order
        ]);
    }

    // 🔹 Génération des PDF
    public function generateInternalLabel(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        // Génération d’un QR code interne basé sur la référence commande
        $qrCode = new QrCode($order->getReference());
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Sauvegarde temporaire du QR
        $tempPath = sys_get_temp_dir() . '/qr_internal_' . $order->getId() . '.png';
        $result->saveToFile($tempPath);

        return $this->pdfService->generate(
            'admin/order/internal_label.html.twig',
            [
                'order' => $order,
                'qrCodePath' => $tempPath
            ],
            'etiquette_interne_' . $order->getReference() . '.pdf',
            'attachment'
        );
    }

    public function generateBpostLabel(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        // Si aucun numéro de suivi, en générer un temporaire
        if (!$order->getTrackingNumber()) {
            $order->setTrackingNumber('TEST-' . random_int(100000000, 999999999));
            $this->entityManager->flush();
        }

        // Génération du QR code
        $qrCode = new QrCode($order->getTrackingNumber());
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Écriture du QR temporaire
        $tempPath = sys_get_temp_dir() . '/qr_' . $order->getId() . '.png';
        $result->saveToFile($tempPath);

        // Dans le PDF Twig
        return $this->pdfService->generate(
            'admin/order/bpost_label.html.twig',
            [
                'order' => $order,
                'qrCodePath' => $tempPath
            ],
            'bordereau_bpost_' . $order->getReference() . '.pdf',
            'attachment'
        );
    }

    // 🔹 Gestion d'état
    public function updatePreparation(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $this->updateOrderState($order, 1, 'en cours de préparation');
        return $this->redirectToOrderDetail($order);
    }

    public function updateDelivery(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $this->updateOrderState($order, 2, 'en cours de livraison');
        return $this->redirectToOrderDetail($order);
    }

    private function updateOrderState(Order $order, int $state, string $message): void
    {
        $order->setDeliveryState($state);
        $this->entityManager->flush();

        $this->addFlash('notice', "Commande {$order->getReference()} $message");

        $mail = new Mail();
        $content = "Bonjour {$order->getUser()->getFirstName()},<br>
        Votre commande n°<strong>{$order->getReference()}</strong> est $message.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstName(), 'Suivi de commande', $content);
    }

    // 🔹 Outils internes
    private function getOrderFromContext(AdminContext $context): ?Order
    {
        $orderId = $context->getRequest()->query->get('entityId');
        return $this->entityManager->getRepository(Order::class)->find($orderId);
    }

    private function redirectToOrderIndex(): Response
    {
        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl());
    }

    private function redirectToOrderDetail(Order $order): Response
    {
        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($order->getId())
            ->generateUrl());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['id' => 'DESC'])
            ->setEntityPermission('ROLE_ADMIN') // facultatif
            ->setPageTitle(Crud::PAGE_INDEX, 'Commandes')
            ->setPageTitle(
                Crud::PAGE_DETAIL,
                fn (Order $order) => 'Commande #' . $order->getId() . ' — ' . $order->getReference()
            )
            ->showEntityActionsInlined(true)
            ->setEntityPermission('ROLE_ADMIN')
            ->setFormOptions(['disabled' => true]); // bloque l’édition dans le form
    }

    public function configureFields(string $pageName): iterable
    {
        // ---------------------- Général ----------------------
        $general = [
            FormField::addPanel('Informations générales')->collapsible(),
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user', 'Utilisateur')->onlyOnDetail(),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            ArrayField::new('orderDetails', 'Produits achetés')
                ->setTemplatePath('admin/fields/order_details.html.twig')
                ->onlyOnDetail(),
        ];

        // ---------------------- Promo ----------------------
        $promo = [
            FormField::addPanel('Promotion')->collapsible(),

            TextField::new('promoInfo', 'Promo')
                ->onlyOnIndex()
                ->formatValue(fn($value, $entity) => $entity->getPromoCode() ?: ($entity->getPromoTitre() ?: '-')),

            TextField::new('promoCode', 'Code promo')->onlyOnDetail(),

            MoneyField::new('promoReduction', 'Réduction promo')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value > 0 ? $value . ' €' : '-')
                ->onlyOnDetail(),
        ];

        // ---------------------- Paiement & Livraison ----------------------
        $paymentDelivery = [
            FormField::addPanel('Paiement & Livraison')->collapsible(),

            TextField::new('promoInfo', 'Promo')
                ->onlyOnDetail()
                ->formatValue(fn($value, $entity) => $entity->getPromoCode() ?: ($entity->getPromoTitre() ?: '-')),

            // Pour le détail
            NumberField::new('weightTotal', 'Poids total')
                ->onlyOnDetail()
                ->formatValue(fn($value) => number_format($value, 2, ',', ' ') . ' kg'),

            // Pour la liste
            NumberField::new('weightTotal', 'Poids total')
                ->onlyOnIndex()
                ->formatValue(fn($value) => number_format($value, 2, ',', ' ') . ' kg'),


            MoneyField::new('carrierPrice', 'Frais de livraison')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value . ' €'),

            MoneyField::new('total', 'Total produit HT')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value . ' €'),

            MoneyField::new('totalAfterReduction', 'Total produit HT (promo)')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value, $entity) =>
                    ($entity->getPromoCode() || $entity->getPromoTitre()) ? $value . ' €' : '-'
                ),

            MoneyField::new('totalTtc', 'Total produit TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value . ' €'),

            MoneyField::new('totalTtcAfterReduction', 'Total produit TTC (promo)')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value, $entity) =>
                    ($entity->getPromoCode() || $entity->getPromoTitre()) ? $value . ' €' : '-'
                ),

            MoneyField::new('cartTotalTtc', 'Total panier TTC (avec livraison)')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value . ' €'),

            ChoiceField::new('paymentState', 'Paiement')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
            ])->renderAsBadges([
                0 => 'danger',
                1 => 'success',
            ]),

            ChoiceField::new('deliveryState', 'Traitement')->setChoices([
                'Commande en attente' => 0,
                'Préparation en cours' => 1,
                'Livraison en cours' => 2,
                'Livraison terminée' => 3,
            ])->renderAsBadges([
                0 => 'secondary',
                1 => 'warning',
                2 => 'info',
                3 => 'success',
            ]),

            TextField::new('carrier', 'Transporteur')->onlyOnDetail(),
            TextField::new('trackingNumber', 'Numéro de suivi')->onlyOnDetail(),
        ];

        // ---------------------- Transport secondaire ----------------------
        $secondaryTransport = [
            FormField::addPanel('Transport secondaire')->collapsible(),
            TextField::new('secondaryCarrier', 'Transporteur secondaire')->onlyOnDetail(),
            TextField::new('secondaryCarrierTrackingNumber', 'N° suivi secondaire')->onlyOnDetail(),
        ];

        // ---------------------- Fusion ----------------------
        return array_merge($general, $promo, $paymentDelivery, $secondaryTransport);
    }


}
