-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 27 nov. 2025 à 15:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `hicham_trotinettes`
--

-- --------------------------------------------------------

--
-- Structure de la table `accessory`
--

CREATE TABLE `accessory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL
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
(10, 4);

-- --------------------------------------------------------

--
-- Structure de la table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `postal` varchar(20) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`) VALUES
(1, 1, 'Maison', 'Yass', 'Qay', NULL, '51 Rue de Konoha', '63200', 'angleur', 'France', '06.11.55.22.51'),
(2, 2, 'Maison', 'Hich', 'Qay', NULL, '51 Belle jardinère', '63118', 'sart-tilman', 'France', '06.11.55.22.51'),
(3, 1, 'Travail', 'Yass', 'Qay', NULL, '51 Rue du Hueco Mundo', '63200', 'clermont ferrand', 'France', '06.11.55.22.51'),
(4, 4, 'Maison', 'Yass', 'Qay', NULL, '17 marie marving', '63200', 'riom', 'FR', '+33641515128');

-- --------------------------------------------------------

--
-- Structure de la table `caracteristique`
--

CREATE TABLE `caracteristique` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `caracteristique`
--

INSERT INTO `caracteristique` (`id`, `name`) VALUES
(1, 'Taille'),
(2, 'Poids'),
(3, 'Batterie'),
(4, 'Vitesse maximale'),
(5, 'Autonomie'),
(6, 'Charge maximale'),
(7, 'sécurité enfant');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_caracteristique`
--

CREATE TABLE `categorie_caracteristique` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
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
(7, 'Équipement de sécurité');

-- --------------------------------------------------------

--
-- Structure de la table `category_accessory`
--

CREATE TABLE `category_accessory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `illustration` varchar(255) NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category_accessory`
--

INSERT INTO `category_accessory` (`id`, `name`, `illustration`, `description`) VALUES
(1, 'Guidon', 'guidon-blunt-black-v2.jpg', 'guidon-blunt-black-v2'),
(2, 'Freins', 'freins.jpg', 'freins'),
(3, 'siege', 'siege-trott-elec.jpg', 'siege-trott-elec'),
(4, 'Roues', 'Roues-freestyle.jpg', 'Roues-freestyle');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20251119155034', '2025-11-19 16:50:37', 1708),
('DoctrineMigrations\\Version20251120155512', '2025-11-20 16:55:21', 238),
('DoctrineMigrations\\Version20251121142003', '2025-11-21 15:20:19', 255),
('DoctrineMigrations\\Version20251124092951', '2025-11-24 10:30:24', 11),
('DoctrineMigrations\\Version20251127140006', '2025-11-27 15:03:04', 119);

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
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
(69, 16, 'guidon-titanium.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `carrier_price` double NOT NULL,
  `delivery` longtext NOT NULL,
  `reference` varchar(255) NOT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `payment_state` int(11) NOT NULL,
  `delivery_state` int(11) NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `secondary_carrier_tracking_number` varchar(255) DEFAULT NULL,
  `secondary_carrier` varchar(255) DEFAULT NULL,
  `promo_code` varchar(50) DEFAULT NULL,
  `promo_reduction` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order`
--

