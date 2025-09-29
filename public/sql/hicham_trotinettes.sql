-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 sep. 2025 à 13:22
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
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_best` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `accessory`
--

INSERT INTO `accessory` (`id`, `name`, `slug`, `description`, `image`, `is_best`) VALUES
(1, 'Volant', 'volant', 'Volant', 'test.png', 1),
(2, 'Roue', 'roue', 'Roue', 'test.png', 1),
(3, 'Guidon', 'guidon', 'Guidon', 'guidon.png', 0),
(4, 'Frein', 'frein', 'Frein', 'frein.png', 0),
(5, 'Accessoire de test', 'Accessoire-de-test', '<div>Accessoire de test</div>', 'controls.png', 0);

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
('DoctrineMigrations\\Version20250924145650', '2025-09-25 10:42:29', 41),
('DoctrineMigrations\\Version20250925080726', '2025-09-25 10:42:29', 100),
('DoctrineMigrations\\Version20250925084521', '2025-09-25 10:45:32', 43),
('DoctrineMigrations\\Version20250926132657', '2025-09-26 15:27:14', 254),
('DoctrineMigrations\\Version20250929072839', '2025-09-29 09:29:00', 543),
('DoctrineMigrations\\Version20250929075713', '2025-09-29 09:57:34', 270),
('DoctrineMigrations\\Version20250929084045', '2025-09-29 10:40:57', 135),
('DoctrineMigrations\\Version20250929095851', '2025-09-29 11:59:01', 271),
('DoctrineMigrations\\Version20250929105520', '2025-09-29 12:55:31', 128),
('DoctrineMigrations\\Version20250929110511', '2025-09-29 13:05:14', 254);

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
(3, 1, 'trottbleue-04.png'),
(4, 1, 'trottbleue-05.png'),
(5, 2, 'trottjaune-02.jpg'),
(6, 2, 'trottjaune-03.jpg'),
(7, 2, 'trottjaune-04.jpg'),
(8, 2, 'trottjaune-05.jpg'),
(9, 3, 'trottvert-02.jpg'),
(10, 3, 'trottvert-03.jpg'),
(11, 3, 'trottvert-04.jpg'),
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
  `header_btn_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette`
--

INSERT INTO `trottinette` (`id`, `name`, `name_short`, `slug`, `description`, `description_short`, `image`, `is_best`, `is_header`, `header_image`, `header_btn_title`, `header_btn_url`) VALUES
(1, 'Trottinette électrique honey whale m5 max avec siège', 'Honey Whale M5 Max', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '<div>【Performance puissante】...</div>', '<div>Moteur 1000 W, pneus 14 pouces, autonomie 40 km</div>', 'trottbleue-01.png', 1, 1, NULL, 'test', 'test'),
(2, 'KUGOO Kukirin C1 Pro', 'KUGOO C1 Pro', 'KUGOO-Kukirin-C1-Pro', '<div>Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège</div>', '<div>Vitesse 45 km/h, autonomie 100 km, charge max 120 kg</div>', 'trottjaune-01.jpg', 1, 0, NULL, 'test', 'test'),
(3, 'Bogist M5 Pro', 'Bogist M5 Pro', 'Bogist-M5-Pro', '<div>Moteur puissant de 500 W pour des vitesses élevées...</div>', '<div>Moteur 500 W, pneus 12 pouces, autonomie 35 km</div>', 'trottvert-01.jpg', 1, 1, NULL, 'test', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessory`
--

