<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            // Fonction Twig `cart()` pour récupérer le panier partout
            new TwigFunction('cart', [CartRuntime::class, 'getCart']),
        ];
    }
}
