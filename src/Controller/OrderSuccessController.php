<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $panier, string $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)
            ->findOneBy(['stripeSessionId' => $stripeSessionId]);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Exécution uniquement si la commande n'a pas encore été validée
        if ($order->getPaymentState() === Order::PAYMENT_UNPAID) {

            // 1. Décrémentation du stock des produits
            foreach ($order->getOrderDetails() as $detail) {
                $product = $detail->getProductEntity();
                if ($product) {
                    $newStock = max(0, $product->getStock() - $detail->getQuantity());
                    $product->setStock($newStock);
                }
            }

            // 2. Incrémentation du compteur de promo si un code a été utilisé
            if ($order->getPromoCode()) {
                $promo = $this->entityManager->getRepository(Promotion::class)->findOneBy([
                    'code' => $order->getPromoCode()
                ]);

                if ($promo && $promo->canBeUsed()) {
                    $promo->incrementUsed();
                }
            }

            // 3. Validation de l'état de paiement
            $order->setPaymentState(Order::PAYMENT_PAID);
            $order->setDeliveryState(Order::STATE_PREPARATION);

            $this->entityManager->flush();

            // 4. Nettoyage du panier en session
            $panier->remove();
            $panier->clearPromos();

            // 5. Envoi des e-mails
            $mail = new Mail();
            $totalFormate = number_format($order->getTotalTtc(), 2, ',', ' ');
            $content = "Bonjour " . $order->getUser()->getFirstname() . ",<br><br>"
                . "MHvolt vous remercie pour votre commande n°<strong>" . $order->getReference() . "</strong> "
                . "d'un montant total de <strong>" . $totalFormate . " €</strong>.<br>"
                . "Votre commande est désormais en cours de préparation dans nos ateliers.<br>";

            $mail->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstname(),
                "Confirmation de votre commande n° " . $order->getReference(),
                $content
            );

            $mailAdmin = new Mail();
            $contentAdmin = "Une nouvelle commande vient d'être réglée :<br>"
                . "Référence : <strong>" . $order->getReference() . "</strong><br>"
                . "Client : <strong>" . $order->getUser()->getFirstname() . " " . $order->getUser()->getLastname() . "</strong><br>"
                . "Montant : <strong>" . $totalFormate . " €</strong>";

            $mailAdmin->send('admin@mhvolt.com', 'MHvolt Admin', 'Nouvelle commande confirmée', $contentAdmin);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
