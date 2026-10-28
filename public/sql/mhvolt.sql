-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 28 oct. 2026 à 17:25
-- Version du serveur : 8.4.3
-- Version de PHP : 8.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mhvolt`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessory`
--

CREATE TABLE `accessory` (
  `id` int NOT NULL,
  `category_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessory`
--

INSERT INTO `accessory` (`id`, `category_id`) VALUES
(106, 1),
(115, 1),
(116, 1),
(107, 2),
(108, 2),
(112, 2),
(104, 3),
(113, 3),
(114, 3),
(105, 4),
(109, 4),
(110, 4),
(111, 4);

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`) VALUES
(1, 2, 'Rue Bleue', 'Yassine', 'Qayouh', 'Michelin Combaude', 'Rue Bleue', '63118', 'Cébazat', 'FR', '+33641512129');

-- --------------------------------------------------------

--
-- Structure de la table `category_accessory`
--

CREATE TABLE `category_accessory` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `illustration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_accessory`
--

INSERT INTO `category_accessory` (`id`, `name`, `slug`, `illustration`, `description`) VALUES
(1, 'Guidon', 'guidon', 'guidon-blunt-black-v2.jpg', '<div>guidon-blunt-black-v2</div>'),
(2, 'Freins', 'freins', 'freins-rouge.jpg', '<div>freins</div>'),
(3, 'Sièges', 'sieges', 'siege-rouge.jpg', '<div>siege-trott-elec</div>'),
(4, 'Roues & Pneus', 'roues-pneus', 'roues-stunt.jpg', '<div>Roues-freestyle</div>'),
(7, 'Casques & Protections', 'casques-protections', 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format&fit=crop&q=80', 'Tous les équipements certifiés pour la catégorie Casques & Protections'),
(8, 'Antivols & Sécurité', 'antivols-securite', 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=800&auto=format&fit=crop&q=80', 'Tous les équipements certifiés pour la catégorie Antivols & Sécurité'),
(9, 'Pneumatiques & Roues', 'pneumatiques-roues', 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&auto=format&fit=crop&q=80', 'Tous les équipements certifiés pour la catégorie Pneumatiques & Roues');

-- --------------------------------------------------------

--
-- Structure de la table `home_video`
--

CREATE TABLE `home_video` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `position` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `home_video`
--

INSERT INTO `home_video` (`id`, `title`, `video_file`, `video_url`, `headline`, `subtitle`, `is_active`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Accueil', 'videoplayback-696117c79ff4f-6ae21494b322a.mp4', NULL, 'Présentation', NULL, 1, 1, '2026-10-28 16:28:20', '2026-10-28 16:28:21');

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `illustration`
--

INSERT INTO `illustration` (`id`, `product_id`, `image`) VALUES
(24, 17, 'trott_6ae1c7b95ec443.81730067.png'),
(25, 17, 'trott_6ae1c7b96201f9.43171912.png'),
(26, 17, 'trott_6ae1c7b96263d2.63666540.png'),
(27, 17, 'trott_6ae1c7b962d247.50326550.png'),
(28, 17, 'trott_6ae1c81732d7c4.48284594.png'),
(29, 18, 'trott_6ae20239c762b2.75607182.jpg'),
(30, 18, 'trott_6ae20239c9ce25.42511718.jpg'),
(31, 18, 'trott_6ae20239ca8f96.39532221.jpg'),
(32, 18, 'trott_6ae20239cb2f01.06929953.jpg'),
(33, 18, 'trott_6ae20239cbc569.69865439.jpg'),
(34, 103, 'trottvert-05.jpg'),
(35, 103, 'trottvert-02.jpg'),
(36, 103, 'trottvert-03.jpg'),
(37, 103, 'trottvert-04.jpg'),
(38, 104, 'siege-trott-elec.jpg'),
(39, 104, 'Xtreme-Siege.jpg'),
(40, 105, 'roues-etoiles.jpg'),
(41, 106, 'guidon-blunt-black-v3.jpg'),
(42, 107, 'freins-jaune.jpg'),
(43, 108, 'etrier-de-frein.jpg'),
(44, 109, 'Roues-freestyle.jpg'),
(45, 110, 'roues-gold.jpg'),
(46, 111, 'roues-stunt.jpg'),
(47, 112, 'freins-rouge.jpeg'),
(48, 113, 'siege-double.jpg'),
(49, 114, 'siege-rouge.jpg'),
(50, 115, 'acc_6ae212b40a3369.91014399.png'),
(51, 116, 'guidon-titanium.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `carrier_price` double NOT NULL,
  `delivery` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_reduction` double DEFAULT NULL,
  `promo_titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_state` int NOT NULL,
  `delivery_state` int NOT NULL,
  `tracking_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_country` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `carrier_price`, `delivery`, `reference`, `stripe_session_id`, `promo_code`, `promo_reduction`, `promo_titre`, `payment_state`, `delivery_state`, `tracking_number`, `carrier`, `delivery_country`) VALUES
