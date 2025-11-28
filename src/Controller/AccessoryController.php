<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Accessory;
use App\Entity\Illustration;
use App\Service\PromotionFinderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccessoryController extends BaseController
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/accessoires', name: 'accessoires')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $accessories = $this->entityManager->getRepository(Accessory::class)->findAll();

        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/show.html.twig', [
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
        PromotionFinderService $promoFinder
    ): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        // 🔹 Récupère toutes les illustrations liées à cet accessoire via Product
        $illustrations = $this->entityManager->getRepository(Illustration::class)
            ->findBy(['product' => $accessory]);

        $formregister = $this->createRegisterForm($request, $encoder);

        // -------------------------------------
        // 🔥 gestion promotion auto
        // -------------------------------------
        $promotion = $promoFinder->FindBestForProduct($accessory);

        // 💰 Prix original
        $originalPrice = $accessory->getPrice();

        // 💸 Prix réduit si promo dispo
        $promoPrice = $promotion ? $promoFinder->calculateDiscountedPrice($accessory, $promotion) : null;

        // Trouver la promo à afficher sur la home (auto ou non)
        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('accessoires/show.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations,
            'formregister' => $formregister->createView(),
            'cart' => $cartService,
            // 🔥 On envoie les infos au template
            'promotion' => $promotion,
            'originalPrice' => $originalPrice,
            'promoPrice' => $promoPrice,
            'homepagePromo' => $homepagePromo,
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

        $trottinettes = [];
        foreach ($accessory->getTrottinetteAccessories() as $pivot) {
            $trottinettes[] = $pivot->getTrottinette();
        }

        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/show-all-trott.html.twig', [
            'accessory' => $accessory,
            'trottinettes' => $trottinettes,
            'formregister' => $formregister->createView(),
        ]);
    }
}
