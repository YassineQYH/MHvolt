<?php

namespace App\Controller;

use App\Entity\Accessory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/show.html.twig', [
            'accessories' => $accessories,
            'formregister' => $formregister->createView(), // nécessaire pour ton include
        ]);
    }

    #[Route('/accessoire/{slug}', name: 'accessory_show')]
    public function show(string $slug, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $accessory = $this->entityManager->getRepository(Accessory::class)
            ->findOneBy(['slug' => $slug]);

        if (!$accessory) {
            throw $this->createNotFoundException('Cet accessoire n’existe pas.');
        }

        $illustrations = $accessory->getIllustrationaccess();

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/single_access.html.twig', [
            'accessory' => $accessory,
            'illustrations' => $illustrations,
            'formregister' => $formregister->createView(),
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

        // -------------------------------
        // 🧍 Formulaire d’inscription
        // -------------------------------
        $formregister = $this->createRegisterForm($request, $encoder);

        return $this->render('accessoires/show-all-trott.html.twig', [
            'accessory' => $accessory,
            'trottinettes' => $trottinettes,
            'formregister' => $formregister->createView(),
        ]);
    }
}
