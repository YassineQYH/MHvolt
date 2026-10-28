<?php
declare(strict_types=1);

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\CategoryAccessoryRepository;
use App\Repository\PromotionRepository;
use App\Repository\WeightRepository;
use App\Service\PdfService;
use App\Service\PromotionService;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PdfService $pdfService
    ) {}

    #[Route('/recapitulatif-commande', name: 'order_recap', methods: ['GET', 'POST'])]
    public function index(
        Cart $cart,
        Request $request,
        WeightRepository $weightRepository,
        CategoryAccessoryRepository $categoryAccessoryRepository,
        PromotionService $promotionService,
        PromotionRepository $promoRepo
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('login_required', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('cart');
        }

        $allPromotions = $promoRepo->findAll();
        $promoTitre = $cart->getDiscountName($promotionService, $allPromotions);

        $form = $this->createForm(OrderType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('error', 'Le formulaire est invalide.');
            return $this->redirectToRoute('cart');
        }

        $delivery = $form->get('addresses')->getData();
        if (!$delivery) {
            $this->addFlash('error', 'Veuillez sélectionner une adresse de livraison.');
            return $this->redirectToRoute('cart');
        }

        // 1. Calcul des frais de port par poids
        $poidsTotal = (float) $cart->getTotalWeight();
        $poidsTarif = $weightRepository->findPriceByWeight($poidsTotal);
        $prixLivraison = $poidsTarif ? (float) $poidsTarif->getPrice() : 0.0;

        $deliveryContent = sprintf(
            '%s %s<br>%s<br>%s%s<br>%s %s<br>%s',
            htmlspecialchars($delivery->getFirstname()),
            htmlspecialchars($delivery->getLastname()),
            htmlspecialchars($delivery->getPhone() ?? ''),
            $delivery->getCompany() ? htmlspecialchars($delivery->getCompany()) . '<br>' : '',
            htmlspecialchars($delivery->getAddress()),
            htmlspecialchars($delivery->getPostal()),
            htmlspecialchars($delivery->getCity()),
            htmlspecialchars($delivery->getCountry())
        );

        // 2. Détection du pays de livraison pour la ventilation fiscale
        $countryCode = strtoupper(trim($delivery->getCountry() ?? 'BE'));
        $defaultTvaRate = ($countryCode === 'FR') ? 20.0 : 21.0;

        $reductionTTC = (float) $cart->getDiscountTTC($promotionService, $allPromotions);

        // 3. Création de la commande
        $order = new Order();
        $order->setReference((new \DateTimeImmutable())->format('dmY') . '-' . strtoupper(substr(uniqid(), -6)));
        $order->setUser($user);
        $order->setCarrier('bpost');
        $order->setCarrierPrice($prixLivraison);
        $order->setDelivery($deliveryContent);
        $order->setDeliveryCountry($countryCode);
        $order->setPaymentState(Order::PAYMENT_UNPAID);
        $order->setDeliveryState(Order::STATE_WAITING);
        $order->setPromoCode($cart->getPromoCode());
        $order->setPromoTitre($promoTitre);
        $order->setPromoReduction($reductionTTC);

        $this->entityManager->persist($order);

        // 4. Calcul du total brut basé sur le prix TTC public saisi en admin
        $totalBrutTTC = array_reduce(
            $cart->getFull(),
            fn($carry, $item) => $carry + ((float)$item['product']->getPrice() * (int)$item['quantity']),
            0.0
        );

        // 5. Enregistrement des lignes OrderDetails sans surtaxe
        foreach ($cart->getFull() as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];

            // Taux de TVA applicable
            $tvaRate = (float) ($produit->getTva() ? $produit->getTva()->getValue() : $defaultTvaRate);

            // Le prix en base de données EST le prix public TTC
            $priceTTC = (float) $produit->getPrice();
            $priceHT = $priceTTC / (1 + ($tvaRate / 100));

            // Répartition équitable de la réduction promo globale sur chaque article
            $partTTC = ($totalBrutTTC > 0)
                ? (($priceTTC * $quantite) / $totalBrutTTC) * $reductionTTC
                : 0.0;
            $reducUnitaireTTC = $quantite > 0 ? ($partTTC / $quantite) : 0.0;

            $priceTtcAfterReduc = max(0.0, $priceTTC - $reducUnitaireTTC);
            $priceHtAfterReduc = $priceTtcAfterReduc / (1 + ($tvaRate / 100));

            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($produit->getName());
            $orderDetails->setProductEntity($produit);
            $orderDetails->setQuantity($quantite);

            // Prix unitaire HT et remisé HT
            $orderDetails->setPrice(round($priceHT, 2));
            $orderDetails->setPriceAfterReduc(round($priceHtAfterReduc, 2));

            // Taux TVA
            $orderDetails->setTva($tvaRate);

            // Prix unitaire TTC et Total TTC réels payés
            $orderDetails->setPriceTtc(round($priceTtcAfterReduc, 2));
            $orderDetails->setTotalTtc(round($priceTtcAfterReduc * $quantite, 2));
            $orderDetails->setWeight((float)$produit->getWeight());

            $this->entityManager->persist($orderDetails);
        }

        $this->entityManager->flush();

        return $this->render('order/index.html.twig', [
            'cart' => $cart->getFull(),
            'cartObject' => $cart,
            'order' => $order,
            'delivery' => $deliveryContent,
            'reference' => $order->getReference(),
            'price' => $prixLivraison,
            'categories' => $categoryAccessoryRepository->findAll(),
            'promoDiscount' => $reductionTTC,
            'promoCode' => $cart->getPromoCode(),
            'promoTitre' => $promoTitre,
            'promoService' => $promotionService,
            'allPromotions' => $allPromotions,
        ]);
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
            $download ? 'attachment' : 'inline'
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

    #[Route('/order/{id}/bordereau/pdf', name: 'order_bordereau_pdf')]
    public function bordereauPdf(Order $order): Response
    {
        $trackingNumber = $order->getTrackingNumber() ?: 'N/A';

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'imageBase64' => true,
            'scale' => 5
        ]);

        $qrcode = new QRCode($options);
        $qrCodeDataUri = $qrcode->render($trackingNumber);

        $html = $this->renderView('pdf/bordereau.html.twig', [
            'order' => $order,
            'qrCodePath' => $qrCodeDataUri
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename = sprintf('bordereau_%s.pdf', $order->getReference());

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }
}
