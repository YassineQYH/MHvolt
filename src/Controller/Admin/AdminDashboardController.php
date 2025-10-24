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
            'orderStatusStats' => $this->getOrderStatusStats(),
            'ordersByMonth' => $this->getOrdersByMonth(),
            'revenueByMonth' => $this->getRevenueByMonth(),
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

         //-- Users --//
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);

        // -- Commandes --//
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Détails Commandes', 'fas fa-receipt', OrderDetails::class);

        //-- Poids --//
        yield MenuItem::section('Tableau tarifaire des Poids');
        yield MenuItem::linkToCrud('Poids', 'fas fa-home', Weight::class);

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

        // --- Statistiques / Graphiques ---
        yield MenuItem::section('Stats');
        yield MenuItem::linkToRoute('Répartition Accessoires', 'fa fa-chart-pie', 'admin_graph_accessories');
        yield MenuItem::linkToRoute('Trottinettes par catégorie', 'fa fa-chart-bar', 'admin_graph_trottinettes');
    }

    // -------------------------------
    // Méthodes pour récupérer les données graphiques
    // -------------------------------
    private function getOrderStats(): array
    {
        $conn = $this->em->getConnection();
        $sql = "
            SELECT
                DATE_FORMAT(o.created_at, '%Y-%m') AS month,
                COUNT(o.id) AS total_orders
            FROM `order` o
            GROUP BY month
            ORDER BY month ASC
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            $labels[] = $row['month'];
            $values[] = $row['total_orders'];
        }

        return ['labels' => $labels, 'values' => $values];
    }


    private function getOrdersByMonth(): array
    {
        $conn = $this->em->getConnection();
        $sql = "
            SELECT
                MONTH(o.created_at) AS month,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY month
            ORDER BY month ASC
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            // Pour rendre le mois lisible, tu peux convertir le numéro en texte
            $labels[] = date('F', mktime(0, 0, 0, $row['month'], 1));
            $values[] = $row['total'];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getOrderStatusStats(): array
    {
        $conn = $this->em->getConnection();
        $sql = "
            SELECT
                CONCAT(o.payment_state, '-', o.delivery_state) AS combined_status,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY combined_status
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            [$payment, $delivery] = explode('-', $row['combined_status']);
            $statusLabel = '';

            if ($payment == 0) $statusLabel = 'Non payée';
            elseif ($payment == 1 && $delivery == 0) $statusLabel = 'Payée - Préparation';
            elseif ($payment == 1 && $delivery == 1) $statusLabel = 'Payée - En livraison';
            else $statusLabel = 'Inconnu';

            $labels[] = $statusLabel;
            $values[] = $row['total'];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getRevenueByMonth(): array
    {
        $conn = $this->em->getConnection();

        $sql = "
            SELECT
                DATE_FORMAT(o.created_at, '%Y-%m') AS month,
                SUM(od.price * od.quantity) AS revenue
            FROM `order` o
            JOIN order_details od ON od.my_order_id = o.id
            WHERE o.payment_state = 1
            GROUP BY month
            ORDER BY month ASC
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            $labels[] = $row['month'];
            $values[] = round($row['revenue'], 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }


}
