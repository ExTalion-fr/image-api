-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 11 déc. 2023 à 10:53
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `image_api`
--
CREATE DATABASE IF NOT EXISTS `image_api` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `image_api`;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(500) NOT NULL,
  `tags` text NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_ID_USER_ID` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `tags`, `date`, `user_id`) VALUES
(6, 'https://picsum.photos/id/11/2500/1667', '[]', '2023-11-15 10:21:11', 1),
(7, 'https://picsum.photos/id/11/2500/1667', '[]', '2023-11-15 10:21:11', 1),
(17, 'https://i.imgur.com/wOorgFw.jpg', '[]', '2023-11-15 12:56:46', 1),
(21, 'https://i.imgur.com/Bdqd7Yl.jpg', '[{\"id\":\"ai_VJXZtfth\",\"name\":\"mountain\",\"value\":0.9974361},{\"id\":\"ai_6w9F49lS\",\"name\":\"hike\",\"value\":0.9904359},{\"id\":\"ai_MTvKbKJv\",\"name\":\"landscape\",\"value\":0.99029434},{\"id\":\"ai_VRmbGVWh\",\"name\":\"travel\",\"value\":0.98502004},{\"id\":\"ai_tBcWlsCp\",\"name\":\"nature\",\"value\":0.97972316}]', '2023-11-15 13:06:51', 1),
(22, 'https://i.imgur.com/85w89RN.jpg', '[{\"id\":\"ai_3DwNCm6R\",\"name\":\"lake\",\"value\":0.9943461},{\"id\":\"ai_LmFtHz7q\",\"name\":\"fall\",\"value\":0.9891521},{\"id\":\"ai_mlrv94tv\",\"name\":\"reflection\",\"value\":0.98343647},{\"id\":\"ai_MTvKbKJv\",\"name\":\"landscape\",\"value\":0.9799135},{\"id\":\"ai_TjbmxC6B\",\"name\":\"tree\",\"value\":0.978365}]', '2023-11-15 13:18:34', 1),
(23, 'https://i.imgur.com/GdcEJAv.png', '[{\"id\":\"ai_Lq00FggW\",\"name\":\"desktop\",\"value\":0.99230486},{\"id\":\"ai_Dm5GLXnB\",\"name\":\"illustration\",\"value\":0.96140075},{\"id\":\"ai_8rNgfppJ\",\"name\":\"symbol\",\"value\":0.9595601},{\"id\":\"ai_rxcHpHks\",\"name\":\"isolated\",\"value\":0.95628536},{\"id\":\"ai_6lhccv44\",\"name\":\"business\",\"value\":0.9561755}]', '2023-12-11 11:48:49', 2),
(24, 'https://i.imgur.com/doKc2tf.png', '[{\"id\":\"ai_MnBdTFRf\",\"name\":\"template\",\"value\":0.9940519},{\"id\":\"ai_WCsfx0Ft\",\"name\":\"World Wide Web\",\"value\":0.9936992},{\"id\":\"ai_RmpTltl9\",\"name\":\"stripe\",\"value\":0.9926521},{\"id\":\"ai_vkQnVcpx\",\"name\":\"navigation\",\"value\":0.9917585},{\"id\":\"ai_jsqHqS3p\",\"name\":\"menu (food)\",\"value\":0.98753345}]', '2023-12-11 11:51:08', 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'ExTalion', '$2y$10$0/Yn.3KsFl6aHt6XIhDcaOdRmV//OPwVJMmRZ5GZ5bRmyKbnMjE..'),
(2, 'toto', '$2y$10$aFAic.e3tfub5wJMqUFjQe9PsRieIXEmbCI/v58jjO4bDBxcGdz5W');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_USER_ID_USER_ID` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
