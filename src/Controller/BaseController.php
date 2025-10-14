<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BaseController extends AbstractController
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Crée et traite le formulaire d'inscription pour les pages publiques
     */
    protected function createRegisterForm(Request $request, UserPasswordHasherInterface $encoder): FormInterface
    {
        $user = new User();
        $formregister = $this->createForm(RegisterType::class, $user);
        $formregister->handleRequest($request);

        if ($formregister->isSubmitted() && $formregister->isValid()) {
            $user = $formregister->getData();

            // Vérifie si l'email existe déjà
            $search_email = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', "Votre compte a été créé avec succès !");
            } else {
                $this->addFlash('error', "L'adresse e-mail existe déjà.");
            }
        }

        return $formregister;
    }
}
