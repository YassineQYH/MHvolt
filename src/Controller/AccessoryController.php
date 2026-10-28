<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Accessory;
use App\Service\PromotionService;
use App\Service\PromotionFinderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccessoryController extends BaseController
{
    #[Route('/accessoires', name: 'accessoires')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $accessories = $this->entityManager->getRepository(Accessory::class)
            ->findBy([], ['id' => 'DESC']);

        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/index.html.twig', [
            'accessories' => $accessories,
            'formregister' => $formregister->createView(),
        ]);
    }

    #[Route('/accessoire/{slug}', name: 'accessory_show')]
    public function show(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder,
        Cart $cartService,
        PromotionFinderService $promoFinder,
        PromotionService $promotionService
    ): Response {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        $illustrations = $accessory->getIllustrations();
        $formregister = $this->createRegisterForm($request, $encoder);

        $promotion = $promoFinder->FindBestForProduct($accessory);
        $originalPrice = $accessory->getPrice();
        $promoPrice = $promotion ? $promoFinder->calculateDiscountedPrice($accessory, $promotion) : null;
        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('accessoires/show.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations,
            'formregister' => $formregister->createView(),
            'cart' => $cartService,
            'promotion' => $promotion,
            'originalPrice' => $originalPrice,
            'promoPrice' => $promoPrice,
            'homepagePromo' => $homepagePromo,
            'promoService' => $promotionService,
        ]);
    }

    #[Route('/accessoire/{slug}/trottinettes', name: 'accessoire_trottinettes')]
    public function showTrottinettes(string $slug, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        // 🔥 Direct via la collection ManyToMany inverse
        $trottinettes = $accessory->getTrottinettes();

        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/show-all-trott.html.twig', [
            'accessory' => $accessory,
            'trottinettes' => $trottinettes,
            'formregister' => $formregister->createView(),
        ]);
    }
}
