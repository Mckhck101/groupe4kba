-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 11 juin 2025 à 00:24
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `groupe4_kba`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `insert_document`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_document` (IN `doc_user_id` INT, IN `doc_nom` VARCHAR(255), IN `doc_chemin` VARCHAR(255), IN `doc_categorie` VARCHAR(100))   BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE id = doc_user_id) THEN
        INSERT INTO documents (user_id, nom, chemin, categorie) VALUES (doc_user_id, doc_nom, doc_chemin, doc_categorie);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `insert_log`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_log` (IN `log_user_id` INT, IN `log_action` VARCHAR(255), IN `log_document_id` INT)   BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE id = log_user_id) THEN
        INSERT INTO logs (user_id, action, document_id) VALUES (log_user_id, log_action, log_document_id);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `insert_partage`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_partage` (IN `partage_user_id` INT, IN `partage_document_id` INT)   BEGIN
    IF EXISTS (SELECT 1 FROM users WHERE id = partage_user_id)
       AND EXISTS (SELECT 1 FROM documents WHERE id = partage_document_id) THEN
        INSERT INTO partage (user_id, document_id) VALUES (partage_user_id, partage_document_id);
    END IF;
END$$

DROP PROCEDURE IF EXISTS `insert_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_user` (IN `user_nom` VARCHAR(100), IN `user_email` VARCHAR(100), IN `user_password` VARCHAR(255), IN `user_role` ENUM('admin','user'))   BEGIN
    IF NOT EXISTS (SELECT 1 FROM users WHERE email = user_email) THEN
        INSERT INTO users (nom, email, mot_de_passe, role) VALUES (user_nom, user_email, user_password, user_role);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `chemin` varchar(255) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `date_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `user_id`, `nom`, `chemin`, `categorie`, `date_upload`) VALUES
(1, 75411, 'Rapport_Groupe-7.pdf', 'documents/6848b2b5e9fa6_Rapport_Groupe-7.pdf', 'Rapport de stage', '2025-06-10 22:33:25'),
(2, 74511, 'PROJET CCNA 1.pdf', 'documents/6848c728bf3e9_PROJET CCNA 1.pdf', 'Projet', '2025-06-11 00:00:40'),
(3, 74511, 'PROJET CCNA 1.pdf', 'documents/6848c77aa9cf4_PROJET CCNA 1.pdf', 'Projet', '2025-06-11 00:02:02'),
(4, 74511, 'PROJET CCNA 1.pdf', 'documents/6848c7849373c_PROJET CCNA 1.pdf', 'Projet', '2025-06-11 00:02:12'),
(5, 74511, 'TP ROUTAGE.pdf', 'documents/6848c890837d1_TP ROUTAGE.pdf', 'Travaux Pratiques', '2025-06-11 00:06:40'),
(6, 74511, 'TP ROUTAGE.pdf', 'documents/6848c8a0ceb54_TP ROUTAGE.pdf', 'Travaux Pratiques', '2025-06-11 00:06:56'),
(7, 74511, 'TP ROUTAGE.pdf', 'documents/6848c8d27d7f4_TP ROUTAGE.pdf', 'Travaux Pratiques', '2025-06-11 00:07:46'),
(8, 745, 'sniffing[1].pdf', 'documents/6848c929cbacc_sniffing[1].pdf', 'Recherche', '2025-06-11 00:09:13'),
(9, 74511, 'Cours Bases de l\'administration windows.pdf', 'documents/6848cbf4034ed_Cours Bases de l\'administration windows.pdf', 'Cours', '2025-06-11 00:21:08'),
(10, 74511, 'Robert Greene - Power, les 48 lois du pouvoir.pdf', 'documents/6848cca4abe5a_Robert Greene - Power, les 48 lois du pouvoir.pdf', 'Livre Manipulation', '2025-06-11 00:24:04');

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `actionU` varchar(255) NOT NULL,
  `document_id` int DEFAULT NULL,
  `dateL` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `document_id` (`document_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partage`
--

DROP TABLE IF EXISTS `partage`;
CREATE TABLE IF NOT EXISTS `partage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `document_id` int DEFAULT NULL,
  `dateP` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `document_id` (`document_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partage`
--

INSERT INTO `partage` (`id`, `user_id`, `document_id`, `dateP`) VALUES
(1, 200, 1, '2025-06-10 23:09:48'),
(2, 74511, 2, '2025-06-11 00:00:40'),
(3, 74511, 3, '2025-06-11 00:02:02'),
(4, 74511, 4, '2025-06-11 00:02:12'),
(5, 74511, 5, '2025-06-11 00:06:40'),
(6, 745, 5, '2025-06-11 00:06:40'),
(7, 74511, 6, '2025-06-11 00:06:56'),
(8, 745, 6, '2025-06-11 00:06:56'),
(9, 74511, 7, '2025-06-11 00:07:46'),
(10, 745, 7, '2025-06-11 00:07:46'),
(11, 200, 7, '2025-06-11 00:07:46'),
(12, 745, 8, '2025-06-11 00:09:13'),
(13, 200, 8, '2025-06-11 00:09:13'),
(14, 74511, 9, '2025-06-11 00:21:08'),
(15, 74511, 10, '2025-06-11 00:24:04'),
(16, 200, 10, '2025-06-11 00:24:04');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','user','invite') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=75412 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(745, 'Edzoa Mackenzy', 'mackedzoajunior@gmail.com', '$2y$10$SIfAuXj1LV69/f9m6PYe9eOGBDDgik5r2CZzXuMe1mvspXSH/EO.m', 'admin'),
(74511, 'Edzoa Mack', 'mackedzoa@gmail.com', '$2y$10$jEX0dCDZAQSzqzaY00qywO0rHY2gS1aSZj7ecE0fdkaI3orrNZsJ6', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
