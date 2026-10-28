<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Entity\UserRegistrationToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Intercepté par le firewall.');
    }

    #[Route('/verify-account/{token}', name: 'verify_account')]
    public function verify(string $token): Response
    {
        $registration = $this->entityManager
            ->getRepository(UserRegistrationToken::class)
            ->findOneBy(['token' => $token]);

        if (!$registration || $registration->getExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('error', 'Lien invalide ou expiré.');
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $user->setEmail($registration->getEmail());
        $user->setPassword($registration->getPasswordHash());
        $user->setFirstName($registration->getFirstName());
        $user->setLastName($registration->getLastName());
        $user->setTel($registration->getTel());
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->remove($registration);
        $this->entityManager->flush();

        $this->addFlash('info-alert', 'Compte activé avec succès 🎉 Vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/set-password/{token}', name: 'app_set_password')]
    public function setPassword(
        Request $request,
        string $token,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if (!$resetPassword || $resetPassword->getCreatedAt() < new \DateTime('-2 hours')) {
            $this->addFlash('error', 'Lien de réinitialisation invalide ou expiré.');
            return $this->redirectToRoute('app_home');
        }

        $user = $resetPassword->getUser();

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe',
                'attr' => ['placeholder' => 'Saisissez votre nouveau mot de passe']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

            $this->entityManager->remove($resetPassword);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès !');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('security/set_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
