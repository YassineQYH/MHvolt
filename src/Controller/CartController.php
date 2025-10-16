<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\User;
use App\Entity\Weight;
use App\Entity\Product;
use App\Form\RegisterType;
use App\Repository\WeightRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CartController extends BaseController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $encoder,
        Cart $cart,
        WeightRepository $weightRepository
    ): Response {

        // -------------------------------
        // 📦 Calcul du panier
        // -------------------------------
        $poid = 0.0;
        $quantity_product = 0;
        $totalLivraison = null;

        $cartItems = $cart->getFull();

        foreach ($cartItems as $element) {
            $weightObj = $element['product']->getWeight();
            $kg = $weightObj ? $weightObj->getKg() : 0;
            $poidAndQuantity = $kg * $element['quantity'];
            $quantity_product += $element['quantity'];
            $poid += $poidAndQuantity;
        }

        $weightEntity = $weightRepository->findByKgPrice($poid);
        $prix = $weightEntity ? $weightEntity->getPrice() : 0;

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('cart/index.html.twig', [
            'cart' => $cartItems,
            'poid' => $poid,
            'price' => $prix,
            'quantity_product' => $quantity_product,
            'totalLivraison' => $totalLivraison,
            'formregister' => $formregister->createView(), // ✅ nécessaire pour ton include
        ]);
    }

    private function fillPriceList(WeightRepository $weightRepository): array
    {
        $priceList = [];
        $weights = $weightRepository->findAll();

        foreach ($weights as $weight) {
            $priceList[(string)$weight->getKg()] = $weight->getPrice();
        }

        return $priceList;
    }

    #[Route('/cart/add/{id}/{type}', name: 'add_to_cart', defaults: ['type' => 'trottinette'], methods: ['GET', 'POST'])]
    public function add(Cart $cart, int $id, string $type, Request $request): Response
    {
        $cart->add($id, $type);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete/{id}/{type}', name: 'delete_to_cart', defaults: ['type' => 'trottinette'])]
    public function delete(Cart $cart, int $id, string $type, Request $request): Response
    {
        $cart->delete($id, $type);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}/{type}', name: 'decrease_to_cart', defaults: ['type' => 'trottinette'])]
    public function decrease(Cart $cart, int $id, string $type, Request $request): Response
    {
        $cart->decrease($id, $type);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/increase/{id}/{type}', name: 'increase_to_cart', defaults: ['type' => 'trottinette'])]
    public function increase(Cart $cart, int $id, string $type, Request $request): Response
    {
        $cart->add($id, $type);

        return $this->redirect($request->headers->get('referer'));
    }
}
