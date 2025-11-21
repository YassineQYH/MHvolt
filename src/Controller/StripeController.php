<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Trottinette;
use App\Entity\Accessory;
use App\Entity\Promotion;
use App\Service\PromotionService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(
        EntityManagerInterface $entityManager,
        Cart $panier,
        RequestStack $requestStack,
        PromotionService $promotionService,
        string $reference
    ): RedirectResponse|JsonResponse {

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $product_for_stripe = [];

        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('cart');
        }

        // Récupération de la commande
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order) {
            return new JsonResponse(['error' => 'order not found'], 404);
        }

        // Lecture du code promo en session
        $session = $requestStack->getSession();
        $promoCode = $session->get('promo_code');
        $promo = $promoCode ? $entityManager->getRepository(Promotion::class)->findOneBy(['code' => $promoCode]) : null;

        // Montant total du panier (avant réduction)
        $totalPanier = 0.0;
        foreach ($order->getOrderDetails() as $item) {
            $totalPanier += $item->getPriceTTC() * $item->getQuantity();
        }

        $reductionTotale = $panier->getReduction();
        $adjustment = 0;

        foreach ($order->getOrderDetails()->getValues() as $item) {

            // Récupération produit réel
            $product_object = $entityManager->getRepository(Trottinette::class)
                ->findOneBy(['name' => $item->getProduct()]);
            if (!$product_object) {
                $product_object = $entityManager->getRepository(Accessory::class)
                    ->findOneBy(['name' => $item->getProduct()]);
            }

            // Image produit
            $productImage = $YOUR_DOMAIN . '/img/default.png';
            if ($product_object) {
                $illustration = method_exists($product_object, 'getIllustrations')
                    ? $product_object->getIllustrations()->first()
                    : null;

                if ($illustration) {
                    $productImage = $YOUR_DOMAIN . '/uploads/' . $product_object->getUploadDirectory() . '/' . $illustration->getImage();
                }
            }

            $quantity = $item->getQuantity();
            $unitPrice = $item->getPriceTTC();

            // Répartition proportionnelle de la réduction
            $unitReduction = ($totalPanier > 0 && $reductionTotale > 0)
                ? ($unitPrice * $quantity / $totalPanier) * $reductionTotale
                : 0;

            // Prix total de la ligne après réduction
            $lineTotal = $unitPrice * $quantity - $unitReduction;

            // ⚠️ Arrondi en centimes pour Stripe
            $lineTotalCents = round($lineTotal * 100);
            $adjustment += ($lineTotal * 100) - $lineTotalCents;

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $lineTotalCents,
                    'product_data' => [
                        'name' => $item->getProduct(),
                        'images' => [$productImage],
                    ],
                ],
                'quantity' => 1, // on met 1 car on a déjà multiplié par la quantité
            ];
        }

        // Appliquer l'ajustement final sur la première ligne pour que le total exact corresponde
        if ($adjustment !== 0 && count($product_for_stripe) > 0) {
            $product_for_stripe[0]['price_data']['unit_amount'] += round($adjustment);
        }

        // 🚚 Livraison (jamais remisée)
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => round($order->getCarrierPrice() * 100),
                'product_data' => [
                    'name' => 'Livraison',
                    'images' => [$YOUR_DOMAIN . '/img/delivery.jpg'],
                ],
            ],
            'quantity' => 1,
        ];

        // 🔑 Stripe : clé API
        Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

        // 🧾 Création de la session Checkout
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url'  => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        // Sauvegarde dans la BDD
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
