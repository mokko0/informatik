-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 06, 2024 alle 23:12
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `markouni_tecnoteca`
--
CREATE DATABASE IF NOT EXISTS `markouni_tecnoteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `markouni_tecnoteca`;

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `id_articolo` int(16) NOT NULL,
  `numero_inventario` varchar(32) NOT NULL,
  `articolo` varchar(64) NOT NULL,
  `stato` set('disponibile','guasto','prenotato','in prestito') NOT NULL,
  `fk_id_categoria` int(16) DEFAULT NULL,
  `fk_id_centro` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id_articolo`, `numero_inventario`, `articolo`, `stato`, `fk_id_categoria`, `fk_id_centro`) VALUES
(1, '122', 'ps1', 'prenotato', 1, 1),
(4, '77', 'playstation 5', 'guasto', 1, 3),
(5, '7254', 'er pimpa', 'disponibile', 2, 3),
(69, '741', 'simba la rue', 'disponibile', 2, 3),
(74, '7984', 'tututa', 'disponibile', 2, 2);

--
-- Trigger `articoli`
--
DELIMITER $$
CREATE TRIGGER `copy_and_delete_trigger` BEFORE DELETE ON `articoli` FOR EACH ROW BEGIN
    -- Copy the deleted record to the articoli_dismessi table
    INSERT INTO articoli_dismessi (id_articolo2, numero_inventario, articolo, stato, fk_id_categoria, fk_id_centro) 
    VALUES (OLD.id_articolo, OLD.numero_inventario, OLD.articolo, OLD.stato, OLD.fk_id_categoria, OLD.fk_id_centro);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli_dismessi`
--

CREATE TABLE `articoli_dismessi` (
  `id_articolo2` int(16) NOT NULL,
  `numero_inventario` varchar(32) NOT NULL,
  `articolo` varchar(64) NOT NULL,
  `stato` set('disponibile','guasto','prenotato','in prestito') NOT NULL,
  `fk_id_categoria` int(16) DEFAULT NULL,
  `fk_id_centro` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `articoli_dismessi`
--

INSERT INTO `articoli_dismessi` (`id_articolo2`, `numero_inventario`, `articolo`, `stato`, `fk_id_categoria`, `fk_id_centro`) VALUES
(8, '100', 'test guasto', 'guasto', 2, 3),
(11, '123456', 'test restore', 'guasto', 1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `categorie`
--

CREATE TABLE `categorie` (
  `id_categoria` int(16) NOT NULL,
  `categoria` varchar(32) NOT NULL,
  `tipologia` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `categorie`
--

INSERT INTO `categorie` (`id_categoria`, `categoria`, `tipologia`) VALUES
(1, 'console', 'hardware'),
(2, 'ebook', 'software');

-- --------------------------------------------------------

--
-- Struttura della tabella `centri`
--

CREATE TABLE `centri` (
  `id_centro` int(16) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `citta` varchar(32) NOT NULL,
  `indirizzo` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `centri`
--

INSERT INTO `centri` (`id_centro`, `nome`, `citta`, `indirizzo`) VALUES
(1, 'Tecnoteca 1', 'El Jadida ', 'Piazza klb'),
(2, 'Tecnoteca 2', 'Marrakech ', 'zizu '),
(3, 'Tecnoteca 3 ', 'Rabat', 'layl');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE `prenotazioni` (
  `id_prenotazione` int(16) NOT NULL,
  `data_inizio` date NOT NULL,
  `fk_id_utente` int(16) DEFAULT NULL,
  `fk_id_articolo` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_prenotazione`, `data_inizio`, `fk_id_utente`, `fk_id_articolo`) VALUES
(81, '2024-04-10', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(16) NOT NULL,
  `nome` varchar(32) NOT NULL,
  `cognome` varchar(32) NOT NULL,
  `indirizzo` varchar(64) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `tipo_utente` set('cliente','operatore','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `nome`, `cognome`, `indirizzo`, `email`, `password`, `tipo_utente`) VALUES
(1, 'Soufian', 'Markouni', 'via z', 'primo@gmail.com', '1a0a283bfe7c549dee6c638a05200e32', 'cliente');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`id_articolo`),
  ADD KEY `fk_id_centro` (`fk_id_centro`),
  ADD KEY `fk_id_categoria` (`fk_id_categoria`);

--
-- Indici per le tabelle `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indici per le tabelle `centri`
--
ALTER TABLE `centri`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`id_prenotazione`),
  ADD KEY `fk_id_utente` (`fk_id_utente`,`fk_id_articolo`),
  ADD KEY `fk_id_articolo` (`fk_id_articolo`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `id_articolo` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT per la tabella `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categoria` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `centri`
--
ALTER TABLE `centri`
  MODIFY `id_centro` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  MODIFY `id_prenotazione` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `articoli`
--
ALTER TABLE `articoli`
  ADD CONSTRAINT `articoli_ibfk_1` FOREIGN KEY (`fk_id_centro`) REFERENCES `centri` (`id_centro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `articoli_ibfk_2` FOREIGN KEY (`fk_id_categoria`) REFERENCES `categorie` (`id_categoria`) ON UPDATE CASCADE;

--
-- Limiti per la tabella `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD CONSTRAINT `prenotazioni_ibfk_1` FOREIGN KEY (`fk_id_utente`) REFERENCES `utenti` (`id_utente`) ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazioni_ibfk_2` FOREIGN KEY (`fk_id_articolo`) REFERENCES `articoli` (`id_articolo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
