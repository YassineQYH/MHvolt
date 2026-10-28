<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{
    ChoiceField,
    CollectionField,
    EmailField,
    FormField,
    IdField,
    TextField
};
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UrlGeneratorInterface $router
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setPageTitle('index', 'Gestion des utilisateurs')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::EDIT, fn(Action $action) => $action->setLabel('Modifier'))
            ->update(Crud::PAGE_INDEX, Action::DELETE, fn(Action $action) => $action->setLabel('Supprimer'))
            ->update(Crud::PAGE_INDEX, Action::DETAIL, fn(Action $action) => $action->setLabel('Voir'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            FormField::addPanel('Informations utilisateur'),
            EmailField::new('email', 'Adresse e-mail'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            TextField::new('tel', 'Téléphone'),

            FormField::addPanel('Rôles'),
            ChoiceField::new('roles', 'Droits & Rôles')
                ->setChoices([
                    'Client'         => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),

            FormField::addPanel('Adresses enregistrées'),
            CollectionField::new('addresses', 'Adresses')
                ->setEntryType(AddressType::class)
                ->allowAdd()
                ->allowDelete()
                ->setFormTypeOption('by_reference', false)
                ->onlyOnForms(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        $entityInstance->setPassword(bin2hex(random_bytes(16)));
        $entityManager->persist($entityInstance);

        $token = bin2hex(random_bytes(32));
        $resetPassword = new ResetPassword();
        $resetPassword->setUser($entityInstance);
        $resetPassword->setToken($token);
        $resetPassword->setCreatedAt(new \DateTime());
        $entityManager->persist($resetPassword);

        $entityManager->flush();

        $url = $this->router->generate(
            'update_password',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $mail = new Mail();
        $mail->send(
            $entityInstance->getEmail(),
            $entityInstance->getFirstName() . ' ' . $entityInstance->getLastName(),
            'Création de votre compte MHvolt',
            "Bonjour {$entityInstance->getFirstName()},<br><br>Un compte a été créé pour vous sur MHvolt.<br>Veuillez définir votre mot de passe en cliquant sur le lien suivant : <a href='{$url}'>Créer mon mot de passe</a>."
        );

        $this->addFlash('success', 'Utilisateur créé. Un e-mail d’invitation a été envoyé.');
    }
}
