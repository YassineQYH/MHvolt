-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 23 jan. 2026 à 19:01
-- Version du serveur : 8.4.3
-- Version de PHP : 8.2.30

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
(6, 1),
(14, 1),
(15, 1),
(16, 1),
(7, 2),
(8, 2),
(11, 2),
(4, 3),
(12, 3),
(13, 3),
(5, 4),
(9, 4),
(10, 4),
(18, 5),
(25, 5);

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`) VALUES
(1, 1, 'Maison', 'Yass', 'Qay', NULL, '51 Rue de Konoha', '63200', 'angleur', 'France', '06.11.55.22.51'),
(2, 2, 'Maison', 'Hich', 'Qay', NULL, '51 Belle jardinère', '63118', 'sart-tilman', 'FR', '+33601020304'),
(3, 1, 'Travail', 'Yass', 'Qay', NULL, '51 Rue du Hueco Mundo', '63200', 'clermont ferrand', 'France', '06.11.55.22.51'),
(6, 3, 'maison', 'pre test', 'nom test', NULL, 'rue davin', '4031', 'angleur', 'BE', '+32485968745');

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

CREATE TABLE `caracteristique` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `caracteristique`
--

INSERT INTO `caracteristique` (`id`, `name`, `categorie_id`) VALUES
(1, 'Taille', 5),
(2, 'Poids', 5),
(3, 'Batterie', 2),
(4, 'Vitesse maximale', 2),
(5, 'Autonomie', 2),
(6, 'Charge maximale', 6),
(7, 'Sécurité enfant', 7),
(8, 'Caractéristique 01', 8),
(9, 'Caractéristique 02', 9),
(10, 'Caractéristique 02.5', 9),
(11, 'Caractéristique 03', 10);

-- --------------------------------------------------------

--
-- Structure de la table `categorie_caracteristique`
--

CREATE TABLE `categorie_caracteristique` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_caracteristique`
--

INSERT INTO `categorie_caracteristique` (`id`, `name`) VALUES
(1, 'Informations générales'),
(2, 'Motorisation'),
(3, 'Freins'),
(4, 'Roues & Pneus'),
(5, 'Dimensions & Poids'),
(6, 'Autres particularités'),
(7, 'Équipement de sécurité'),
(8, 'Nouvelle catégorie temporaire des caractéristiques 01'),
(9, 'Nouvelle catégorie temporaire des caractéristiques 02'),
(10, 'Nouvelle catégorie temporaire des caractéristiques 03');

-- --------------------------------------------------------

--
-- Structure de la table `category_accessory`
--

CREATE TABLE `category_accessory` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `illustration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_accessory`
--

INSERT INTO `category_accessory` (`id`, `name`, `illustration`, `description`) VALUES
(1, 'Guidon', 'guidon-blunt-black-v2.jpg', '<div>guidon-blunt-black-v2</div>'),
(2, 'Freins', 'freins-rouge.jpg', '<div>freins</div>'),
(3, 'siege', 'siege-rouge.jpg', '<div>siege-trott-elec</div>'),
(4, 'Roues', 'roues-stunt.jpg', '<div>Roues-freestyle</div>'),
(5, 'Categorie 01 d\'accessoire', '2b753fdb0013b7a44b322b19405b.jpg', '<div>Categorie 01 d\'accessoire</div>');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20260107143346', '2026-01-07 15:37:01', 168),
('DoctrineMigrations\\Version20260114131843', '2026-01-14 14:19:00', 202),
('DoctrineMigrations\\Version20260121154605', '2026-01-21 16:46:18', 168),
('DoctrineMigrations\\Version20260123084635', '2026-01-23 09:47:29', 723),
('DoctrineMigrations\\Version20260123090023', '2026-01-23 10:00:31', 174);

-- --------------------------------------------------------

--
-- Structure de la table `home_video`
--