CREATE TABLE `trottinette_accessory` (
  `trottinette_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_accessory`
--

INSERT INTO `trottinette_accessory` (`trottinette_id`, `accessory_id`, `id`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(2, 1, 5),
(2, 3, 6),
(3, 2, 7),
(3, 4, 8),
(1, 5, 9);

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_caracteristique`
--

CREATE TABLE `trottinette_caracteristique` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `caracteristique_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_caracteristique`
--

INSERT INTO `trottinette_caracteristique` (`id`, `trottinette_id`, `value`, `title`, `caracteristique_id`, `categorie_id`) VALUES
(1, 1, '1380 x 320 x 630 mm', 'Dimensions', 1, 1),
(2, 1, '36 kg', 'Poids', 2, 2),
(3, 1, '48 V 13 Ah', 'Batterie', 3, 3),
(4, 1, '40 km/h', 'Vitesse maximale', 4, 4),
(5, 1, '40 km', 'Autonomie', 5, 5),
(6, 1, '120 kg', 'Charge maximale', 6, 6),
(7, 2, '1200 x 300 x 600 mm', 'Dimensions', 1, 1),
(8, 2, '30 kg', 'Poids', 2, 2),
(9, 2, '48 V 12 Ah', 'Batterie', 3, 3),
(10, 2, '45 km/h', 'Vitesse maximale', 4, 4),
(11, 2, '100 km', 'Autonomie', 5, 5),
(12, 2, '120 kg', 'Charge maximale', 6, 1),
(13, 3, '1250 x 310 x 620 mm', 'Dimensions', 1, 1),
(14, 3, '25 kg', 'Poids', 2, 2),
(15, 3, '48 V 15 Ah', 'Batterie', 3, 3),
(16, 3, '40 km/h', 'Vitesse maximale', 4, 4),
(17, 3, '35 km', 'Autonomie', 5, 2),
(18, 3, '120 kg', 'Charge maximale', 6, 1),
(19, 1, 'oui', 'Équipement', 7, 6),
(20, 1, '110 cm', 'Hauteur', 1, 5),
(21, 1, '33,7 kg', 'Poids', 2, 5),
(22, 1, 'Batterie incluse', 'Batterie incluse', 3, 6),
(23, 1, '45 km/h', 'Vitesse maximale', 4, 1),
(24, 1, '100 km', 'Autonomie', 5, 2),
(25, 1, '120 kg', 'Charge maximale', 6, 1),
(26, 1, 'Oui', NULL, 7, 7),
(27, 2, '140 cm', NULL, 1, 5),
(28, 2, '35 kg', NULL, 2, 5),
(29, 2, 'Batterie incluse', NULL, 3, 6),
(30, 2, '45 km/h', NULL, 4, 1),
(31, 2, '100 km', NULL, 5, 2),
(32, 2, '120 kg', NULL, 6, 1),
(33, 2, 'Oui', NULL, 7, 7),
(34, 3, '140 cm', NULL, 1, 5),
(35, 3, '36 kg', NULL, 2, 5),
(36, 3, 'Batterie incluse', NULL, 3, 6),
(37, 3, '45 km/h', NULL, 4, 1),
(38, 3, '100 km', NULL, 5, 2),
(39, 3, '120 kg', NULL, 6, 1),
(40, 3, 'Oui', NULL, 7, 7),
(41, 1, '1380 x 320 x 630 mm', 'Dimensions', 1, 2),
(42, 1, '36 kg', 'Poids', 2, 2),
(43, 1, '48 V 13 Ah', 'Batterie', 3, 3),
(44, 1, '40 km/h', 'Vitesse maximale', 4, 4),
(45, 1, '40 km', 'Autonomie', 5, 5),
(46, 1, '120 kg', 'Charge maximale', 6, 6),
(47, 1, 'oui', 'Équipement', 7, 7),
(48, 1, '110 cm', 'Hauteur', 1, 2),
(49, 1, '33,7 kg', 'Poids', 2, 2),
(50, 1, 'Batterie incluse', 'Batterie', 3, 3),
(51, 1, '45 km/h', 'Vitesse maximale', 4, 4),
(52, 1, '100 km', 'Autonomie', 5, 5),
(53, 1, '120 kg', 'Charge maximale', 6, 6),
(54, 2, '1200 x 300 x 600 mm', 'Dimensions', 1, 2),
(55, 2, '30 kg', 'Poids', 2, 2),
(56, 2, '48 V 12 Ah', 'Batterie', 3, 3),
(57, 2, '45 km/h', 'Vitesse maximale', 4, 4),
(58, 2, '100 km', 'Autonomie', 5, 5),
(59, 2, '120 kg', 'Charge maximale', 6, 6),
(60, 2, 'Siège inclus', 'Équipement', 7, 7),
(61, 2, '115 cm', 'Hauteur', 1, 2),
(62, 2, '32 kg', 'Poids', 2, 2),
(63, 2, 'Batterie incluse', 'Batterie', 3, 3),
(64, 2, '45 km/h', 'Vitesse maximale', 4, 4),
(65, 2, '100 km', 'Autonomie', 5, 5),
(66, 2, '120 kg', 'Charge maximale', 6, 6),
(67, 3, '1250 x 310 x 620 mm', 'Dimensions', 1, 2),
(68, 3, '25 kg', 'Poids', 2, 2),
(69, 3, '48 V 15 Ah', 'Batterie', 3, 3),
(70, 3, '40 km/h', 'Vitesse maximale', 4, 4),
(71, 3, '35 km', 'Autonomie', 5, 5),
(72, 3, '120 kg', 'Charge maximale', 6, 6),
(73, 3, 'Éclairage complet', 'Équipement', 7, 7),
(74, 3, '108 cm', 'Hauteur', 1, 2),
(75, 3, '33 kg', 'Poids', 2, 2),
(76, 3, 'Batterie incluse', 'Batterie', 3, 3),
(77, 3, '40 km/h', 'Vitesse maximale', 4, 4),
(78, 3, '35 km', 'Autonomie', 5, 5),
(79, 3, '120 kg', 'Charge maximale', 6, 6);

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
(1, 3, 'Moteur puissant de 500 W', 'Le BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante pour les trajets urbains ou les aventures hors route. Il peut atteindre une vitesse de pointe de 40 km/h, offrant une conduite palpitante avec suffisamment de puissance pour affronter divers terrains, y compris les collines.', 1),
(2, 3, 'Batterie haute capacité 48 V 15 Ah', 'Équipé d\'une batterie au lithium 48 V 15 Ah, le M5 Pro offre une autonomie allant jusqu\'à 35 km par charge, idéale pour les trajets plus longs.', 2),
(3, 3, 'Cadre en aluminium robuste et léger', 'Construit en alliage d\'aluminium de haute qualité, le M5 Pro allie durabilité et portabilité. Poids de seulement 25 kg et charge maximale 120 kg. Conception pliable pour un transport et stockage faciles.', 3),
(4, 3, 'Pneus pneumatiques de 12 pouces', 'Les pneus pneumatiques de 12 pouces offrent une conduite en douceur sur diverses surfaces, meilleure adhérence et confort amélioré pour les rues urbaines et sentiers hors route.', 4),
(5, 3, 'Système d\'éclairage complet', 'Équipé d\'un système d\'éclairage avancé avec phares avant LED, feu arrière clignotant et klaxon pour une sécurité optimale de nuit.', 5),
(6, 3, 'Freins à double disque', 'Freins à disque avant et arrière pour des arrêts rapides et sécurisés dans toutes les conditions.', 6),
(7, 1, 'Moteur performant 1000 W', 'La Honey Whale M5 Max est équipée d\'un moteur 1000 W offrant une vitesse de pointe de 45 km/h et une puissance optimale pour tout type de trajet.', 1),
(8, 1, 'Batterie longue durée', 'Batterie lithium 52 V 20 Ah, offrant jusqu\'à 40 km d\'autonomie pour les trajets quotidiens.', 2),
(9, 1, 'Cadre robuste et léger', 'Cadre en aluminium léger et pliable, permettant un transport facile. Charge maximale 130 kg.', 3),
(10, 1, 'Roues larges de 14 pouces', 'Roues larges offrant confort et stabilité sur les routes urbaines et chemins accidentés.', 4),
(11, 1, 'Éclairage complet et sécurité', 'Éclairage LED à l\'avant et à l\'arrière avec klaxon intégré pour la sécurité nocturne.', 5),
(12, 1, 'Système de freinage puissant', 'Freins à disque avant et arrière pour une conduite sûre et contrôlée.', 6),
(13, 2, 'Moteur économique 750 W', 'La KUGOO C1 Pro dispose d\'un moteur 750 W, adapté pour des trajets urbains efficaces et une vitesse maximale de 35 km/h.', 1),
(14, 2, 'Batterie 36 V 12 Ah', 'Batterie lithium offrant une autonomie de 30 km par charge, parfaite pour les trajets quotidiens.', 2),
(15, 2, 'Cadre compact et pliable', 'Cadre en aluminium léger, pliable pour un rangement facile et un transport pratique.', 3),
(16, 2, 'Pneus tout-terrain de 12 pouces', 'Pneus pneumatiques résistants offrant adhérence et confort sur routes irrégulières.', 4),
(17, 2, 'Éclairage et sécurité', 'Phares avant et arrière LED, avec feux de signalisation et klaxon pour assurer la sécurité de nuit.', 5),
(18, 2, 'Freinage efficace', 'Freins à disque arrière avec système de récupération d\'énergie pour un arrêt rapide et sécurisé.', 6);

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
  `tel` varchar(16) NOT NULL,
  `country` varchar(32) NOT NULL,
  `postal_code` varchar(16) NOT NULL,
  `address` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `tel`, `country`, `postal_code`, `address`) VALUES
(1, 'admin@admin.fr', '[\"ROLE_ADMIN\",\"ROLE_USER\"]', 'Admin', 'Admin', 'Admin', '06 04 05 02 09', 'France', '63200', '51 Rue de Konoha'),
(2, 'user@user.fr', '[]', 'User', 'User', 'User', '06 01 01 01 02', 'France', '63118', '51 Rue du Hueco Mundo');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessory`
--
ALTER TABLE `accessory`
  ADD PRIMARY KEY (`id`);

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
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accessory`
--
ALTER TABLE `accessory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `illustrationaccess`
--
ALTER TABLE `illustrationaccess`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `trottinette_caracteristique`
--
ALTER TABLE `trottinette_caracteristique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT pour la table `trottinette_description_section`
--
ALTER TABLE `trottinette_description_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

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
