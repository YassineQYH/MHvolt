<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    ArrayField,
    ChoiceField,
    DateTimeField,
    FormField,
    IdField,
    MoneyField,
    NumberField,
    TextEditorField,
    TextField
};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

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
        $cancelOrder = Action::new('cancelOrder', 'Annuler', 'fas fa-ban')
            ->linkToCrudAction('cancelOrder')
            ->displayIf(fn(Order $order) => $order->getDeliveryState() < Order::STATE_DELIVERED);

        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')
            ->linkToCrudAction('updatePreparation')
            ->displayIf(fn(Order $order) => $order->getDeliveryState() === Order::STATE_WAITING);

        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            ->linkToCrudAction('updateDelivery')
            ->displayIf(fn(Order $order) => $order->getDeliveryState() === Order::STATE_PREPARATION);

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
            ->add(Crud::PAGE_DETAIL, $cancelOrder)
            ->add(Crud::PAGE_DETAIL, $updatePreparation)
            ->add(Crud::PAGE_DETAIL, $updateDelivery)
            ->add(Crud::PAGE_DETAIL, $internalLabelWeb)
            ->add(Crud::PAGE_DETAIL, $internalLabelPdf)
            ->add(Crud::PAGE_DETAIL, $bpostLabelWeb)
            ->add(Crud::PAGE_DETAIL, $bpostLabelPdf)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function internalLabelWeb(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $options = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'imageBase64' => true,
            'scale'       => 5,
        ]);
        $qrcode = new QRCode($options);
        $qrCodePath = $qrcode->render($order->getReference());

        return $this->render('admin/order/internal_label_web.html.twig', [
            'order' => $order,
            'qrCodePath' => $qrCodePath,
        ]);
    }

    public function bpostLabelWeb(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        if (!$order->getTrackingNumber()) {
            $order->setTrackingNumber('TEST-' . random_int(100000000, 999999999));
            $this->entityManager->flush();
        }

        $options = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'imageBase64' => true,
            'scale'       => 5,
        ]);
        $qrcode = new QRCode($options);
        $qrCodePath = $qrcode->render($order->getTrackingNumber());

        return $this->render('admin/order/bpost_label_web.html.twig', [
            'order' => $order,
            'qrCodePath' => $qrCodePath,
        ]);
    }

    public function generateInternalLabel(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $options = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'scale'       => 5,
            'imageBase64' => true,
        ]);
        $qrcode = new QRCode($options);
        $qrCodeDataUri = $qrcode->render($order->getReference());

        return $this->pdfService->generate(
            'admin/order/internal_label.html.twig',
            ['order' => $order, 'qrCodePath' => $qrCodeDataUri],
            'etiquette_interne_' . $order->getReference() . '.pdf',
            'attachment'
        );
    }

    public function generateBpostLabel(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        if (!$order->getTrackingNumber()) {
            $order->setTrackingNumber('TEST-' . random_int(100000000, 999999999));
            $this->entityManager->flush();
        }

        $options = new QROptions([
            'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'    => QRCode::ECC_L,
            'scale'       => 5,
            'imageBase64' => true,
        ]);
        $qrcode = new QRCode($options);
        $qrCodeDataUri = $qrcode->render($order->getTrackingNumber());

        return $this->pdfService->generate(
            'admin/order/bpost_label.html.twig',
            ['order' => $order, 'qrCodePath' => $qrCodeDataUri],
            'bordereau_bpost_' . $order->getReference() . '.pdf',
            'attachment'
        );
    }

    public function updatePreparation(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $this->updateOrderState($order, Order::STATE_PREPARATION, 'en cours de préparation');
        return $this->redirectToOrderDetail($order);
    }

    public function updateDelivery(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $this->updateOrderState($order, Order::STATE_SHIPPING, 'en cours de livraison');
        return $this->redirectToOrderDetail($order);
    }

