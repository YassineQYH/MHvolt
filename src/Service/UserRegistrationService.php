<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRegistrationService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function register(User $user): string
    {
        // Vérifier si l’email existe déjà
        $existingUser = $this->entityManager->getRepository(User::class)
            ->findOneByEmail($user->getEmail());

        if ($existingUser) {
            return "L'email que vous avez renseigné existe déjà.";
        }

        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        // Persister en BDD
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return "Votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
    }
}
