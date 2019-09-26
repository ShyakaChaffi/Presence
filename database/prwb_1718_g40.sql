-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 14 Février 2018 à 15:25
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `prwb_1718_gxx`
--
DROP DATABASE IF EXISTS `prwb_1718_g40`;
CREATE DATABASE `prwb_1718_g40` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `prwb_1718_g40`;

-- --------------------------------------------------------

--
-- Structure de la table `certificate`
--

CREATE TABLE `certificate` (
  `id` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `finishdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

CREATE TABLE `course` (
  `code` int(4) NOT NULL,
  `title` varchar(128) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `startdate` date NOT NULL,
  `finishdate` date NOT NULL,
  `teacher` int(11) NOT NULL,
  `dayofweek` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `course`
--

INSERT INTO `course` (`code`, `title`, `starttime`, `endtime`, `startdate`, `finishdate`, `teacher`, `dayofweek`) VALUES
(1111, 'PRWB - Projet de développement Web', '15:00:00', '17:30:00', '2017-09-18', '2018-06-30', 1, 0),
(2222, 'PRWB - Projet de développement Web', '10:00:00', '12:30:00', '2017-09-18', '2018-06-30', 2, 1),
(3333, 'WEB1 - Principes de base Web', '13:00:00', '15:00:00', '2018-02-05', '2018-06-30', 2, 1),
(4444, 'PRM2 - Principes algorithmiques et programmation', '13:00:00', '17:00:00', '2018-02-05', '2018-06-30', 1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `courseoccurrence`
--

CREATE TABLE `courseoccurrence` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `course` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `courseoccurrence`
--

INSERT INTO `courseoccurrence` (`id`, `date`, `course`) VALUES
(1, '2017-09-18', 1111),
(42, '2017-09-19', 2222),
(2, '2017-09-25', 1111),
(43, '2017-09-26', 2222),
(3, '2017-10-02', 1111),
(44, '2017-10-03', 2222),
(4, '2017-10-09', 1111),
(45, '2017-10-10', 2222),
(5, '2017-10-16', 1111),
(46, '2017-10-17', 2222),
(6, '2017-10-23', 1111),
(47, '2017-10-24', 2222),
(7, '2017-10-30', 1111),
(48, '2017-10-31', 2222),
(8, '2017-11-06', 1111),
(49, '2017-11-07', 2222),
(9, '2017-11-13', 1111),
(50, '2017-11-14', 2222),
(10, '2017-11-20', 1111),
(51, '2017-11-21', 2222),
(11, '2017-11-27', 1111),
(52, '2017-11-28', 2222),
(12, '2017-12-04', 1111),
(53, '2017-12-05', 2222),
(13, '2017-12-11', 1111),
(54, '2017-12-12', 2222),
(14, '2017-12-18', 1111),
(55, '2017-12-19', 2222),
(15, '2017-12-25', 1111),
(56, '2017-12-26', 2222),
(16, '2018-01-01', 1111),
(57, '2018-01-02', 2222),
(17, '2018-01-08', 1111),
(58, '2018-01-09', 2222),
(18, '2018-01-15', 1111),
(59, '2018-01-16', 2222),
(19, '2018-01-22', 1111),
(60, '2018-01-23', 2222),
(20, '2018-01-29', 1111),
(61, '2018-01-30', 2222),
(21, '2018-02-05', 1111),
(62, '2018-02-06', 2222),
(83, '2018-02-06', 3333),
(104, '2018-02-08', 4444),
(22, '2018-02-12', 1111),
(63, '2018-02-13', 2222),
(84, '2018-02-13', 3333),
(105, '2018-02-15', 4444),
(23, '2018-02-19', 1111),
(64, '2018-02-20', 2222),
(85, '2018-02-20', 3333),
(106, '2018-02-22', 4444),
(24, '2018-02-26', 1111),
(65, '2018-02-27', 2222),
(86, '2018-02-27', 3333),
(107, '2018-03-01', 4444),
(25, '2018-03-05', 1111),
(66, '2018-03-06', 2222),
(87, '2018-03-06', 3333),
(108, '2018-03-08', 4444),
(26, '2018-03-12', 1111),
(67, '2018-03-13', 2222),
(88, '2018-03-13', 3333),
(109, '2018-03-15', 4444),
(27, '2018-03-19', 1111),
(68, '2018-03-20', 2222),
(89, '2018-03-20', 3333),
(110, '2018-03-22', 4444),
(28, '2018-03-26', 1111),
(69, '2018-03-27', 2222),
(90, '2018-03-27', 3333),
(111, '2018-03-29', 4444),
(29, '2018-04-02', 1111),
(70, '2018-04-03', 2222),
(91, '2018-04-03', 3333),
(112, '2018-04-05', 4444),
(30, '2018-04-09', 1111),
(71, '2018-04-10', 2222),
(92, '2018-04-10', 3333),
(113, '2018-04-12', 4444),
(31, '2018-04-16', 1111),
(72, '2018-04-17', 2222),
(93, '2018-04-17', 3333),
(114, '2018-04-19', 4444),
(32, '2018-04-23', 1111),
(73, '2018-04-24', 2222),
(94, '2018-04-24', 3333),
(115, '2018-04-26', 4444),
(33, '2018-04-30', 1111),
(74, '2018-05-01', 2222),
(95, '2018-05-01', 3333),
(116, '2018-05-03', 4444),
(34, '2018-05-07', 1111),
(75, '2018-05-08', 2222),
(96, '2018-05-08', 3333),
(117, '2018-05-10', 4444),
(35, '2018-05-14', 1111),
(76, '2018-05-15', 2222),
(97, '2018-05-15', 3333),
(118, '2018-05-17', 4444),
(36, '2018-05-21', 1111),
(77, '2018-05-22', 2222),
(98, '2018-05-22', 3333),
(119, '2018-05-24', 4444),
(37, '2018-05-28', 1111),
(78, '2018-05-29', 2222),
(99, '2018-05-29', 3333),
(120, '2018-05-31', 4444),
(38, '2018-06-04', 1111),
(79, '2018-06-05', 2222),
(100, '2018-06-05', 3333),
(121, '2018-06-07', 4444),
(39, '2018-06-11', 1111),
(80, '2018-06-12', 2222),
(101, '2018-06-12', 3333),
(122, '2018-06-14', 4444),
(40, '2018-06-18', 1111),
(81, '2018-06-19', 2222),
(102, '2018-06-19', 3333),
(123, '2018-06-21', 4444),
(41, '2018-06-25', 1111),
(82, '2018-06-26', 2222),
(103, '2018-06-26', 3333),
(124, '2018-06-28', 4444);

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

CREATE TABLE `presence` (
  `student` int(11) NOT NULL,
  `courseoccurence` int(11) NOT NULL,
  `present` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `sex` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `student`
--

INSERT INTO `student` (`id`, `lastname`, `firstname`, `sex`) VALUES
(1, 'Aragon', 'Louis', 'M'),
(2, 'Dylan', 'Bob', 'M'),
(3, 'Gréco', 'Juliette', 'F'),
(4, 'Piaf', 'Edith', 'F'),
(5, 'Macias', 'Enrico', 'M'),
(6, 'Delerm', 'Vincent', 'M'),
(7, 'Cherhal', 'Jeanne', 'F'),
(8, 'Dion', 'Céline', 'F'),
(9, 'Franklin', 'Aretha', 'F'),
(10, 'Sardou', 'Michel', 'M');

-- --------------------------------------------------------

--
-- Structure de la table `studentcourses`
--

CREATE TABLE `studentcourses` (
  `student` int(11) NOT NULL,
  `course` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `studentcourses`
--

INSERT INTO `studentcourses` (`student`, `course`) VALUES
(3, 1111),
(6, 1111),
(7, 1111),
(8, 1111),
(4, 2222),
(5, 2222),
(9, 2222),
(10, 2222),
(1, 3333),
(2, 3333),
(3, 3333),
(1, 4444),
(2, 4444),
(3, 4444),
(4, 4444),
(5, 4444);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `fullname` varchar(128) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `pseudo`, `password`, `fullname`, `role`) VALUES
(1, 'boris', '56ce92d1de4f05017cf03d6cd514d6d1', 'Boris Verhaegen', 'teacher'),
(2, 'benoit', '56ce92d1de4f05017cf03d6cd514d6d1', 'Benoit Penelle', 'teacher'),
(3, 'stephanie', '56ce92d1de4f05017cf03d6cd514d6d1', 'Stéphanie', 'admin'),
(4, 'alain', '56ce92d1de4f05017cf03d6cd514d6d1', 'Alain Silovy', 'teacher');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `certificate`
--
ALTER TABLE `certificate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student` (`student`);

--
-- Index pour la table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`code`),
  ADD KEY `teacher` (`teacher`);

--
-- Index pour la table `courseoccurrence`
--
ALTER TABLE `courseoccurrence`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`,`course`),
  ADD UNIQUE KEY `date_2` (`date`,`course`),
  ADD KEY `course` (`course`);

