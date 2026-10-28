<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Trottinette;
use App\Service\PromotionFinderService;
use App\Service\PromotionService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TrottinetteController extends BaseController
{
    #[Route('/nos-trottinettes', name: 'nos_trottinettes')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        UserPasswordHasherInterface $encoder
    ): Response {
        $query = $this->entityManager
            ->getRepository(Trottinette::class)
            ->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        $formregister = $this->createRegisterForm($request, $encoder);

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
        Cart $cartService,
        PromotionFinderService $promoFinder,
        PromotionService $promotionService
    ): Response {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        // Collections directes
        $accessoires = $trottinette->getAccessories();
        $illustrations = $trottinette->getIllustrations();
        $sections = $trottinette->getDescriptionSections();
        $specifications = $trottinette->getSpecifications();

        $formregister = $this->createRegisterForm($request, $encoder);

        $promotion = $promoFinder->findBestForProduct($trottinette);
        $originalPrice = $trottinette->getPrice();
        $promoPrice = $promotion ? $promoFinder->calculateDiscountedPrice($trottinette, $promotion) : null;

        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('trottinette/show.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'illustrations' => $illustrations,
            'sections' => $sections,
            'specifications' => $specifications,
            'formregister' => $formregister->createView(),
            'cart' => $cartService,
            'promotion' => $promotion,
            'originalPrice' => $originalPrice,
            'promoPrice' => $promoPrice,
            'homepagePromo' => $homepagePromo,
            'promoService' => $promotionService,
        ]);
    }

    #[Route('/trottinette/{slug}/accessoires', name: 'trottinette_accessoires')]
    public function showAccessoires(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder,
        PromotionFinderService $promoFinder,
        PromotionService $promotionService
    ): Response {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        $accessoires = $trottinette->getAccessories();
        $formregister = $this->createRegisterForm($request, $encoder);
        $homepagePromo = $promoFinder->findHomepagePromo();

        return $this->render('trottinette/show-all-access.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'formregister' => $formregister->createView(),
            'homepagePromo' => $homepagePromo,
            'promoService' => $promotionService,
        ]);
    }
}
