<?php

namespace App\Controller;

use App\Entity\Accessory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccessoryController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/accessoires', name: 'app_accessoires')]
    public function index(): Response
    {
        $accessories = $this->entityManager->getRepository(Accessory::class)->findAll();

        return $this->render('access/show.html.twig', [
            'accessories' => $accessories
        ]);
    }

    #[Route('/accessoire/{slug}', name: 'accessoire')]
    public function show(string $slug): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        $illustrations = $accessory->getIllustrationaccess();

        return $this->render('access/single_access.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations
        ]);
    }

    #[Route('/accessoire/{slug}/trottinettes', name: 'app_accessoire_trottinettes')]
    public function showTrottinettes(string $slug): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        $trottinettes = $accessory->getTrottinettes(); // relation ManyToMany

        return $this->render('access/show-all-trott.html.twig', [
            'accessory' => $accessory,
            'trottinettes' => $trottinettes
        ]);
    }
}
