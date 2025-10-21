<?php
declare(strict_types=1);

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\WeightRepository;
use App\Repository\CategoryAccessoryRepository;
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

    #[Route('/commande', name: 'order', methods: ['GET','POST'])]
    public function index(
        Cart $cart,
        Request $request,
        CategoryAccessoryRepository $categoryAccessoryRepository
    ): Response {
        $categories = $categoryAccessoryRepository->findAll();

        /** @var User|null $user */
        $user = $this->getUser();

        // 🔐 Si non connecté → redirection vers le panier (ou login selon ton flux)
        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        // 🏠 Si pas d'adresse → redirection vers ajout d'adresse
        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info', 'Veuillez ajouter une adresse avant de passer commande.');
            return $this->redirectToRoute('account_address_add');
        }

        // Création du formulaire (OrderType attend l'option 'user')
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
        CategoryAccessoryRepository $categoryAccessoryRepository
    ): Response {
        $categories = $categoryAccessoryRepository->findAll();

        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);

        $form->handleRequest($request);

        // Débug utile (décommenter si besoin)
        // dump([
        //     'isSubmitted' => $form->isSubmitted(),
        //     'isValid' => $form->isValid(),
        //     'request_method' => $request->getMethod(),
        //     'request_data' => $request->request->all(),
        // ]);
        // if ($form->isSubmitted() && !$form->isValid()) {
        //     dd($form->getErrors(true, false));
        // }

        // Calcul du poids total et de la quantité
        $poidsTotal = 0.0;
        $quantiteTotale = 0;
        foreach ($cart->getFull() as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];
            $poids = $produit->getWeight() ? (float) $produit->getWeight()->getKg() : 0.0;

            $poidsTotal += $poids * $quantite;
            $quantiteTotale += $quantite;
        }

        $poidsTarif = $weightRepository->findByKgPrice($poidsTotal);
        $prixLivraison = $poidsTarif ? $poidsTarif->getPrice() : 0;

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

            // Création de la commande
            $order = new Order();
            $reference = $date->format('dmY') . '-' . uniqid();

            $order->setReference($reference);
            $order->setUser($user);
            $order->setCreatedAt($date);
            $order->setCarrierPrice($prixLivraison);
            $order->setDelivery($deliveryContent);
            $order->setState(0);

            $this->entityManager->persist($order);

            // Enregistrement des détails
            foreach ($cart->getFull() as $element) {
                $produit = $element['product'];
                $quantite = (int) $element['quantity'];

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($produit->getName());
                $orderDetails->setWeight($produit->getWeight() ? (string) $produit->getWeight()->getKg() : '0');
                $orderDetails->setQuantity($quantite);
                $orderDetails->setPrice($produit->getPrice());
                $orderDetails->setTotal($produit->getPrice() * $quantite);

                $this->entityManager->persist($orderDetails);
            }

            $this->entityManager->flush();

            // Affiche la page récap avant paiement (order/add.html.twig)
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'delivery' => $deliveryContent,
                'reference' => $order->getReference(),
                'price' => $prixLivraison,
                'totalLivraison' => null,
                'categories' => $categories,
            ]);
        }

        // Non soumis / invalide → retour panier (tu peux dump pour debug)
        // dd('form invalid', $request->request->all());
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
