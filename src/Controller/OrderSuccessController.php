<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $panier, string $stripeSessionId): Response
    {
        // Gestion automatique du stock
        foreach ($panier->getFull() as $element) {
            $product = $element['product'];
            $quantityInCart = $element['quantity'];

            $product->setStock($product->getStock() - $quantityInCart);
            $this->entityManager->persist($product);
        }
        $this->entityManager->flush();

        $order = $this->entityManager->getRepository(Order::class)
            ->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getPaymentState() === 0) {
            // Vider la session "cart" après le paiement
            $panier->remove();

            // Modifier le statut de la commande
            $order->setPaymentState(1); // Payée
            $this->entityManager->flush();

            // Envoyer un email au client
            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstname() . "</br>"
                . "SY-Shop vous remercie pour votre commande n°<strong>" . $order->getReference() . "</strong> "
                . "pour un total de " . $order->getTotal() / 100 . " Euros.</br>"
                . "Vous serez averti lorsque la préparation de la commande sera terminée et envoyée.</br>";
            $mail->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstname(),
                "Votre commande n° " . $order->getReference() . " est bien validée. Vous serez averti par mail lors de la préparation et de l'envoi.",
                $content
            );

            // Envoyer un email à l'admin
            $mailAdmin = new Mail();
            $subject = "Nouvelle commande validée et payée";
            $contentAdmin = "Bonjour, </br>La commande n°<strong>" . $order->getReference() . "</strong> "
                . "de <strong>" . $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname() . "</strong> "
                . "vient d'être payée et validée.";
            $mailAdmin->send('admin@sy-shop.yassine-qayouh-dev.com', '', $subject, $contentAdmin);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
            'panier' => $panier,
        ]);
    }
}
