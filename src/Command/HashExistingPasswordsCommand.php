<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:hash-existing-passwords',
    description: 'Hache tous les mots de passe non hachés existants dans la base de données (option --dry-run pour simuler).'
)]
class HashExistingPasswordsCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Si présent, n’effectue aucune modification en base et affiche ce qui serait modifié.'
            )
            ->setHelp(<<<'HELP'
Ce commande parcourt tous les utilisateurs et hache les mots de passe qui
semble ne pas être hachés. Utilisez --dry-run pour simuler sans modifier la BDD.
HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dryRun = (bool)$input->getOption('dry-run');

        $userRepo = $this->entityManager->getRepository(User::class);
        $users = $userRepo->findAll();

        if (count($users) === 0) {
            $output->writeln('<comment>Aucun utilisateur trouvé.</comment>');
            return Command::SUCCESS;
        }

        $output->writeln(sprintf('<info>Traitement de %d utilisateur(s)...</info>', count($users)));
        $toUpdate = 0;

        foreach ($users as $user) {
            // On récupère le mot de passe actuel stocké
            $current = $user->getPassword();

            // Ignore les mots de passe vides (sécurité)
            if (empty($current)) {
                $output->writeln(sprintf(' - <comment>%s</comment> : mot de passe vide, ignoré.', $user->getEmail() ?? 'user#' . $user->getId()));
                continue;
            }

            // Détecte si le mot de passe semble déjà être un hachage (bcrypt / argon2 / autres formats courants)
            // bcrypt: $2y$ / $2a$  | argon2: $argon2i$ / $argon2id$
            if (preg_match('/^\$(2y|2a)\$|^\$argon2i\$|^\$argon2id\$/i', $current)) {
                $output->writeln(sprintf(' - %s : déjà haché, OK.', $user->getEmail() ?? 'user#' . $user->getId()));
                continue;
            }

            // Si on arrive ici, le mot de passe semble être en clair → on va le hacher
            $toUpdate++;
            $output->writeln(sprintf(' - %s : mot de passe en clair détecté.', $user->getEmail() ?? 'user#' . $user->getId()));

            if (!$dryRun) {
                // Hash et applique
                $hashed = $this->passwordHasher->hashPassword($user, $current);
                $user->setPassword($hashed);
                // On ne flush pas ici à chaque itération pour perf -> flush après la boucle
            } else {
                $output->writeln('   (dry-run) → ne modifie pas la BDD.');
            }
        }

        if ($toUpdate === 0) {
            $output->writeln('<info>Aucun mot de passe à mettre à jour.</info>');
            return Command::SUCCESS;
        }

        if (!$dryRun) {
            // Persiste en une seule fois
            $this->entityManager->flush();
            $output->writeln(sprintf('<info>✅ %d mot(s) de passe mis à jour et hachés.</info>', $toUpdate));
        } else {
            $output->writeln(sprintf('<comment>--dry-run : %d mot(s) auraient été modifiés.</comment>', $toUpdate));
        }

        return Command::SUCCESS;
    }
}