INSERT INTO `order` (`id`, `user_id`, `created_at`, `carrier_price`, `delivery`, `reference`, `stripe_session_id`, `payment_state`, `delivery_state`, `tracking_number`, `carrier`, `secondary_carrier_tracking_number`, `secondary_carrier`, `promo_code`, `promo_reduction`) VALUES
(1, 1, '2025-10-21 14:27:06', 23.22, 'Yass Qay 06.11.55.22.51 51 Rue de Konoha 63200 angleur<br>Franc', '21102025-68f77c1a7c6dd', NULL, 0, 0, '13211311123113112211', 'bpost', NULL, NULL, NULL, NULL),
(2, 1, '2025-10-21 14:29:08', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77c9423c2c', NULL, 0, 0, '31131211131221', 'bpost', NULL, NULL, NULL, NULL),
(3, 1, '2025-10-21 14:29:28', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77ca83d2a7', NULL, 0, 0, '1113213211', 'bpost', NULL, NULL, NULL, NULL),
(4, 1, '2025-10-21 14:31:59', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77d3f4629b', 'cs_test_b1IFG5Q9gll0ZuAgc3zlYqDKvwrd6uJOHwJcWVZSGbXo8AewDIYVEWUcv9', 0, 0, 'CC088942925FR', 'bpost', NULL, NULL, NULL, NULL),
(5, 1, '2025-10-21 14:32:57', 23.22, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f77d799ff9b', 'cs_test_b1hL2avKtpCc9IokW6GFqrzSV3jEvMmzBlqosbd8TBlykrOREGjpR1c7YE', 1, 1, '6G61316524338', 'bpost', NULL, NULL, NULL, NULL),
(6, 1, '2025-10-21 16:34:49', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a09bd15f', NULL, 1, 1, '6G61366363482', 'bpost', NULL, NULL, NULL, NULL),
(7, 1, '2025-10-21 16:35:23', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a2bbf571', NULL, 1, 3, '6G61397378424', 'colissimo', NULL, NULL, NULL, NULL),
(8, 1, '2025-10-21 16:37:04', 19.8, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21102025-68f79a902672b', 'cs_test_b11AmvahjrP8RuTIhMPNCtqZwyg6T0fJQ81CLWFBzisnEATWsvQWmN8fSD', 1, 1, '6G61397895822', 'colissimo', NULL, NULL, NULL, NULL),
(9, 1, '2025-10-22 09:35:14', 19.8, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '22102025-68f88932a6b2e', 'cs_test_b1MfDJ2kREupjottpXlscZl1IhorILdWvOFmrGBHtokfu5bVWFuqXapsYo', 1, 2, '6G61398207501', 'colissimo', NULL, NULL, NULL, NULL),
(10, 4, '2025-10-22 16:28:00', 20.65, 'Yass Qay<br>+33641515128<br>17 marie marving<br>63200 riom<br>FR', '22102025-68f8e9f1541e1', 'cs_test_b16TGqtnQO05GSgFvVzu0G8kdkwP4QvAaAaCwIZ289xG8H11x0UVaRWmMH', 1, 3, 'CC084147325FR', 'colissimo', NULL, NULL, NULL, NULL),
(11, 1, '2025-10-24 16:05:28', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24102025-68fb87a892a1e', 'cs_test_b1WyzxbI3cyBvwK3HaidvFnWnn9KC5Jcw7aihkIPGyNNW9iLukn8DDkEii', 1, 2, '6G61398207501', 'bpost', NULL, NULL, NULL, NULL),
(12, 1, '2025-11-07 16:17:59', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0da74f3bb', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(13, 1, '2025-11-07 16:18:34', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0dcac0a47', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(14, 1, '2025-11-07 16:19:23', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '07112025-690e0dfb84c42', 'cs_test_b1dBawcIBxgReWwFYdp09zUSRn0vimwwlZwUe6m2CXGlYsTdow69zvzqmT', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(15, 1, '2025-11-12 11:12:15', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69145d7f8c9aa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(16, 1, '2025-11-12 11:19:26', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69145f2e7cd25', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(17, 1, '2025-11-12 14:20:27', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-6914899bf088f', 'cs_test_b16icOL3eKCOGSrpXI3joO157yTvlZIKVeDH6eR9PGlS0syx3O2afkwIbk', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(18, 1, '2025-11-12 14:24:54', 21.5, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '12112025-69148aa67739b', 'cs_test_b16mYHKZuSZ7gTMJK7Ww3962lF1lGARoS7euwCkcFAjjPjhi7NwoQNSr22', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(19, 1, '2025-11-19 11:15:04', 32.28, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '19112025-691d98a8a849f', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(20, 1, '2025-11-19 11:16:03', 32.28, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '19112025-691d98e3552fb', 'cs_test_b1iOr5roGWuli6Qegu7XxdSlHYXZdbyiyH5N63YSlfHA2PbRdCVjWmzLMW', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(21, 1, '2025-11-20 09:54:18', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed73ac2b2c', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(22, 1, '2025-11-20 09:58:27', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed833da4c8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(23, 1, '2025-11-20 10:01:07', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '20112025-691ed8d3d4876', 'cs_test_b1SIqJGeIaesZnuUNln1hbcdEjBp3cnFCknphr3nqV9RApBwtgEF9mYedD', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(24, 1, '2025-11-21 10:25:24', 24.68, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69203004670c1', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(25, 1, '2025-11-21 14:30:01', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206959f2044', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(26, 1, '2025-11-21 14:39:14', 0, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206b82550be', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(27, 1, '2025-11-21 14:41:07', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206bf310820', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(28, 1, '2025-11-21 14:53:14', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206eca7e072', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(29, 1, '2025-11-21 14:55:09', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206f3d0c3b8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(30, 1, '2025-11-21 14:56:32', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69206f902430d', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(31, 1, '2025-11-21 14:59:52', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207058e8b2a', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(32, 1, '2025-11-21 15:02:11', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692070e3087a8', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(33, 1, '2025-11-21 15:02:36', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692070fc1a2fa', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(34, 1, '2025-11-21 15:02:43', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207103b245e', NULL, 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(35, 1, '2025-11-21 15:03:19', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-69207127c5cb7', 'cs_test_b1CToltoIUvDc7BiAvOuuTnrBa4jMYxRJFKqnyK5n0jRhMzFUQpyi97Q56', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(36, 1, '2025-11-21 15:10:55', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692072efc7298', 'cs_test_b11PX2F0pIqPXdxOfw7AEl9E6NxEnrIgxTaOH933jv76lQMgLVRHEdcXc7', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(38, 1, '2025-11-21 15:20:26', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920752a758ca', 'cs_test_b15QApSnckG00aqtR2lB3s8K71dX93r7E6LCcstV3P8ASBpTWlxaJsDzxd', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(39, 1, '2025-11-21 15:24:31', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920761fea2de', 'cs_test_b1wcjF2o3ohl1H4bLvAo6ckSMtq5LD8BFVwCiBdPjtc4Z7xI2AV4DjPmzP', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(40, 1, '2025-11-21 15:27:09', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692076bd78bbd', 'cs_test_b1NcmTfawrvu2DoO6U9ftJHLlynN7gwgBRdq8Xe4Xz0Gf5Za1DESLomH03', 0, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(41, 1, '2025-11-21 15:31:29', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-692077c14302c', 'cs_test_b1V8UOdDZ1LdEZag7qyT716AnuAwbUbdzCpy0SG6UNxFCvJ8vAOhR7U5gv', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(42, 1, '2025-11-21 16:28:46', 22.35, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '21112025-6920852ee7076', 'cs_test_b1X4XMyohiuCr1k9Ee129fJgLjCrKv7lrafxmbSM9i7UYVnds0OIWcju3j', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(43, 1, '2025-11-24 10:15:46', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-69242242dea4c', 'cs_test_b1F7mdk5W0C4z4pUgC9IddZHtxKj1FXdVj1tYDjxFvpkmerJi4Y46pAfP9', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(44, 1, '2025-11-24 10:17:52', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692422c03dc77', 'cs_test_b1QgCwSkD1p4kJ4m8P8LJAnW0VYK9lTtVaGnRzKR9iDu3Kf2eABBrq9DIr', 1, 0, NULL, 'bpost', NULL, NULL, NULL, NULL),
(45, 1, '2025-11-24 10:39:45', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692427e179c6d', 'cs_test_b1BPppiDPYl3bcHHfCEGQEBkq7g6t8pFBWbLzPtfETE3bCliuWu1xYMCIX', 1, 0, NULL, 'bpost', NULL, NULL, NULL, 0),
(46, 1, '2025-11-24 10:42:17', 18.95, 'Yass Qay<br>06.11.55.22.51<br>51 Rue de Konoha<br>63200 angleur<br>France', '24112025-692428791d7d4', 'cs_test_b1g4259dT5UwNA6BHVulQSAPPZyFUaGVkYTQMJlRfE8wAniMIbl7nTEH7N', 1, 0, NULL, 'bpost', NULL, NULL, 'family-25', 212.8575);

-- --------------------------------------------------------

--
-- Structure de la table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `my_order_id` int(11) NOT NULL,
  `product_entity_id` int(11) DEFAULT NULL,
  `product` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` double NOT NULL,
  `total` double NOT NULL,
  `weight` varchar(64) NOT NULL,
  `tva` double DEFAULT NULL,
  `price_ttc` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `order_details`
--

INSERT INTO `order_details` (`id`, `my_order_id`, `product_entity_id`, `product`, `quantity`, `price`, `total`, `weight`, `tva`, `price_ttc`) VALUES
(1, 1, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(2, 1, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(3, 1, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(4, 1, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(5, 2, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(6, 2, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(7, 2, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(8, 2, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(9, 3, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(10, 3, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(11, 3, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(12, 3, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(13, 4, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(14, 4, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(15, 4, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(16, 4, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(17, 5, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(18, 5, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(19, 5, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(20, 5, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(21, 6, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(22, 6, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(23, 6, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(24, 7, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(25, 7, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(26, 7, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(27, 8, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(28, 8, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(29, 8, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(30, 8, 9, 'Roues freestyle', 1, 1, 1, '0.75', 21, 0),
(31, 9, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(32, 9, 6, 'guidon blunt black v3', 1, 49, 49, '0.5', 21, 0),
(33, 9, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(34, 9, 9, 'Roues freestyle', 1, 1, 1, '0.75', 21, 0),
(35, 10, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(36, 10, 9, 'Roues freestyle', 2, 1, 2, '0.75', 21, 0),
(37, 10, 12, 'freins rouge', 1, 26, 26, '0.5', 21, 0),
(38, 11, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(39, 11, 4, 'Xtreme Siege', 1, 15, 15, '0.75', 21, 0),
(40, 12, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(41, 12, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(42, 13, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(43, 13, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(44, 14, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(45, 14, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(46, 15, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(47, 15, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0),
(48, 16, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(49, 16, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0),
(50, 17, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(51, 17, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0),
(52, 18, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(53, 18, 14, 'siege rouge', 1, 45, 45, '0.5', 21, 0),
(54, 19, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(55, 19, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(56, 19, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(57, 20, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(58, 20, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(59, 20, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(60, 21, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(61, 21, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(62, 22, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(63, 22, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(64, 23, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(65, 23, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(66, 24, 3, 'Bogist M5 Pro', 1, 754, 754, '17', 21, 0),
(67, 24, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(68, 25, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(69, 25, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(70, 27, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(71, 27, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(72, 28, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(73, 28, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(74, 29, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(75, 29, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(76, 30, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(77, 30, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(78, 31, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(79, 31, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(80, 32, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(81, 32, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(82, 33, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(83, 33, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(84, 34, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(85, 34, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(86, 35, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(87, 35, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(88, 36, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 0),
(89, 36, 5, 'roues etoiles', 1, 45, 45, '4', 21, 0),
(90, 38, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(91, 38, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45),
(92, 39, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(93, 39, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45),
(94, 40, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(95, 40, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45),
(96, 41, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(97, 41, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45),
(98, 42, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(99, 42, 5, 'roues etoiles', 1, 45, 45, '4', 21, 54.45),
(100, 43, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(101, 43, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69),
(102, 44, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(103, 44, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69),
(104, 45, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(105, 45, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69),
(106, 46, 1, 'Trottinette électrique honey whale m5 max avec siège', 1, 599, 599, '14', 21, 724.79),
(107, 46, 10, 'roues gold', 1, 89, 89, '0.5', 21, 107.69);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `weight_id` int(11) NOT NULL,
  `tva_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `price` double NOT NULL,
  `stock` int(11) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `weight_id`, `tva_id`, `name`, `slug`, `description`, `price`, `stock`, `is_best`, `created_at`, `updated_at`, `type`) VALUES
(1, 25, 1, 'Trottinette électrique honey whale m5 max avec siège', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '【Performance puissante】...', 599, 5, 1, '2025-11-18 16:19:20', '2025-11-24 10:42:47', 'trottinette'),
(2, 22, 1, 'KUGOO Kukirin C1 Pro', 'KUGOO-Kukirin-C1-Pro', 'Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège', 1299, 4, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'trottinette'),
(3, 28, 1, 'Bogist M5 Pro', 'Bogist-M5-Pro', 'Moteur puissant de 500 W pour des vitesses élevées...', 754, 2, 1, '2025-11-18 16:19:20', '2025-11-19 11:30:29', 'trottinette'),
(4, 3, 1, 'Xtreme Siege', 'Xtreme-Siege', 'Siege pour trott', 15, 2, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(5, 8, 1, 'roues etoiles', 'roues-etoiles', 'Roue pour trott', 45, 6, 1, '2025-11-18 16:19:20', '2025-11-21 16:32:40', 'accessoire'),
(6, 2, 1, 'guidon blunt black v3', 'guidon-blunt-black-v3', 'Guidon pour trott', 49, 1, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(7, 1, 1, 'Frein Jaune', 'freinfreins-jaune', 'Frein pour trott', 19, 0, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(8, 5, 1, 'etrier de frein', 'etrier-de-frein', 'etrier-de-frein', 56, 2, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(9, 3, 1, 'Roues freestyle', 'Roues-freestyle', 'Roues-freestyle', 1, 1, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(10, 2, 1, 'roues gold', 'roues-gold', 'roues-gold', 89, 5, 1, '2025-11-18 16:19:20', '2025-11-24 10:42:47', 'accessoire'),
(11, 4, 1, 'roues stunt', 'roues-stunt', 'roues-stunt', 55, 4, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(12, 2, 1, 'freins rouge', 'freins-rouge', 'freins-rouge', 26, 1, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(13, 4, 1, 'siege double', 'siege-double', 'siege-double', 21, 2, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(14, 2, 1, 'siege rouge', 'siege-rouge', 'siege-rouge', 45, 5, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(15, 7, 1, 'guidon multicolor', 'guidon-multicolor', '<div>guidon-multicolor</div>', 25, 4, 1, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire'),
(16, 4, 1, 'guidon titanium', 'guidon-titanium', 'guidon-titanium', 48, 0, 0, '2025-11-18 16:19:20', '2025-11-18 16:19:20', 'accessoire');

-- --------------------------------------------------------

--
-- Structure de la table `product_history`
--

CREATE TABLE `product_history` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` double DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE `promotion` (
  `id` int(11) NOT NULL,
  `category_access_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `target_type` varchar(30) NOT NULL,
  `discount_amount` double DEFAULT NULL,
  `discount_percent` double DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `used` int(11) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `auto_apply` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `promotion`
--

INSERT INTO `promotion` (`id`, `category_access_id`, `product_id`, `code`, `target_type`, `discount_amount`, `discount_percent`, `start_date`, `end_date`, `quantity`, `used`, `titre`, `auto_apply`) VALUES
(5, NULL, NULL, 'family-25', 'all', NULL, 25, '2025-11-20 16:09:00', NULL, 5, 3, NULL, 0),
(6, 4, NULL, 'promo-roues', 'category_access', 5, NULL, '2025-11-26 14:25:00', NULL, 3, 0, NULL, 0),
(7, NULL, 2, 'promo-kugoo', 'product', 200, NULL, '2025-11-26 16:16:00', NULL, 3, 0, NULL, 0),
(8, NULL, NULL, 'promo-rouge', 'product_list', 10, NULL, '2025-11-17 16:34:00', '2025-11-25 23:59:00', 3, 0, NULL, 0),
(9, NULL, NULL, 'family-25-', 'all', 25, NULL, '2025-11-26 16:59:00', NULL, 4, 0, NULL, 0),
(10, 4, NULL, 'promo-roues-', 'category_access', NULL, 10, '2025-11-26 17:00:00', NULL, 2, 0, NULL, 0),
(11, NULL, 2, 'promo-kugoo-', 'product', NULL, 10, '2025-11-26 17:11:00', NULL, 2, 0, NULL, 0),
(12, NULL, NULL, 'promo-rouge-', 'product_list', NULL, 10, '2025-11-26 17:13:00', NULL, 1, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `promotion_product`
--

CREATE TABLE `promotion_product` (
  `promotion_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id` int(11) NOT NULL,
  `name_short` varchar(255) DEFAULT NULL,
  `description_short` longtext DEFAULT NULL,
  `is_header` tinyint(1) NOT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `header_btn_title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `name_short`, `description_short`, `is_header`, `header_image`, `header_btn_title`) VALUES
(1, 'Honey Whale M5 Max', 'Moteur 1000 W, pneus 14 pouces, autonomie 40 km', 1, 'foot-soccer.jpg', 'test'),
(2, 'KUGOO C1 Pro', 'Vitesse 45 km/h, autonomie 100 km, charge max 120 kg', 0, 'foot-hiver.jpg', 'test'),
(3, 'Bogist M5 Pro', 'Moteur 500 W, pneus 12 pouces, autonomie 35 km', 1, 'foot-ete.jpg', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessory`
--

CREATE TABLE `trottinette_accessory` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL
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
(19, 3, 16);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_caracteristique`
--

CREATE TABLE `trottinette_caracteristique` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) DEFAULT NULL,
  `caracteristique_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_caracteristique`
--

INSERT INTO `trottinette_caracteristique` (`id`, `trottinette_id`, `caracteristique_id`, `categorie_id`, `title`, `value`) VALUES
(1, 1, 1, 1, 'Dimensions', '1380 x 320 x 630 mm'),
(2, 1, 2, 2, 'Poids', '36 kg'),
(3, 1, 3, 3, 'Batterie', '48 V 13 Ah'),
(79, 3, 6, 6, 'Charge maximale', '120 kg'),
(80, 1, 1, 5, 'Dimensions', '1380 x 320 x 630 mm'),
(81, 1, 2, 5, 'Poids', '36 kg'),
(82, 1, 3, 2, 'Batterie', '48 V 13 Ah'),
(83, 1, 4, 2, 'Vitesse maximale', '45 km/h'),
(84, 1, 5, 2, 'Autonomie', '40 km'),
(85, 1, 6, 2, 'Charge maximale', '120 kg'),
(86, 1, 7, 7, 'Sécurité enfant', 'Oui'),
(87, 2, 1, 5, 'Dimensions', '1250 x 300 x 600 mm'),
(88, 2, 2, 5, 'Poids', '32 kg'),
(89, 2, 3, 2, 'Batterie', '48 V 10 Ah'),
(90, 2, 4, 2, 'Vitesse maximale', '45 km/h'),
(91, 2, 5, 2, 'Autonomie', '100 km'),
(92, 2, 6, 2, 'Charge maximale', '120 kg'),
(93, 2, 7, 7, 'Sécurité enfant', 'Non'),
(94, 3, 1, 5, 'Dimensions', '1200 x 280 x 600 mm'),
(95, 3, 2, 5, 'Poids', '28 kg'),
(96, 3, 3, 2, 'Batterie', '48 V 15 Ah'),
(97, 3, 4, 2, 'Vitesse maximale', '40 km/h'),
(98, 3, 5, 2, 'Autonomie', '35 km'),
(99, 3, 6, 2, 'Charge maximale', '120 kg'),
(100, 3, 7, 7, 'Sécurité enfant', 'Oui');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_description_section`
--

CREATE TABLE `trottinette_description_section` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `section_order` int(11) NOT NULL
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
(30, 3, 'Sécurité complète', 'Éclairage LED, freins à disque et protection enfant pour une sécurité optimale.', 4);

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

CREATE TABLE `tva` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
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
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `tel` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `tel`) VALUES
(1, 'admin@admin.fr', '[\"ROLE_ADMIN\",\"ROLE_USER\"]', '$2y$13$4bwJInzuXY/eug5T/185NOh32jBDFRFDCp2HH79Xzmkb344xMCdJy', 'Admin', 'Admin', '06 04 05 02 09'),
(2, 'user@user.fr', '[\"ROLE_USER\"]', '$2y$13$LhrRJcEyiJpsDCwVooctFeP6ee/jHM7M8qigKaJSz1v5bwU5Un7qa', 'User', 'User', '06 01 01 01 02'),
(3, 'test@test.fr', '[]', '$2y$13$A0Y74ufwtfrtYNJZ9cHUYufIrqqrpudNWJ.wWbuCURqXN1c8KCYcC', 'test', 'test', '+33641414141'),
(4, 'yassine.qyh@gmail.com', '[]', '$2y$13$YRpq.Q95CEOdyQSWa.TnLe0oU0QykXGeOHHkcWxQnh3x/909Rd4kO', 'Yass', 'Qay', '+33641512128');

-- --------------------------------------------------------

--
-- Structure de la table `weight`
--

CREATE TABLE `weight` (
  `id` int(11) NOT NULL,
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
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `IDX_D34A04AD350035DC` (`weight_id`),
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
  ADD KEY `IDX_22FC340C1704EEB7` (`caracteristique_id`),
  ADD KEY `IDX_22FC340CBCF5E72D` (`categorie_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `caracteristique`
--
ALTER TABLE `caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `categorie_caracteristique`
--
ALTER TABLE `categorie_caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `category_accessory`
--
ALTER TABLE `category_accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `product_history`
--
ALTER TABLE `product_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `promotion`
--
ALTER TABLE `promotion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `tva`
--
ALTER TABLE `tva`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `weight`
--
ALTER TABLE `weight`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
  ADD CONSTRAINT `FK_D34A04AD350035DC` FOREIGN KEY (`weight_id`) REFERENCES `weight` (`id`),
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
  ADD CONSTRAINT `FK_22FC340CBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_caracteristique` (`id`),
  ADD CONSTRAINT `FK_22FC340CF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  ADD CONSTRAINT `FK_B92E215BF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
