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
    IdField, TextField, MoneyField, ChoiceField, DateTimeField, TextEditorField, CollectionField
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

    // ------------------- Actions -------------------
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')
            ->linkToCrudAction('updatePreparation');

        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')
            ->linkToCrudAction('updateDelivery');

        return $actions
            ->add(Crud::PAGE_DETAIL, $updatePreparation)
            ->add(Crud::PAGE_DETAIL, $updateDelivery)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:green;'><strong>La commande ".$order->getReference()." est <u>en cours de préparation</u>.</strong></span>");

        // Redirection vers la liste
        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl();

        // Envoi du mail
        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstname()."<br>Hich'Trott vous informe que votre commande n°<strong>" .$order->getReference()."</strong> est en cours de préparation.";
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstname(),
            "Votre commande ".$order->getReference()." est en cours de préparation",
            $content
        );

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:orange;'><strong>La commande ".$order->getReference()." est <u>en cours de livraison</u>.</strong></span>");

        // Redirection vers la liste
        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl();

        // Envoi du mail
        $mail = new Mail();
        $content = "Bonjour ".$order->getUser()->getFirstname()."<br>Hich'Trott vous informe que votre commande n°<strong>" .$order->getReference()."</strong> est en cours de livraison.";
        $mail->send(
            $order->getUser()->getEmail(),
            $order->getUser()->getFirstname(),
            "Votre commande ".$order->getReference()." est en cours de livraison",
            $content
        );

        return $this->redirect($url);
    }

    // ------------------- CRUD config -------------------
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC'])
                    ->setEntityLabelInSingular('Commande')
                    ->setEntityLabelInPlural('Commandes');
    }

    // ------------------- Fields -------------------
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'Passée le')->setFormat('dd/MM/yyyy HH:mm'),
            TextField::new('user.getFullname', 'Utilisateur'),

            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),

            MoneyField::new('total', 'Total produit')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            MoneyField::new('carrierPrice', 'Frais de livraison')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            ChoiceField::new('state', 'Statut')->setChoices([
                'Non payée' => 0,
                'Payée' => 1,
                'Préparation en cours' => 2,
                'Livraison en cours' => 3,
            ]),

            // Affichage des détails de commande directement
            CollectionField::new('orderDetails', 'Produits achetés')
                ->setTemplatePath('admin/fields/order_details.html.twig')
                ->onlyOnDetail(),
        ];
    }
}
