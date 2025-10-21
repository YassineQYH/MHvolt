<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Trottinette;
use App\Entity\Accessory;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, Cart $panier, string $reference): RedirectResponse|JsonResponse
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order) {
            return new JsonResponse(['error' => 'order not found'], 404);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $productName = $product->getProduct();
            $productImage = null;

            // 🔍 On cherche d'abord dans les trottinettes
            $product_object = $entityManager->getRepository(Trottinette::class)->findOneBy(['name' => $productName]);

            // 🧩 Si ce n’est pas une trottinette, on cherche dans les accessoires
            if (!$product_object) {
                $product_object = $entityManager->getRepository(Accessory::class)->findOneBy(['name' => $productName]);
            }

            // 🖼️ Si on trouve un objet avec une image, on la prépare pour Stripe
            if ($product_object && $product_object->getImage()) {
                $productImage = $YOUR_DOMAIN . "/uploads/" . $product_object->getImage();
            } else {
                // 🔸 Image par défaut si le produit n’en a pas
                $productImage = $YOUR_DOMAIN . "/images/default-product.jpg";
            }

            // 💶 Préparation du produit pour Stripe
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(), // ⚠️ Vérifie si ton prix est déjà en centimes, sinon *100
                    'product_data' => [
                        'name' => $productName,
                        'images' => [$productImage],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        // 🚚 Ajout des frais de livraison
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => 'Livraison',
                    'images' => [$YOUR_DOMAIN . '/images/delivery.jpg'],
                ],
            ],
            'quantity' => 1,
        ];

        // 🔑 Clé API Stripe
        Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