CREATE TABLE `home_video` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `headline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `position` int DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `home_video`
--

INSERT INTO `home_video` (`id`, `title`, `video_file`, `video_url`, `headline`, `subtitle`, `is_active`, `position`, `created_at`, `updated_at`) VALUES
(1, 'super trott', 'YTDown-com-YouTube-Le-business-model-du-marche-de-la-trotti-Media-82VKfuNn1GQ-001-1080p-69739c0e17cf3.mp4', NULL, 'Avengers Trottinette', 'Trottinette de superHero Marvel', 1, 1, '2026-01-07 16:02:19', '2026-01-23 17:04:30');

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `illustration`
--

INSERT INTO `illustration` (`id`, `product_id`, `image`) VALUES
(1, 1, 'trottbleue-02.png'),
(2, 1, 'trottbleue-03.png'),
(12, 3, 'trottvert-05.jpg'),
(13, 1, 'trottbleue-04.png'),
(14, 1, 'trottbleue-05.png'),
(15, 2, 'trottjaune-02.jpg'),
(16, 2, 'trottjaune-03.jpg'),
(17, 2, 'trottjaune-04.jpg'),
(18, 2, 'trottjaune-05.jpg'),
(19, 3, 'trottvert-02.jpg'),
(20, 3, 'trottvert-03.jpg'),
(21, 3, 'trottvert-04.jpg'),
(22, 4, 'siege-trott-elec.jpg'),
(23, 7, 'etrier-de-frein.jpg'),
(24, 4, 'Xtreme-Siege.jpg'),
(25, 4, 'Xtreme-Siege.jpg'),
(26, 4, 'Xtreme-Siege.jpg'),
(27, 4, 'Xtreme-Siege.jpg'),
(28, 5, 'roues-etoiles.jpg'),
(29, 5, 'roues-etoiles.jpg'),
(30, 5, 'roues-etoiles.jpg'),
(31, 6, 'guidon-blunt-black-v3.jpg'),
(32, 6, 'guidon-blunt-black-v3.jpg'),
(33, 6, 'guidon-blunt-black-v3.jpg'),
(34, 7, 'freins-jaune.jpg'),
(35, 7, 'freins-jaune.jpg'),
(36, 7, 'freins-jaune.jpg'),
(37, 8, 'etrier-de-frein.jpg'),
(38, 8, 'etrier-de-frein.jpg'),
(39, 8, 'etrier-de-frein.jpg'),
(40, 9, 'Roues-freestyle.jpg'),
(41, 9, 'Roues-freestyle.jpg'),
(42, 9, 'Roues-freestyle.jpg'),
(43, 10, 'roues-gold.jpg'),
(44, 10, 'roues-gold.jpg'),
(45, 10, 'roues-gold.jpg'),
(46, 11, 'roues-stunt.jpg'),
(47, 11, 'roues-stunt.jpg'),
(48, 11, 'roues-stunt.jpg'),
(49, 12, 'freins-rouge.jpeg'),
(50, 12, 'freins-rouge.jpeg'),
(51, 12, 'freins-rouge.jpeg'),
(52, 13, 'siege-double.jpg'),
(53, 13, 'siege-double.jpg'),
(54, 13, 'siege-double.jpg'),
(55, 14, 'siege-rouge.jpg'),
(56, 14, 'siege-rouge.jpg'),
(57, 14, 'siege-rouge.jpg'),
(58, 15, 'Xtreme-Siege.jpg'),
(59, 15, 'Xtreme-Siege.jpg'),
(60, 15, 'Xtreme-Siege.jpg'),
(61, 16, 'guidon-blunt-black-v3.jpg'),
(62, 16, 'guidon-blunt-black-v3.jpg'),
(63, 16, 'guidon-blunt-black-v3.jpg'),
(64, 15, 'guidon-multicolor.jpg'),
(65, 15, 'guidon-multicolor.jpg'),
(66, 15, 'guidon-multicolor.jpg'),
(67, 16, 'guidon-titanium.jpg'),
(68, 16, 'guidon-titanium.jpg'),
(69, 16, 'guidon-titanium.jpg'),
(70, 1, 'trottbleue-01.png'),
(71, 1, 'trottbleue-02.png'),
(72, 1, 'trottbleue-03.png'),
(73, 17, '004.png'),
(74, 17, '005.png'),
(75, 18, '001.png'),
(76, 18, '002.png'),
(77, 17, '025 - Pikatchu - LBP.png'),
(78, 18, '074 - Racailloux - YQ.png'),
(87, 5, 'FB_IMG_1623620188790.jpg'),
(88, 23, '6973b1ca81fa4.jpg'),
(89, 23, '6973b1ca827f5.jpg'),
(90, 25, '6973b39a7393f.jpg'),
(91, 25, '6973b39a7488b.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:28:\\\"Symfony\\\\Component\\\\Mime\\\\Email\\\":6:{i:0;N;i:1;N;i:2;s:212:\\\"Bonjour yassine,<br><br>Cliquez sur ce lien pour créer votre mot de passe : <a href=\\\'https://mhvolt.test/set-password/876f97ce-f45e-45d1-93fb-3a789ddc64cd\\\'>Créer mon mot de passe</a><br>Ce lien expire dans 24h.\\\";i:3;s:5:\\\"utf-8\\\";i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:23:\\\"no-reply@hich-trott.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:21:\\\"yassine.qyh@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:31:\\\"Création de votre mot de passe\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2026-01-22 09:13:42', '2026-01-22 09:13:42', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_state` int NOT NULL,
  `delivery_state` int NOT NULL,
  `tracking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_carrier_tracking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_carrier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_reduction` double DEFAULT NULL,
  `promo_titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `carrier_price`, `delivery`, `reference`, `stripe_session_id`, `payment_state`, `delivery_state`, `tracking_number`, `carrier`, `secondary_carrier_tracking_number`, `secondary_carrier`, `promo_code`, `promo_reduction`, `promo_titre`) VALUES
(1, 1, '2025-10-21 14:27:06', 23.22, 'Yass Qay 06.11.55.22.51 51 Rue de Konoha 63200 angleur<br>Franc', '21102025-68f77c1a7c6dd', NULL, 0, 0, '13211311123113112211', 'bpost', NULL, NULL, NULL, NULL, NULL),
(2, 1, '2025-10-21 14:29:08', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77c9423c2c', NULL, 0, 0, '31131211131221', 'bpost', NULL, NULL, NULL, NULL, NULL),
(3, 1, '2025-10-21 14:29:28', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77ca83d2a7', NULL, 0, 0, '1113213211', 'bpost', NULL, NULL, NULL, NULL, NULL),
(4, 1, '2025-10-21 14:31:59', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77d3f4629b', 'cs_test_b1IFG5Q9gll0ZuAgc3zlYqDKvwrd6uJOHwJcWVZSGbXo8AewDIYVEWUcv9', 0, 0, 'CC088942925FR', 'bpost', NULL, NULL, NULL, NULL, NULL),
(5, 1, '2025-10-21 14:32:57', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77d799ff9b', 'cs_test_b1hL2avKtpCc9IokW6GFqrzSV3jEvMmzBlqosbd8TBlykrOREGjpR1c7YE', 1, 1, '6G61316524338', 'bpost', NULL, NULL, NULL, NULL, NULL),
(6, 1, '2025-10-21 16:34:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a09bd15f', NULL, 1, 1, '6G61366363482', 'bpost', NULL, NULL, NULL, NULL, NULL),
(7, 1, '2025-10-21 16:35:23', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a2bbf571', NULL, 1, 3, '6G61397378424', 'colissimo', NULL, NULL, NULL, NULL, NULL),
(8, 1, '2025-10-21 16:37:04', 19.8, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a902672b', 'cs_test_b11AmvahjrP8RuTIhMPNCtqZwyg6T0fJQ81CLWFBzisnEATWsvQWmN8fSD', 1, 1, '6G61397895822', 'colissimo', NULL, NULL, NULL, NULL, NULL),
(9, 1, '2025-10-22 09:35:14', 19.8, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '22102025-68f88932a6b2e', 'cs_test_b1MfDJ2kREupjottpXlscZl1IhorILdWvOFmrGBHtokfu5bVWFuqXapsYo', 1, 2, '6G61398207501', 'colissimo', NULL, NULL, NULL, NULL, NULL),
(11, 1, '2025-10-24 16:05:28', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24102025-68fb87a892a1e', 'cs_test_b1WyzxbI3cyBvwK3HaidvFnWnn9KC5Jcw7aihkIPGyNNW9iLukn8DDkEii', 1, 2, '6G61398207501', 'bpost', NULL, NULL, NULL, NULL, NULL),
(12, 1, '2025-11-07 16:17:59', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0da74f3bb', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(13, 1, '2025-11-07 16:18:34', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0dcac0a47', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(14, 1, '2025-11-07 16:19:23', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0dfb84c42', 'cs_test_b1dBawcIBxgReWwFYdp09zUSRn0vimwwlZwUe6m2CXGlYsTdow69zvzqmT', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(15, 1, '2025-11-12 11:12:15', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69145d7f8c9aa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(16, 1, '2025-11-12 11:19:26', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69145f2e7cd25', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(17, 1, '2025-11-12 14:20:27', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-6914899bf088f', 'cs_test_b16icOL3eKCOGSrpXI3joO157yTvlZIKVeDH6eR9PGlS0syx3O2afkwIbk', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(18, 1, '2025-11-12 14:24:54', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69148aa67739b', 'cs_test_b16mYHKZuSZ7gTMJK7Ww3962lF1lGARoS7euwCkcFAjjPjhi7NwoQNSr22', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(19, 1, '2025-11-19 11:15:04', 32.28, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '19112025-691d98a8a849f', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(20, 1, '2025-11-19 11:16:03', 32.28, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '19112025-691d98e3552fb', 'cs_test_b1iOr5roGWuli6Qegu7XxdSlHYXZdbyiyH5N63YSlfHA2PbRdCVjWmzLMW', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(21, 1, '2025-11-20 09:54:18', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed73ac2b2c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(22, 1, '2025-11-20 09:58:27', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed833da4c8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(23, 1, '2025-11-20 10:01:07', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed8d3d4876', 'cs_test_b1SIqJGeIaesZnuUNln1hbcdEjBp3cnFCknphr3nqV9RApBwtgEF9mYedD', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(24, 1, '2025-11-21 10:25:24', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69203004670c1', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(25, 1, '2025-11-21 14:30:01', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206959f2044', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(26, 1, '2025-11-21 14:39:14', 0, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206b82550be', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(27, 1, '2025-11-21 14:41:07', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206bf310820', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(28, 1, '2025-11-21 14:53:14', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206eca7e072', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(29, 1, '2025-11-21 14:55:09', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206f3d0c3b8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(30, 1, '2025-11-21 14:56:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206f902430d', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(31, 1, '2025-11-21 14:59:52', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207058e8b2a', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(32, 1, '2025-11-21 15:02:11', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692070e3087a8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(33, 1, '2025-11-21 15:02:36', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692070fc1a2fa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(34, 1, '2025-11-21 15:02:43', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207103b245e', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(35, 1, '2025-11-21 15:03:19', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207127c5cb7', 'cs_test_b1CToltoIUvDc7BiAvOuuTnrBa4jMYxRJFKqnyK5n0jRhMzFUQpyi97Q56', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(36, 1, '2025-11-21 15:10:55', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692072efc7298', 'cs_test_b11PX2F0pIqPXdxOfw7AEl9E6NxEnrIgxTaOH933jv76lQMgLVRHEdcXc7', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(38, 1, '2025-11-21 15:20:26', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920752a758ca', 'cs_test_b15QApSnckG00aqtR2lB3s8K71dX93r7E6LCcstV3P8ASBpTWlxaJsDzxd', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(39, 1, '2025-11-21 15:24:31', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920761fea2de', 'cs_test_b1wcjF2o3ohl1H4bLvAo6ckSMtq5LD8BFVwCiBdPjtc4Z7xI2AV4DjPmzP', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(40, 1, '2025-11-21 15:27:09', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692076bd78bbd', 'cs_test_b1NcmTfawrvu2DoO6U9ftJHLlynN7gwgBRdq8Xe4Xz0Gf5Za1DESLomH03', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(41, 1, '2025-11-21 15:31:29', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692077c14302c', 'cs_test_b1V8UOdDZ1LdEZag7qyT716AnuAwbUbdzCpy0SG6UNxFCvJ8vAOhR7U5gv', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(42, 1, '2025-11-21 16:28:46', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920852ee7076', 'cs_test_b1X4XMyohiuCr1k9Ee129fJgLjCrKv7lrafxmbSM9i7UYVnds0OIWcju3j', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(43, 1, '2025-11-24 10:15:46', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-69242242dea4c', 'cs_test_b1F7mdk5W0C4z4pUgC9IddZHtxKj1FXdVj1tYDjxFvpkmerJi4Y46pAfP9', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(44, 1, '2025-11-24 10:17:52', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692422c03dc77', 'cs_test_b1QgCwSkD1p4kJ4m8P8LJAnW0VYK9lTtVaGnRzKR9iDu3Kf2eABBrq9DIr', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL, NULL),
(45, 1, '2025-11-24 10:39:45', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692427e179c6d', 'cs_test_b1BPppiDPYl3bcHHfCEGQEBkq7g6t8pFBWbLzPtfETE3bCliuWu1xYMCIX', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 0, NULL),
(46, 1, '2025-11-24 10:42:17', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692428791d7d4', 'cs_test_b1g4259dT5UwNA6BHVulQSAPPZyFUaGVkYTQMJlRfE8wAniMIbl7nTEH7N', 1, 0, NULL, 'bpost', NULL, NULL, 'family-25', 212.8575, NULL),
(47, 1, '2025-12-04 09:19:15', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-6931440321104', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(48, 1, '2025-12-04 09:22:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-693144c8ef4fc', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(49, 1, '2025-12-04 09:22:53', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-693144dda39f3', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(50, 1, '2025-12-04 09:25:57', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-69314595d1662', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(51, 1, '2025-12-04 09:35:20', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-693147c81879b', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(52, 1, '2025-12-04 09:35:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-693147d4d0382', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(53, 1, '2025-12-04 15:49:08', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-69319f649bdb0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(54, 1, '2025-12-04 15:49:51', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-69319f8fd7eb8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(55, 1, '2025-12-04 15:50:30', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-69319fb6371fa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(56, 1, '2025-12-04 15:54:01', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-6931a0897b365', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(57, 1, '2025-12-04 15:54:03', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '04122025-6931a08bf4022', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(59, 1, '2025-12-09 12:13:11', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938044750558', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(60, 1, '2025-12-09 12:18:30', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69380586f08aa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(61, 1, '2025-12-09 13:16:02', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938130219b78', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(62, 1, '2025-12-09 13:16:23', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693813170cef2', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(63, 1, '2025-12-09 13:17:59', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69381377b303c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(64, 1, '2025-12-09 13:22:29', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693814858f450', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(65, 1, '2025-12-09 13:22:51', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938149b029f1', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(66, 1, '2025-12-09 13:25:17', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938152db86d0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(67, 1, '2025-12-09 15:03:38', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69382c3a38b87', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(68, 1, '2025-12-09 15:06:00', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69382cc8b97d2', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 161, NULL),
(69, 1, '2025-12-09 15:25:01', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938313d2141c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(70, 1, '2025-12-09 15:26:05', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938317d5d395', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(71, 1, '2025-12-09 15:35:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693833c57bd77', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(72, 1, '2025-12-09 15:45:57', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693836252f71f', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(73, 1, '2025-12-09 15:46:05', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938362da1d14', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(74, 1, '2025-12-09 15:46:13', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383635ed178', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(75, 1, '2025-12-09 15:49:54', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383712e0fb7', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(76, 1, '2025-12-09 15:52:12', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938379c1280d', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(77, 1, '2025-12-09 15:52:29', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693837add7c84', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(78, 1, '2025-12-09 15:57:21', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693838d1063e3', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(79, 1, '2025-12-09 16:02:45', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383a151b1fc', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(80, 1, '2025-12-09 16:03:39', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383a4b6b1a5', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(81, 1, '2025-12-09 16:04:28', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383a7c3346a', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(82, 1, '2025-12-09 16:05:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383acded351', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(83, 1, '2025-12-09 16:06:36', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383afc787ab', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(84, 1, '2025-12-09 16:07:39', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383b3bc4db3', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(85, 1, '2025-12-09 16:08:53', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383b8591dde', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(86, 1, '2025-12-09 16:10:31', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383be787fbc', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(87, 1, '2025-12-09 16:12:06', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383c4666ca6', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(88, 1, '2025-12-09 16:12:13', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383c4df29d8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(89, 1, '2025-12-09 16:13:42', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383ca61fbf0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(90, 1, '2025-12-09 16:14:40', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383ce0539b9', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(91, 1, '2025-12-09 16:17:00', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383d6c11aef', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(92, 1, '2025-12-09 16:17:15', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383d7b4ce28', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(93, 1, '2025-12-09 16:17:40', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383d948e1d0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(94, 1, '2025-12-09 16:17:59', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383da75fc1c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(95, 1, '2025-12-09 16:21:54', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383e92d3b92', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(96, 1, '2025-12-09 16:23:51', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383f078897e', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(97, 1, '2025-12-09 16:25:10', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383f56b5660', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(98, 1, '2025-12-09 16:26:21', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383f9dc148b', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(99, 1, '2025-12-09 16:26:31', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383fa76042f', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(100, 1, '2025-12-09 16:26:38', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69383fae3913c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(101, 1, '2025-12-09 16:29:05', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693840416f21b', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(102, 1, '2025-12-09 16:29:14', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938404ab6afd', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(103, 1, '2025-12-09 16:29:24', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69384054487ac', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(104, 1, '2025-12-09 16:30:55', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693840af1548f', 'cs_test_b1G99gZAe3htUIlFDYYlBeLVXl4KvTCEAcQ3VY24mr3QLdSBE1RT6iviHN', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(105, 1, '2025-12-09 16:43:17', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69384395b5ede', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(106, 1, '2025-12-09 16:43:27', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938439f4ba70', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(107, 1, '2025-12-09 16:58:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938473921f72', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(108, 1, '2025-12-09 17:02:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-6938482936a18', 'cs_test_b1sPM4Njx149BNOFOqf32aLhkcqjiekiksvpBpRAlihocmKM1Ylylasv9J', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(109, 1, '2025-12-09 17:10:36', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-693849fcaf948', 'cs_test_b12lxbgN0NqHZpvyZuzWW1gqdkQ04haocQzzeK36lJyFBNNqDHTzUwVSvp', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(110, 1, '2025-12-09 17:14:47', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69384af7e18d7', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(111, 1, '2025-12-09 17:22:59', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69384ce373c89', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(112, 1, '2025-12-09 17:23:01', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '09122025-69384ce54d9e0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(113, 1, '2025-12-09 17:23:12', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue du Hueco Mundo<br>63200 clermont ferrand<br>France', '09122025-69384cf029ae7', 'cs_test_b13Jy0iawxHNoCkgQCnX4o0BxJOUHBuA1olTW70Bik1jtXKI4NwtR5X2Br', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(114, 1, '2025-12-10 09:02:53', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-6939292d54811', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(115, 1, '2025-12-10 10:03:40', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-6939376c3afb3', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(116, 1, '2025-12-10 10:07:29', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938515a293', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(117, 1, '2025-12-10 10:08:28', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-6939388c56beb', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(118, 1, '2025-12-10 10:08:56', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938a8d50d9', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(119, 1, '2025-12-10 10:09:03', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938afde859', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(120, 1, '2025-12-10 10:09:17', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938bd42a5a', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(121, 1, '2025-12-10 10:09:26', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938c6b9482', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(122, 1, '2025-12-10 10:09:34', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938ce82717', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(123, 1, '2025-12-10 10:09:43', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938d7258dc', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(124, 1, '2025-12-10 10:09:50', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693938de9a95f', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(125, 1, '2025-12-10 10:18:47', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-69393af7a4cb0', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(126, 1, '2025-12-10 11:21:57', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '10122025-693949c552e65', 'cs_test_b12r5EJQRJIUT5CmQPWTiAZ0xC8HpxDGHI4kg4iEgEW86mtL2fepjPAy1y', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(127, 1, '2025-12-11 09:06:26', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a7b8267748', 'cs_test_b1tOpIiclcHe5LSaXI3hAG6w0s3sjWMuc7pYUm1QnXLzi2JYuHg8e7C51i', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(128, 1, '2025-12-11 09:40:23', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a8377284a8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(129, 1, '2025-12-11 09:41:17', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a83adaf81a', 'cs_test_b17FQsacPaOFvYDDcl18moVMLIUPBhe5Vy4CA52wLWX8mHH7vXUtyubNE3', 0, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(130, 1, '2025-12-11 09:45:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a84bdd8d27', 'cs_test_b1oubqzbQ0iZnB2XxACBDyKBTiGrrruN8XFLl2UR7HDiaHisuAblrNqXOh', 1, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(131, 1, '2025-12-11 09:50:37', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a85dd5ed19', 'cs_test_b1UJjijuN7EjQXQpZSLhHfSrskNfUKLaWadBCcBm54qmu2o2UdwxeAHIWo', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 116.886, NULL),
(132, 1, '2025-12-11 09:51:39', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693a861bf24ff', 'cs_test_b1zuvasG8QgkLtO23bqlEDoSypGYek4lK3ZwsHlse1ic43jNnXZAT7zuTe', 1, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(133, 1, '2025-12-11 12:40:09', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693aad9970da1', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, 116.886, NULL),
(134, 1, '2025-12-11 12:40:17', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue du Hueco Mundo<br>63200 clermont ferrand<br>France', '11122025-693aada11a86f', 'cs_test_b1zDtVJ4x9AXOAanJhps4fGVtXMr09YuUI88TdSHRbeuh2WyueQlZ0Y8Ul', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 116.886, NULL),
(135, 1, '2025-12-11 12:46:56', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue du Hueco Mundo<br>63200 clermont ferrand<br>France', '11122025-693aaf307f501', 'cs_test_b1LpXilrS1P9qg3ZWiUkLH9zD9bqEMv4VekugMBrQBygo2yxeotw8xtCTG', 1, 0, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, NULL),
(136, 1, '2025-12-11 13:03:55', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue du Hueco Mundo<br>63200 clermont ferrand<br>France', '11122025-693ab32b03bd4', 'cs_test_b136SiSBeUKH6ZAHtBkUXsltDqZc58Za7Yo0llOQ6YM2HfRdSQOynbvXii', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 116.886, 'Black Friday 15%'),
(138, 1, '2025-12-11 14:15:03', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693ac3d7c3d45', 'cs_test_b16Vf0KeYxbCduGE8RVv9yqneyn2m6UeqK4JOiephwzlAQ7xmye9r7uCju', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 116.886, 'Black Friday 15%'),
(139, 1, '2025-12-11 14:16:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693ac430f350c', 'cs_test_b1akeV83mtncJRUDijN2McomhVYQmrWTArMUUk8EneI8D4e30b7TIXON9g', 1, 0, 'TEST-624895410', 'bpost', NULL, NULL, 'family-25', 194.81, 'Famille 25%'),
(140, 1, '2025-12-11 15:35:40', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '11122025-693ad6bc16610', 'cs_test_b1nu7oXIO6RcLhbLP7yGhWOGvVKyaVfFoZP0YRZBB7d3PaDT2LoBtkLFpW', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 0, NULL),
(141, 1, '2025-12-12 16:43:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12122025-693c38246b0a1', 'cs_test_b1tbaMFjVPHxZn7ouQNoXuiSO439AeNJzug7b2s7dowWho3EuN3YRQiO3D', 1, 4, NULL, 'bpost', NULL, NULL, 'family-25', 194.81, 'Famille -25%'),
(142, 1, '2025-12-23 15:15:43', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '23122025-694aa40fe7ee4', 'cs_test_b1U56bhB2q2ZxS5Hf61B9bgMlsQPYu35wLFxVYaNaujsk9s9HzmpIHK6QW', 0, 0, NULL, 'bpost', NULL, NULL, NULL, 111.441, 'Black Friday -15%'),
(143, 1, '2025-12-23 15:22:04', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '23122025-694aa58cd74ad', 'cs_test_b1S890AxEGkhN3yUVBqvHIWqzILw9YknN6VnLrqnFLKripLpMwRQTUge0c', 0, 0, NULL, 'bpost', NULL, NULL, NULL, 111.441, 'Black Friday -15%'),
(144, 1, '2025-12-23 15:23:23', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '23122025-694aa5db370e9', 'cs_test_b1gJ9Dj5W28MwY7MSvHeYi1cCGfk4KxfldW6ZPylmPdC6MoFFl0lSJJtZg', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 111.441, 'Black Friday -15%'),
(145, 3, '2026-01-14 10:21:02', 24.68, 'pre test nom test<br>+32485968745<br>rue davin<br>4031 angleur<br>BE', '14012026-69675ffeb7164', 'cs_test_b1EKjeQS1dCiRPf5ushRJT7ug33sBscMWoLeoYcxvBdv8YV8YpGZon9knK', 1, 2, 'TEST-333724131', 'bpost', NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `my_order_id` int NOT NULL,
  `product_entity_id` int DEFAULT NULL,
  `product` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL,
  `weight` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tva` double DEFAULT NULL,
  `price_ttc` double NOT NULL,
  `price_after_reduc` double DEFAULT NULL,
  `total_after_reduc` double DEFAULT NULL,
  `price_ttc_after_reduc` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `my_order_id`, `product_entity_id`, `product`, `quantity`, `price`, `total`, `weight`, `tva`, `price_ttc`, `price_after_reduc`, `total_after_reduc`, `price_ttc_after_reduc`) VALUES
