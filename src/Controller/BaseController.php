<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\UserRegistrationToken;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    protected function createRegisterForm(Request $request, UserPasswordHasherInterface $encoder): FormInterface
    {
        $user = new User();
        $formregister = $this->createForm(RegisterType::class, $user);
        $formregister->handleRequest($request);

        if ($formregister->isSubmitted() && $formregister->isValid()) {
            $email = $user->getEmail();
            $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($existingUser) {
                $this->addFlash('error', "L'adresse e-mail existe déjà.");
                return $formregister;
            }

            // Inscription sécurisée avec token d'activation
            $token = bin2hex(random_bytes(32));
            $hashedPassword = $encoder->hashPassword($user, $user->getPassword());

            $registration = new UserRegistrationToken();
            $registration->setEmail($email);
            $registration->setPasswordHash($hashedPassword);
            $registration->setFirstName($user->getFirstName() ?? 'Client');
            $registration->setLastName($user->getLastName() ?? '');
            $registration->setTel($user->getTel() ?? '');
            $registration->setToken($token);
            $registration->setExpiresAt(new \DateTimeImmutable('+24 hours'));

            $this->entityManager->persist($registration);
            $this->entityManager->flush();

            $verificationUrl = $this->generateUrl(
                'verify_account',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $mail = new Mail();
            $mail->send(
                $email,
                $user->getFirstName() ?? 'Client',
                "Confirmation de votre inscription - MHvolt",
                $this->renderView('emails/confirm_registration.html.twig', [
                    'firstName' => $user->getFirstName(),
                    'verificationUrl' => $verificationUrl
                ])
            );

            $this->addFlash('info-alert', "Un e-mail de confirmation vient de vous être envoyé pour activer votre compte.");
        }

        return $formregister;
    }
}
