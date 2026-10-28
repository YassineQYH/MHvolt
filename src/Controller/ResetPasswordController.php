<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request, Cart $panier): Response
    {
        $panierContent = $panier->getFull();

        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $email = $request->request->get('email') ?? $request->query->get('email');

        if ($email) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                // Nettoyer les anciennes demandes éventuelles de cet utilisateur
                $oldResets = $this->entityManager->getRepository(ResetPassword::class)->findBy(['user' => $user]);
                foreach ($oldResets as $oldReset) {
                    $this->entityManager->remove($oldReset);
                }

                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(bin2hex(random_bytes(32)));
                $resetPassword->setCreatedAt(new \DateTime());

                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                // Lien absolu sécurisé
                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $content = sprintf(
                    "Bonjour %s,<br><br>Vous avez demandé à réinitialiser votre mot de passe sur le site MHvolt.<br><br>Merci de cliquer sur le lien suivant (valide 1 heure) : <a href='%s'>Réinitialiser mon mot de passe</a>.",
                    htmlspecialchars($user->getFirstName() ?? 'Client'),
                    $url
                );

                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    ($user->getFirstName() ?? '') . ' ' . ($user->getLastName() ?? ''),
                    'Réinitialiser votre mot de passe sur MHvolt',
                    $content
                );

                $this->addFlash('notice', 'Un e-mail vient de vous être envoyé pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse e-mail est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig', [
            'panier' => $panierContent
        ]);
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(
        Request $request,
        string $token,
        UserPasswordHasherInterface $passwordHasher,
        Cart $panier
    ): Response {
        $panierContent = $panier->getFull();

        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)
            ->findOneBy(['token' => $token]);

        if (!$resetPassword) {
            $this->addFlash('notice', 'Lien de réinitialisation invalide.');
            return $this->redirectToRoute('reset_password');
        }

        // Vérification de l'expiration (1 heure) sans muter l'objet initial
        $createdAt = clone $resetPassword->getCreatedAt();
        // 3600 secondes = 1 heure
        if ((new \DateTime())->getTimestamp() - $resetPassword->getCreatedAt()->getTimestamp() > 3600) {
            $this->entityManager->remove($resetPassword);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Votre demande a expiré. Merci de renouveler la procédure.');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $resetPassword->getUser();
            $newPassword = $form->get('new_password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

            // Suppression du jeton pour empêcher toute réutilisation
            $this->entityManager->remove($resetPassword);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_home', ['login' => 1]);
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
            'panier' => $panierContent
        ]);
    }
}
