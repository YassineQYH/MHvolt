<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use App\Entity\Weight;
use App\Repository\CategoryRepository;
use App\Repository\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/mon-panier', name: 'cart')]
    public function index(
        Cart $cart,
        CategoryRepository $categoryRepository,
        WeightRepository $weightRepository
    ): Response {
        $categories = $categoryRepository->findAll();

        $poid = 0.0;
        $quantity_product = 0;
        $totalLivraison = null;

        $cartItems = $cart->getFull();

        foreach ($cartItems as $element) {
            $poidAndQuantity = $element['product']->getWeight()->getKg() * $element['quantity'];
            $quantity_product += $element['quantity'];
            $poid += $poidAndQuantity;
        }

        $weightEntity = $weightRepository->findByKgPrice($poid);
        $prix = $weightEntity ? $weightEntity->getPrice() : 0;

        // Génération du tableau des poids négatifs
        $weight_negatif = [];
        for ($x = 0.01; $x < 1; $x += 0.01) {
            $weight_negatif[] = $x;
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartItems,
            'categories' => $categories,
            'poid' => $poid,
            'price' => $prix,
            'quantity_product' => $quantity_product,
            'totalLivraison' => $totalLivraison,
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

    #[Route('/cart/add/{id}', name: 'add_to_cart', methods: ['GET', 'POST'])]
    public function add(Cart $cart, int $id, Request $request): Response
    {
        $cart->add($id);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, int $id, Request $request): Response
    {
        $cart->delete($id);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, int $id, Request $request): Response
    {
        $cart->decrease($id);

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/increase/{id}', name: 'increase_to_cart')]
    public function increase(Cart $cart, int $id, Request $request): Response
    {
        $cart->add($id);

        return $this->redirect($request->headers->get('referer'));
    }
}
