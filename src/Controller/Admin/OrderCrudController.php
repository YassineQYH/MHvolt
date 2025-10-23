<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    IdField, TextField, ArrayField, MoneyField, ChoiceField, DateTimeField, TextEditorField
};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')
            ->linkToCrudAction('updatePreparation')
            ->displayIf(static fn(Order $order) => $order->getDeliveryState() === 0);

        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            ->linkToCrudAction('updateDelivery')
            ->displayIf(static fn(Order $order) => $order->getDeliveryState() === 1 && $order->getPaymentState() === 1);

        return $actions
            ->add(Crud::PAGE_DETAIL, $updatePreparation)
            ->add(Crud::PAGE_DETAIL, $updateDelivery)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    private function handleDeliveryState(Order $order, int $state, string $message)
    {
        $order->setDeliveryState($state);
        $this->entityManager->flush();

        $this->addFlash('notice', $message);

        // Envoi du mail
        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getFirstName() . "<br>Hich'Trott vous informe que votre commande n°<strong>" . $order->getReference() . "</strong> est " . $message;
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstName(),
            "Votre commande " . $order->getReference(),
            $content
        );
    }

    public function updatePreparation(AdminContext $context)
    {
        $orderId = $context->getRequest()->query->get('entityId');
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirect($this->adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $this->handleDeliveryState($order, 1, '<u>en cours de préparation</u>');

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($order->getId())
            ->generateUrl());
    }

    public function updateDelivery(AdminContext $context)
    {
        $orderId = $context->getRequest()->query->get('entityId');
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        if (!$order) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirect($this->adminUrlGenerator->setController(self::class)->setAction('index')->generateUrl());
        }

        $this->handleDeliveryState($order, 2, '<u>en cours de livraison</u>');

        return $this->redirect($this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('detail')
            ->setEntityId($order->getId())
            ->generateUrl());
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Passée le'),

            // Affiche le user mais ne le rend pas éditable
            TextField::new('user', 'Utilisateur')
                ->onlyOnDetail()
                ->formatValue(fn($value, $entity) => (string) $entity->getUser()),

            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR')->setStoredAsCents(false),
            MoneyField::new('carrierPrice', 'Frais de livraison')->setCurrency('EUR')->setStoredAsCents(false),

            // Paiement séparé
            ChoiceField::new('paymentState', 'Paiement')
                ->setChoices([
                    'Non payée' => 0,
                    'Payée' => 1,
                ])
                ->renderAsBadges([
                    0 => 'danger',
                    1 => 'success',
                ]),

            // Livraison / traitement
            ChoiceField::new('deliveryState', 'Traitement')
                ->setChoices([
                    'Commande en attente' => 0,
                    'Préparation en cours' => 1,
                    'Livraison en cours' => 2,
                ])
                ->renderAsBadges([
                    0 => 'secondary',
                    1 => 'warning',
                    2 => 'info',
                ]),

            ArrayField::new('orderDetails', 'Produits achetés')
                ->setTemplatePath('admin/fields/order_details.html.twig')
                ->onlyOnDetail(),
        ];
    }
}
