<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
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
        // --- Gestion automatique du stock des PRODUITS ---
        foreach ($panier->getFull() as $element) {
            $product = $element['product'];
            $quantityInCart = $element['quantity'];

            $product->setStock(max(0, $product->getStock() - $quantityInCart));
            $this->entityManager->persist($product);
        }

        // --- Gestion automatique de l'utilisation des PROMOTIONS ---
        if ($panier->getPromoCode()) {
            $promo = $this->entityManager->getRepository(Promotion::class)->findOneBy([
                'code' => $panier->getPromoCode()
            ]);

            if ($promo && $promo->canBeUsed()) {
                $promo->incrementUsed();
                $this->entityManager->persist($promo);
            }
        }

        $this->entityManager->flush();

        // --- Récupération de la commande ---
        $order = $this->entityManager->getRepository(Order::class)
            ->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($order->getPaymentState() === 0) {
            $panier->remove();       // vide le panier
            $panier->clearPromos();  // supprime code promo et remise

            $order->setPaymentState(1); // Payée
            $this->entityManager->flush();

            // Email client
            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getFirstname() . "</br>"
                . "SY-Shop vous remercie pour votre commande n°<strong>" . $order->getReference() . "</strong> "
                . "pour un total de " . $order->getTotal() / 100 . " Euros.</br>"
                . "Vous serez averti lorsque la préparation de la commande sera terminée et envoyée.</br>";
            $mail->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstname(),
                "Votre commande n° " . $order->getReference() . " est bien validée.",
                $content
            );

            // Email admin
            $mailAdmin = new Mail();
            $subject = "Nouvelle commande validée et payée";
            $contentAdmin = "Bonjour, </br>La commande n°<strong>" . $order->getReference() . "</strong> "
                . "de <strong>" . $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname() . "</strong> "
                . "vient d'être payée et validée.";
            $mailAdmin->send('admin@hich-trott.com', '', $subject, $contentAdmin);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
            'panier' => $panier,
        ]);
    }
}
