-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 05. lis 2024, 15:28
-- Verze serveru: 10.4.22-MariaDB
-- Verze PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `index`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `doklad` varchar(20) NOT NULL,
  `nazev` varchar(64) NOT NULL,
  `datum` date NOT NULL,
  `datum_vyrazeni` date DEFAULT NULL,
  `prijemvydaj` varchar(10) NOT NULL,
  `dan` varchar(4) NOT NULL,
  `castka` int(11) NOT NULL,
  `typ` varchar(10) NOT NULL,
  `uhrada` varchar(10) NOT NULL,
  `odpis` int(11) NOT NULL,
  `zpusob` varchar(11) NOT NULL,
  `popis` varchar(64) NOT NULL,
  `hiddenSlot` varchar(16) NOT NULL,
  `lastYear` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Struktura tabulky `asset_depreciation`
--

CREATE TABLE `asset_depreciation` (
  `id` int(16) NOT NULL,
  `doklad` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `nazev` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `userID` bigint(20) NOT NULL,
  `row` int(4) NOT NULL,
  `assetID` bigint(20) NOT NULL,
  `castka` int(16) NOT NULL,
  `excuses` int(32) NOT NULL,
  `lastYear` tinyint(1) NOT NULL,
  `datum` date NOT NULL,
  `zpusob` varchar(16) COLLATE utf8_czech_ci NOT NULL,
  `prijemvydaj` varchar(10) COLLATE utf8_czech_ci NOT NULL,
  `dan` varchar(5) COLLATE utf8_czech_ci NOT NULL,
  `popis` varchar(128) COLLATE utf8_czech_ci NOT NULL,
  `uhrada` text COLLATE utf8_czech_ci NOT NULL,
  `zbyva` int(20) NOT NULL,
  `hiddenSlot` varchar(16) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


--
-- Struktura tabulky `demanddebt`
--

CREATE TABLE `demanddebt` (
  `id` bigint(20) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `nazev` varchar(64) NOT NULL,
  `cislodoklad` varchar(32) NOT NULL,
  `firma` varchar(64) NOT NULL,
  `datum` date NOT NULL,
  `datums` date NOT NULL,
  `pohledavkadluh` varchar(11) NOT NULL,
  `hodnota` double NOT NULL,
  `dan` varchar(3) NOT NULL,
  `uhrazeno` varchar(4) NOT NULL,
  `uhrada` varchar(10) NOT NULL,
  `doklad` varchar(16) NOT NULL,
  `popis` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Struktura tabulky `document_counter`
--

CREATE TABLE `document_counter` (
  `user_id` int(11) NOT NULL,
  `HP` int(11) DEFAULT NULL,
  `HV` int(11) DEFAULT NULL,
  `BP` int(11) DEFAULT NULL,
  `BV` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `incomeexpense`
--

CREATE TABLE `incomeexpense` (
  `nazev` varchar(64) NOT NULL,
  `doklad` varchar(20) NOT NULL,
  `datum` date NOT NULL,
  `prijemvydaj` varchar(16) NOT NULL,
  `castka` int(11) NOT NULL,
  `dan` varchar(10) NOT NULL,
  `uhrada` text NOT NULL,
  `popis` varchar(128) NOT NULL,
  `id` int(11) NOT NULL,
  `userID` bigint(20) NOT NULL,
  `assetID` bigint(11) DEFAULT NULL,
  `hiddenSlot` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Struktura tabulky `users`
--

CREATE TABLE `users` (
  `id` int(9) NOT NULL,
  `username` varchar(64) NOT NULL,
  `ico` int(16) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Indexy pro tabulku `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `incomeexpense`
--
ALTER TABLE `incomeexpense`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pro tabulku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT pro tabulku `incomeexpense`
--
ALTER TABLE `incomeexpense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2296;

--
-- AUTO_INCREMENT pro tabulku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
