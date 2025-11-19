<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
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
        $YOUR_DOMAIN = 'http://127.0.0.1:8000'; // Remplace par ton domaine réel en prod
        $product_for_stripe = [];

        /** @var User|null $user */
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour procéder au paiement.');
            return $this->redirectToRoute('cart');
        }

        // 🔎 Récupération de la commande
        $order = $entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order) {
            return new JsonResponse(['error' => 'order not found'], 404);
        }

        // 🧾 Parcours des produits de la commande
        foreach ($order->getOrderDetails()->getValues() as $item) {
            $product_object = $entityManager->getRepository(Trottinette::class)->findOneBy(['name' => $item->getProduct()]);
            if (!$product_object) {
                $product_object = $entityManager->getRepository(Accessory::class)->findOneBy(['name' => $item->getProduct()]);
            }

            // 🖼️ Détection de l'image
            $productImage = $YOUR_DOMAIN . '/img/default.png'; // fallback
            if ($product_object) {
                $illustration = method_exists($product_object, 'getIllustrations') ? $product_object->getIllustrations()->first() : null;
                if ($illustration) {
                    // ⚠️ ici mettre l'URL publique finale
                    $productImage = $YOUR_DOMAIN . '/uploads/'
                        . $product_object->getUploadDirectory()
                        . '/' . $illustration->getImage();
                }
            }

            $priceTTC = $item->getPrice() * (1 + ($item->getTva() / 100));

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($priceTTC * 100),
                    'product_data' => [
                        'name' => $item->getProduct(),
                        'images' => [$productImage],
                    ],
                ],
                'quantity' => $item->getQuantity(),
            ];
        }

        // 🚚 Ajouter les frais de livraison
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => 'Livraison',
                    'images' => [$YOUR_DOMAIN . '/img/delivery.jpg'],
                ],
            ],
            'quantity' => 1,
        ];

        // 🔑 Clé API Stripe
        Stripe::setApiKey('sk_test_51KNdRaBMBArCOnoiBGyovclE3rWKPO9X8dngKjHXezHj9SXaWeC3HrqOz7LCZAtXpVrJQzbx3PBPucDocAP8anBu00ZjyOIrSx');

        // 🧾 Création de la session Stripe
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $product_for_stripe,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        // 💾 Sauvegarde ID Stripe
        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return $this->redirect($checkout_session->url);
    }
}
