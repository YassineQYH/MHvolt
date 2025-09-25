<?php

namespace App\Controller;

use App\Entity\Trottinette;
use App\Entity\Accessory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrottinetteController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-trottinettes', name: 'app_trottinettes')]
    public function index(): Response
    {
        $trottinettes = $this->entityManager->getRepository(Trottinette::class)->findAll();

        return $this->render('trott/show.html.twig', [
            'trotinettes' => $trottinettes
        ]);
    }

    #[Route('/trottinette/{slug}', name: 'app_trottinette_show')]
    public function show(string $slug): Response
    {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        $accessoires = $trottinette->getAccessories(); // relation ManyToMany

        return $this->render('trot/single_trott.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires
        ]);
    }

    #[Route('/trottinette/{slug}/accessoires', name: 'app_trottinette_accessoires')]
    public function showAccessoires(string $slug): Response
    {
        $trottinette = $this->entityManager->getRepository(Trottinette::class)
            ->findOneBy(['slug' => $slug]);

        if (!$trottinette) {
            throw $this->createNotFoundException('Cette trottinette n’existe pas.');
        }

        $accessoires = $trottinette->getAccessories();

        return $this->render('trottinette/show-all-access.html.twig', [
            'trottinette' => $trottinette,
            'accessoires' => $accessoires
        ]);
    }
}