--
-- Index pour la table `presence`
--
ALTER TABLE `presence`
  ADD PRIMARY KEY (`student`,`courseoccurence`),
  ADD UNIQUE KEY `student` (`student`,`courseoccurence`),
  ADD KEY `student_2` (`student`),
  ADD KEY `courseoccurence` (`courseoccurence`);

--
-- Index pour la table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `studentcourses`
--
ALTER TABLE `studentcourses`
  ADD PRIMARY KEY (`student`,`course`),
  ADD KEY `course` (`course`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `certificate`
--
ALTER TABLE `certificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `courseoccurrence`
--
ALTER TABLE `courseoccurrence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT pour la table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `certificate`
--
ALTER TABLE `certificate`
  ADD CONSTRAINT `certificate_ibfk_1` FOREIGN KEY (`student`) REFERENCES `student` (`id`);

--
-- Contraintes pour la table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`teacher`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `courseoccurrence`
--
ALTER TABLE `courseoccurrence`
  ADD CONSTRAINT `courseoccurrence_ibfk_1` FOREIGN KEY (`course`) REFERENCES `course` (`code`);

--
-- Contraintes pour la table `presence`
--
ALTER TABLE `presence`
  ADD CONSTRAINT `presence_ibfk_1` FOREIGN KEY (`student`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `presence_ibfk_2` FOREIGN KEY (`courseoccurence`) REFERENCES `courseoccurrence` (`id`);

--
-- Contraintes pour la table `studentcourses`
--
ALTER TABLE `studentcourses`
  ADD CONSTRAINT `studentcourses_ibfk_1` FOREIGN KEY (`student`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `studentcourses_ibfk_2` FOREIGN KEY (`course`) REFERENCES `course` (`code`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
