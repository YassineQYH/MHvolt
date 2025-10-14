<?php

namespace App\Controller;

use App\Entity\Trottinette;
use App\Entity\Illustration;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
            'formregister' => $formregister->createView(), // ✅ pour ton include
        ]);
    }

    #[Route('/trottinette/{slug}', name: 'trottinette_show')]
    public function show(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder
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
        // 🔗 Relations : accessoires & illustrations
        // -------------------------------
        $accessoires = $trottinette->getAccessories(); // ManyToMany
        $illustrations = $this->entityManager
            ->getRepository(Illustration::class)
            ->findByTrottinette($trottinette);

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('trottinette/show.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'illustrations' => $illustrations,
            'formregister' => $formregister->createView(), // ✅
        ]);
    }

    #[Route('/trottinette/{slug}/accessoires', name: 'trottinette_accessoires')]
    public function showAccessoires(
        string $slug,
        Request $request,
        UserPasswordHasherInterface $encoder
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

        // -------------------------------
        // ⚙️ Rendu du template
        // -------------------------------
        return $this->render('trottinette/show-all-access.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires,
            'formregister' => $formregister->createView(), // ✅
        ]);
    }
}