(1, 2, '2026-10-28 16:48:52', 9.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-4D0545', 'cs_test_b14v0lxWQhZ1CCVnJ3mH4tmCdp52w2nBSsJ6yaI6dr2uTVnYNe9W3nCfeQ', NULL, 0, NULL, 0, 0, NULL, 'bpost', 'FR'),
(2, 2, '2026-10-28 16:51:33', 9.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-5DE945', NULL, NULL, 0, NULL, 0, 0, NULL, 'bpost', 'FR'),
(3, 2, '2026-10-28 16:54:38', 9.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-E588B6', 'cs_test_b1QOt1V7vcLMygOx18QAcjgIGludBycfRchxHqwlGaPcF1HSvI0C6p3B15', NULL, 0, NULL, 0, 0, NULL, 'bpost', 'FR'),
(4, 2, '2026-10-28 16:59:26', 9.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-E33FC1', 'cs_test_b1JGx1tf21QUlQQvlAeA6TfuiPH3lfCg0BZaKhs9vcAf8HkTc8wqcZhKAM', NULL, 0, NULL, 1, 1, NULL, 'bpost', 'FR'),
(5, 2, '2026-10-28 17:17:35', 19.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-FC4E53', 'cs_test_b1D2opVa07xGTgAtWgTPChmCr8BKTIOLB88mq4iaooTVSZ1vIUVy6aJRBj', 'WELCOME10', 79.9, 'Offre de Bienvenue', 1, 1, NULL, 'bpost', 'FR'),
(6, 2, '2026-10-28 17:19:27', 0, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-F2041A', NULL, NULL, 0, NULL, 0, 0, NULL, 'bpost', 'FR'),
(7, 2, '2026-10-28 17:20:07', 19.9, 'Yassine Qayouh<br>+33641512129<br>Michelin Combaude<br>Rue Bleue<br>63118 Cébazat<br>FR', '28102026-70F046', 'cs_test_b1x3K9ojIzBNnLmb6ipx5u03vt6nqPlCYWjRjuOhKHoHQw0YZJIdmM6Pq8', 'WELCOME10', 79.9, 'Offre de Bienvenue', 1, 1, NULL, 'bpost', 'FR');

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `my_order_id` int NOT NULL,
  `product_entity_id` int DEFAULT NULL,
  `product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL,
  `price_after_reduc` double NOT NULL,
  `total_ttc` double NOT NULL,
  `weight` double NOT NULL,
  `tva` double NOT NULL,
  `price_ttc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `my_order_id`, `product_entity_id`, `product`, `quantity`, `price`, `price_after_reduc`, `total_ttc`, `weight`, `tva`, `price_ttc`) VALUES
(1, 1, 115, 'Guidon Multicolor Néo', 1, 25, 25, 30.25, 5, 21, 30.25),
(2, 2, 115, 'Guidon Multicolor Néo', 1, 25, 25, 30.25, 5, 21, 30.25),
(3, 3, 115, 'Guidon Multicolor Néo', 1, 25, 25, 30.25, 5, 21, 30.25),
(4, 4, 115, 'Guidon Multicolor Néo', 1, 25, 25, 30.25, 5, 21, 30.25),
(5, 5, 103, 'Bogist M5 Pro', 1, 660.33, 594.3, 719.1, 17, 21, 719.1),
(6, 7, 103, 'Bogist M5 Pro', 1, 660.33, 594.3, 719.1, 17, 21, 719.1);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `tva_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL,
  `promo_price` double DEFAULT NULL,
  `stock` int NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `weight` double NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `tva_id`, `name`, `slug`, `description`, `price`, `promo_price`, `stock`, `is_best`, `weight`, `created_at`, `updated_at`, `type`) VALUES
(17, 5, 'Honey Whale M5 Max', 'honey-whale-m5-max', '<div>&nbsp;La Honey Whale M5 Max est équipée d\'un moteur puissant de 1000 W, offrant des accélérations rapides et une vitesse maximale de 45 km/h.&nbsp;</div>', 750, 700, 5, 1, 35, '2026-10-28 11:00:25', '2026-10-28 11:03:19', 'trottinette'),
(18, 5, 'KUGOO Kukirin C1 Pro', 'kugoo-kukirin-c1-pro', NULL, 630, NULL, 2, 1, 30, '2026-10-28 15:09:56', '2026-10-28 15:10:01', 'trottinette'),
(103, 5, 'Bogist M5 Pro', 'bogist-m5-pro', '<div>Moteur puissant de 500 W pour des vitesses élevées, parfait pour la ville.</div>', 799, NULL, 0, 1, 17, '2025-11-18 16:19:20', '2026-10-28 17:20:20', 'trottinette'),
(104, 5, 'Xtreme Siège', 'xtreme-siege', 'Siège ergonomique confortable pour trottinette électrique.', 15, NULL, 1, 1, 4, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(105, 5, 'Roues Étoiles', 'roues-etoiles', '<div>Roue renforcée motif étoiles pour trottinette urbaine.</div>', 45, NULL, 6, 1, 7, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(106, 5, 'Guidon Blunt Black V3', 'guidon-blunt-black-v3', 'Guidon haute résistance en aluminium noir mat.', 49, NULL, 1, 0, 2, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(107, 5, 'Frein Jaune Sport', 'frein-jaune-sport', 'Système d étrier de frein haute performance jaune.', 19, NULL, 2, 0, 4, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(108, 5, 'Étrier de Frein Renforcé', 'etrier-de-frein-renforce', 'Étrier de frein en alliage avec plaquettes semi-métalliques.', 56, NULL, 2, 0, 5, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(109, 5, 'Roues Freestyle Grip', 'roues-freestyle-grip', '<div>Roues légères avec gomme tendre haute adhérence.</div>', 39, NULL, 1, 1, 8, '2025-11-18 16:19:20', '2026-10-28 16:25:27', 'accessoire'),
(110, 5, 'Roues Gold Edition', 'roues-gold-edition', '<div>Roues avec jantes aluminium dorées anodisées.</div>', 89, 59, 5, 1, 6, '2025-11-18 16:19:20', '2026-10-28 16:25:03', 'accessoire'),
(111, 5, 'Roues Stunt Pro', 'roues-stunt-pro', 'Roues robustes conçues pour résister aux chocs et trottoirs.', 55, NULL, 4, 0, 3, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(112, 5, 'Freins Rouge Carbone', 'freins-rouge-carbone', '<div>Ensemble de freinage rouge vif pour une réactivité maximale.</div>', 26, 25, 1, 1, 4, '2025-11-18 16:19:20', '2026-10-28 16:24:49', 'accessoire'),
(113, 5, 'Siège Double Confort', 'siege-double-confort', 'Siège avec assise élargie et amortisseur intégré sous tige.', 21, NULL, 2, 0, 6, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(114, 5, 'Siège Rouge Sport', 'siege-rouge-sport', 'Selle profilée rouge avec revêtement imperméable.', 45, NULL, 5, 0, 9, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire'),
(115, 5, 'Guidon Multicolor Néo', 'guidon-multicolor-neo', '<div>Guidon avec finition néochrome arc-en-ciel.</div>', 25, 20, 3, 1, 5, '2025-11-18 16:19:20', '2026-10-28 16:59:55', 'accessoire'),
(116, 5, 'Guidon Titanium Pro', 'guidon-titanium-pro', '<div>Guidon ultra léger en titane poli.</div>', 48, NULL, 3, 0, 6, '2025-11-18 16:19:20', '2026-10-28 16:09:30', 'accessoire');

-- --------------------------------------------------------

--
-- Structure de la table `product_history`
--

CREATE TABLE `product_history` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL,
  `price` double DEFAULT NULL,
  `main_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `promo_price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id` int NOT NULL,
  `category_access_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` double DEFAULT NULL,
  `discount_percent` double DEFAULT NULL,
  `start_date` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `end_date` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `quantity` int NOT NULL,
  `used` int NOT NULL,
  `auto_apply` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id`, `category_access_id`, `product_id`, `titre`, `code`, `target_type`, `discount_type`, `discount_amount`, `discount_percent`, `start_date`, `end_date`, `quantity`, `used`, `auto_apply`) VALUES
(3, NULL, NULL, 'Offre de Bienvenue', 'WELCOME10', 'all', 'PERCENTAGE', NULL, 10, '2026-10-26 15:52:11', NULL, 200, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotion_id` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site_config`
--

CREATE TABLE `site_config` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `specification_group`
--

CREATE TABLE `specification_group` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `specification_group`
--

INSERT INTO `specification_group` (`id`, `name`, `position`) VALUES
(1, 'Dimensions & Poids', 0),
(2, 'Motorisation', 1),
(3, 'Autres particularités', 2),
(4, 'Équipement de sécurité', 3);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id` int NOT NULL,
  `name_short` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_short` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `name_short`, `description_short`) VALUES
(17, 'M5 Max', '<div>&nbsp;Moteur 1000 W, pneus 14 pouces, autonomie 40 km&nbsp;</div>'),
(18, 'C1 PRO', '<div>&nbsp;Vitesse 45 km/h, autonomie 100 km, charge max 120 kg&nbsp;</div>'),
(103, 'Bogist M5 Pro', '<div>Moteur 500 W, pneus 12 pouces, autonomie 35 km</div>');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessories_pivot`
--

CREATE TABLE `trottinette_accessories_pivot` (
  `trottinette_id` int NOT NULL,
  `accessory_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_accessories_pivot`
--

INSERT INTO `trottinette_accessories_pivot` (`trottinette_id`, `accessory_id`) VALUES
(17, 104),
(17, 105),
(17, 106),
(17, 107),
(17, 110),
(17, 113),
(17, 114),
(17, 115),
(17, 116),
(18, 104),
(18, 106),
(18, 108),
(18, 110),
(18, 115),
(103, 105),
(103, 106),
(103, 107),
(103, 112),
(103, 116);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_description_section`
--

CREATE TABLE `trottinette_description_section` (
  `id` int NOT NULL,
  `trottinette_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `section_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_description_section`
--

INSERT INTO `trottinette_description_section` (`id`, `trottinette_id`, `title`, `content`, `section_order`) VALUES
(5, 17, 'Moteur haute performance', 'La Honey Whale M5 Max est équipée d\'un moteur puissant de 1000 W, offrant des accélérations rapides et une vitesse maximale de 45 km/h.', 1),
(6, 17, 'Batterie longue durée', 'Batterie lithium 48 V 13 Ah pour une autonomie allant jusqu\'à 40 km, idéale pour les trajets urbains quotidiens.', 2),
(7, 17, 'Confort et design', 'Siège ergonomique et suspension optimisée pour un confort maximal lors des trajets.', 3),
(8, 17, 'Sécurité', 'Éclairage LED avant/arrière et freins à disque pour un freinage sûr dans toutes les conditions.', 4),
(9, 18, 'Puissance du moteur', 'Moteur 500 W offrant une conduite stable et une vitesse maximale de 45 km/h.', 1),
(10, 18, 'Autonomie prolongée', 'Batterie 48 V 10 Ah permettant jusqu\'à 100 km d\'autonomie selon le mode de conduite.', 2),
(11, 18, 'Design compact', 'Structure légère et pliable pour un transport facile et un rangement pratique.', 3),
(12, 18, 'Freinage sûr', 'Freins à disque arrière avec récupération d\'énergie pour un arrêt rapide.', 4),
(13, 18, 'Freinage efficace', 'Freins à disque arrière avec système de récupération d\'énergie pour un arrêt rapide et sécurisé.', 5),
(14, 103, 'Moteur puissant de 500 W', 'Le BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante pour les trajets urbains ou les aventures hors route.', 1),
(15, 103, 'Batterie haute capacité 48 V 15 Ah', 'Équipé d\'une batterie au lithium 48 V 15 Ah, le M5 Pro offre une autonomie allant jusqu\'à 35 km par charge.', 2),
(16, 103, 'Confort et maniabilité', 'Pneus de 12 pouces et suspension optimisée pour un confort maximal sur tous les types de routes.', 3),
(17, 103, 'Sécurité complète', 'Éclairage LED, freins à disque et protection enfant pour une sécurité optimale.', 4);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_specification`
--

CREATE TABLE `trottinette_specification` (
  `id` int NOT NULL,
  `trottinette_id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL,
  `group_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_specification`
--

INSERT INTO `trottinette_specification` (`id`, `trottinette_id`, `name`, `value`, `position`, `group_id`) VALUES
(1, 17, 'Taille', '1380 x 320 x 630 mm', 1, 1),
(2, 17, 'Poids', '36 kg', 1, 1),
(3, 17, 'Batterie', '48 V 13 Ah', 1, 2),
(4, 17, 'Vitesse maximale', '45 km/h', 1, 2),
(5, 17, 'Autonomie', '40 km', 1, 2),
(6, 17, 'Charge maximale', '120 kg', 1, 3),
(7, 17, 'Sécurité enfant', 'Oui', 1, 4),
(8, 18, 'Taille', '1250 x 300 x 600 mm', 1, 1),
(9, 18, 'Poids', '32 kg', 2, 1),
(10, 18, 'Batterie', '48 V 10 Ah', 1, 2),
(11, 18, 'Vitesse maximale', '45 km/h', 1, 2),
(12, 18, 'Autonomie', '100 km', 1, 2),
(13, 18, 'Charge maximale', '120 kg', 1, 3),
(14, 18, 'Sécurité enfant', 'Non', 1, 4),
(15, 103, 'Taille', '1200 x 280 x 600 mm', 1, 1),
(16, 103, 'Poids', '28 kg', 1, 1),
(17, 103, 'Batterie', '48 V 15 Ah', 1, 2),
(18, 103, 'Vitesse maximale', '40 km/h', 1, 2),
(19, 103, 'Autonomie', '35 km', 1, 2),
(20, 103, 'Charge maximale', '120 kg', 1, 3),
(21, 103, 'Sécurité enfant', 'Oui', 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

CREATE TABLE `tva` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tva`
--

INSERT INTO `tva` (`id`, `name`, `value`) VALUES
(5, 'Taux standard', 21),
(6, 'Taux réduit', 6);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `tel`) VALUES
(2, 'admin@admin.com', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$sGAND4Mg8Dr11gl1trUMT.TH6v6FOlDaotBlQxBD1NHJm9DJzte2S', 'Admin', 'MHvolt', '0600000000'),
(3, 'user@user.com', '[\"ROLE_USER\"]', '$2y$13$zpRrFxGW/q/lWCuE44HGcurzZaJbObb1GXCKwH/8Rg.M8Q7JNY4My', 'Client', 'Test', '0611223344');

-- --------------------------------------------------------

--
-- Structure de la table `user_registration_token`
--

CREATE TABLE `user_registration_token` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `weight`
--

CREATE TABLE `weight` (
  `id` int NOT NULL,
  `kg` double NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `weight`
--

INSERT INTO `weight` (`id`, `kg`, `price`) VALUES
(1, 2, 5.9),
(2, 5, 9.9),
(3, 15, 19.9),
(4, 30, 29.9),
(5, 60, 49.9);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A1B1251C12469DE2` (`category_id`);

--
-- Index pour la table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D4E6F81A76ED395` (`user_id`);

--
-- Index pour la table `category_accessory`
--
ALTER TABLE `category_accessory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_66D1F72C989D9B62` (`slug`);

--
-- Index pour la table `home_video`
--
ALTER TABLE `home_video`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D67B9A424584665A` (`product_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Index pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_845CA2C1BFCDF877` (`my_order_id`),
  ADD KEY `IDX_845CA2C1EF85CBD0` (`product_entity_id`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D34A04AD989D9B62` (`slug`),
  ADD KEY `IDX_D34A04AD4D79775F` (`tva_id`);

--
-- Index pour la table `product_history`
--
ALTER TABLE `product_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F6636BFB4584665A` (`product_id`);

--
-- Index pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_C11D7DD177153098` (`code`),
  ADD KEY `IDX_C11D7DD1888AB5FF` (`category_access_id`),
  ADD KEY `IDX_C11D7DD14584665A` (`product_id`);

--
-- Index pour la table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD PRIMARY KEY (`promotion_id`,`product_id`),
  ADD KEY `IDX_8B37F297139DF194` (`promotion_id`),
  ADD KEY `IDX_8B37F2974584665A` (`product_id`);

--
-- Index pour la table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B9983CE5A76ED395` (`user_id`);

--
-- Index pour la table `site_config`
--
ALTER TABLE `site_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A42481235E237E06` (`name`);

--
-- Index pour la table `specification_group`
--
ALTER TABLE `specification_group`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trottinette_accessories_pivot`
--
ALTER TABLE `trottinette_accessories_pivot`
  ADD PRIMARY KEY (`trottinette_id`,`accessory_id`),
  ADD KEY `IDX_78D31E12F6798F43` (`trottinette_id`),
  ADD KEY `IDX_78D31E1227E8CC78` (`accessory_id`);

--
-- Index pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B92E215BF6798F43` (`trottinette_id`);

--
-- Index pour la table `trottinette_specification`
--
ALTER TABLE `trottinette_specification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F55E2F14F6798F43` (`trottinette_id`),
  ADD KEY `IDX_F55E2F14FE54D947` (`group_id`);

--
-- Index pour la table `tva`
--
ALTER TABLE `tva`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- Index pour la table `user_registration_token`
--
ALTER TABLE `user_registration_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7CD9E7285F37A13B` (`token`);

--
-- Index pour la table `weight`
--
ALTER TABLE `weight`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `category_accessory`
--
ALTER TABLE `category_accessory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `home_video`
--
ALTER TABLE `home_video`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT pour la table `product_history`
--
ALTER TABLE `product_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `site_config`
--
ALTER TABLE `site_config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `specification_group`
--
ALTER TABLE `specification_group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `trottinette_specification`
--
ALTER TABLE `trottinette_specification`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `tva`
--
ALTER TABLE `tva`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_registration_token`
--
ALTER TABLE `user_registration_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `weight`
--
ALTER TABLE `weight`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD CONSTRAINT `FK_A1B1251C12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category_accessory` (`id`),
  ADD CONSTRAINT `FK_A1B1251CBF396750` FOREIGN KEY (`id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD CONSTRAINT `FK_D67B9A424584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_845CA2C1BFCDF877` FOREIGN KEY (`my_order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_845CA2C1EF85CBD0` FOREIGN KEY (`product_entity_id`) REFERENCES `product` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD4D79775F` FOREIGN KEY (`tva_id`) REFERENCES `tva` (`id`);

--
-- Contraintes pour la table `product_history`
--
ALTER TABLE `product_history`
  ADD CONSTRAINT `FK_F6636BFB4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `FK_C11D7DD14584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_C11D7DD1888AB5FF` FOREIGN KEY (`category_access_id`) REFERENCES `category_accessory` (`id`);

--
-- Contraintes pour la table `promotion_product`
--
ALTER TABLE `promotion_product`
  ADD CONSTRAINT `FK_8B37F297139DF194` FOREIGN KEY (`promotion_id`) REFERENCES `promotion` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8B37F2974584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reset_password`
--
ALTER TABLE `reset_password`
  ADD CONSTRAINT `FK_B9983CE5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD CONSTRAINT `FK_44559939BF396750` FOREIGN KEY (`id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trottinette_accessories_pivot`
--
ALTER TABLE `trottinette_accessories_pivot`
  ADD CONSTRAINT `FK_78D31E1227E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_78D31E12F6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD CONSTRAINT `FK_B92E215BF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trottinette_specification`
--
ALTER TABLE `trottinette_specification`
  ADD CONSTRAINT `FK_F55E2F14F6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F55E2F14FE54D947` FOREIGN KEY (`group_id`) REFERENCES `specification_group` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
