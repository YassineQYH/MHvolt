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
use App\Service\PdfService;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PdfService $pdfService
    ) {}

    #[Route('/commande', name: 'order', methods: ['GET','POST'])]
    public function index(
        Cart $cart,
        Request $request,
        CategoryAccessoryRepository $categoryAccessoryRepository,
        WeightRepository $weightRepository
    ): Response {
        $categories = $categoryAccessoryRepository->findAll();

        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info', 'Veuillez ajouter une adresse avant de passer commande.');
            return $this->redirectToRoute('account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);

        // Calcul du poids total
        $poidsTotal = 0.0;
        foreach ($cart->getFull() as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];
            $poids = $produit->getWeight() ? (float) $produit->getWeight()->getKg() : 0.0;
            $poidsTotal += $poids * $quantite;
        }

        $poidsTarif = $weightRepository->findByKgPrice($poidsTotal);
        $prixLivraison = $poidsTarif ? $poidsTarif->getPrice() : 0.0;

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull(),
            'categories' => $categories,
            'price' => $prixLivraison, // <--- ajouté
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
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $user,
        ]);

        $form->handleRequest($request);

        // Calcul poids total + quantité
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
            $date = new \DateTime();
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
            $order->setCarrier('bpost');
            $order->setReference($date->format('dmY') . '-' . uniqid());
            $order->setUser($user);
            $order->setCreatedAt($date);
            $order->setCarrierPrice($prixLivraison);
            $order->setDelivery($deliveryContent);
            $order->setPaymentState(0);
            $order->setDeliveryState(0);

            $this->entityManager->persist($order);

            foreach ($cart->getFull() as $element) {
                $produit = $element['product'];
                $quantite = (int) $element['quantity'];

                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($produit->getName()); // nom produit en string
                $orderDetails->setProductEntity($produit); // <-- Lien vers l'entité Product
                $orderDetails->setWeight($produit->getWeight() ? (string) $produit->getWeight()->getKg() : '0');
                $orderDetails->setQuantity($quantite);
                $orderDetails->setPrice($produit->getPrice());
                $orderDetails->setTotal($produit->getPrice() * $quantite);

                // ----- AJOUT DE LA TVA -----
                $tvaValue = $produit->getTva() ? $produit->getTva()->getValue() : 0;
                $orderDetails->setTva($tvaValue);

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


    #[Route('/account/order/{reference}/facture', name: 'account_order_invoice', methods: ['GET'])]
    public function generateInvoice(string $reference, Request $request): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Commande introuvable ou non autorisée.');
        }

        $download = $request->query->getBoolean('download', false);

        return $this->pdfService->generate(
            'account/invoice.html.twig',
            ['order' => $order],
            'facture_' . $order->getReference() . '.pdf',
            $download ? 'attachment' : 'inline'  // inline = affichage navigateur, attachment = téléchargement
        );
    }

    #[Route('/account/order/{reference}/facture/web', name: 'account_order_invoice_web', methods: ['GET'])]
    public function viewInvoiceWeb(string $reference): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['reference' => $reference]);
        if (!$order || $order->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Commande introuvable ou non autorisée.');
        }

        return $this->render('account/invoice_web.html.twig', [
            'order' => $order
        ]);
    }

}
