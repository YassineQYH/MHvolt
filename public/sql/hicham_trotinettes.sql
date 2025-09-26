-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 26 sep. 2025 à 16:19
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
(1, 'Volant', 'volant', 'volant', 'test.png', 1),
(2, 'roue', 'roue', 'roue', 'test.png', 1),
(3, 'guidon', 'guidon', 'guidon', 'guidon.png', 0),
(4, 'frein', 'frein', 'frein', 'frein.png', 0);

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
('DoctrineMigrations\\Version20250926132657', '2025-09-26 15:27:14', 254);

-- --------------------------------------------------------

--
-- Structure de la table `illustration`
--

CREATE TABLE `illustration` (
  `id` int(11) NOT NULL,
  `trottinette_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `slug` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
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

INSERT INTO `trottinette` (`id`, `name`, `slug`, `description`, `image`, `is_best`, `is_header`, `header_image`, `header_btn_title`, `header_btn_url`) VALUES
(1, 'Trottinette électrique honey whale m5 max avec siège', 'Trottinette-électrique-honey-whale-m5-max-avec-siège', '【Performance puissante】\n\nLe moteur de 1000 W offre des performances puissantes et stables, permettant au scooter d\'atteindre des vitesses allant jusqu\'à 40 km/h. Le scooter est alimenté par une batterie au lithium 48 V 13 Ah, offrant une autonomie allant jusqu\'à 40 km sur une seule charge, ce qui le rend idéal pour les trajets plus longs.\n\n \n【Grands pneus pneumatiques】\n\nLes pneus pneumatiques de 14 pouces sont conçus pour une élasticité, une résistance à l\'usure et une dissipation de la chaleur supérieures. Le motif spécial de la bande de roulement augmente la friction pour offrir une meilleure traction, assurant une conduite plus douce et plus sûre sur diverses surfaces.\n\n \n【Système d\'éclairage avancé】\n\nÉquipé de lumières LED haute luminosité à l\'avant et à l\'arrière, le Honey Whale M5 Max améliore la visibilité pendant les trajets de nuit, garantissant des trajets plus sûrs dans des conditions de faible luminosité.\n\n \n【Système de freinage double】\n\nLes freins à disque avant et arrière offrent une puissance de freinage efficace, vous offrant un contrôle fiable et une décélération rapide. Cette configuration à double freinage améliore la sécurité, en particulier dans les situations d\'urgence.\n\n \n【Conception durable et légère】\n\nConstruit en acier au carbone, le M5 Max allie durabilité et cadre léger. Malgré sa construction robuste, il reste relativement facile à manœuvrer, ce qui le rend pratique pour les déplacements urbains et le stockage.\n\n \n【Spécification】\n\nMarque : Honey Whale\nModèle : M5 MAX\nType : trottinette électrique\nMatériau : acier au carbone\nPneus : pneus pneumatiques 140/40-10\nBatterie : 54,6 V 13 Ah\nCharge maximale : 120 kg\nVitesse de pointe : 40 km/h\nPortée : 40 km\nTemps de chargement : 6-8 h\nFrein : freins à disque\npente : 15°\nÉtanchéité : IP5\nPoids du produit : 36 kg\nPoids du colis : 43 kg\nTaille du produit : 1380 x 320 x 630 mm\nTaille du colis : 1400 x 320 x 650 mm', 'trottbleue-01.png', 1, 1, 'trottbleue-01.png', 'test', 'test'),
(2, 'KUGOO Kukirin C1 Pro', 'KUGOO-Kukirin-C1-Pro', 'Aperçu du produit : Vitesse maximale 45 km/h Charge max. 120 kg Autonomie 100 km Puissance continue 500 W Siège', 'trottjaune-01.jpg', 1, 0, 'trottvert-01.jpg', 'test', 'test'),
(3, ' Bogist M5 Pro ', ' Bogist-M5-Pro ', 'Moteur puissant de 500 W pour des vitesses élevées\n\nLe BOGIST M5 Pro est propulsé par un moteur de 500 W, offrant une puissance impressionnante pour les trajets urbains ou les aventures hors route. Il peut atteindre une vitesse de pointe de 40 km/h (25 mph), offrant une conduite palpitante mais efficace avec suffisamment de puissance pour affronter divers terrains, y compris les collines avec jusqu\'à 12 degrés de capacité de montée.\n\n    Batterie haute capacité 48 V 15 Ah\n\nÉquipé d\'une batterie au lithium 48 V 15 Ah, le M5 Pro offre une excellente autonomie et durabilité. Sur une seule charge, le scooter peut parcourir des distances allant jusqu\'à 35 km (22 miles). Cette durée de vie prolongée de la batterie est idéale pour les trajets plus longs, garantissant que les conducteurs n\'auront pas besoin de recharger fréquemment.\n\n    Cadre en aluminium robuste et léger\n\nConstruit en alliage d\'aluminium de haute qualité, le M5 Pro allie durabilité et portabilité. Avec un poids de seulement 25 kg, il est suffisamment léger pour une utilisation quotidienne, tout en étant capable de supporter une charge maximale de 120 kg (265 lb). Cela le rend adapté à une large gamme de cyclistes, tandis que sa conception pliable permet un transport et un stockage faciles.\n\n    Pneus pneumatiques de 12 pouces pour une utilisation tout-terrain\n\nLa trottinette est équipée de pneus pneumatiques de 12 pouces, qui offrent une grande élasticité, une résistance à l\'usure et une excellente adhérence. Ces pneus offrent une conduite en douceur sur diverses surfaces, avec une meilleure dissipation de la chaleur pour assurer la sécurité même pendant les longs trajets. La grande taille des pneus améliore le confort et la stabilité, ce qui rend le M5 Pro adapté aux rues urbaines et aux sentiers hors route.\n\n    Système d\'éclairage complet pour la conduite de nuit\n\nLe M5 Pro est équipé d\'un système d\'éclairage avancé, assurant la sécurité pendant les trajets de nuit. Il comprend des phares avant à LED haute luminosité, un rappel de klaxon et un feu arrière avec un voyant d\'avertissement qui clignote pour augmenter la visibilité. Cela rend les déplacements de nuit ou par faible visibilité beaucoup plus sûrs, permettant au conducteur et aux autres usagers de la route d\'être plus vigilants.\n\n    Freins à double disque pour une sécurité accrue\n\nPour une sécurité maximale, le M5 Pro est équipé de freins à disque avant et arrière, offrant une puissance de freinage supérieure. Ce double système de freinage est essentiel pour des arrêts rapides et contrôlés, en particulier lorsque vous roulez à des vitesses élevées ou dans des environnements difficiles. Il réduit les risques d\'accident et garantit des performances de freinage fiables dans toutes les conditions.\n', 'trottvert-01.jpg', 1, 1, 'trottvert-01.jpg', 'test', 'test');

-- --------------------------------------------------------

--
-- Structure de la table `trottinette_accessory`
--

CREATE TABLE `trottinette_accessory` (
  `trottinette_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trottinette_accessory`
--

INSERT INTO `trottinette_accessory` (`trottinette_id`, `accessory_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(3, 2),
(3, 4);

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
-- Index pour les tables déchargées
--

--
-- Index pour la table `accessory`
--
ALTER TABLE `accessory`
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
  ADD PRIMARY KEY (`trottinette_id`,`accessory_id`),
  ADD KEY `IDX_B37F755EF6798F43` (`trottinette_id`),
  ADD KEY `IDX_B37F755E27E8CC78` (`accessory_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `illustration`
--
ALTER TABLE `illustration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `FK_B37F755E27E8CC78` FOREIGN KEY (`accessory_id`) REFERENCES `accessory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B37F755EF6798F43` FOREIGN KEY (`trottinette_id`) REFERENCES `trottinette` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
