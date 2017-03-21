-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 21 Mars 2017 à 15:44
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `microcms`
--
create database if not exists microcms character set utf8 collate utf8_unicode_ci;
use microcms;

grant all privileges on microcms.* to 'microcms_user'@'localhost' identified by 'secret';
-- --------------------------------------------------------

--
-- Structure de la table `t_article`
--

CREATE TABLE `t_article` (
  `art_id` int(11) NOT NULL,
  `art_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `art_content` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `art_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_article`
--

INSERT INTO `t_article` (`art_id`, `art_title`, `art_content`, `art_date`) VALUES
(1, 'Premier article', 'Oh, loneliness and cheeseburgers are a dangerous mix. Weaseling out of things is important to learn. It\'s what separates us from the animals…except the weasel. Dear Mr. President, There are too many states nowadays. Please, eliminate three. P.S. I am not a crackpot.\r\n\r\nUh, no, you got the wrong number. This is 9-1…2. Fame was like a drug. But what was even more like a drug were the drugs. Fat Tony is a cancer on this fair city! He is the cancer and I am the…uh…what cures cancer?\r\n\r\nSon, when you participate in sporting events, it\'s not whether you win or lose: it\'s how drunk you get. Weaseling out of things is important to learn. It\'s what separates us from the animals…except the weasel.\r\n\r\nWeaseling out of things is important to learn. It\'s what separates us from the animals…except the weasel. Well, he\'s kind of had it in for me ever since I accidentally ran over his dog. Actually, replace "accidentally" with "repeatedly" and replace "dog" with "son."\r\n\r\nOh, I\'m in no condition to drive. Wait a minute. I don\'t have to listen to myself. I\'m drunk. But, Aquaman, you cannot marry a woman without gills. You\'re from two different worlds… Oh, I\'ve wasted my life.', '2017-03-10 10:40:12'),
(2, 'Deuxième article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit mauris ac porttitor accumsan. Nunc vitae pulvinar odio, auctor interdum dolor. Aenean sodales dui quis metus iaculis, hendrerit vulputate lorem vestibulum. Suspendisse pulvinar, purus at euismod semper, nulla orci pulvinar massa, ac placerat nisi urna eu tellus. Fusce dapibus rutrum diam et dictum. Sed tellus ipsum, ullamcorper at consectetur vitae, gravida vel sem. Vestibulum pellentesque tortor et elit posuere vulputate. Sed et volutpat nunc. Praesent nec accumsan nisi, in hendrerit nibh. In ipsum mi, fermentum et eleifend eget, eleifend vitae libero. Phasellus in magna tempor diam consequat posuere eu eget urna. Fusce varius nulla dolor, vel semper dui accumsan vitae. Sed eget risus neque.', '2017-03-10 10:40:12'),
(3, 'Troisième article', 'J’en dis autant de ceux qui, par mollesse d’esprit, c’est-à-dire par la crainte de la peine et de la douleur, manquent aux devoirs de la vie. Et il est très facile de rendre raison de ce que j’avance. Car, lorsque nous sommes tout à fait libres, et que rien ne nous empêche de faire ce qui peut nous donner le plus de plaisir, nous pouvons nous livrer entièrement à la volupté et chasser toute sorte de douleur ; mais, dans les temps destinés aux devoirs de la société ou à la nécessité des affaires, souvent il faut faire divorce avec la volupté, et ne se point refuser à la peine. La règle que suit en cela un homme sage, c’est de renoncer à de légères voluptés pour en avoir de plus grandes, et de savoir supporter des douleurs légères pour en éviter de plus fâcheuses.', '2017-03-10 10:40:12'),
(4, 'Quatrième article', 'Integer elementum massa at nulla placerat varius. Suspendisse in libero risus, in interdum massa. Vestibulum ac leo vitae metus faucibus gravida ac in neque. Nullam est eros, suscipit sed dictum quis, accumsan a ligula. In sit amet justo lectus. Etiam feugiat dolor ac elit suscipit in elementum orci fringilla. Aliquam in felis eros. Praesent hendrerit lectus sit amet turpis tempus hendrerit. Donec laoreet volutpat molestie. Praesent tempus dictum nibh ac ullamcorper. Sed eu consequat nisi. Quisque ligula metus, tristique eget euismod at, ullamcorper et nibh. Duis ultricies quam egestas nibh mollis in ultrices turpis pharetra. Vivamus et volutpat mi. Donec nec est eget dolor laoreet iaculis a sit amet diam. \r\n', '2017-03-10 10:40:12'),
(6, 'Cinquième article', 'Maecenas eu placerat ante. Fusce ut neque justo, et aliquet enim. In hac habitasse platea dictumst. Nullam commodo neque erat, vitae facilisis erat. Cras at mauris ut tortor vestibulum fringilla vel sed metus. Donec interdum purus a justo feugiat rutrum. Sed ac neque ut neque dictum accumsan. Cras lacinia rutrum risus, id viverra metus dictum sit amet. Fusce venenatis, urna eget cursus placerat, dui nisl fringilla purus, nec tincidunt sapien justo ut nisl. Curabitur lobortis semper neque et varius. Etiam eget lectus risus, a varius orci. Nam placerat mauris at dolor imperdiet at aliquet lectus ultricies. Duis tincidunt mi at quam condimentum lobortis. \r\n', '2017-03-10 10:40:12');

-- --------------------------------------------------------

--
-- Structure de la table `t_comment`
--

CREATE TABLE `t_comment` (
  `com_id` int(11) NOT NULL,
  `com_author` varchar(100) NOT NULL,
  `com_content` text NOT NULL,
  `com_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `art_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `signale` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `t_comment`
--

INSERT INTO `t_comment` (`com_id`, `com_author`, `com_content`, `com_date`, `art_id`, `parent_id`, `signale`) VALUES
(73, 'numero 1', '<p>Commentaire num&eacute;ro 1</p>', '2017-03-21 12:54:04', 1, NULL, NULL),
(74, 'numero 1-1', '<p>reponse au commentaire 1</p>', '2017-03-21 12:54:26', 1, 73, NULL),
(75, 'numero 1-2', '<p>reponse au commentaire 1-1</p>', '2017-03-21 12:54:52', 1, 74, NULL),
(76, 'commentaire 2', '<p>commentaire 2</p>', '2017-03-21 12:55:26', 1, 73, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `usr_id` int(11) NOT NULL,
  `usr_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usr_password` varchar(88) COLLATE utf8_unicode_ci NOT NULL,
  `usr_salt` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `usr_role` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_user`
--

INSERT INTO `t_user` (`usr_id`, `usr_name`, `usr_password`, `usr_salt`, `usr_role`) VALUES
(1, 'Jean', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'EDDsl&fBCJB|a5XUtAlnQN8', 'ROLE_ADMIN');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `t_article`
--
ALTER TABLE `t_article`
  ADD PRIMARY KEY (`art_id`);

--
-- Index pour la table `t_comment`
--
ALTER TABLE `t_comment`
  ADD PRIMARY KEY (`com_id`),
  ADD KEY `art_id` (`art_id`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `t_article`
--
ALTER TABLE `t_article`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT pour la table `t_comment`
--
ALTER TABLE `t_comment`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;
--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
