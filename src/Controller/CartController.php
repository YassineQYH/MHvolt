<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Weight;
use App\Form\OrderType;
use App\Entity\Promotion;
use App\Service\PromotionService;
use App\Repository\WeightRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryAccessoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\Order;
use App\Service\PdfService;
use App\Entity\OrderDetails;

class CartController extends BaseController
{
    private readonly PdfService $pdfService;

    public function __construct(EntityManagerInterface $entityManager, PdfService $pdfService)
    {
        parent::__construct($entityManager); // ⚠️ appelle le constructeur de BaseController
        $this->pdfService = $pdfService;
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
        $user = $this->getUser();

        // ⚠️ Vérification utilisateur connecté
        if (!$user) {
            $this->addFlash('info-alert', 'Vous devez être connecté pour valider votre panier.');

            // On redirige vers la page home avec un paramètre pour afficher le modal
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }


        /** @var User $user */
        $user = $this->getUser();

        // ⚠️ Vérification adresse
        if ($user->getAddresses()->isEmpty()) {
            $this->addFlash('info-alert', 'Veuillez ajouter une adresse avant de passer commande.');
            return $this->redirectToRoute('account_address_add');
        }

        $formOrder = $this->createForm(OrderType::class, null, ['user' => $user]);
        $formOrder->handleRequest($request);

        if ($formOrder->isSubmitted()) {
            dump($formOrder->getData());
            dump($formOrder->get('addresses')->getData());
        }

        $articlesPanier = $cart->getFull();

        // Calcul poids total
        $poidsTotal = 0.0;
        foreach ($articlesPanier as $element) {
            $produit = $element['product'];
            $quantite = (int) $element['quantity'];

            // ⚠️ garder l'entité Weight pour Twig
            $poids = $produit->getWeight() ?? 0.0; // directement le float
            $poidsTotal += $poids * $quantite;
        }

        // ⚠️ Ne PAS faire ->getPrice() ici si tu veux afficher kg dans Twig
        $poidsTarif = $weightRepository->findPriceByWeight($poidsTotal); // entité Weight
        $prixLivraison = $poidsTarif ? $poidsTarif->getPrice() : 0.0;


        // Promo
        $promoDiscount = method_exists($cart, 'getReduction') ? (float) $cart->getReduction($promotionService) : 0.0;
        $promoCode = method_exists($cart, 'getPromoCode') ? $cart->getPromoCode() : null;

        $categories = $categoryAccessoryRepository->findAll();
        $allPromotions = $promoRepo->findAll();

        // 🔹 Plus de redirect : le formulaire POST gère directement la soumission vers order_recap
        // if ($formOrder->isSubmitted() && $formOrder->isValid()) {
        //     return $this->redirectToRoute('order_recap', [
        //         'addressId' => $formOrder->get('addresses')->getData()->getId()
        //     ]);
        // }

        return $this->render('cart/index.html.twig', [
            'cart' => $articlesPanier,
            'cartObject' => $cart,
            'price' => $prixLivraison,
            'promoDiscount' => $promoDiscount,
            'promoCode' => $promoCode,
            'form_order' => $formOrder->createView(),
            'categories' => $categories,
            'allPromotions' => $allPromotions,
            'promoService' => $promotionService
        ]);
    }


