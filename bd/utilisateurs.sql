-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 30 août 2025 à 06:55
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `erpvision`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `numero` int NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `confirm_mdp` varchar(255) NOT NULL,
  `titre` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `statut` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `numero`, `mdp`, `confirm_mdp`, `titre`, `statut`, `permission`) VALUES
(32, 'Pierre Martin', 'pierre@gmail.com', 0, 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '', 'accountant', 'Inactive', 'Read Access'),
(33, 'kenne loi', 'kene@gmail.com', 0, 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '', 'marketing_agent', 'Active', 'Write Access'),
(34, 'nathan role', 'nathan@gmail.com', 0, 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '', 'customer', 'Pending', 'Write Access'),
(35, 'yann martin', 'yann@gmail.com', 0, 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', '', 'marketing_agent', 'Inactive', 'Delete Access');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