(1, 1, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(2, 1, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(3, 1, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(4, 1, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(5, 2, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(6, 2, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(7, 2, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(8, 2, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(9, 3, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(10, 3, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(11, 3, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(12, 3, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(13, 4, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(14, 4, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(15, 4, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(16, 4, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(17, 5, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(18, 5, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(19, 5, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(20, 5, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(21, 6, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(22, 6, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(23, 6, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(24, 7, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(25, 7, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(26, 7, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(27, 8, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(28, 8, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(29, 8, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(30, 8, 9, 'Roues freestyle', 1, 1, 1, '0.75', 21, 0, NULL, NULL, NULL),
(31, 9, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(32, 9, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0, NULL, NULL, NULL),
(33, 9, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0, NULL, NULL, NULL),
(34, 9, 9, 'Roues freestyle', 1, 1, 1, '0.75', 21, 0, NULL, NULL, NULL),
(38, 11, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(39, 11, 4, 'Xtreme Siege', 1, 15, 15, '0.75', 21, 0, NULL, NULL, NULL),
(40, 12, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(41, 12, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(42, 13, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(43, 13, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(44, 14, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(45, 14, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(46, 15, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(47, 15, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0, NULL, NULL, NULL),
(48, 16, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(49, 16, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0, NULL, NULL, NULL),
(50, 17, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(51, 17, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0, NULL, NULL, NULL),
(52, 18, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(53, 18, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0, NULL, NULL, NULL),
(54, 19, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(55, 19, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(56, 19, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(57, 20, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(58, 20, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(59, 20, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(60, 21, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(61, 21, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(62, 22, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(63, 22, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(64, 23, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(65, 23, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(66, 24, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0, NULL, NULL, NULL),
(67, 24, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(68, 25, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(69, 25, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(70, 27, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(71, 27, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(72, 28, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(73, 28, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(74, 29, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(75, 29, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(76, 30, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(77, 30, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(78, 31, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(79, 31, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(80, 32, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(81, 32, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(82, 33, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(83, 33, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(84, 34, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(85, 34, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(86, 35, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(87, 35, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(88, 36, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0, NULL, NULL, NULL),
(89, 36, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0, NULL, NULL, NULL),
(90, 38, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(91, 38, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(92, 39, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(93, 39, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(94, 40, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(95, 40, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(96, 41, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(97, 41, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(98, 42, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(99, 42, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(100, 43, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(101, 43, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69, NULL, NULL, NULL),
(102, 44, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(103, 44, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69, NULL, NULL, NULL),
(104, 45, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(105, 45, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69, NULL, NULL, NULL),
(106, 46, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(107, 46, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69, NULL, NULL, NULL),
(108, 47, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(109, 47, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(110, 48, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(111, 48, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(112, 49, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(113, 49, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(114, 50, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(115, 50, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(116, 51, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(117, 51, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(118, 52, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(119, 52, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(120, 53, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(121, 53, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(122, 54, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(123, 54, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(124, 55, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(125, 55, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(126, 56, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(127, 56, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(128, 57, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(129, 57, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(130, 59, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(131, 59, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(132, 60, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(133, 60, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(134, 61, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(135, 61, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(136, 62, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(137, 62, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(138, 63, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(139, 63, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(140, 64, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(141, 64, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(142, 65, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(143, 65, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(144, 66, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(145, 66, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(146, 67, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(147, 67, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(148, 68, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(149, 68, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(150, 69, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(151, 69, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(152, 70, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(153, 70, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(154, 71, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(155, 71, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(156, 72, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(157, 72, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(158, 73, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(159, 73, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(160, 74, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(161, 74, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(162, 75, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(163, 75, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(164, 76, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(165, 76, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(166, 77, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(167, 77, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(168, 78, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(169, 78, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(170, 79, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(171, 79, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(172, 80, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(173, 80, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(174, 81, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(175, 81, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(176, 82, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(177, 82, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(178, 83, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(179, 83, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(180, 84, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(181, 84, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(182, 85, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(183, 85, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(184, 86, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(185, 86, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(186, 87, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(187, 87, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(188, 88, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(189, 88, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(190, 89, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(191, 89, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(192, 90, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(193, 90, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(194, 91, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(195, 91, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(196, 92, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(197, 92, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(198, 93, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(199, 93, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(200, 94, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(201, 94, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(202, 95, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(203, 95, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(204, 96, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(205, 96, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(206, 97, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(207, 97, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(208, 98, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(209, 98, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(210, 99, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(211, 99, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(212, 100, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(213, 100, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(214, 101, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(215, 101, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(216, 102, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(217, 102, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(218, 103, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(219, 103, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(220, 104, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(221, 104, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(222, 105, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(223, 105, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(224, 106, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(225, 106, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(226, 107, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(227, 107, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(228, 108, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(229, 108, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(230, 109, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(231, 109, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(232, 110, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(233, 110, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(234, 111, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(235, 111, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(236, 112, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(237, 112, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(238, 113, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(239, 113, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(240, 114, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(241, 114, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(242, 115, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(243, 115, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(244, 116, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(245, 116, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(246, 117, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(247, 117, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(248, 118, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(249, 118, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(250, 119, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(251, 119, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(252, 120, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(253, 120, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(254, 121, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(255, 121, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(256, 122, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(257, 122, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(258, 123, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(259, 123, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(260, 124, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(261, 124, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(262, 125, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(263, 125, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(264, 126, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(265, 126, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(266, 127, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(267, 127, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(268, 128, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(269, 128, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(270, 129, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(271, 129, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(272, 130, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(273, 130, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(274, 131, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(275, 131, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(276, 132, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(277, 132, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(278, 133, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(279, 133, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(280, 134, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(281, 134, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(282, 135, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(283, 135, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(284, 136, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, NULL, NULL, NULL),
(285, 136, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, NULL, NULL, NULL),
(288, 138, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 509.15, 509.15, 616.0715),
(289, 138, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, 38.25, 38.25, 46.2825),
(290, 139, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 449.25, 449.25, 543.5925),
(291, 139, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, 33.75, 33.75, 40.8375),
(292, 140, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 599, 599, 724.79),
(293, 140, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, 45, 45, 54.45),
(294, 141, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 449.25, 449.25, 543.5925),
(295, 141, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45, 33.75, 33.75, 40.8375),
(296, 142, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 509.15, 509.15, 616.0715),
(297, 142, 4, 'Xtreme Siege', 1, 15, 15, '4', 21, 18.15, 12.75, 12.75, 15.4275),
(298, 143, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 509.15, 509.15, 616.0715),
(299, 143, 4, 'Xtreme Siege', 1, 15, 15, '4', 21, 18.15, 12.75, 12.75, 15.4275),
(300, 144, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 509.15, 509.15, 616.0715),
(301, 144, 4, 'Xtreme Siege', 1, 15, 15, '4', 21, 18.15, 12.75, 12.75, 15.4275),
(302, 145, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79, 599, 599, 724.79),
(303, 145, 5, 'roues etoiles', 1, 45, 45, '7', 21, 54.45, 45, 45, 54.45);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int NOT NULL,
  `tva_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL,
  `stock` int NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `tva_id`, `name`, `slug`, `description`, `price`, `stock`, `is_best`, `created_at`, `updated_at`, `type`, `weight`) VALUES
(1, 1, 'Trottinette électrique honey whale m5 max avec siège', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '<div>【Performance puissante】...</div>', 599, 5, 1, '2025-11-18 16:19:20', '2026-01-14 10:26:14', 'trottinette', 14),
(2, 1, 'KUGOO Kukirin C1 Pro', 'KUGOO-Kukirin-C1-Pro', 'Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège', 1299, 4, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'trottinette', 11),
(3, 1, 'Bogist M5 Pro', 'Bogist-M5-Pro', 'Moteur puissant de 500 W pour des vitesses élevées...', 754, 2, 1, '2025-11-18 16:19:20', '2025-11-19 11:30:29', 'trottinette', 17),
(4, 1, 'Xtreme Siege', 'Xtreme-Siege', 'Siege pour trott', 15, 1, 1, '2025-11-18 16:19:20', '2025-12-23 15:23:34', 'accessoire', 4),
(5, 1, 'roues etoiles', 'roues-etoiles', '<div>Roue pour trott</div>', 45, 6, 1, '2025-11-18 16:19:20', '2026-01-14 10:26:14', 'accessoire', 7),
(6, 1, 'guidon blunt black v3', 'guidon-blunt-black-v3', 'Guidon pour trott', 49, 1, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 2),
(7, 1, 'Frein Jaune', 'freinfreins-jaune', 'Frein pour trott', 19, 0, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 4),
(8, 1, 'etrier de frein', 'etrier-de-frein', 'etrier-de-frein', 56, 2, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 5),
(9, 1, 'Roues freestyle', 'Roues-freestyle', 'Roues-freestyle', 1, 1, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 8),
(10, 1, 'roues gold', 'roues-gold', 'roues-gold', 89, 5, 1, '2025-11-18 16:19:20', '2025-11-24 10:42:47', 'accessoire', 6),
(11, 1, 'roues stunt', 'roues-stunt', 'roues-stunt', 55, 4, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 3),
(12, 1, 'freins rouge', 'freins-rouge', 'freins-rouge', 26, 1, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 4),
(13, 1, 'siege double', 'siege-double', 'siege-double', 21, 2, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 6),
(14, 1, 'siege rouge', 'siege-rouge', 'siege-rouge', 45, 5, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 9),
(15, 1, 'guidon multicolor', 'guidon-multicolor', '<div>guidon-multicolor</div>', 25, 4, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire', 5),
(16, 1, 'Guidon Titanium', 'guidon-titanium', '<div>guidon-titanium</div>', 48, 0, 0, '2025-11-18 16:19:20', '2025-11-28 10:11:29', 'accessoire', 6),
(17, 1, 'Trottinette de test', 'trottinette-de-test', '<div>descrition de la Trottinette de test</div>', 50, 4, 0, '2026-01-22 14:21:27', '2026-01-23 15:54:18', 'trottinette', 5),
(18, 1, 'access de test', 'access-de-test', '<div>description de access test</div>', 50, 3, 0, '2026-01-22 15:47:12', '2026-01-23 15:53:44', 'accessoire', 4),
(23, 1, 'Trottinette Ultra Instinct', 'trottinette-ultra-instinct', '<div>Description Trottinette Ultra Instinct</div>', 150, 3, 0, '2026-01-23 18:37:14', '2026-01-23 18:37:14', 'trottinette', 50),
(25, 1, 'access SSJ', 'access-ssj', '<div>Description access SSJ</div>', 60, 2, 0, '2026-01-23 18:44:58', '2026-01-23 18:44:58', 'accessoire', 20);

-- --------------------------------------------------------

--
-- Structure de la table `product_history`
--

CREATE TABLE `product_history` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stock` int NOT NULL,
  `price` double DEFAULT NULL,
  `main_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id` int NOT NULL,
  `category_access_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` double DEFAULT NULL,
  `discount_percent` double DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `quantity` int NOT NULL,
  `used` int NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_apply` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id`, `category_access_id`, `product_id`, `code`, `target_type`, `discount_amount`, `discount_percent`, `start_date`, `end_date`, `quantity`, `used`, `titre`, `auto_apply`) VALUES
(5, NULL, NULL, 'family-25', 'all', NULL, 25, '2025-11-20 16:09:00', NULL, 8, 8, 'Famille -25%', 0),
(6, 4, NULL, 'promo-roues', 'category_access', 5, NULL, '2025-11-26 14:25:00', NULL, 3, 0, 'Roues -5€', 0),
(7, NULL, 2, 'promo-kugoo', 'product', 200, NULL, '2025-11-26 16:16:00', NULL, 3, 0, 'Kugoo -200€', 0),
(8, NULL, NULL, 'promo-rouge', 'product_list', 10, NULL, '2025-11-17 16:34:00', '2025-12-29 23:59:00', 3, 0, 'Rouge -10€', 0),
(9, NULL, NULL, 'family-25-', 'all', 25, NULL, '2025-11-26 16:59:00', NULL, 4, 0, 'Famille -25€', 0),
(10, 4, NULL, 'promo-roues-', 'category_access', NULL, 10, '2025-11-26 17:00:00', NULL, 2, 0, 'Roues -10%', 0),
(11, NULL, 2, 'promo-kugoo-', 'product', NULL, 10, '2025-11-26 17:11:00', '2025-11-28 12:00:00', 2, 0, 'Kugoo -10%', 0),
(12, NULL, NULL, 'promo-rouge-', 'product_list', NULL, 10, '2025-11-26 17:13:00', NULL, 1, 0, 'Rouge -10%', 0),
(13, NULL, NULL, NULL, 'all', NULL, 15, '2025-11-28 10:06:00', '2025-12-26 23:59:00', 50, 0, 'Black Friday -15%', 1),
(14, 2, NULL, NULL, 'category_access', 100, NULL, '2025-11-28 10:40:00', '2025-12-07 23:59:00', 20, 0, 'Noël -100€', 0);

-- --------------------------------------------------------

--
-- Structure de la table `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotion_id` int NOT NULL,
  `product_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotion_product`
--

INSERT INTO `promotion_product` (`promotion_id`, `product_id`) VALUES
(8, 12),
(8, 14),
(12, 12),
(12, 14);

-- --------------------------------------------------------

--
-- Structure de la table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id` int NOT NULL,
  `name_short` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_short` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `name_short`, `description_short`) VALUES
(1, 'Honey Whale M5 Max', 'Moteur 1000 W, pneus 14 pouces, autonomie 40 km'),
(2, 'KUGOO C1 Pro', 'Vitesse 45 km/h, autonomie 100 km, charge max 120 kg'),
(3, 'Bogist M5 Pro', 'Moteur 500 W, pneus 12 pouces, autonomie 35 km'),
(17, 'trott test', '<div>court desc trott de test</div>'),
(23, 'Trott UI', '<div>Desc Trott UI</div>');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessory`
--

CREATE TABLE `trottinette_accessory` (
  `id` int NOT NULL,
  `trottinette_id` int NOT NULL,
  `accessory_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_accessory`
--

INSERT INTO `trottinette_accessory` (`id`, `trottinette_id`, `accessory_id`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 1, 6),
(4, 1, 7),
(5, 1, 10),
(6, 1, 13),
(7, 1, 14),
(8, 1, 15),
(9, 1, 16),
(10, 2, 4),
(11, 2, 6),
(12, 2, 8),
(13, 2, 10),
(14, 2, 15),
(15, 3, 5),
(16, 3, 6),
(17, 3, 7),
(18, 3, 12),
(19, 3, 16),
(20, 17, 18),
(21, 17, 12),
(22, 17, 14),
(24, 23, 25);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_caracteristique`
--

CREATE TABLE `trottinette_caracteristique` (
  `id` int NOT NULL,
  `trottinette_id` int NOT NULL,
  `caracteristique_id` int NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_caracteristique`
--

INSERT INTO `trottinette_caracteristique` (`id`, `trottinette_id`, `caracteristique_id`, `value`) VALUES
(1, 1, 1, '1380 x 320 x 630 mm'),
(2, 1, 2, '36 kg'),
(3, 1, 3, '48 V 13 Ah'),
(79, 3, 6, '120 kg'),
(80, 1, 1, '1380 x 320 x 630 mm'),
(81, 1, 2, '36 kg'),
(82, 1, 3, '48 V 13 Ah'),
(83, 1, 4, '45 km/h'),
(84, 1, 5, '40 km'),
(85, 1, 6, '120 kg'),
(86, 1, 7, 'Oui'),
(87, 2, 1, '1250 x 300 x 600 mm'),
(88, 2, 2, '32 kg'),
(89, 2, 3, '48 V 10 Ah'),
(90, 2, 4, '45 km/h'),
(91, 2, 5, '100 km'),
(92, 2, 6, '120 kg'),
(93, 2, 7, 'Non'),
(94, 3, 1, '1200 x 280 x 600 mm'),
(95, 3, 2, '28 kg'),
(96, 3, 3, '48 V 15 Ah'),
(97, 3, 4, '40 km/h'),
(98, 3, 5, '35 km'),
(99, 3, 6, '120 kg'),
(100, 3, 7, 'Oui'),
(101, 17, 8, 'info 01'),
(102, 17, 9, 'info 02'),
(103, 17, 10, 'Info 02.5'),
(104, 17, 11, 'Info 03'),
(105, 17, 11, 'Info 03.3'),
(106, 17, 11, 'Valeur 03'),
(109, 23, 5, '5h'),
(110, 23, 2, '56'),
(111, 23, 4, '90');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_description_section`
--

CREATE TABLE `trottinette_description_section` (
  `id` int NOT NULL,
  `trottinette_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `section_order` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_description_section`
--

INSERT INTO `trottinette_description_section` (`id`, `trottinette_id`, `title`, `content`, `section_order`) VALUES
(1, 3, 'Moteur puissant de 500 W', 'Le BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante pour les trajets urbains ou les aventures hors route...', 1),
(2, 3, 'Batterie haute capacité 48 V 15 Ah', 'Équipé d\'une batterie au lithium 48 V 15 Ah, le M5 Pro offre une autonomie allant jusqu\'à 35 km par charge...', 2),
(18, 2, 'Freinage efficace', 'Freins à disque arrière avec système de récupération d\'énergie pour un arrêt rapide et sécurisé.', 6),
(19, 1, 'Moteur haute performance', 'La Honey Whale M5 Max est équipée d\'un moteur puissant de 1000 W, offrant des accélérations rapides et une vitesse maximale de 45 km/h.', 1),
(20, 1, 'Batterie longue durée', 'Batterie lithium 48 V 13 Ah pour une autonomie allant jusqu\'à 40 km, idéale pour les trajets urbains quotidiens.', 2),
(21, 1, 'Confort et design', 'Siège ergonomique et suspension optimisée pour un confort maximal lors des trajets.', 3),
(22, 1, 'Sécurité', 'Éclairage LED avant/arrière et freins à disque pour un freinage sûr dans toutes les conditions.', 4),
(23, 2, 'Puissance du moteur', 'Moteur 500 W offrant une conduite stable et une vitesse maximale de 45 km/h.', 1),
(24, 2, 'Autonomie prolongée', 'Batterie 48 V 10 Ah permettant jusqu\'à 100 km d\'autonomie selon le mode de conduite.', 2),
(25, 2, 'Design compact', 'Structure légère et pliable pour un transport facile et un rangement pratique.', 3),
(26, 2, 'Freinage sûr', 'Freins à disque arrière avec récupération d\'énergie pour un arrêt rapide.', 4),
(27, 3, 'Moteur puissant de 500 W', 'Le BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante pour les trajets urbains ou les aventures hors route.', 1),
(28, 3, 'Batterie haute capacité 48 V 15 Ah', 'Équipé d\'une batterie au lithium 48 V 15 Ah, le M5 Pro offre une autonomie allant jusqu\'à 35 km par charge.', 2),
(29, 3, 'Confort et maniabilité', 'Pneus de 12 pouces et suspension optimisée pour un confort maximal sur tous les types de routes.', 3),
(30, 3, 'Sécurité complète', 'Éclairage LED, freins à disque et protection enfant pour une sécurité optimale.', 4),
(31, 17, 'test  DescriptionSection', '<div>test&nbsp; DescriptionSection</div>', 1),
(32, 17, 'bla bla bla', '<div>bla bla bla</div>', 3),
(34, 17, 'toto', '<div>toto</div>', 4),
(40, 17, 'tutu', '<div>tutu</div>', 2),
(41, 23, 'Option Boost', 'Activé le SSJ afin d\'avoir d\'avoir un boost de vitesse, mais attention, en contrapartie une perte d\'autonomie !', 1),
(42, 23, 'Style', 'Activé les neon Jaune sous votre trottinette', 2);

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

CREATE TABLE `tva` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tva`
--

INSERT INTO `tva` (`id`, `name`, `value`) VALUES
(1, 'Belgique', 21),
(2, 'Belgique intermediaire', 12),
(3, 'France', 20),
(4, 'Réduit France', 10);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `tel` varchar(16) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `password_reset_token_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `tel`, `password_reset_token`, `password_reset_token_expires_at`) VALUES
(1, 'admin@admin.fr', '[\"ROLE_ADMIN\", \"ROLE_USER\"]', '$2y$13$4bwJInzuXY/eug5T/185NOh32jBDFRFDCp2HH79Xzmkb344xMCdJy', 'Admin', 'Admin', '+33609080705', NULL, NULL),
(2, 'user@user.fr', '[\"ROLE_USER\"]', '$2y$13$LhrRJcEyiJpsDCwVooctFeP6ee/jHM7M8qigKaJSz1v5bwU5Un7qa', 'User', 'User', '+33601020304', NULL, NULL),
(3, 'test@test.fr', '[\"ROLE_USER\"]', '$2y$13$A0Y74ufwtfrtYNJZ9cHUYufIrqqrpudNWJ.wWbuCURqXN1c8KCYcC', 'test', 'test', '+33641414141', NULL, NULL),
(6, 'zine@zine.be', '[\"ROLE_USER\"]', '$2y$13$j6u/p/.f9pWBgSH7B0RWBeCiGyvK5mHQEIWXmZlomQjVqSGRACL1q', 'zine', 'qyh', '+32441512129', NULL, NULL),
(11, 'test@test.be', '[\"ROLE_USER\"]', '$2y$13$TBF3pDhIrvO3uXfiaZeZz.ajNDBXUS/4UAUnbwCeITqLFBSq2DheW', 'test', 'test', '+33654879584', NULL, NULL),
(18, 'test@test1.fr', '[\"ROLE_USER\"]', '$2y$13$nFP6H4H2P.jx8E88OAr12e4WHmuUdS34h6N8nrvSqTwcT8ADCnDeS', 'test', 'test', '+33658987415', NULL, NULL),
(19, 'test@t1est.fr', '[\"ROLE_USER\"]', '$2y$13$KGPKAhVe9Mtv.DJqMma8.uY9jl81K9rP6dS/qlc5S81MT2npFt6Z2', 'test', 'test', '+33697845785', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user_registration_token`
--

CREATE TABLE `user_registration_token` (
  `id` int NOT NULL,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_registration_token`
--

INSERT INTO `user_registration_token` (`id`, `email`, `password_hash`, `first_name`, `last_name`, `tel`, `token`, `expires_at`) VALUES
(1, 'yassine.qyh@gmail.com', '$2y$13$cQx43nLySPh7VH2kmlxVGOLo4nrm8Ns6j7gN/49c/I0mKuexFpDyS', 'yass', 'qyh', '+33645857968', '757b30fbc92134c59346492ccfd0c8400ebc7c2c2826461f47f90adb2a588b23', '2026-01-15 14:19:39'),
(2, 'yassine.qyh@gmail.com', '$2y$13$4hSVfZ1P0csIHeGl194rj.ODMMZYFAohIY4WeZeWp15qDTOs.A8GO', 'yass', 'qay', '+33641021585', 'd7514283f0e3b17c5ecc1086e5b8b8337bba47018ee2205e07215ce90dc01c13', '2026-01-23 10:23:52'),
(3, 'yassine.qyh@gmail.com', '$2y$13$/FOrR78pyOS1Aol03yAa4e8axP2/NHPAZp1UrEChA7g3gZEpGpmdu', 'yass', 'qay', '+33641021585', 'ae91f631aefa8478c040240c3a704b09f17eafe82135ba026554adf9d11b3da4', '2026-01-23 10:24:03');

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
(1, 0.25, 5.96),
(2, 0.5, 6.71),
(3, 0.75, 7.5),
(4, 1, 8.14),
(5, 2, 9.13),
(7, 3, 10.01),
(8, 4, 10.92),
(9, 5, 11.8),
(10, 6, 12.35),
(18, 7, 13.21),
(19, 8, 14.07),
(20, 9, 14.96),
(21, 10, 15.83),
(22, 11, 16.38),
(23, 12, 17.23),
(24, 13, 18.08),
(25, 14, 18.95),
(26, 15, 19.8),
(27, 16, 20.65),
(28, 17, 21.5),
(29, 18, 22.35),
(30, 19, 23.22),
(31, 20, 24.06),
(32, 21, 24.68),
(33, 22, 25.52),
(34, 23, 26.37),
(35, 24, 27.22),
(36, 25, 28.05),
(37, 26, 28.91),
(38, 27, 29.75),
(39, 28, 30.6),
(40, 29, 31.46),
(41, 30, 32.28),
(46, 0.1, 2.65),
(47, 100, 40);

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
-- Index pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D14FBE8BBCF5E72D` (`categorie_id`);

--
-- Index pour la table `categorie_caracteristique`
--
ALTER TABLE `categorie_caracteristique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_accessory`
--
ALTER TABLE `category_accessory`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

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
  ADD KEY `IDX_C11D7DD14584665A` (`product_id`),
  ADD KEY `IDX_C11D7DD1888AB5FF` (`category_access_id`);

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
-- Index pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B37F755EF6798F43` (`trottinette_id`),
  ADD KEY `IDX_B37F755E27E8CC78` (`accessory_id`);

--
-- Index pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_22FC340CF6798F43` (`trottinette_id`),
  ADD KEY `IDX_22FC340C1704EEB7` (`caracteristique_id`);

--
-- Index pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B92E215BF6798F43` (`trottinette_id`);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `categorie_caracteristique`
--
ALTER TABLE `categorie_caracteristique`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `category_accessory`
--
ALTER TABLE `category_accessory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `home_video`
--
ALTER TABLE `home_video`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `product_history`
--
ALTER TABLE `product_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `tva`
--
ALTER TABLE `tva`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user_registration_token`
--
ALTER TABLE `user_registration_token`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `weight`
--
ALTER TABLE `weight`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

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
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  ADD CONSTRAINT `FK_D14FBE8BBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_caracteristique` (`id`);

--
-- Contraintes pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD CONSTRAINT `FK_D67B9A424584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `FK_845CA2C1BFCDF877` FOREIGN KEY (`my_order_id`) REFERENCES `order` (`id`),
  ADD CONSTRAINT `FK_845CA2C1EF85CBD0` FOREIGN KEY (`product_entity_id`) REFERENCES `product` (`id`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD4D79775F` FOREIGN KEY (`tva_id`) REFERENCES `tva` (`id`);

--
-- Contraintes pour la table `product_history`
--
ALTER TABLE `product_history`
  ADD CONSTRAINT `FK_F6636BFB4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Contraintes pour la table `promotion`
--
ALTER TABLE `promotion`
  ADD CONSTRAINT `FK_C11D7DD14584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
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
  ADD CONSTRAINT `FK_B9983CE5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD CONSTRAINT `FK_44559939BF396750` FOREIGN KEY (`id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  ADD CONSTRAINT `FK_B37F755E27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_B37F755EF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  ADD CONSTRAINT `FK_22FC340C1704EEB7` FOREIGN KEY (`caracteristique_id`) REFERENCES `caracteristique` (`id`),
  ADD CONSTRAINT `FK_22FC340CF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD CONSTRAINT `FK_B92E215BF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
