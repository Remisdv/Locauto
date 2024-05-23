-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 23 mai 2024 à 09:25
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
-- Structure de la table `voitures`
--

CREATE TABLE `voitures` (
  `immatriculation` varchar(16) NOT NULL,
  `id_marque` int(11) NOT NULL,
  `id_modele` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `kilometrage` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `voitures`
--

INSERT INTO `voitures` (`immatriculation`, `id_marque`, `id_modele`, `image`, `kilometrage`, `id_categorie`, `prix`) VALUES
('AB-123-CD', 1, 1, 'https://www.largus.fr/images/styles/max_1300x1300/public/images/trophe-es-argus-2019-02_1.jpg?itok=t0qCk1H8', 10200, 1, 50.00),
('EF-456-GH', 2, 2, 'https://www.gpas-cache.ford.com/guid/93030b0b-72c2-3bd2-ae49-6de6957c8f0b.png', 5000, 2, 100.00),
('IJ-789-KL', 3, 3, 'https://www.largus.fr/images/images/2019-bmw-x5-hyrbide-45e-iperformance-blanc-10.jpg', 15000, 3, 150.00);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `voitures`
--
ALTER TABLE `voitures`
  ADD PRIMARY KEY (`immatriculation`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_marque` (`id_marque`),
  ADD KEY `id_modele` (`id_modele`);

--
-- Contraintes pour les tables déchargées
--

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
