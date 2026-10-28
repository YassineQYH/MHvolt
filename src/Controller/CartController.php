<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Form\OrderType;
use App\Service\PromotionService;
use App\Repository\WeightRepository;
use App\Repository\PromotionRepository;
use App\Repository\CategoryAccessoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\PdfService;

class CartController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private readonly PdfService $pdfService
    ) {
        parent::__construct($entityManager);
    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(
        Request $request,
        Cart $cart,
        WeightRepository $weightRepository,
        PromotionService $promotionService,
        PromotionRepository $promoRepo,
        CategoryAccessoryRepository $categoryAccessoryRepository
    ): Response {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('info-alert', 'Vous devez être connecté pour valider votre panier.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info-alert', 'Veuillez ajouter une adresse avant de passer commande.');
            return $this->redirectToRoute('account_address_add');
        }

        $formOrder = $this->createForm(OrderType::class, null, ['user' => $user]);
        $formOrder->handleRequest($request);

        $articlesPanier = $cart->getFull();

        // Calcul du poids total
        $poidsTotal = 0.0;
        foreach ($articlesPanier as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];
            $poidsTotal += ($produit->getWeight() ?? 0.0) * $quantite;
        }

        $poidsTarif = $weightRepository->findPriceByWeight($poidsTotal);
        $prixLivraison = $poidsTarif ? $poidsTarif->getPrice() : 0.0;

        $promoDiscount = method_exists($cart, 'getReduction') ? (float) $cart->getReduction($promotionService) : 0.0;
        $promoCode = method_exists($cart, 'getPromoCode') ? $cart->getPromoCode() : null;

        $categories = $categoryAccessoryRepository->findAll();
        $allPromotions = $promoRepo->findAll();

        return $this->render('cart/index.html.twig', [
            'cart' => $articlesPanier,
            'cartObject' => $cart,
            'price' => $prixLivraison,
            'promoDiscount' => $promoDiscount,
            'promoCode' => $promoCode,
            'form_order' => $formOrder->createView(),
            'categories' => $categories,
            'allPromotions' => $allPromotions,
            'promoService' => $promotionService,
        ]);
    }

    #[Route('/cart/apply-promo', name: 'cart_apply_promo', methods: ['POST'])]
    public function appliquerCodePromoAjax(
        Request $request,
        Cart $cart,
        PromotionRepository $promotionRepository,
        WeightRepository $weightRepository,
        PromotionService $promotionService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $code = trim($data['promo_code'] ?? '');

        if (!$code) {
            return new JsonResponse(['error' => 'Veuillez saisir un code promo.']);
        }

        $promo = $promotionRepository->findOneBy(['code' => $code]);

        if (!$promo || !$promo->canBeUsed() || !$promo->isDiscountValid()) {
            $cart->clearPromos();
            return new JsonResponse(['error' => 'Ce code promo est invalide ou a expiré.']);
        }

        $allPromos = $promotionRepository->findAll();
        $autoDiscount = $cart->getDiscountTTC($promotionService, $allPromos);
        $codeDiscount = $cart->getReduction($promotionService, $promo);

        if ($codeDiscount <= 0) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => "Ce code promo ne s'applique à aucun article de votre panier."
            ]);
        }

        if ($autoDiscount > $codeDiscount && $cart->getPromoCode() !== $code) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => "Une promotion automatique plus avantageuse est déjà appliquée.",
                'reload' => false
            ]);
        }

        $needsReload = $cart->getPromoCode() === null || $cart->getPromoCode() !== $code;
        $cart->setPromoCode($code);

        $totalTTC = array_reduce(
            $cart->getFull(),
            fn($carry, $item) => $carry + ($item['product']->getPriceTtc() * $item['quantity']),
            0
        );

        $totalAfterPromo = $totalTTC - $codeDiscount + $cart->getLivraisonPrice($weightRepository);

        return new JsonResponse([
            'discount' => $codeDiscount,
            'totalAfterPromo' => $totalAfterPromo,
            'reload' => $needsReload
        ]);
    }

    #[Route('/cart/add/{id}/{type}', name: 'add_to_cart', defaults: ['type' => 'trottinette'], methods: ['GET', 'POST'])]
    public function add(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->add($id, $type);
        return $this->redirect($requete->headers->get('referer') ?? $this->generateUrl('cart'));
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $panier): Response
    {
        $panier->remove();
        return $this->redirectToRoute('nos_trottinettes');
    }

    #[Route('/cart/delete/{id}/{type}', name: 'delete_to_cart', defaults: ['type' => 'trottinette'])]
    public function delete(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->delete($id, $type);
        return $this->redirect($requete->headers->get('referer') ?? $this->generateUrl('cart'));
    }

    #[Route('/cart/decrease/{id}/{type}', name: 'decrease_to_cart', defaults: ['type' => 'trottinette'])]
    public function decrease(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->decrease($id, $type);
        return $this->redirect($requete->headers->get('referer') ?? $this->generateUrl('cart'));
    }

    #[Route('/cart/increase/{id}/{type}', name: 'increase_to_cart', defaults: ['type' => 'trottinette'])]
    public function increase(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->add($id, $type);
        return $this->redirect($requete->headers->get('referer') ?? $this->generateUrl('cart'));
    }

    #[Route('/cart/ajax/increase', name: 'ajax_increase_to_cart', methods: ['POST'])]
    public function ajaxIncrease(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cart->add($data['id'], $data['type']);
        return $this->json($this->getCartData($cart, $weightRepository));
    }

    #[Route('/cart/ajax/decrease', name: 'ajax_decrease_to_cart', methods: ['POST'])]
    public function ajaxDecrease(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cart->decrease($data['id'], $data['type']);
        return $this->json($this->getCartData($cart, $weightRepository));
    }

    #[Route('/cart/ajax/delete', name: 'ajax_delete_to_cart', methods: ['POST'])]
    public function ajaxDelete(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cart->delete($data['id'], $data['type']);
        return $this->json($this->getCartData($cart, $weightRepository));
    }

    private function getCartData(Cart $cart, WeightRepository $weightRepository): array
    {
        $full = $cart->getFull();
        $total = 0;
        $poids = 0;

        foreach ($full as $item) {
            $priceTTC = $item['product']->getPriceTtc();
            $total += $priceTTC * $item['quantity'];
            $poids += ($item['product']->getWeight() ?? 0) * $item['quantity'];
        }

        $poidsEntity = $weightRepository->findPriceByWeight($poids);
        $livraison = $poidsEntity ? $poidsEntity->getPrice() : 0;

        return [
            'items' => array_map(function ($item) {
                $priceTTC = $item['product']->getPriceTtc();
                return [
                    'id' => $item['product']->getId(),
                    'type' => $item['type'],
                    'name' => $item['product']->getName(),
                    'quantity' => $item['quantity'],
                    'price_unit_ttc' => $priceTTC,
                    'price_total_ttc' => $priceTTC * $item['quantity'],
                    'image' => $item['product']->getFirstIllustration() ?? 'default.jpg',
                ];
            }, $full),
            'total' => $total,
            'livraison' => $livraison,
            'grand_total' => $total + $livraison
        ];
    }
}
