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
        ): RedirectResponse {
            $YOUR_DOMAIN = $_ENV['APP_URL'];
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
                $this->addFlash('error', 'Commande introuvable.');
                return $this->redirectToRoute('cart');
            }

            // Récupération des promos (même méthode que dans OrderController)
            $allPromotions = $entityManager->getRepository(Promotion::class)->findAll();

            // Calcul total panier TTC (base pour la répartition)
            $totalPanier = 0.0;
            foreach ($order->getOrderDetails() as $item) {
                // getPriceTTC() doit déjà renvoyer le prix TTC unitaire selon ta logique dans OrderDetails
                $totalPanier += $item->getPriceTTC() * $item->getQuantity();
            }

            // ✅ Calcul de la réduction totale en TTC (même méthode que sur le récap)
            $reductionTotale = $panier->getDiscountTTC($promotionService, $allPromotions);

            // Construction des lignes Stripe : on répartit la réduction proportionnellement sur les lignes TTC
            $sumCents = 0;
            $lines = []; // stock temporaire des lignes pour pouvoir ajuster ensuite

            foreach ($order->getOrderDetails()->getValues() as $item) {
                // Essayer de récupérer l'entité produit pour l'image (optionnel)
                $product_object = $entityManager->getRepository(\App\Entity\Trottinette::class)
                    ->findOneBy(['name' => $item->getProduct()])
                    ?? $entityManager->getRepository(\App\Entity\Accessory::class)
                        ->findOneBy(['name' => $item->getProduct()]);

                $productImage = $YOUR_DOMAIN . '/img/default.png';
                if ($product_object) {
                    $illustration = method_exists($product_object, 'getIllustrations')
                        ? $product_object->getIllustrations()->first()
                        : null;
                    if ($illustration) {
                        $productImage = $YOUR_DOMAIN . '/uploads/' . $product_object->getUploadDirectory() . '/' . $illustration->getImage();
                    }
                }

                $lineTTC = $item->getPriceTTC() * $item->getQuantity(); // TTC total ligne avant répartition
                $lineDiscount = 0.0;

                if ($totalPanier > 0 && $reductionTotale > 0) {
                    // part proportionnelle de la réduction sur cette ligne (en euros)
                    $lineDiscount = ($lineTTC / $totalPanier) * $reductionTotale;
                }

                $lineTotalAfterDiscount = $lineTTC - $lineDiscount; // TTC total ligne après remise

                // convertir en centimes et arrondir
                $lineTotalCents = (int) round($lineTotalAfterDiscount * 100);

                $lines[] = [
                    'product_name' => $item->getProduct(),
                    'image' => $productImage,
                    'quantity' => $item->getQuantity(),
                    'line_total_cents' => $lineTotalCents,
                    'unit_price_cents' => $item->getQuantity() > 0 ? (int) round(($lineTotalAfterDiscount / $item->getQuantity()) * 100) : $lineTotalCents,
                    // on garde lineTotalCents pour somme et ajustement ultérieur
                ];

                $sumCents += $lineTotalCents;
            }

            // Ajouter la livraison (en centimes) — jamais remisée
            $shippingCents = (int) round($order->getCarrierPrice() * 100);
            // Somme attendue (produits remisés + livraison), calculée en centimes
            $expectedTotalCents = (int) round((($totalPanier - $reductionTotale) + $order->getCarrierPrice()) * 100);

            // Somme actuelle (produits répartis) + livraison
            $currentSumCents = $sumCents + $shippingCents;

            // Différence d'arrondi à corriger (peut être négative ou positive)
            $diff = $expectedTotalCents - $currentSumCents;

            // Si besoin, on applique la différence sur la première ligne unit_amount
            if ($diff !== 0 && count($lines) > 0) {
                // ajouter la différence aux centimes de la première ligne
                $lines[0]['line_total_cents'] += $diff;
                // recalculer aussi son unit_price_cents
                $qty0 = max(1, $lines[0]['quantity']);
                $lines[0]['unit_price_cents'] = (int) round($lines[0]['line_total_cents'] / $qty0);
                $currentSumCents += $diff; // maintenant égal à expectedTotalCents
            }

            // Construire enfin $product_for_stripe
            foreach ($lines as $l) {
                $product_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $l['unit_price_cents'],
                        'product_data' => [
                            'name' => $l['product_name'],
                            'images' => [$l['image']],
                        ],
                    ],
                    'quantity' => $l['quantity'],
                ];
            }

            // Ajout livraison
            if ($shippingCents > 0) {
                $product_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $shippingCents,
                        'product_data' => [
                            'name' => 'Livraison',
                            'images' => [$YOUR_DOMAIN . '/img/delivery.jpg'],
                        ],
                    ],
                    'quantity' => 1,
                ];
            }

            // --- DEBUG : décommenter si tu veux voir ce qu'on envoie à Stripe ---
            // dump($totalPanier, $reductionTotale, $expectedTotalCents, $sumCents, $shippingCents, $diff, $product_for_stripe);
            // dd('STOP debug');

            // Stripe key (remplace par ta clé prod/test)
            Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

            $checkout_session = Session::create([
                'customer_email' => $user->getEmail(),
                'payment_method_types' => ['card'],
                'line_items' => $product_for_stripe,
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url'  => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            // Sauvegarde de la session Stripe dans la commande
            $order->setStripeSessionId($checkout_session->id);
            $entityManager->flush();

            return $this->redirect($checkout_session->url);
        }
}
