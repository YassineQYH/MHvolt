<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\WeightRepository;
use App\Repository\CategoryAccessoryRepository; // ✅ On importe la bonne classe
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/commande', name: 'order', methods: ['GET', 'POST'])]
    public function index(
        Cart $cart,
        Request $request,
        CategoryAccessoryRepository $categoryAccessoryRepository
    ): Response {
        $categories = $categoryAccessoryRepository->findAll();

        $user = $this->getUser();

        // 🔐 Si non connecté → redirection vers login
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        // 🏠 Vérifie que l’utilisateur a au moins une adresse
        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
            'categories' => $categories,
        ]);
    }


    #[Route('/commande/recapitulatif', name: 'order_recap', methods: ['POST'])]
    public function add(
        Cart $cart,
        Request $request,
        WeightRepository $weightRepository,
        CategoryAccessoryRepository $categoryAccessoryRepository // ✅
    ): Response {
        $categories = $categoryAccessoryRepository->findAll(); // ✅

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $form->handleRequest($request);

        $poidsTotal = 0.0;
        $quantiteTotale = 0;

        foreach ($cart->getFull() as $element) {
            $produit = $element['product'];
            $quantite = $element['quantity'];
            $poids = $produit->getWeight()->getKg();

            $poidsTotal += $poids * $quantite;
            $quantiteTotale += $quantite;
        }

        $poidsTarif = $weightRepository->findByKgPrice($poidsTotal);
        $prixLivraison = $poidsTarif ? $poidsTarif->getPrice() : 0;

        $priceList = $this->fillPriceList($weightRepository);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $delivery = $form->get('addresses')->getData();

            $deliveryContent = sprintf(
                '%s %s<br>%s<br>%s%s%s<br>%s',
                $delivery->getFirstname(),
                $delivery->getLastname(),
                $delivery->getPhone(),
                $delivery->getCompany() ? $delivery->getCompany() . '<br>' : '',
                $delivery->getAddress(),
                '<br>' . $delivery->getPostal() . ' ' . $delivery->getCity(),
                $delivery->getCountry()
            );

            $order = new Order();
            $reference = $date->format('dmY') . '-' . uniqid();

            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierPrice($prixLivraison);
            $order->setDelivery($deliveryContent);
            $order->setState(0);

            $this->entityManager->persist($order);

            foreach ($cart->getFull() as $element) {
                $produit = $element['product'];
                $quantite = $element['quantity'];

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($produit->getName());
                $orderDetails->setWeight($produit->getWeight());
                $orderDetails->setQuantity($quantite);
                $orderDetails->setPrice($produit->getPrice());
                $orderDetails->setTotal($produit->getPrice() * $quantite);

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'delivery' => $deliveryContent,
                'reference' => $order->getReference(),
                'price' => $prixLivraison,
                'totalLivraison' => null,
                'categories' => $categories,
            ]);
        }

        return $this->redirectToRoute('cart');
    }

    private function fillPriceList(WeightRepository $weightRepository): array
    {
        $priceList = [];
        foreach ($weightRepository->findAll() as $item) {
            $priceList[(string) $item->getKg()] = (string) $item->getPrice();
        }

        return $priceList;
    }
}
