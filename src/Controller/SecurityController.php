<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * 🔒 Déconnexion utilisateur
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode est interceptée par le firewall de sécurité.');
    }

    /**
     * 🧍‍♂️ Inscription utilisateur
     */
    #[Route(path: '/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        AuthenticationUtils $authenticationUtils
    ): Response {
        // Partie login (erreurs et dernier username)
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Partie inscription
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $existingUser = $this->entityManager
                ->getRepository(User::class)
                ->findOneByEmail($user->getEmail());

            if ($form->isValid() && !$existingUser) {
                // Hachage du mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashedPassword);

                // Persistance utilisateur
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Message de succès
                $this->addFlash('register_success', "✅ Votre inscription s'est bien déroulée. Vous pouvez maintenant vous connecter.");
            } else {
                if ($existingUser) {
                    // Email déjà utilisé
                    $this->addFlash('info-alert', "⚠️ L'adresse e-mail est déjà utilisée.");
                } else {
                    // Formulaire invalide
                    $this->addFlash('info-alert', "⚠️ L’inscription n’a pas pu aboutir. Veuillez vérifier vos informations.");
                }
            }
        }


        /* if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('register_error', '⚠️ L’inscription n’a pas pu aboutir. Veuillez vérifier vos informations.');
        } */

        return $this->render('register/index.html.twig', [
            'formregister' => $form->createView(),
            'notification' => $notification,
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * 🔑 Connexion API
     */
    #[Route(path: '/api/login', name: 'api_login')]
    public function apiLogin(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        return $this->json([
            'email' => $user?->getEmail(),
            'password' => $user?->getPassword(),
        ]);
    }

    /**
     * 🧾 Enregistrement API (exemple d’API d’inscription)
     */
    #[Route(path: '/api/register', name: 'api_register')]
    public function apiRegister(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user = $this->getUser();

        return $this->json([
            'email' => $user?->getEmail(),
            'lastname' => $user?->getLastname(),
            'firstname' => $user?->getFirstname(),
            'phone' => $user?->getTel(),
            'password' => $user?->getPassword(),
        ]);
    }
}
