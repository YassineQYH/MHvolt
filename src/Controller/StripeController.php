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

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $product_for_stripe = [];

        // Produits
        foreach ($order->getOrderDetails() as $item) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($item->getPriceTTC() * 100),
                    'product_data' => [
                        'name' => $item->getProduct(),
                        'images' => [$YOUR_DOMAIN . '/img/default.png'],
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        // Livraison
        if ($order->getCarrierPrice() > 0) {
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
        }

        // Remise globale (ligne négative)
        $remise = $panier->getReduction($promotionService, $promo); // ou ton calcul exact
        if ($remise > 0) {
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round(-$remise * 100), // valeur négative
                    'product_data' => [
                        'name' => 'Remise promotionnelle',
                    ],
                ],
                'quantity' => 1,
            ];
        }



        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('cart');
        }

        // 🔍 Récupération de la commande
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order) {
            $this->addFlash('error', 'Commande introuvable.');
            return $this->redirectToRoute('cart');
        }

        // 🔑 Lecture du code promo en session
        $session = $requestStack->getSession();
        $promoCode = $session->get('promo_code');
        $promo = $promoCode ? $entityManager->getRepository(Promotion::class)->findOneBy(['code' => $promoCode]) : null;

        // 1️⃣ Calcul total panier TTC avant remise
        $totalPanier = 0.0;
        foreach ($order->getOrderDetails() as $item) {
            $totalPanier += $item->getPriceTTC() * $item->getQuantity();
        }

        // 2️⃣ Calcul de la réduction totale
        $reductionTotale = $panier->getReduction($promotionService, $promo);

        // 3️⃣ Construction des lignes Stripe
        $product_for_stripe = [];
        $totalPanier = 0.0;

        // 🔹 Calcul du total panier TTC avant remise
        foreach ($order->getOrderDetails() as $item) {
            $totalPanier += $item->getPriceTTC() * $item->getQuantity();
        }

        // 🔹 Calcul de la réduction totale
        $reductionTotale = $panier->getReduction($promotionService, $promo);

        // 🔹 Distribution proportionnelle de la remise sur chaque ligne
        $distributedDiscount = 0; // pour ajuster l'arrondi final
        $orderDetailsArray = $order->getOrderDetails()->getValues();
        $lastIndex = count($orderDetailsArray) - 1;

        foreach ($orderDetailsArray as $index => $item) {
            // Récupération du produit
            $product_object = $entityManager->getRepository(Trottinette::class)
                ->findOneBy(['name' => $item->getProduct()])
                ?? $entityManager->getRepository(Accessory::class)
                    ->findOneBy(['name' => $item->getProduct()]);

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

            // 🔹 Remise proportionnelle par unité
            $unitDiscount = ($totalPanier > 0 && $reductionTotale > 0)
                ? ($unitPrice / $totalPanier) * $reductionTotale
                : 0;

            $unitPriceAfterDiscount = $unitPrice - $unitDiscount;

            // ⚠️ Arrondi pour Stripe (en centimes)
            $unitPriceCents = round($unitPriceAfterDiscount * 100);

            // 🔹 On cumule la remise déjà appliquée pour ajustement
            $distributedDiscount += ($unitPrice - $unitPriceAfterDiscount) * $quantity;

            // 🔹 Dernier produit : on ajuste pour que la somme exacte corresponde au total réel
            if ($index === $lastIndex) {
                $adjustment = round(($reductionTotale - $distributedDiscount) * 100);
                $unitPriceCents -= $adjustment / $quantity; // répartit l’ajustement sur les unités
            }

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $unitPriceCents,
                    'product_data' => [
                        'name' => $item->getProduct(),
                        'images' => [$productImage],
                    ],
                ],
                'quantity' => $quantity,
            ];
        }

        // 🔹 Livraison (jamais remisée)
        if ($order->getCarrierPrice() > 0) {
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
        }


        // 🔑 Stripe API key
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

        // 🔹 Sauvegarde de la session Stripe dans la commande
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        // 🔹 Redirection vers Stripe
        return $this->redirect($checkout_session->url);
    }
}
