<?php

namespace App\Classe;

use App\Entity\Trottinette;
use App\Entity\Accessory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{
    private EntityManagerInterface $entityManager;
    private ?SessionInterface $session;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;

        // Récupère la session si elle existe
        $this->session = $requestStack->getSession();

        // Démarre la session si elle existe mais n'est pas encore active
        if ($this->session && !$this->session->isStarted()) {
            $this->session->start();
        }
    }

    // ------------------- Ajout au panier -------------------
    public function add(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);

        // On distingue les types (trottinette ou accessoire)
        $cart[$type][$id] = ($cart[$type][$id] ?? 0) + 1;

        $this->session->set('cart', $cart);
    }

    // ------------------- Récupère le panier -------------------
    public function get(): array
    {
        return $this->session ? $this->session->get('cart', []) : [];
    }

    // ------------------- Supprime tout le panier -------------------
    public function remove(): void
    {
        if ($this->session) {
            $this->session->remove('cart');
        }
    }

    // ------------------- Supprime un élément -------------------
    public function delete(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        unset($cart[$type][$id]);
        $this->session->set('cart', $cart);
    }

    // ------------------- Diminue la quantité -------------------
    public function decrease(int $id, string $type = 'trottinette'): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        if (!empty($cart[$type][$id])) {
            if ($cart[$type][$id] > 1) {
                $cart[$type][$id]--;
            } else {
                unset($cart[$type][$id]);
            }
            $this->session->set('cart', $cart);
        }
    }

    // ------------------- Récupère le panier complet -------------------
    public function getFull(): array
    {
        $cartComplete = [];
        if (!$this->session) return $cartComplete;

        $cart = $this->get();

        // ---- Trottinettes ----
        if (!empty($cart['trottinette'])) {
            foreach ($cart['trottinette'] as $id => $quantity) {
                $product = $this->entityManager->getRepository(Trottinette::class)->find($id);
                if (!$product) {
                    $this->delete($id, 'trottinette');
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => 'trottinette'
                ];
            }
        }

        // ---- Accessoires ----
        if (!empty($cart['accessory'])) {
            foreach ($cart['accessory'] as $id => $quantity) {
                $product = $this->entityManager->getRepository(Accessory::class)->find($id);
                if (!$product) {
                    $this->delete($id, 'accessory');
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => 'accessory'
                ];
            }
        }

        return $cartComplete;
    }

    // ------------------- Poids total du panier -------------------
    public function getTotalWeight(): float
    {
        $totalWeight = 0.0;
        foreach ($this->getFull() as $element) {
            $weightObj = $element['product']->getWeight();
            if ($weightObj) {
                $totalWeight += $weightObj->getKg() * $element['quantity'];
            }
        }
        return $totalWeight;
    }
}
