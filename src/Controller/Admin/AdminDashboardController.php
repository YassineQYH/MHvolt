<?php

namespace App\Controller\Admin;

use App\Entity\Tva;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Weight;
use App\Entity\Product;
use App\Entity\Accessory;
use App\Entity\Promotion;
use App\Entity\Trottinette;
use App\Entity\Illustration;
use App\Entity\OrderDetails;
use App\Entity\ProductHistory;
use App\Entity\Caracteristique;
use App\Entity\CategoryAccessory;
use App\Entity\TrottinetteAccessory;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TrottinetteCaracteristique;
use App\Entity\TrottinetteDescriptionSection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class AdminDashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $em;
    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('assets/css/admin/illustrations.css')
            ->addJsFile('assets/js/admin/illustrations.js');
    }

    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $ordersByMonthData = $this->getOrdersByMonth($request);

        if ($request && $request->isXmlHttpRequest()) {
            return $this->json([
                'ordersByMonth'  => $ordersByMonthData['ordersByMonth'],
                'yearToDisplay'  => $ordersByMonthData['yearToDisplay'],
                'prevYear'       => $ordersByMonthData['prevYear'],
                'nextYear'       => $ordersByMonthData['nextYear'],
                'revenueByMonth' => $this->getRevenueByMonthByYear(
                    $ordersByMonthData['yearToDisplay']
                ),
            ]);
        }

        return $this->render('admin/dashboard.html.twig', [
            'orderStatusStats' => $this->getOrderStatusStats(),
            'ordersByMonth'    => $ordersByMonthData['ordersByMonth'],
            'yearToDisplay'    => $ordersByMonthData['yearToDisplay'],
            'prevYear'         => $ordersByMonthData['prevYear'],
            'nextYear'         => $ordersByMonthData['nextYear'],
            'revenueByMonth'   => $this->getRevenueByMonthByYear(
                $ordersByMonthData['yearToDisplay']
            ),
        ]);
    }





    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MHvolt');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        //-- Users --//
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);

        // -- Commandes --//
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Détails Commandes', 'fas fa-receipt', OrderDetails::class);

        //-- Poids --//
        yield MenuItem::section('Tableau tarifaire des Poids');
        yield MenuItem::linkToCrud('Poids', 'fas fa-weight', Weight::class);

        // --- CRUD Produits ---
        yield MenuItem::section('Produits');
        yield MenuItem::linkToCrud('Produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Historique Produits', 'fas fa-history', ProductHistory::class);
        yield MenuItem::linkToCrud('Promotions', 'fas fa-tags', Promotion::class);
        yield MenuItem::linkToCrud('TVA', 'fas fa-percentage', Tva::class);

        // --- CRUD Trottinettes ---
        yield MenuItem::section('Trottinettes');
        yield MenuItem::linkToCrud('Trottinettes', 'fas fa-folder', Trottinette::class);
        yield MenuItem::linkToCrud('Descriptions', 'fas fa-align-left', TrottinetteDescriptionSection::class);
        yield MenuItem::linkToCrud('Caractéristiques', 'fas fa-list', Caracteristique::class);
        yield MenuItem::linkToCrud('Catégories des caractéristiques', 'fas fa-folder', \App\Entity\CategorieCaracteristique::class);
        yield MenuItem::linkToCrud('Trottinette ↔ Caractéristique', 'fas fa-list-alt', TrottinetteCaracteristique::class);

        // --- CRUD Accessoires ---
        yield MenuItem::section('Accessoires');
        yield MenuItem::linkToCrud('Accessoires', 'fas fa-box', Accessory::class);
        yield MenuItem::linkToCrud('Catégorie des accessoires', 'fas fa-link', CategoryAccessory::class);
        yield MenuItem::linkToCrud('Trottinette ↔ Accessoires', 'fas fa-tags', TrottinetteAccessory::class);

        //-- Illustrations --//
        yield MenuItem::section('Illustrations');
        yield MenuItem::linkToCrud('Illustrations Produits', 'fas fa-image', Illustration::class);

        // --- CRUD Home Video ---
        yield MenuItem::section('Page d’accueil');
        yield MenuItem::linkToCrud('Home Video', 'fas fa-video', \App\Entity\HomeVideo::class);

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


    public function getOrdersByMonth(Request $request): array
    {
        $conn = $this->em->getConnection();

        // 📌 Requête SQL avec année et mois
        $sql = "
            SELECT
                YEAR(o.created_at) AS year,
                MONTH(o.created_at) AS month,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY year, month
            ORDER BY year ASC, month ASC
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        // 📌 Organiser les données par année
        $data = [];
        foreach ($results as $row) {
            $year = (int)$row['year'];
            $month = (int)$row['month'];
            $total = (int)$row['total'];

            $data[$year][$month] = $total;
        }

        // 📌 Récupérer l'année sélectionnée dans la requête (GET)
        $years = array_keys($data);
        $currentYear = date('Y');

        $yearToDisplay = $request->query->getInt('year', $currentYear);
        if (!in_array($yearToDisplay, $years)) {
            // si l'année sélectionnée n'existe pas dans les données, afficher la dernière année
            $yearToDisplay = max($years);
        }

        // 📌 Préparer labels et valeurs pour Chart.js
        $labels = [];
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = strftime('%B', mktime(0, 0, 0, $m, 1)); // Janvier, Février, ...
            $values[] = $data[$yearToDisplay][$m] ?? 0;
        }

        // 📌 Années pour navigation flèches
        $prevYear = $yearToDisplay - 1;
        $nextYear = $yearToDisplay + 1;

        return [
            'ordersByMonth' => [
                'labels' => $labels,
                'values' => $values,
            ],
            'yearToDisplay' => $yearToDisplay,
            'prevYear' => in_array($prevYear, $years) ? $prevYear : null,
            'nextYear' => in_array($nextYear, $years) ? $nextYear : null,
        ];
    }


    private function getOrderStatusStats(): array
    {
        $conn = $this->em->getConnection();

        $sql = "
            SELECT
                o.payment_state,
                o.delivery_state,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY o.payment_state, o.delivery_state
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $labels = [];
        $values = [];

        foreach ($results as $row) {
            $payment = (int) $row['payment_state'];
            $delivery = (int) $row['delivery_state'];

            if ($payment === 0) {
                $label = 'Non payée';
            } elseif ($payment === 1 && isset(Order::DELIVERY_STATES[$delivery])) {
                $label = 'Payée - ' . Order::DELIVERY_STATES[$delivery];
            } else {
                $label = 'Statut incohérent';
            }

            $labels[] = $label;
            $values[] = (int) $row['total'];
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
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

    private function getRevenueByMonthByYear(int $year): array
    {
        $conn = $this->em->getConnection();

        $sql = "
            SELECT
                MONTH(o.created_at) AS month,
                SUM(od.price * od.quantity) AS revenue
            FROM `order` o
            JOIN order_details od ON od.my_order_id = o.id
            WHERE o.payment_state = 1
            AND YEAR(o.created_at) = :year
            GROUP BY month
            ORDER BY month ASC
        ";

        $results = $conn->executeQuery($sql, ['year' => $year])->fetchAllAssociative();

        $labels = [];
        $values = [];

        for ($m = 1; $m <= 12; $m++) {
            $labels[] = strftime('%B', mktime(0, 0, 0, $m, 1));
            $values[] = 0;
        }

        foreach ($results as $row) {
            $values[(int)$row['month'] - 1] = round($row['revenue'], 2);
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

}
