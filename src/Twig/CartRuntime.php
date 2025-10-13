<?php

namespace App\Twig;

use App\Classe\Cart;
use Twig\Extension\RuntimeExtensionInterface;

class CartRuntime implements RuntimeExtensionInterface
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Retourne le panier complet si la session existe, sinon tableau vide
     */
    public function getCart(): array
    {
        try {
            return $this->cart->getFull();
        } catch (\Exception $e) {
            // Si la session n'existe pas, on retourne un tableau vide
            return [];
        }
    }
}