    #[Route('/cart/apply-promo', name: 'cart_apply_promo', methods: ['POST'])]
    public function appliquerCodePromoAjax(
        Request $request,
        Cart $cart,
        PromotionRepository $promotionRepository,
        WeightRepository $weightRepository,
        PromotionService $promotionService // <-- injecté
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $code = trim($data['promo_code'] ?? '');

        if (!$code) {
            return new JsonResponse(['error' => 'Veuillez saisir un code promo.']);
        }

        $promo = $promotionRepository->findOneBy(['code' => $code]);

        // ❌ Code inexistant
        if (!$promo) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => 'Ce code promo est invalide.'
            ]);
        }

        // ❌ Code expiré
        if ($promo->isExpired()) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => 'Ce code promo est expiré.'
            ]);
        }

        // ❌ Code épuisé (quantity atteinte) ← TON CAS
        if (!$promo->isAvailable()) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => 'Ce code promo n’est plus disponible.'
            ]);
        }

        // ❌ Pas encore actif
        if (!$promo->isActive()) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => 'Ce code promo n’est pas encore actif.'
            ]);
        }

        // ❌ Mal configuré
        if (!$promo->isDiscountValid()) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => 'Ce code promo est invalide.'
            ]);
        }


        // ✅ Comparaison entre promo automatique et code promo : la plus avantageuse gagne

        $allPromos = $promotionRepository->findAll();

        // Réduction provenant d'une éventuelle promo automatique
        $autoDiscount = $cart->getDiscountTTC($promotionService, $allPromos);

        // 🔹 Réduction provenant du code promo saisi
        $codeDiscount = $cart->getReduction($promotionService, $promo);

        // 🔍 Vérification : si la promo ne s'applique à AUCUN article
        if ($codeDiscount <= 0) {
            $cart->clearPromos();
            return new JsonResponse([
                'error' => "Ce code promo ne s'applique à aucun article de votre panier."
            ]);
        }

        // 🔹 Réduction provenant d'une éventuelle promo automatique
        $allPromos = $promotionRepository->findAll();
        $autoDiscount = $cart->getDiscountTTC($promotionService, $allPromos);

        // ❌ Si le code est moins ou égal à l’auto → on refuse
        if ($autoDiscount > $codeDiscount) {
            return new JsonResponse([
                'error' => "Une promotion automatique plus avantageuse est déjà appliquée."
            ]);
        }

        // ✅ Sinon, le code est meilleur → on continue normalement
        $discount = $codeDiscount;

        // Stocke uniquement le code promo
        $cart->setPromoCode($code);

        // Total final = produits + livraison - réduction
        $totalTTC = array_reduce($cart->getFull(), fn($carry, $item) =>
            $carry + $item['product']->getPrice()
                * (1 + ($item['product']->getTva()?->getValue()/100 ?? 0))
                * $item['quantity'],
            0
        );

        $totalAfterPromo = $totalTTC - $discount + $cart->getLivraisonPrice($weightRepository);

        return new JsonResponse([
            'discount' => $discount,
            'totalAfterPromo' => $totalAfterPromo,
            'reload' => true
        ]);

    }


    #[Route('/cart/add/{id}/{type}', name: 'add_to_cart', defaults: ['type' => 'trottinette'], methods: ['GET', 'POST'])]
    public function add(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->add($id, $type);
        return $this->redirect($requete->headers->get('referer'));
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $panier): Response
    {
        $panier->remove();
        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete/{id}/{type}', name: 'delete_to_cart', defaults: ['type' => 'trottinette'])]
    public function delete(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->delete($id, $type);
        return $this->redirect($requete->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}/{type}', name: 'decrease_to_cart', defaults: ['type' => 'trottinette'])]
    public function decrease(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->decrease($id, $type);
        return $this->redirect($requete->headers->get('referer'));
    }

    #[Route('/cart/increase/{id}/{type}', name: 'increase_to_cart', defaults: ['type' => 'trottinette'])]
    public function increase(Cart $panier, int $id, string $type, Request $requete): Response
    {
        $panier->add($id, $type);
        return $this->redirect($requete->headers->get('referer'));
    }

        // -------------------------------------------
    // 🚀 ROUTES AJAX POUR LE MINI PANIER (sans reload)
    // -------------------------------------------

    #[Route('/cart/ajax/increase', name: 'ajax_increase_to_cart', methods: ['POST'])]
    public function ajaxIncrease(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        // Récupère l'id et le type envoyés en JSON
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $type = $data['type'];

        // Augmente la quantité du produit
        $cart->add($id, $type);

        // Retourne tout le panier mis à jour (quantités, prix…)
        return $this->json($this->getCartData($cart, $weightRepository));
    }

    #[Route('/cart/ajax/decrease', name: 'ajax_decrease_to_cart', methods: ['POST'])]
    public function ajaxDecrease(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $type = $data['type'];

        // Diminue la quantité
        $cart->decrease($id, $type);

        return $this->json($this->getCartData($cart, $weightRepository));
    }

    #[Route('/cart/ajax/delete', name: 'ajax_delete_to_cart', methods: ['POST'])]
    public function ajaxDelete(Request $request, Cart $cart, WeightRepository $weightRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $type = $data['type'];

        // Supprime complètement la ligne du panier
        $cart->delete($id, $type);

        return $this->json($this->getCartData($cart, $weightRepository));
    }

    // ------------------------------------------------------------
    // 🧠 Fonction interne : construit les données à renvoyer en AJAX
    // ------------------------------------------------------------
    private function getCartData(Cart $cart, WeightRepository $weightRepository): array
    {
        $full = $cart->getFull();

        $total = 0;
        $poids = 0;

        foreach ($full as $item) {

            // Prix TTC
            $priceHT = $item['product']->getPrice();
            $tva = $item['product']->getTva() ? $item['product']->getTva()->getValue() / 100 : 0;
            $priceTTC = $priceHT * (1 + $tva);

            $total += $priceTTC * $item['quantity'];

            // Poids pour recalcul livraison
            $poids += ($item['product']->getWeight() ?? 0) * $item['quantity'];
        }

        $poidsEntity = $weightRepository->findPriceByWeight($poids);
        $livraison = $poidsEntity ? $poidsEntity->getPrice() : 0;

        return [
            'items' => array_map(function ($item) {

                // Calcule le prix TTC par ligne
                $priceHT = $item['product']->getPrice();
                $tva = $item['product']->getTva() ? $item['product']->getTva()->getValue() / 100 : 0;
                $priceTTC = $priceHT * (1 + $tva);

                // Récupère la première illustration ou image par défaut
                $illustration = $item['product']->getIllustrations()->first();
                $image = $illustration ? $illustration->getImage() : 'default.jpg';

                return [
                    'id' => $item['product']->getId(),
                    'type' => $item['type'],            // 🔥 je garde ton type (trottinette/accessoire)
                    'name' => $item['product']->getName(),
                    'quantity' => $item['quantity'],
                    'price_unit_ttc' => $priceTTC,
                    'price_total_ttc' => $priceTTC * $item['quantity'],
                    'image' => $image,
                ];
            }, $full),

            'total' => $total,
            'livraison' => $livraison,
            'grand_total' => $total + $livraison
        ];
    }

}
