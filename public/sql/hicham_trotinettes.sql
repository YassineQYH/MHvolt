-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 16 oct. 2025 à 14:21
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
  `weight_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `stock` int(11) NOT NULL,
  `price` double DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessory`
--

INSERT INTO `accessory` (`id`, `weight_id`, `category_id`, `name`, `slug`, `description`, `stock`, `price`, `image`, `is_best`) VALUES
(1, 3, 3, 'Xtreme Siege', 'Xtreme-Siege', 'Siege pour trott', 3, 15, 'Xtreme-Siege.jpg', 1),
(2, 8, 4, 'roues etoiles', 'roues-etoiles', 'Roue pour trott', 4, 45, 'roues-etoiles.jpg', 1),
(3, 2, 1, 'guidon blunt black v3', 'guidon-blunt-black-v3', 'Guidon pour trott', 2, 49, 'guidon-blunt-black-v3.jpg', 0),
(4, 1, 2, 'Frein Jaune', 'freinfreins-jaune', 'Frein pour trott', 0, 19, 'freins-jaune.jpg', 0),
(6, 5, 2, 'etrier de frein', 'etrier-de-frein', 'etrier-de-frein', 2, 56, 'etrier-de-frein.jpg', 1),
(7, 3, 4, 'Roues freestyle', 'Roues-freestyle', 'Roues-freestyle', 4, 1, 'Roues-freestyle.jpg', 0),
(8, 2, 4, 'roues gold', 'roues-gold.jpg', 'roues-gold', 5, 89, 'roues-gold.jpg', 0),
(9, 4, 4, 'roues stunt', 'roues-stunt', 'roues-stunt', 4, 55, 'roues-stunt.jpg', 0),
(10, 2, 2, 'freins rouge', 'freins-rouge', 'freins-rouge', 3, 26, 'freins-rouge.jpeg', 0),
(11, 4, 3, 'siege double', 'siege-double', 'siege-double', 2, 21, 'siege-double.jpg', 0),
(12, 2, 3, 'siege rouge', 'siege-rouge', 'siege-rouge', 6, 45, 'siege-rouge.jpg', 1),
(13, 3, 3, 'Xtreme Siege', 'Xtreme-Siege', 'Xtreme-Siege', 0, 25, 'Xtreme-Siege.jpg', 0),
(14, 7, 1, 'guidon blunt black v3', 'guidon-blunt-black-v3', 'guidon-blunt-black-v3', 3, 89, 'guidon-blunt-black-v3.jpg', 0),
(15, 7, 1, 'guidon multicolor', 'guidon-multicolor', 'guidon-multicolor', 4, 25, 'guidon-multicolor.jpg', 0),
(16, 4, 1, 'guidon titanium', 'guidon-titanium', 'guidon-titanium', 0, 48, 'guidon-titanium.jpg', 0);

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
  `phone` varchar(20) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `address`
--

INSERT INTO `address` (`id`, `user_id`, `name`, `firstname`, `lastname`, `company`, `address`, `postal`, `city`, `country`, `phone`, `type`) VALUES
(1, 1, '', '', '', NULL, '51 Rue de Konoha', '63200', '', 'France', '', NULL),
(2, 2, '', '', '', NULL, '51 Rue du Hueco Mundo', '63118', '', 'France', '', NULL);

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
('DoctrineMigrations\\Version20250924130421', '2025-09-25 10:42:28', 295),
('DoctrineMigrations\\Version20251016112647', '2025-10-16 13:32:30', 80),
('DoctrineMigrations\\Version20251016114952', '2025-10-16 13:49:55', 784);

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `illustration`
--

