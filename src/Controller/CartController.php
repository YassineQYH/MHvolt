<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Weight;
use App\Entity\Promotion;
use App\Service\PromotionService;
use App\Repository\WeightRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CartController extends BaseController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(
        Request $requete,
        UserPasswordHasherInterface $encodeur,
        Cart $panier,
        WeightRepository $weightRepository,
        PromotionService $promotionService
    ): Response {

        $articlesPanier = $panier->getFull();

        $poids = 0.0;
        $quantite_produits = 0;

        foreach ($articlesPanier as $article) {
            $objetPoids = $article['product']->getWeight();
            $kg = $objetPoids ? $objetPoids->getKg() : 0;
            $poidsEtQuantite = $kg * $article['quantity'];
            $quantite_produits += $article['quantity'];
            $poids += $poidsEtQuantite;
        }

        $poidsEntity = $weightRepository->findByKgPrice($poids);
        $prixLivraison = $poidsEntity ? $poidsEntity->getPrice() : 0;

        // 🧍 Formulaire d’inscription
        $user = new User();
        $formregister = $this->createForm(\App\Form\RegisterType::class, $user, [
            'by_reference' => false
        ]);
        $formregister->handleRequest($requete);

        return $this->render('cart/index.html.twig', [
            'cart' => $articlesPanier,
            'cartObject' => $panier,
            'poid' => $poids,
            'price' => $prixLivraison,
            'quantity_product' => $quantite_produits,
            'totalLivraison' => $prixLivraison,
            'formregister' => $formregister->createView(),
            'promoService' => $promotionService,
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

        // 🛑 Vérification : une promotion automatique est-elle déjà applicable ?
        $allPromos = $promotionRepository->findAll();
        $autoPromo = $promotionService->getAutomaticPromotion($cart->getFull(), $allPromos);

        if ($autoPromo) {
            return new JsonResponse([
                'error' => "Une promotion automatique est déjà appliquée. Vous ne pouvez pas utiliser un code promo."
            ]);
        }

        $promo = $promotionRepository->findOneBy(['code' => $code]);

        if (!$promo || !$promo->canBeUsed()) {
            // Supprime le code promo stocké si invalide
            $cart->clearPromos();
            return new JsonResponse(['error' => 'Code promo invalide ou expiré.']);
        }

        // Calcul de la réduction avec le service dédié
        $discount = $cart->getReduction($promotionService, $promo);
        // 🔍 Vérification : si la promo ne s'applique à AUCUN article
        if ($discount <= 0) {
            // 👉 Je supprime toute promo stockée en session
            $cart->clearPromos();

            // 👉 Je renvoie une erreur spécifique
            return new JsonResponse([
                'error' => "Ce code promo ne s'applique pas à votre panier."
            ]);
        }

        // Total final = produits + livraison - réduction
        $totalTTC = array_reduce($cart->getFull(), fn($carry, $item) =>
            $carry + $item['product']->getPrice() * (1 + ($item['product']->getTva()?->getValue()/100 ?? 0)) * $item['quantity'],
            0
        );

        $totalAfterPromo = $totalTTC - $discount + $cart->getLivraisonPrice($weightRepository);

        // Stocke uniquement le code promo
        $cart->setPromoCode($code);

        return new JsonResponse([
            'discount' => $discount,
            'totalAfterPromo' => $totalAfterPromo
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
            $productWeight = $item['product']->getWeight();
            $kg = $productWeight ? $productWeight->getKg() : 0;
            $poids += $kg * $item['quantity'];
        }

        $poidsEntity = $weightRepository->findByKgPrice($poids);
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