private function updateOrderState(Order $order, int $state, string $message): void
    {
        $order->setDeliveryState($state);
        $this->entityManager->flush();

        $this->addFlash('notice', "Commande {$order->getReference()} $message");

        $user = $order->getUser();
        if ($user && $user->getEmail()) {
            $mail = new Mail();
            $content = "Bonjour " . htmlspecialchars($user->getFirstName() ?? 'Client') . ",<br>Votre commande n°<strong>{$order->getReference()}</strong> est $message.";
            $mail->send($user->getEmail(), $user->getFirstName() ?? 'Client', 'Suivi de commande', $content);
        }
    }

    public function cancelOrder(AdminContext $context): Response
    {
        $order = $this->getOrderFromContext($context);
        if (!$order) return $this->redirectToOrderIndex();

        $order->setDeliveryState(Order::STATE_CANCELED);

        // Remise en stock sécurisée
        foreach ($order->getOrderDetails() as $item) {
            // Supporte getProductEntity() ou getProduct() si relation directe
            $product = method_exists($item, 'getProductEntity') ? $item->getProductEntity() : null;
            if ($product) {
                $product->setStock($product->getStock() + $item->getQuantity());
            }
        }

        $this->entityManager->flush();

        $user = $order->getUser();
        if ($user && $user->getEmail()) {
            $mail = new Mail();
            $content = "Bonjour " . htmlspecialchars($user->getFirstName() ?? 'Client') . ",<br>Votre commande n°<strong>{$order->getReference()}</strong> a été annulée.";
            $mail->send($user->getEmail(), $user->getFirstName() ?? 'Client', 'Commande annulée', $content);
        }

        $this->addFlash('notice', "Commande {$order->getReference()} annulée et stock réajusté.");
        return $this->redirectToOrderDetail($order);
    }

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
            ->setEntityPermission('ROLE_ADMIN')
            ->setPageTitle(Crud::PAGE_INDEX, 'Commandes')
            ->setPageTitle(
                Crud::PAGE_DETAIL,
                fn (Order $order) => 'Commande #' . $order->getId() . ' — ' . $order->getReference()
            )
            ->showEntityActionsInlined(true)
            ->setFormOptions(['disabled' => true]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations générales')->collapsible(),
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user', 'Utilisateur')->onlyOnDetail(),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            ArrayField::new('orderDetails', 'Produits achetés')
                ->setTemplatePath('admin/fields/order_details.html.twig')
                ->onlyOnDetail(),

            FormField::addPanel('Promotion')->collapsible(),
            TextField::new('promoInfo', 'Promo')
                ->formatValue(fn($value, $entity) => $entity->getPromoCode() ?: ($entity->getPromoTitre() ?: '-')),
            MoneyField::new('promoReduction', 'Réduction promo')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => $value > 0 ? number_format((float)$value, 2, ',', ' ') . ' €' : '-')
                ->onlyOnDetail(),

            FormField::addPanel('Paiement & Livraison')->collapsible(),
            NumberField::new('weightTotal', 'Poids total')
                ->formatValue(fn($value) => number_format((float)$value, 2, ',', ' ') . ' kg'),

            MoneyField::new('carrierPrice', 'Frais de port')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => number_format((float)$value, 2, ',', ' ') . ' €'),

            MoneyField::new('subtotalTtc', 'Total articles TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => number_format((float)$value, 2, ',', ' ') . ' €'),

            MoneyField::new('totalTtc', 'Total commande TTC')
                ->setCurrency('EUR')
                ->setStoredAsCents(false)
                ->formatValue(fn($value) => number_format((float)$value, 2, ',', ' ') . ' €'),

            ChoiceField::new('paymentState', 'Paiement')
                ->setChoices([
                    'Non payée' => Order::PAYMENT_UNPAID,
                    'Payée'     => Order::PAYMENT_PAID,
                ])
                ->renderAsBadges([
                    Order::PAYMENT_UNPAID => 'danger',
                    Order::PAYMENT_PAID   => 'success',
                ]),

            ChoiceField::new('deliveryState', 'Traitement')
                ->setChoices(array_flip(Order::DELIVERY_STATES))
                ->renderAsBadges([
                    Order::STATE_WAITING     => 'secondary',
                    Order::STATE_PREPARATION => 'warning',
                    Order::STATE_SHIPPING    => 'info',
                    Order::STATE_DELIVERED   => 'success',
                    Order::STATE_CANCELED    => 'danger',
                ]),

            TextField::new('carrier', 'Transporteur')->onlyOnDetail(),
            TextField::new('trackingNumber', 'Numéro de suivi')->onlyOnDetail(),
        ];
    }
}