INSERT INTO `illustration` (`id`, `trottinette_id`, `image`) VALUES
(1, 1, 'trottbleue-02.png'),
(2, 1, 'trottbleue-03.png'),
(12, 3, 'trottvert-05.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `illustrationaccess`
--

CREATE TABLE `illustrationaccess` (
  `id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `illustrationaccess`
--

INSERT INTO `illustrationaccess` (`id`, `accessory_id`, `image`) VALUES
(1, 1, 'siege-trott-elec.jpg'),
(12, 4, 'etrier-de-frein.jpg'),
(13, 1, 'Xtreme-Siege.jpg'),
(14, 1, 'Xtreme-Siege.jpg'),
(15, 1, 'Xtreme-Siege.jpg'),
(16, 1, 'Xtreme-Siege.jpg'),
(17, 2, 'roues-etoiles.jpg'),
(18, 2, 'roues-etoiles.jpg'),
(19, 2, 'roues-etoiles.jpg'),
(20, 3, 'guidon-blunt-black-v3.jpg'),
(21, 3, 'guidon-blunt-black-v3.jpg'),
(22, 3, 'guidon-blunt-black-v3.jpg'),
(23, 4, 'freins-jaune.jpg'),
(24, 4, 'freins-jaune.jpg'),
(25, 4, 'freins-jaune.jpg'),
(26, 6, 'etrier-de-frein.jpg'),
(27, 6, 'etrier-de-frein.jpg'),
(28, 6, 'etrier-de-frein.jpg'),
(29, 7, 'Roues-freestyle.jpg'),
(30, 7, 'Roues-freestyle.jpg'),
(31, 7, 'Roues-freestyle.jpg'),
(32, 8, 'roues-gold.jpg'),
(33, 8, 'roues-gold.jpg'),
(34, 8, 'roues-gold.jpg'),
(35, 9, 'roues-stunt.jpg'),
(36, 9, 'roues-stunt.jpg'),
(37, 9, 'roues-stunt.jpg'),
(38, 10, 'freins-rouge.jpeg'),
(39, 10, 'freins-rouge.jpeg'),
(40, 10, 'freins-rouge.jpeg'),
(41, 11, 'siege-double.jpg'),
(42, 11, 'siege-double.jpg'),
(43, 11, 'siege-double.jpg'),
(44, 12, 'siege-rouge.jpg'),
(45, 12, 'siege-rouge.jpg'),
(46, 12, 'siege-rouge.jpg'),
(47, 13, 'Xtreme-Siege.jpg'),
(48, 13, 'Xtreme-Siege.jpg'),
(49, 13, 'Xtreme-Siege.jpg'),
(50, 14, 'guidon-blunt-black-v3.jpg'),
(51, 14, 'guidon-blunt-black-v3.jpg'),
(52, 14, 'guidon-blunt-black-v3.jpg'),
(53, 15, 'guidon-multicolor.jpg'),
(54, 15, 'guidon-multicolor.jpg'),
(55, 15, 'guidon-multicolor.jpg'),
(56, 16, 'guidon-titanium.jpg'),
(57, 16, 'guidon-titanium.jpg'),
(58, 16, 'guidon-titanium.jpg');

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
-- Structure de la table `trottinette`
--

CREATE TABLE `trottinette` (
  `id` int(11) NOT NULL,
  `weight_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `name_short` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `description_short` longtext DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL,
  `is_header` tinyint(1) NOT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `header_btn_title` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `weight_id`, `name`, `name_short`, `slug`, `description`, `description_short`, `image`, `is_best`, `is_header`, `header_image`, `header_btn_title`, `stock`, `price`) VALUES
(1, 25, 'Trottinette électrique honey whale m5 max avec siège', 'Honey Whale M5 Max', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '【Performance puissante】...', 'Moteur 1000 W, pneus 14 pouces, autonomie 40 km', 'trottbleue-01.png', 1, 1, 'foot-soccer.jpg', 'test', 5, 599),
(2, 22, 'KUGOO Kukirin C1 Pro', 'KUGOO C1 Pro', 'KUGOO-Kukirin-C1-Pro', 'Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège', 'Vitesse 45 km/h, autonomie 100 km, charge max 120 kg', 'trottjaune-01.jpg', 1, 0, 'foot-hiver.jpg', 'test', 0, 1299),
(3, 28, 'Bogist M5 Pro', 'Bogist M5 Pro', 'Bogist-M5-Pro', 'Moteur puissant de 500 W pour des vitesses élevées...', 'Moteur 500 W, pneus 12 pouces, autonomie 35 km', 'trottvert-01.jpg', 1, 1, 'foot-ete.jpg', 'test', 4, 754);

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
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 3),
(7, 3, 2),
(8, 3, 4),
(21, 1, 1),
(22, 1, 3),
(23, 1, 7),
(24, 1, 10),
(25, 1, 11),
(26, 1, 15),
(27, 2, 2),
(28, 2, 4),
(29, 2, 6),
(30, 2, 8),
(31, 2, 15),
(32, 3, 1),
(33, 3, 3),
(34, 3, 7),
(35, 3, 12),
(36, 3, 16);

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
(1, 3, 'Moteur puissant de 500 W', 'Le BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante...', 1),
(30, 3, 'Sécurité complète', 'Éclairage LED, freins à disque et protection enfant pour une sécurité optimale.', 4);

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
(1, 'admin@admin.fr', '[\"ROLE_ADMIN\",\"ROLE_USER\"]', 'Admin', 'Admin', 'Admin', '06 04 05 02 09'),
(2, 'user@user.fr', '[]', 'User', 'User', 'User', '06 01 01 01 02');

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
(46, 0.1, 2.65);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A1B1251C350035DC` (`weight_id`),
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
  ADD KEY `IDX_D67B9A42F6798F43` (`trottinette_id`);

--
-- Index pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EA75D19D27E8CC78` (`accessory_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_44559939350035DC` (`weight_id`);

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
-- AUTO_INCREMENT pour la table `accessory`
--
ALTER TABLE `accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trottinette`
--
ALTER TABLE `trottinette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `weight`
--
ALTER TABLE `weight`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD CONSTRAINT `FK_A1B1251C12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category_accessory` (`id`),
  ADD CONSTRAINT `FK_A1B1251C350035DC` FOREIGN KEY (`weight_id`) REFERENCES `weight` (`id`);

--
-- Contraintes pour la table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `illustration`
--
ALTER TABLE `illustration`
  ADD CONSTRAINT `FK_D67B9A42F6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`);

--
-- Contraintes pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  ADD CONSTRAINT `FK_EA75D19D27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`);

--
-- Contraintes pour la table `trottinette`
--
ALTER TABLE `trottinette`
  ADD CONSTRAINT `FK_44559939350035DC` FOREIGN KEY (`weight_id`) REFERENCES `weight` (`id`);

--
-- Contraintes pour la table `trottinette_accessory`
--
ALTER TABLE `trottinette_accessory`
  ADD CONSTRAINT `FK_B37F755E27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`),
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
