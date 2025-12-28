<?php

namespace App\Controller;

use App\Entity\Order;
use App\Classe\Cart;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(Cart $cart, WeightRepository $weightRepository): \Symfony\Component\HttpFoundation\Response
    {
        $cartItems = $cart->getFull();

        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        $poid = 0.0;
        $quantityProduct = 0;

        foreach ($cartItems as $element) {
            $poidAndQuantity = $element['product']->getWeight() ?? 0.0; // float direct
            $quantityProduct += $element['quantity'];
            $poid += $poidAndQuantity * $element['quantity'];
        }

        // Récupération du prix livraison selon poids
        $weightEntity = $weightRepository->findPriceByWeight($poid);
        $prix = $weightEntity ? $weightEntity->getPrice() : 0.0;


        return $this->render('account/order.html.twig', [
            'orders' => $orders,
            'poid' => $poid,
            'price' => $prix,
            'quantity_product' => $quantityProduct,
            'panier' => $cartItems,
        ]);
    }

    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show(string $reference): \Symfony\Component\HttpFoundation\Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }

        // Calcul du poids total de la commande
        $totalWeight = 0;
        foreach ($order->getOrderDetails() as $item) {
            $totalWeight += $item->getWeight() * $item->getQuantity();
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order,
            'totalWeight' => $totalWeight,
        ]);
    }


    public function fillPriceList(WeightRepository $weightRepository): array
    {
        $priceList = [];
        $weights = $weightRepository->findAll();

        foreach ($weights as $item) {
            $priceList[(string)$item->getKg()] = $item->getPrice();
        }

        return $priceList;
    }
}
