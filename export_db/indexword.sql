-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Dim 24 Novembre 2019 à 22:55
-- Version du serveur :  5.7.26-0ubuntu0.18.10.1
-- Version de PHP :  7.2.19-0ubuntu0.18.10.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Création de la base de données`indexword`
--

CREATE DATABASE IF NOT EXISTS indexword;

-- --------------------------------------------------------

--
-- Base de données :  `indexword`
--

DELIMITER $$
--
-- Fonctions
--
CREATE DEFINER=`sofiane`@`localhost` FUNCTION `levenshtein` (`s1` VARCHAR(255), `s2` VARCHAR(255)) RETURNS INT(11) BEGIN 
DECLARE s1_len, s2_len, i, j, c, c_temp, cost INT; 
DECLARE s1_char CHAR; 
DECLARE cv0, cv1 VARBINARY(256); 
SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2), cv1 = 0x00, j = 1, i = 1, c = 0; 
IF s1 = s2 THEN 
  RETURN 0; 
ELSEIF s1_len = 0 THEN 
  RETURN s2_len; 
ELSEIF s2_len = 0 THEN 
  RETURN s1_len; 
ELSE 
  WHILE j <= s2_len DO 
    SET cv1 = CONCAT(cv1, UNHEX(HEX(j))), j = j + 1; 
  END WHILE; 
  WHILE i <= s1_len DO 
    SET s1_char = SUBSTRING(s1, i, 1), c = i, cv0 = UNHEX(HEX(i)), j = 1; 
    WHILE j <= s2_len DO 
      SET c = c + 1; 
      IF s1_char = SUBSTRING(s2, j, 1) THEN  
        SET cost = 0; ELSE SET cost = 1; 
      END IF; 
      SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost; 
      IF c > c_temp THEN SET c = c_temp; END IF; 
        SET c_temp = CONV(HEX(SUBSTRING(cv1, j+1, 1)), 16, 10) + 1; 
        IF c > c_temp THEN  
          SET c = c_temp;  
        END IF; 
        SET cv0 = CONCAT(cv0, UNHEX(HEX(c))), j = j + 1; 
    END WHILE; 
    SET cv1 = cv0, i = i + 1; 
  END WHILE; 
END IF; 
RETURN c; 
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `document` varchar(255) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `indexs`
--

CREATE TABLE `indexs` (
  `id` int(11) NOT NULL,
  `id_mot` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `poids` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mots`
--

CREATE TABLE `mots` (
  `id` int(11) NOT NULL,
  `mot` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `indexs`
--
ALTER TABLE `indexs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mots`
--
ALTER TABLE `mots`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `indexs`
--
ALTER TABLE `indexs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `mots`
--
ALTER TABLE `mots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
