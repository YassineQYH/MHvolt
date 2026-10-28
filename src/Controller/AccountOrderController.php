<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(Cart $cart, WeightRepository $weightRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $cartItems = $cart->getFull();
        $orders = $this->entityManager->getRepository(Order::class)->findBy(
            ['user' => $user, 'paymentState' => Order::PAYMENT_PAID],
            ['id' => 'DESC']
        );

        $poidsTotal = $cart->getTotalWeight();
        $weightEntity = $weightRepository->findPriceByWeight($poidsTotal);
        $prixLivraison = $weightEntity ? $weightEntity->getPrice() : 0.0;

        return $this->render('account/order.html.twig', [
            'orders' => $orders,
            'poid' => $poidsTotal,
            'price' => $prixLivraison,
            'quantity_product' => $cart->getTotalQuantity(),
            'panier' => $cartItems,
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show(string $reference): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);

        if (!$order || $order->getUser() !== $user) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order,
            'totalWeight' => $order->getWeightTotal(),
        ]);
    }
}
