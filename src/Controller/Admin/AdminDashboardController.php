<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Weight;
use App\Entity\Accessory;
use App\Entity\Trottinette;
use App\Entity\Illustration;
use App\Entity\Caracteristique;
use App\Entity\Illustrationaccess;
use App\Entity\OrderDetails;
use App\Entity\TrottinetteAccessory;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrottinetteCaracteristique;
use App\Entity\TrottinetteDescriptionSection;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class AdminDashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index(): Response
    {
        // Affichage du template custom avec les graphiques
        return $this->render('admin/dashboard.html.twig', [
            'statsAccessories' => $this->getAccessoryStats(),
            'statsTrottinettes' => $this->getTrottinetteStats(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hicham Trotinettes');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // --- CRUD Produits ---
        yield MenuItem::section('Trottinettes');
        yield MenuItem::linkToCrud('Trottinettes', 'fas fa-folder', Trottinette::class);
        yield MenuItem::linkToCrud('Sections Description', 'fas fa-align-left', TrottinetteDescriptionSection::class);
        yield MenuItem::linkToCrud('Caractéristiques', 'fas fa-list', Caracteristique::class);
        yield MenuItem::linkToCrud('Trottinette ↔ Caractéristique', 'fas fa-list-alt', TrottinetteCaracteristique::class);

        // --- CRUD Accessoires ---
        yield MenuItem::section('Accessoires');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-folder', \App\Entity\CategorieCaracteristique::class);
        yield MenuItem::linkToCrud('Accessoires', 'fas fa-box', Accessory::class);
        yield MenuItem::linkToCrud('Trottinette ↔ Accessoires', 'fas fa-tags', TrottinetteAccessory::class);

        //-- Illustrations --//
        yield MenuItem::section('Illustrations');
        yield MenuItem::linkToCrud('Illustrations Trottinettes', 'fas fa-image', Illustration::class);
        yield MenuItem::linkToCrud('Illustration Accessoires', 'fas fa-image', Illustrationaccess::class);

        //-- Poids --//
        yield MenuItem::section('Poids');
        yield MenuItem::linkToCrud('Poids', 'fas fa-home', Weight::class);

        //-- Users --//
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);

        //-- Commandes --//
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commande', 'fa fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Détails de commande', 'fa fa-shopping-cart', OrderDetails::class);

        // --- Statistiques / Graphiques ---
        yield MenuItem::section('Stats');
        yield MenuItem::linkToRoute('Répartition Accessoires', 'fa fa-chart-pie', 'admin_graph_accessories');
        yield MenuItem::linkToRoute('Trottinettes par catégorie', 'fa fa-chart-bar', 'admin_graph_trottinettes');
    }

    // -------------------------------
    // Méthodes pour récupérer les données graphiques
    // -------------------------------
    private function getAccessoryStats(): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a.name AS accessory', 'COUNT(ta.id) AS trottinettes')
            ->from(Accessory::class, 'a')
            ->leftJoin('a.trottinetteAccessories', 'ta')
            ->groupBy('a.id');

        $results = $qb->getQuery()->getResult();

        $labels = [];
        $values = [];
        foreach ($results as $row) {
            $labels[] = $row['accessory'];
            $values[] = $row['trottinettes'];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getTrottinetteStats(): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('t.isBest', 't.isHeader', 'COUNT(t.id) AS count')
            ->from(Trottinette::class, 't')
            ->groupBy('t.isBest')
            ->addGroupBy('t.isHeader');

        $results = $qb->getQuery()->getResult();

        $labels = [];
        $values = [];
        foreach ($results as $row) {
            $labels[] = ($row['isBest'] ? 'Best ' : '') . ($row['isHeader'] ? 'Header' : '');
            $values[] = $row['count'];
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
