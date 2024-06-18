-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 18 juin 2024 à 13:15
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
-- Base de données : `locauto`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `categorie` varchar(256) NOT NULL,
  `prix` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `categorie`, `prix`) VALUES
(1, 'Economique', 19.99),
(2, 'Standard', 29.99),
(3, 'Luxe', 49.99),
(4, 'Sport', 0.00),
(5, 'SUV', 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `choixoptions`
--

CREATE TABLE `choixoptions` (
  `id_choix_option` int(11) NOT NULL,
  `id_option` int(11) NOT NULL,
  `id_louer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `choixoptions`
--

INSERT INTO `choixoptions` (`id_choix_option`, `id_option`, `id_louer`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `id_type_client` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `adresse` varchar(256) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `id_type_client`, `nom`, `prenom`, `adresse`, `email`, `password`, `is_admin`) VALUES
(1, 1, 'Dupont', 'Jean', '123 Rue de la République', '', '', 0),
(2, 2, 'Durand', 'Marie', '456 Avenue des Champs', '', '', 0),
(3, 1, 'Lhuillier', 'Remi', '6 rue des trente', 'tgl@gmail.com', '$2y$10$a.A5vSWnu2EPHeJyrTg6Z.Lo0LdDtUqvniXzn7iTPlg0suUEquPmq', 1),
(4, 1, 'Enzo', 'Deca', '11 rue', 'tgl2@gmail.com', '$2y$10$0k7.spbuNko9L2bqOzKxceYJkw5xasmO1HoBqTlNh.c79Ghyjax2a', 0),
(5, 1, 'Lhuillier', 'remi', '11 rue louis arragon', 'tgl3@gmail.com', '$2y$10$/CoKdfE9InHmOAJtQumJBu5MQ889OVyB876JWWqGvHxvh3xVhnM6O', 0);

-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id_image` int(11) NOT NULL,
  `immatriculation` varchar(16) DEFAULT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `images`
--

INSERT INTO `images` (`id_image`, `immatriculation`, `image_url`) VALUES
(1, 'AB-123-CD', 'https://www.largus.fr/images/styles/max_1300x1300/public/images/trophe-es-argus-2019-02_1.jpg?itok=t0qCk1H8'),
(2, 'EF-456-GH', 'https://www.gpas-cache.ford.com/guid/93030b0b-72c2-3bd2-ae49-6de6957c8f0b.png'),
(3, 'IJ-789-KL', 'https://www.largus.fr/images/images/2019-bmw-x5-hyrbide-45e-iperformance-blanc-10.jpg'),
(5, 'AB-123-CD', 'https://scene7.toyota.eu/is/image/toyotaeurope/cor0003a_23_GR-SPORT_Gris-Mineral_03-2023:Medium-Landscape?ts=0&resMode=sharp2&op_usm=1.75,0.3,2,0'),
(6, 'AB-123-CD', 'https://scene7.toyota.eu/is/image/toyotaeurope/cor0003a_23_GR-SPORT_Gris-Mineral_03-2023:Medium-Landscape?ts=0&resMode=sharp2&op_usm=1.75,0.3,2,0'),
(7, 'XY-542-FT', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRsEuuQhntMbOZEBzhlN8FjRafm_c6QD2G--j8duDC-2A&s'),
(12, 'FR-587-EF', 'https://www.bmw.fr/content/dam/bmw/common/all-models/1-series/5-door/2022/highlights/bmw-1-series-gallery-image-design-01_890.jpg/jcr:content/renditions/cq5dam.resized.img.890.medium.time1632213545861.jpg'),
(13, 'FR-587-EF', 'https://www.bmw.fr/content/dam/bmw/common/all-models/1-series/5-door/2022/highlights/bmw-1-series-gallery-image-design-04_890.jpg/jcr:content/renditions/cq5dam.resized.img.890.medium.time1686780229054.jpg'),
(14, 'TY-975-MP', 'https://www.bmw.fr/content/dam/bmw/common/all-models/2-series/active-tourer/2021/onepager/bmw-2-series-active-tourer-onepager-mc-phev-highlights-hero-desktop.jpg/jcr:content/renditions/cq5dam.resized.img.1680.large.time1676370326517.jpg'),
(15, 'TY-975-MP', 'https://www.bmw.fr/content/dam/bmw/common/all-models/2-series/active-tourer/2021/onepager/bmw-2-series-active-tourer-onepager-model-brief-vehicle-specs.jpg/jcr:content/renditions/cq5dam.resized.img.585.low.time1631198336480.jpg'),
(21, 'IG-975-FG', 'https://www.bmw.fr/content/dam/bmw/common/all-models/m-series/x1-m35i/2023/highlights/bmw-x1-mp-gallery-impressions-01_890.jpg'),
(22, 'PW-571-AP', 'https://bmw.scene7.com/is/image/BMW/bmw-x3-technical-data-stage-desktop?wid=1680&hei=756'),
(23, 'TY-712-BV', 'https://cdn.motor1.com/images/mgl/OoeJje/s3/bmw-x2-2024.jpg'),
(24, 'TT-157-981', 'https://cdn.drivek.com/configurator-imgs/cars/fr/Original/BMW/X4/40475_SUV-VP-5-PORTES/bmw-x4-2021-side-front.jpg'),
(25, 'RT-577-GT', 'https://cdn.drivek.com/configurator-imgs/cars/fr/Original/BMW/X6-M/41548_SUV-5-DOORS/bmw-x6-competition-front-view.jpg'),
(26, 'RT-987-MP', 'https://www.bmw.fr/content/dam/bmw/common/all-models/x-series/x7/2022/highlights/bmw-x-series-x7-sp-desktop.jpg'),
(27, 'RT-987-MP', 'https://cdn.drivek.com/configurator-imgs/cars/fr/Original/BMW/X7/41136_SUV-5-DOORS/bmw-x7-front-view.jpg'),
(29, 'YT-685-CF', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A1/A1-Sportback/2023/1920x1080_Conduite-1_A1-SB_100823.jpg?imwidth=1920&imdensity=1'),
(30, 'YT-685-CF', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A1/A1-Sportback/2023/1920x1080_Conduite-2_A1-SB_100823.jpg?imwidth=1920&imdensity=1'),
(31, 'TR-684-CV', 'https://www.audi.fr/dam/nemo/fr/Gamme/A3/A3SB/2024/1920x1080A3_SB_Media_Framed_11032024.jpg?width=1766'),
(32, 'TR-684-CV', 'https://www.audi.fr/dam/nemo/fr/Gamme/A3/A3SB/2024/1920x1080_A3_SB_S_line_11032024.jpg?width=1766'),
(33, 'TR-684-CV', 'https://cdn.discordapp.com/attachments/783742345853272126/1245999212529844274/image.png?ex=665acab1&is=66597931&hm=98add96a109e38db98e6d3a2868bf463bcbf930125d48e41d986a42367315d2a&'),
(34, 'TF-687-MP', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A3/Refonte-2022/rs3-sportback/1920x1080_RS3Sportback_liste_15122022.jpg?imwidth=1920&imdensity=1'),
(35, 'TF-687-MP', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A3/Refonte-2022/rs3-sportback/1920x1080_RS3Sportback_design1_06122022.jpg?imwidth=1920&imdensity=1'),
(36, 'TF-687-MP', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A3/Refonte-2022/rs3-sportback/1920x1080_RS3Sportback_design2_06122022.jpg?imwidth=1920&imdensity=1'),
(37, 'TF-687-MP', 'https://www.audi.fr/content/dam/nemo/fr/Gamme/A3/Refonte-2022/rs3-sportback/1920x1080_RS3Sportback_jantes_14122022.jpg?imwidth=1920&imdensity=1');

-- --------------------------------------------------------

--
-- Structure de la table `louer`
--

CREATE TABLE `louer` (
  `id_louer` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `immatriculation` varchar(16) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `kilometrage_debut` int(11) NOT NULL,
  `kilometrage_fin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `louer`
--

INSERT INTO `louer` (`id_louer`, `id_client`, `immatriculation`, `date_debut`, `date_fin`, `kilometrage_debut`, `kilometrage_fin`) VALUES
(1, 1, 'AB-123-CD', '2023-01-01', '2023-01-10', 10000, 11000),
(2, 2, 'EF-456-GH', '2023-02-01', '2023-02-05', 5000, 5500);

-- --------------------------------------------------------

--
-- Structure de la table `marques`
--

CREATE TABLE `marques` (
  `id_marque` int(11) NOT NULL,
  `marque` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `marques`
--

INSERT INTO `marques` (`id_marque`, `marque`) VALUES
(1, 'Toyota'),
(2, 'Ford'),
(3, 'BMW'),
(4, 'BMW'),
(5, 'Audi');

-- --------------------------------------------------------

--
-- Structure de la table `modeles`
--

CREATE TABLE `modeles` (
  `id_modele` int(11) NOT NULL,
  `modele` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `modeles`
--

INSERT INTO `modeles` (`id_modele`, `modele`) VALUES
(1, 'Corolla'),
(2, 'Focus'),
(3, 'X5'),
(4, 'M4'),
(5, 'Série 1'),
(6, 'Série 2 Active Tourer'),
(7, 'BMW iX'),
(8, 'iX'),
(9, 'X1'),
(10, 'X2'),
(11, 'X3'),
(12, 'X4'),
(13, 'X5'),
(14, 'X6'),
(15, 'X7'),
(16, 'A1 Sportback'),
(17, 'A3 Sportback'),
(18, 'RS3');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

CREATE TABLE `options` (
  `id_option` int(11) NOT NULL,
  `option` varchar(256) NOT NULL,
  `prix` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id_option`, `option`, `prix`) VALUES
(1, 'GPS', 5.00),
(2, 'Siège bébé', 7.50),
(3, 'Assurance complémentaire', 10.00),
(4, 'Plein de carburant', 80.00);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `immatriculation` varchar(16) DEFAULT NULL,
  `kilometres` int(11) DEFAULT NULL,
  `days` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `id_client`, `immatriculation`, `kilometres`, `days`, `total_price`, `status`, `start_date`, `end_date`) VALUES
(16, 3, 'AB-123-CD', 100, 2, 100.00, 'accepted', '2024-05-01', '2024-05-02'),
(17, 3, 'AB-123-CD', 100, 1, 75.00, 'accepted', '2024-05-30', '2024-05-30'),
(18, 3, 'AB-123-CD', 100, 3, 125.00, 'pending', '2024-05-01', '2024-05-03'),
(19, 3, 'XY-542-FT', 100, 3, 750.00, 'pending', '2024-05-30', '2024-06-01'),
(20, 3, 'AB-123-CD', 100, 2, 100.00, 'accepted', '2024-06-06', '2024-06-07');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_options`
--

CREATE TABLE `reservation_options` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `typesclient`
--

CREATE TABLE `typesclient` (
  `id_type_client` int(11) NOT NULL,
  `type_client` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `typesclient`
--

INSERT INTO `typesclient` (`id_type_client`, `type_client`) VALUES
(1, 'Particulier'),
(2, 'Entreprise');

-- --------------------------------------------------------

--
-- Structure de la table `voitures`
--

CREATE TABLE `voitures` (
  `immatriculation` varchar(16) NOT NULL,
  `id_marque` int(11) NOT NULL,
  `id_modele` int(11) NOT NULL,
  `kilometrage` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `voitures`
--

INSERT INTO `voitures` (`immatriculation`, `id_marque`, `id_modele`, `kilometrage`, `id_categorie`, `prix`) VALUES
('AB-123-CD', 1, 1, 10700, 1, 50.00),
('EF-456-GH', 2, 2, 5100, 2, 100.00),
('FR-587-EF', 3, 5, 1000, 2, 50.00),
('IG-975-FG', 3, 9, 10000, 5, 100.00),
('IJ-789-KL', 3, 3, 15000, 3, 150.00),
('PW-571-AP', 3, 11, 10000, 5, 120.00),
('RT-577-GT', 3, 14, 10000, 3, 140.00),
('RT-987-MP', 3, 15, 10000, 3, 150.00),
('TF-687-MP', 5, 18, 10000, 4, 140.00),
('TR-684-CV', 5, 17, 10000, 2, 80.00),
('TT-157-981', 4, 12, 10000, 5, 130.00),
('TY-712-BV', 3, 10, 10000, 5, 110.00),
('TY-975-MP', 3, 6, 10000, 2, 80.00),
('XY-542-FT', 4, 4, 10000, 4, 300.00),
('YT-685-CF', 5, 16, 10000, 2, 70.00);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `choixoptions`
--
ALTER TABLE `choixoptions`
  ADD PRIMARY KEY (`id_choix_option`),
  ADD KEY `id_option` (`id_option`),
  ADD KEY `id_louer` (`id_louer`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `id_type_client` (`id_type_client`);

--
-- Index pour la table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `immatriculation` (`immatriculation`);

--
-- Index pour la table `louer`
--
ALTER TABLE `louer`
  ADD PRIMARY KEY (`id_louer`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `immatriculation` (`immatriculation`);

--
-- Index pour la table `marques`
--
ALTER TABLE `marques`
  ADD PRIMARY KEY (`id_marque`);

--
-- Index pour la table `modeles`
--
ALTER TABLE `modeles`
  ADD PRIMARY KEY (`id_modele`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id_option`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `immatriculation` (`immatriculation`);

--
-- Index pour la table `reservation_options`
--
ALTER TABLE `reservation_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Index pour la table `typesclient`
--
ALTER TABLE `typesclient`
  ADD PRIMARY KEY (`id_type_client`);

--
-- Index pour la table `voitures`
--
ALTER TABLE `voitures`
  ADD PRIMARY KEY (`immatriculation`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_marque` (`id_marque`),
  ADD KEY `id_modele` (`id_modele`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `choixoptions`
--
ALTER TABLE `choixoptions`
  MODIFY `id_choix_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `images`
--
ALTER TABLE `images`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `louer`
--
ALTER TABLE `louer`
  MODIFY `id_louer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `marques`
--
ALTER TABLE `marques`
  MODIFY `id_marque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `modeles`
--
ALTER TABLE `modeles`
  MODIFY `id_modele` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `id_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `reservation_options`
--
ALTER TABLE `reservation_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `typesclient`
--
ALTER TABLE `typesclient`
  MODIFY `id_type_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `choixoptions`
--
ALTER TABLE `choixoptions`
  ADD CONSTRAINT `choixoptions_ibfk_1` FOREIGN KEY (`id_option`) REFERENCES `options` (`id_option`),
  ADD CONSTRAINT `choixoptions_ibfk_2` FOREIGN KEY (`id_louer`) REFERENCES `louer` (`id_louer`);

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`id_type_client`) REFERENCES `typesclient` (`id_type_client`);

--
-- Contraintes pour la table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`immatriculation`) REFERENCES `voitures` (`immatriculation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `louer`
--
ALTER TABLE `louer`
  ADD CONSTRAINT `louer_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `louer_ibfk_2` FOREIGN KEY (`immatriculation`) REFERENCES `voitures` (`immatriculation`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`immatriculation`) REFERENCES `voitures` (`immatriculation`);

--
-- Contraintes pour la table `reservation_options`
--
ALTER TABLE `reservation_options`
  ADD CONSTRAINT `reservation_options_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `reservation_options_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id_option`);

--
-- Contraintes pour la table `voitures`
--
ALTER TABLE `voitures`
  ADD CONSTRAINT `voitures_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`),
  ADD CONSTRAINT `voitures_ibfk_2` FOREIGN KEY (`id_marque`) REFERENCES `marques` (`id_marque`),
  ADD CONSTRAINT `voitures_ibfk_3` FOREIGN KEY (`id_modele`) REFERENCES `modeles` (`id_modele`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
