<?php
declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};
use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Order;
use App\Form\OrderType;
use App\Service\PdfService;
use App\Entity\OrderDetails;
use App\Service\PromotionService;
use App\Repository\WeightRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryAccessoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



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

        // récupération de toutes les promos
        $allPromotions = $promoRepo->findAll();

        $form = $this->createForm(OrderType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        // 🔹 DUMP pour debug
        /* dump($request->request->all()); // toutes les données POST
        dump($form->get('addresses')->getData()); // l'adresse sélectionnée
        die('DEBUG'); */

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addFlash('error', 'Le formulaire est invalide.');
            return $this->redirectToRoute('cart');
        }

        // récupération de l'adresse sélectionnée
        $delivery = $form->get('addresses')->getData();

        if (!$delivery) {
            $this->addFlash('error', 'Veuillez sélectionner une adresse de livraison.');
            return $this->redirectToRoute('cart');
        }

        // ✅ TOUT EST OK => ON PASSE AU RECAP
        /* return $this->redirectToRoute('order_recap'); */

        // Calcul du poids total et du prix de livraison
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

        // Contenu de l'adresse de livraison
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
        $order->setReference((new \DateTime())->format('dmY') . '-' . uniqid());
        $order->setUser($user);
        $order->setCreatedAt(new \DateTime());
        $order->setCarrierPrice($prixLivraison);
        $order->setDelivery($deliveryContent);
        $order->setPaymentState(0);
        $order->setDeliveryState(0);

        // Code promo
        if (method_exists($cart, 'getPromoCode')) {
            $order->setPromoCode($cart->getPromoCode());
        }

        // 🎁 AJOUT - hydratation du titre de la promo (corrige la colonne vide en BDD)
        if (method_exists($cart, 'getDiscountName')) {
            $order->setPromoTitre($cart->getDiscountName($promotionService, $allPromotions));
        }

        // Réduction TTC
        $order->setPromoReduction(
            $cart->getDiscountTTC($promotionService, $allPromotions)
        );

        $this->entityManager->persist($order);

        // 🔹 Calcul pour la répartition de la réduction sur les produits
        $reductionTTC = $cart->getDiscountTTC($promotionService, $allPromotions);
        $totalTTCPanier = array_reduce($cart->getFull(), fn($carry, $item) =>
            $carry + $item['product']->getPrice() * (1 + ($item['product']->getTva()?->getValue() / 100 ?? 0)) * $item['quantity'],
        0);

        // Détails des produits
        foreach ($cart->getFull() as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];

            $tvaValue = $produit->getTva() ? $produit->getTva()->getValue() : 0;
            $priceTTC = $produit->getPrice() * (1 + ($tvaValue / 100));

            // 🔹 proportion de réduction pour ce produit
            $productTTC = $priceTTC * $quantite;
            $reductionProportion = $totalTTCPanier > 0 ? $productTTC / $totalTTCPanier : 0;
            $reductionProduitTTC = $reductionTTC * $reductionProportion;

            $orderDetails = new OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($produit->getName());
            $orderDetails->setProductEntity($produit);
            $orderDetails->setWeight($produit->getWeight() ? (string) $produit->getWeight()->getKg() : '0');
            $orderDetails->setQuantity($quantite);
            $orderDetails->setPrice($produit->getPrice());
            $orderDetails->setTotal($produit->getPrice() * $quantite);
            $orderDetails->setTva($tvaValue);
            $orderDetails->setPriceTTC($priceTTC);

            // 🔹 NOUVELLES COLONNES
            $priceAfterReducHT = $produit->getPrice() - ($reductionProduitTTC / (1 + ($tvaValue / 100)) / $quantite);
            $priceAfterReducTTC = $priceTTC - ($reductionProduitTTC / $quantite);
            $totalAfterReduc = $priceAfterReducHT * $quantite;

            $orderDetails->setPriceAfterReduc($priceAfterReducHT);
            $orderDetails->setPriceTTCAfterReduc($priceAfterReducTTC);
            $orderDetails->setTotalAfterReduc($totalAfterReduc);

            $this->entityManager->persist($orderDetails);
        }

        $this->entityManager->flush();

        return $this->render('order/index.html.twig', [
            'cart' => $cart->getFull(),
            'cartObject' => $cart,
            'delivery' => $deliveryContent,
            'reference' => $order->getReference(),
            'price' => $prixLivraison,
            'totalLivraison' => null,
            'categories' => $categoryAccessoryRepository->findAll(),
            'promoDiscount' => $cart->getDiscountTTC($promotionService, $allPromotions),
            'promoCode' => $cart->getPromoCode(),
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

        // Configuration du QR code
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'imageBase64' => true, // Générer directement base64
            'scale' => 5
        ]);

        // Générer le QR code
        $qrcode = new QRCode($options);
        $qrCodeDataUri = $qrcode->render($trackingNumber); // base64 PNG

        // Rendre le template twig en HTML
        $html = $this->renderView('pdf/bordereau.html.twig', [
            'order' => $order,
            'qrCodePath' => $qrCodeDataUri
        ]);

        // Générer le PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait');
        $dompdf->render();

        // Envoyer le PDF en téléchargement
        return new Response($dompdf->stream("bordereau_{$order->getReference()}.pdf", [
            "Attachment" => true
        ]));
    }
}
