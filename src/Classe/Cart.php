<?php

namespace App\Classe;

use App\Entity\Trottinette;
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

    public function add(int $id): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        $cart[$id] = ($cart[$id] ?? 0) + 1;
        $this->session->set('cart', $cart);
    }

    public function get(): array
    {
        return $this->session ? $this->session->get('cart', []) : [];
    }

    public function remove(): void
    {
        if ($this->session) {
            $this->session->remove('cart');
        }
    }

    public function delete(int $id): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        $this->session->set('cart', $cart);
    }

    public function decrease(int $id): void
    {
        if (!$this->session) return;

        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
            $this->session->set('cart', $cart);
        }
    }

    public function getFull(): array
    {
        $cartComplete = [];
        if (!$this->session) return $cartComplete;

        foreach ($this->get() as $id => $quantity) {
            $product = $this->entityManager->getRepository(Trottinette::class)->find($id);
            if (!$product) {
                $this->delete($id);
                continue;
            }

            $cartComplete[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        return $cartComplete;
    }
}
