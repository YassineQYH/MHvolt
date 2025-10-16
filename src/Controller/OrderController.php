<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/commande', name: 'order')]
    public function index(Cart $cart, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // 🔐 Si non connecté → connexion
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        // 🛒 Vérifie que le panier n’est pas vide
        if (empty($cart->getFull())) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('products');
        }

        // 🏠 Vérifie si l’utilisateur a au moins une adresse
        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info', 'Veuillez ajouter une adresse avant de passer commande.');
            return $this->redirectToRoute('account_address_add');
        }

        // 🧾 Création du formulaire
        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(Cart $cart, Request $request, WeightRepository $weightRepo): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info', 'Veuillez ajouter une adresse avant de confirmer votre commande.');
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        $cartItems = $cart->getFull();

        // ------------------- Poids total du panier -------------------
        $totalWeight = $cart->getTotalWeight();

        // 💰 Récupération du tarif selon le poids total
        $tarif = $weightRepo->findByKgPrice($totalWeight);
        $shippingPrice = $tarif ? $tarif->getPrice() : 0;

        if ($form->isSubmitted() && $form->isValid()) {
            $delivery = $form->get('addresses')->getData();

            // 🏠 Formatage de l’adresse pour stockage
            $deliveryContent = sprintf(
                "%s %s</br>%s%s</br>%s</br>%s %s</br>%s",
                $delivery->getFirstname(),
                $delivery->getLastname(),
                $delivery->getPhone(),
                $delivery->getCompany() ? '</br>' . $delivery->getCompany() : '',
                $delivery->getAddress(),
                $delivery->getPostal(),
                $delivery->getCity(),
                $delivery->getCountry()
            );

            // 🧾 Création de la commande
            $order = (new Order())
                ->setReference((new DateTime())->format('dmY') . '-' . uniqid())
                ->setUser($user)
                ->setCreatedAt(new DateTime())
                ->setCarrierPrice($shippingPrice)
                ->setDelivery($deliveryContent)
                ->setState(0);

            $this->entityManager->persist($order);

            // 🧩 Ajout des détails produits
            foreach ($cartItems as $element) {
                $details = (new OrderDetails())
                    ->setMyOrder($order)
                    ->setProduct($element['product']->getName())
                    ->setWeight($element['product']->getWeight())
                    ->setQuantity($element['quantity'])
                    ->setPrice($element['product']->getPrice())
                    ->setTotal($element['product']->getPrice() * $element['quantity']);

                $this->entityManager->persist($details);
            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cartItems,
                'delivery' => $deliveryContent,
                'reference' => $order->getReference(),
                'price' => $shippingPrice,
                'totalLivraison' => null,
            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
