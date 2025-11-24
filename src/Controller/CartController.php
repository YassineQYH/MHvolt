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
        WeightRepository $weightRepository
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
        ]);
    }

    #[Route('/cart/apply-promo', name: 'cart_apply_promo', methods: ['POST'])]
    public function appliquerCodePromoAjax(
        Request $request,
        Cart $cart,
        PromotionRepository $promotionRepository,
        WeightRepository $weightRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $code = trim($data['promo_code'] ?? '');

        if (!$code) {
            return new JsonResponse(['error' => 'Veuillez saisir un code promo.']);
        }

        $promo = $promotionRepository->findOneBy(['code' => $code]);

        if (!$promo || !$promo->canBeUsed()) {
            // Supprime le code promo stocké si invalide
            $cart->setReduction(0);
            $cart->setPromoCode(null);
            return new JsonResponse(['error' => 'Code promo invalide ou expiré.']);
        }

        // Calcul du total TTC uniquement sur les produits
        $totalTTC = 0;
        foreach ($cart->getFull() as $item) {
            $prixHT = $item['product']->getPrice();
            $tvaRate = $item['product']->getTva() ? $item['product']->getTva()->getValue() / 100 : 0;
            $totalTTC += $prixHT * (1 + $tvaRate) * $item['quantity'];
        }

        // Calcul de la réduction uniquement sur les produits
        $discount = $promo->getDiscountPercent() !== null
            ? $totalTTC * ($promo->getDiscountPercent() / 100)
            : $promo->getDiscountAmount();

        // Total final = produits après réduction + livraison
        $totalAfterPromo = $totalTTC - $discount + $cart->getLivraisonPrice($weightRepository);

        // Stocke la réduction et le code promo pour le paiement final
        $cart->setReduction($discount);
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
}
