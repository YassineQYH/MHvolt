<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request, Cart $panier): Response
    {
        $panier = $panier->getFull();

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)
                ->findOneBy(['email' => $request->get('email')]);

            if ($user) {
                // Enregistrer la demande de reset password
                $resetPassword = new ResetPassword();
                $resetPassword->setUser($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTime());

                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                // Générer le lien de mise à jour du mot de passe
                $url = $this->generateUrl('update_password', [
                    'token' => $resetPassword->getToken()
                ]);

                $content = "Bonjour " . $user->getFirstname() . "</br>"
                    . "Vous avez demandé à réinitialiser votre mot de passe sur le site Hich-Trott.</br></br>"
                    . "Merci de bien vouloir cliquer sur le lien suivant pour "
                    . "<a href='https://hicham_trotinettes.test/" . $url . "'>mettre à jour votre mot de passe</a>.";

                $mail = new Mail();
                $mail->send(
                    $user->getEmail(),
                    $user->getFirstname() . ' ' . $user->getLastname(),
                    'Réinitialiser votre mot de passe sur Hich-Trott',
                    $content
                );

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig', [
            'panier' => $panier
        ]);
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(
        Request $request,
        string $token,
        UserPasswordHasherInterface $passwordHasher,
        Cart $panier
    ): Response {
        $panier = $panier->getFull();

        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)
            ->findOneBy(['token' => $token]);

        if (!$resetPassword) {
            return $this->redirectToRoute('reset_password');
        }

        $now = new DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('+1 hour')) {
            $this->addFlash('notice', 'Votre demande de modification de mot de passe a expiré. Merci de la renouveller.');
            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('new_password')->getData();
            $hashedPassword = $passwordHasher->hashPassword($resetPassword->getUser(), $newPassword);
            $resetPassword->getUser()->setPassword($hashedPassword);

            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
            'panier' => $panier
        ]);
    }
}
