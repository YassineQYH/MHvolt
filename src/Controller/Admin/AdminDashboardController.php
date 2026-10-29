<?php

namespace App\Controller\Admin;

use App\Entity\Accessory;
use App\Entity\CategoryAccessory;
use App\Entity\HomeVideo;
use App\Entity\Illustration;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Entity\ProductHistory;
use App\Entity\Promotion;
use App\Entity\SiteConfig;
use App\Entity\Trottinette;
use App\Entity\TrottinetteDescriptionSection;
use App\Entity\Tva;
use App\Entity\User;
use App\Entity\Weight;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RequestStack $requestStack
    ) {}

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('assets/css/admin/illustrations.css')
            ->addCssFile('assets/css/admin/maintenance.css')
            ->addJsFile('assets/js/admin/illustrations.js');
    }

    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();$ordersByMonthData = $this->getOrdersByMonth($request);

        if ($request &&$request->isXmlHttpRequest()) {
            return $this->json([
                'ordersByMonth'  => $ordersByMonthData['ordersByMonth'],
                'yearToDisplay'  => $ordersByMonthData['yearToDisplay'],
                'prevYear'       => $ordersByMonthData['prevYear'],
                'nextYear'       => $ordersByMonthData['nextYear'],
                'revenueByMonth' => $this->getRevenueByMonthByYear($ordersByMonthData['yearToDisplay']),
            ]);
        }

        return $this->render('admin/dashboard.html.twig', [
            'orderStatusStats' => $this->getOrderStatusStats(),
            'ordersByMonth'    => $ordersByMonthData['ordersByMonth'],
            'yearToDisplay'    => $ordersByMonthData['yearToDisplay'],
            'prevYear'         => $ordersByMonthData['prevYear'],
            'nextYear'         => $ordersByMonthData['nextYear'],
            'revenueByMonth'   => $this->getRevenueByMonthByYear($ordersByMonthData['yearToDisplay']),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MHvolt Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::section('Configuration');
        $maintenanceActive =$this->isMaintenanceActive();
        yield MenuItem::linkToCrud(
            $maintenanceActive ? 'Maintenance (ACTIVE)' : 'Maintenance (Inactif)',
            $maintenanceActive ? 'fa fa-exclamation-triangle text-danger' : 'fa fa-check-circle text-success',
            SiteConfig::class
        );

        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Comptes clients & admins', 'fas fa-user', User::class);

        yield MenuItem::section('Ventes');
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Lignes de commandes', 'fas fa-receipt', OrderDetails::class);
        yield MenuItem::linkToCrud('Tarifs livraison (Poids)', 'fas fa-weight', Weight::class);

        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Tous les produits', 'fas fa-box', Product::class);
        yield MenuItem::linkToCrud('Historique des modifications', 'fas fa-history', ProductHistory::class);
        yield MenuItem::linkToCrud('Codes promo & remises', 'fas fa-tags', Promotion::class);
        yield MenuItem::linkToCrud('Classes de TVA', 'fas fa-percentage', Tva::class);
        yield MenuItem::linkToCrud('Illustrations', 'fas fa-image', Illustration::class);

        yield MenuItem::section('Trottinettes');
        yield MenuItem::linkToCrud('Trottinettes', 'fas fa-bolt', Trottinette::class);
        yield MenuItem::linkToCrud('Groupes de caractéristiques', 'fas fa-layer-group', \App\Entity\SpecificationGroup::class);
        yield MenuItem::linkToCrud('Sections descriptives', 'fas fa-align-left', TrottinetteDescriptionSection::class);

        yield MenuItem::section('Accessoires');
        yield MenuItem::linkToCrud('Accessoires', 'fas fa-tools', Accessory::class);
        yield MenuItem::linkToCrud('Catégories accessoires', 'fas fa-tags', CategoryAccessory::class);

        yield MenuItem::section('Contenu du site');
        yield MenuItem::linkToCrud('Vidéos d’accueil', 'fas fa-video', HomeVideo::class);

        yield MenuItem::section('Statistiques');
        yield MenuItem::linkToRoute('Répartition Accessoires', 'fa fa-chart-pie', 'admin_graph_accessories');
        yield MenuItem::linkToRoute('Trottinettes par catégorie', 'fa fa-chart-bar', 'admin_graph_trottinettes');
    }

    public function getOrdersByMonth(?Request $request): array
    {
        $conn = $this->em->getConnection();$sql = "
            SELECT
                YEAR(o.created_at) AS year,
                MONTH(o.created_at) AS month,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY year, month
            ORDER BY year ASC, month ASC
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();

        $data = [];
        foreach ($results as$row) {
            $year = (int)$row['year'];
            $month = (int)$row['month'];
            $data[$year][$month] = (int)$row['total'];
        }

        $years = !empty($data) ? array_keys($data) : [(int)date('Y')];
        $currentYear = (int)date('Y');$yearToDisplay = $request ? $request->query->getInt('year', $currentYear) :$currentYear;

        if (!in_array($yearToDisplay,$years)) {
            $yearToDisplay = max($years);
        }

        $monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $values[] =$data[$yearToDisplay][$m] ?? 0;
        }

        $prevYear =$yearToDisplay - 1;
        $nextYear =$yearToDisplay + 1;

        return [
            'ordersByMonth' => [
                'labels' => $monthNames,
                'values' => $values,
            ],
            'yearToDisplay' => $yearToDisplay,
            'prevYear' => in_array($prevYear, $years) ?$prevYear : null,
            'nextYear' => in_array($nextYear, $years) ?$nextYear : null,
        ];
    }

    private function getOrderStatusStats(): array
    {
        $conn = $this->em->getConnection();$sql = "
            SELECT
                o.payment_state,
                o.delivery_state,
                COUNT(o.id) AS total
            FROM `order` o
            GROUP BY o.payment_state, o.delivery_state
        ";

        $results = $conn->executeQuery($sql)->fetchAllAssociative();
        $labels = [];$values = [];

        foreach ($results as$row) {
            $payment = (int)$row['payment_state'];
            $delivery = (int)$row['delivery_state'];

            if ($payment === Order::PAYMENT_UNPAID) {$label = 'Non payée';
            } elseif (isset(Order::DELIVERY_STATES[$delivery])) {
                $label = 'Payée - ' . Order::DELIVERY_STATES[$delivery];
            } else {
                $label = 'Inconnu';
            }

            $labels[] =$label;
            $values[] = (int)$row['total'];
        }

        return ['labels' => $labels, 'values' =>$values];
    }

    private function getRevenueByMonthByYear(int $year): array
    {
        $conn = $this->em->getConnection();$sql = "
            SELECT
                MONTH(o.created_at) AS month,
                SUM(od.total_ttc) AS revenue
            FROM `order` o
            JOIN order_details od ON od.my_order_id = o.id
            WHERE o.payment_state = :paidState
            AND YEAR(o.created_at) = :year
            GROUP BY month
            ORDER BY month ASC
        ";

        $results = $conn->executeQuery($sql, [
            'paidState' => Order::PAYMENT_PAID,
            'year' => $year
        ])->fetchAllAssociative();

        $monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        $values = array_fill(0, 12, 0.0);

        foreach ($results as$row) {
            $monthIndex = ((int)$row['month']) - 1;
            if (isset($values[$monthIndex])) {$values[$monthIndex] = round((float)$row['revenue'], 2);
            }
        }

        return ['labels' => $monthNames, 'values' =>$values];
    }

    private function isMaintenanceActive(): bool
    {
        $config =$this->em->getRepository(SiteConfig::class)
            ->findOneBy(['name' => 'maintenance_enabled']);

        return $config ? ((int)$config->getValue() === 1) : false;
    }
}
