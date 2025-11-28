<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Trottinette;
use App\Entity\Illustration;
use App\Service\PromotionFinderService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TrottinetteController extends BaseController
{
    #[Route('/nos-trottinettes', name: 'nos_trottinettes')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        UserPasswordHasherInterface $encoder
    ): Response {
        // -------------------------------
        // ⚙️ Récupération et pagination
        // -------------------------------
        $query = $this->entityManager
            ->getRepository(Trottinette::class)
            ->createQueryBuilder('t')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('trottinette/index.html.twig', [
            'trottinettes' => $pagination,
            'formregister' => $formregister->createView(),
        ]);
    }

    #[Route('/trottinette/{slug}', name: 'trottinette_show')]
    public function show(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder,
        Cart $cartService, // 🛒 On injecte ton service Cart ici
        PromotionFinderService $promoFinder
    ): Response {
        // -------------------------------
        // 🛴 Récupération de la trottinette
        // -------------------------------
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        // -------------------------------
        // 🔗 Relations
        // -------------------------------
        $accessoires = array_map(fn($ta) => $ta->getAccessory(), $trottinette->getTrottinetteAccessories()->toArray());
        $illustrations = $trottinette->getIllustrations();


        $caracteristiques = $trottinette->getTrottinetteCaracteristiques();
        $sections = $trottinette->getDescriptionSections();

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        // -------------------------------------
        // 🔥 gestion promotion auto
        // -------------------------------------
        $promotion = $promoFinder->FindBestForProduct($trottinette);

        // 💰 Prix original
        $originalPrice = $trottinette->getPrice();

        // 💸 Prix réduit si promo dispo
        $promoPrice = $promotion ? $promoFinder->calculateDiscountedPrice($trottinette, $promotion) : null;


        // -------------------------------
        // 🛒 Panier via le service
        // -------------------------------
        $cart = $cartService->get(); // ou $cartService->getFull() si tu veux les objets complets

        // Trouver la promo à afficher sur la home (auto ou non)
        $homepagePromo = $promoFinder->findHomepagePromo();

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('trottinette/show.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'illustrations' => $illustrations,
            'caracteristiques' => $caracteristiques,
            'sections' => $sections,
            'formregister' => $formregister->createView(),
            'cart' => $cartService, // 💡 ici, on passe le service entier à Twig
            // 🔥 On envoie les infos au template
            'promotion' => $promotion,
            'originalPrice' => $originalPrice,
            'promoPrice' => $promoPrice,
            'homepagePromo' => $homepagePromo,
        ]);
    }

    #[Route('/trottinette/{slug}/accessoires', name: 'trottinette_accessoires')]
    public function showAccessoires(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder,
        PromotionFinderService $promoFinder,
    ): Response {
        // -------------------------------
        // 🛠️ Récupération de la trottinette
        // -------------------------------
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        // -------------------------------
        // 🎒 Récupération des accessoires liés
        // -------------------------------
        $accessoires = [];
        foreach ($trottinette->getTrottinetteAccessories() as $ta) {
            $accessoires[] = $ta->getAccessory();
        }

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        // Trouver la promo à afficher sur la home (auto ou non)
        $homepagePromo = $promoFinder->findHomepagePromo();

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('trottinette/show-all-access.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'formregister' => $formregister->createView(),
            'homepagePromo' => $homepagePromo,
        ]);
    }
}
