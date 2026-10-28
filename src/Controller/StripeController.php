<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(
        EntityManagerInterface $entityManager,
        string $reference
    ): RedirectResponse {
        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('cart');
        }

        /** @var Order|null $order */
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order || $order->getUser() !== $user) {
            $this->addFlash('error', 'Commande introuvable ou non autorisée.');
            return $this->redirectToRoute('cart');
        }

        $productForStripe = [];

        // 1. Lignes d'articles depuis OrderDetails en TTC final
        foreach ($order->getOrderDetails() as $item) {
            $productEntity = $item->getProductEntity();

            $stripeImages = [];
            if ($productEntity && $productEntity->getFirstIllustration()) {
                $rawImg = $productEntity->getFirstIllustration();
                if (str_starts_with($rawImg, 'https://')) {
                    $stripeImages[] = $rawImg;
                }
            }

            // Récupération directe du TTC final remisé (ex: 719.10 €)
            $unitPrice = (float) (
                $item->getPriceTtc()
                ?? ($item->getTotalTtc() > 0 ? $item->getTotalTtc() / $item->getQuantity() : null)
                ?? $item->getPrice()
            );

            $unitAmountCents = (int) round($unitPrice * 100);

            $productData = [
                'name' => $item->getProduct(),
            ];
            if (!empty($stripeImages)) {
                $productData['images'] = $stripeImages;
            }

            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $unitAmountCents,
                    'product_data' => $productData,
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        // 2. Frais de port
        if ($order->getCarrierPrice() > 0) {
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => (int) round($order->getCarrierPrice() * 100),
                    'product_data' => [
                        'name' => 'Frais de livraison (' . ($order->getCarrier() ?? 'Standard') . ')',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        // 3. Initialisation Stripe
        $stripeSecretKey = $_ENV['STRIPE_SECRET_KEY'] ?? 'sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx';
        Stripe::setApiKey($stripeSecretKey);

        $successUrl = $this->generateUrl('order_validate', ['stripeSessionId' => 'CHECKOUT_SESSION_ID_PLACEHOLDER'], UrlGeneratorInterface::ABSOLUTE_URL);
        $successUrl = str_replace('CHECKOUT_SESSION_ID_PLACEHOLDER', '{CHECKOUT_SESSION_ID}', $successUrl);

        $cancelUrl = $this->generateUrl('order_cancel', ['stripeSessionId' => 'CHECKOUT_SESSION_ID_PLACEHOLDER'], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancelUrl = str_replace('CHECKOUT_SESSION_ID_PLACEHOLDER', '{CHECKOUT_SESSION_ID}', $cancelUrl);

        $checkoutSession = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $productForStripe,
            'mode' => 'payment',
            'metadata' => [
                'order_reference' => $order->getReference(),
            ],
            'success_url' => $successUrl,
            'cancel_url'  => $cancelUrl,
        ]);

        $order->setStripeSessionId($checkoutSession->id);
        $entityManager->flush();

        return $this->redirect($checkoutSession->url);
    }
}
