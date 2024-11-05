-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Úte 05. lis 2024, 12:35
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
-- Vypisuji data pro tabulku `assets`
--

INSERT INTO `assets` (`id`, `userID`, `doklad`, `nazev`, `datum`, `datum_vyrazeni`, `prijemvydaj`, `dan`, `castka`, `typ`, `uhrada`, `odpis`, `zpusob`, `popis`, `hiddenSlot`, `lastYear`) VALUES
(145, 34, '666', 'peklo', '2023-09-14', '0000-00-00', 'VÃ½daj', '', 2147483647, '', '', 6, 'ZrychlenÃ½', 'atomovÃ¡ bomba mÄ›la spadnout na dalÅ¡Ã­ japonskÃ¡ mÄ›sta', '', 0),
(146, 34, '15496666666666666666', '1549666666666666666666666666666666666666666666666666666666666666', '1414-09-05', '0000-00-00', 'VÃ½daj', '', 2147483647, '', '', 2, 'RovnomÄ›rnÃ', '1549666666666666666666666666666666666666666666666666666666666666', '', 0),
(147, 34, 'æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™', 'æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ™®å¾·çˆ¾æ', '2022-08-08', '0000-00-00', 'VÃ½daj', '', 159191146, '', '', 1, 'RovnomÄ›rnÃ', 'æ™®å¾·çˆ¾', '', 0),
(150, 38, '5600a5s1f5aas', 'negrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegr', '1611-06-05', '0000-00-00', 'VÃ½daj', '', 2147483647, '', '', 6, 'ZrychlenÃ½', '1549666666666666666666666666666666666666666666666666666666666666', '', 0),
(151, 38, '5262', 'adasdadasdasd', '0000-00-00', '0000-00-00', 'VÃ½daj', '', 21, '', '', 1, 'RovnomÄ›rnÃ', '1549666666666666666666666666666666666666666666666666666666666666', '', 0),
(210, 38, '69', 'kokot', '2023-10-11', '0000-00-00', 'Výdaj', '', 80001, 'Hmotný', '', 2, 'Zrychlený', 'co já kurwa vím', 'Odpis', 0),
(221, 9, 'likvidace', 'likvidace', '2017-01-15', '2022-01-15', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(246, 9, 'fčfgč', 'Lopata', '2019-01-16', '2023-10-28', 'Výdaj', '', 100000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(247, 9, '32434', 'cvesse', '2017-01-11', '2022-01-11', 'Výdaj', '', 100000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(250, 9, 'aha', 'aha', '2020-12-29', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(252, 9, 'hello', 'hello', '2020-09-17', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(256, 9, '123', '123', '2021-01-06', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(257, 9, 'šfšfšfšf', 'ěšššf', '2020-12-30', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(258, 9, 'fafwa', 'afwf', '2021-01-07', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(259, 9, 'afawfaw', 'fafwafw', '2020-01-10', '2023-01-10', 'Výdaj', '', 80000, 'Nehmotný', '', 1, 'Rovnoměrný', '', 'Odpis', 0),
(260, 9, 'lalala', '1', '2022-01-05', '2023-10-28', 'Výdaj', '', 80000, 'Hmotný', '', 2, 'Rovnoměrný', '', 'Odpis', 0),
(264, 9, '123', 'auto', '2023-11-02', '0000-00-00', 'Výdaj', '', 250000, 'Hmotný', '', 2, 'Zrychlený', 'škoda ', 'Odpis', 0),
(272, 46, 'awdaawdawd', 'awd', '2023-10-30', '0000-00-00', 'Výdaj', '', 542444, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(273, 46, 'awdwad', 'awda', '2021-06-01', '0000-00-00', 'Výdaj', '', 47452742, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(275, 46, 'fesfsef', 'fasee', '2021-01-05', '0000-00-00', 'Výdaj', '', 2000000, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(276, 46, 'afeaf', 'abc', '2020-01-08', '0000-00-00', 'Výdaj', '', 100000, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(277, 46, 'adwa', 'dawaw', '2019-01-10', '0000-00-00', 'Výdaj', '', 200000, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(278, 46, 'probehlodpis?', 'probehlodpis?', '2021-11-08', '0000-00-00', 'Výdaj', '', 200000, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(280, 46, 'awdad', 'awd', '2022-11-09', '0000-00-00', 'Výdaj', '', 100000, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(0, 1, '654321', '654321', '2024-11-05', '2024-11-05', 'Výdaj', 'Ne', 654321, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(0, 1, '654321', '654321', '2024-11-05', '2024-11-05', 'Výdaj', 'Ne', 654321, 'Hmotný', 'Banka', 2, 'Rovnoměrný', '', 'Odpis', 0),
(0, 1, '951', '156+9+', '2020-01-08', '2024-11-05', 'Výdaj', 'Ne', 951000, 'Hmotný', 'Banka', 3, 'Rovnoměrný', '', 'Odpis', 0);

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `asset_depreciation`
--

INSERT INTO `asset_depreciation` (`id`, `doklad`, `nazev`, `userID`, `row`, `assetID`, `castka`, `excuses`, `lastYear`, `datum`, `zpusob`, `prijemvydaj`, `dan`, `popis`, `uhrada`, `zbyva`, `hiddenSlot`) VALUES
(2695, '', '', 9, 1, 221, 8800, 8800, 0, '2018-01-15', '', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2696, '', '', 9, 2, 221, 17800, 17800, 0, '2019-01-15', '', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2697, '', '', 9, 3, 221, 17800, 17800, 0, '2020-01-15', '', 'Výdaj', 'Ano', '', '', 35600, 'Odpis'),
(2698, '', '', 9, 4, 221, 17800, 17800, 0, '2021-01-15', '', 'Výdaj', 'Ano', '', '', 17800, 'Odpis'),
(2699, '', '', 9, 5, 221, 17800, 17800, 1, '2022-01-15', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2702, '', '', 9, 1, 246, 11000, 11000, 0, '2020-01-16', '', 'Výdaj', 'Ano', '', '', 89000, 'Odpis'),
(2703, '', '', 9, 2, 246, 22250, 22250, 0, '2021-01-16', '', 'Výdaj', 'Ano', '', '', 66750, 'Odpis'),
(2704, '', '', 9, 3, 246, 22250, 22250, 0, '2022-01-16', '', 'Výdaj', 'Ano', '', '', 44500, 'Odpis'),
(2705, '', '', 9, 4, 246, 22250, 22250, 0, '2023-01-16', '', 'Výdaj', 'Ano', '', '', 22250, 'Odpis'),
(2706, '', '', 9, 5, 246, 22250, 22250, 1, '2023-10-28', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2707, '', '', 9, 1, 247, 11000, 11000, 0, '2018-01-11', '', 'Výdaj', 'Ano', '', '', 89000, 'Odpis'),
(2708, '', '', 9, 2, 247, 22250, 22250, 0, '2019-01-11', '', 'Výdaj', 'Ano', '', '', 66750, 'Odpis'),
(2709, '', '', 9, 3, 247, 22250, 22250, 0, '2020-01-11', '', 'Výdaj', 'Ano', '', '', 44500, 'Odpis'),
(2710, '', '', 9, 4, 247, 22250, 22250, 0, '2021-01-11', '', 'Výdaj', 'Ano', '', '', 22250, 'Odpis'),
(2711, '', '', 9, 5, 247, 22250, 22250, 1, '2022-01-11', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2714, '', '', 9, 1, 250, 8800, 8800, 0, '2021-12-29', '', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2715, '', '', 9, 2, 250, 17800, 17800, 0, '2022-12-29', '', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2716, '', '', 9, 3, 250, 17800, 17800, 0, '2023-12-29', '', 'Výdaj', 'Ano', '', '', 35600, 'Odpis'),
(2719, '', '', 9, 1, 252, 8800, 8800, 0, '2021-09-17', '', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2720, '', '', 9, 2, 252, 17800, 17800, 0, '2022-09-17', '', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2721, '', '', 9, 3, 252, 17800, 17800, 0, '2023-09-17', '', 'Výdaj', 'Ano', '', '', 35600, 'Odpis'),
(2722, '', '', 9, 4, 252, 35600, 17800, 1, '2023-10-28', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2723, '', '', 9, 4, 250, 35600, 17800, 1, '2023-10-28', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2729, '', '', 9, 1, 256, 8800, 8800, 0, '2022-01-06', '', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2730, '', '', 9, 2, 256, 17800, 17800, 0, '2023-01-06', '', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2731, '', '', 9, 1, 256, 71200, 8800, 1, '2023-10-28', '', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2733, 'šfšfšfšf', 'ěšššf', 9, 1, 257, 8800, 8800, 0, '2021-12-30', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2734, 'šfšfšfšf', 'ěšššf', 9, 2, 257, 17800, 17800, 0, '2022-12-30', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2735, 'šfšfšfšf', 'ěšššf', 9, 3, 257, 17800, 17800, 0, '2023-12-30', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 35600, 'Odpis'),
(2736, 'šfšfšfšf', 'ěšššf', 9, 1, 257, 71200, 8800, 1, '2023-10-28', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2737, 'fafwa', 'afwf', 9, 1, 258, 8800, 8800, 0, '2022-01-07', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2738, 'fafwa', 'afwf', 9, 2, 258, 17800, 17800, 0, '2023-01-07', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 53400, 'Odpis'),
(2739, 'fafwa', 'afwf', 9, 1, 258, 71200, 8800, 1, '2023-10-28', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2740, 'afawfaw', 'fafwafw', 9, 1, 259, 16000, 16000, 0, '2021-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 64000, 'Odpis'),
(2741, 'afawfaw', 'fafwafw', 9, 2, 259, 32000, 32000, 0, '2022-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 32000, 'Odpis'),
(2742, 'afawfaw', 'fafwafw', 9, 3, 259, 32000, 32000, 1, '2023-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2743, 'ergehrg', 'wčterřgz', 9, 1, 260, 8800, 8800, 0, '2023-01-05', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 71200, 'Odpis'),
(2744, 'ergehrg', 'wčterřgz', 9, 1, 260, 71200, 8800, 1, '2023-10-28', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 0, 'Vyřazení'),
(2751, 'awdwad', 'awda', 46, 1, 273, 5219801, 5219802, 0, '2022-06-01', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 42232940, 'Odpis'),
(2752, 'awdwad', 'awda', 46, 2, 273, 10558235, 10558235, 0, '2023-06-01', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 31674705, 'Odpis'),
(2755, 'fesfsef', 'fasee', 46, 1, 275, 220000, 220000, 0, '2022-01-05', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 1780000, 'Odpis'),
(2756, 'fesfsef', 'fasee', 46, 2, 275, 445000, 445000, 0, '2023-01-05', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 1335000, 'Odpis'),
(2757, 'afeaf', 'abc', 46, 1, 276, 11000, 11000, 0, '2021-01-08', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 89000, 'Odpis'),
(2758, 'afeaf', 'abc', 46, 2, 276, 22250, 22250, 0, '2022-01-08', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 66750, 'Odpis'),
(2759, 'afeaf', 'abc', 46, 3, 276, 22250, 22250, 0, '2023-01-08', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 44500, 'Odpis'),
(2760, 'adwa', 'dawaw', 46, 1, 277, 22000, 22000, 0, '2020-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 178000, 'Odpis'),
(2761, 'adwa', 'dawaw', 46, 2, 277, 44500, 44500, 0, '2021-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 133500, 'Odpis'),
(2762, 'adwa', 'dawaw', 46, 3, 277, 44500, 44500, 0, '2022-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 89000, 'Odpis'),
(2763, 'adwa', 'dawaw', 46, 4, 277, 44500, 44500, 0, '2023-01-10', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 44500, 'Odpis'),
(2764, 'probehlodpis?', 'probehlodpis?', 46, 1, 278, 22000, 22000, 0, '2022-11-08', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 178000, 'Odpis'),
(2765, 'probehlodpis?', 'probehlodpis?', 46, 2, 278, 44500, 44500, 0, '2023-11-08', 'Rovnoměrný', 'Výdaj', 'Ano', '', '', 133500, 'Odpis'),
(0, '654321', '654321', 1, 1, 0, 654321, 0, 1, '2024-11-05', 'Rovnoměrný', 'Výdaj', 'Ano', '', 'Banka', 0, 'Vyřazení'),
(0, '951', '156+9+', 1, 1, 0, 52305, 52305, 0, '2020-12-31', 'Rovnoměrný', 'Výdaj', 'Ano', '', 'Banka', 898695, 'Odpis'),
(0, '951', '156+9+', 1, 2, 0, 99855, 99855, 0, '2021-12-31', 'Rovnoměrný', 'Výdaj', 'Ano', '', 'Banka', 798840, 'Odpis'),
(0, '951', '156+9+', 1, 3, 0, 99855, 99855, 0, '2022-12-31', 'Rovnoměrný', 'Výdaj', 'Ano', '', 'Banka', 698985, 'Odpis'),
(0, '951', '156+9+', 1, 4, 0, 99855, 99855, 0, '2023-12-31', 'Rovnoměrný', 'Výdaj', 'Ano', '', 'Banka', 599130, 'Odpis');

-- --------------------------------------------------------

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
  `popis` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `demanddebt`
--

INSERT INTO `demanddebt` (`id`, `userID`, `nazev`, `cislodoklad`, `firma`, `datum`, `datums`, `pohledavkadluh`, `hodnota`, `dan`, `popis`) VALUES
(1, 1, 'Kredit', '0', 'Vodafone', '2022-11-28', '0000-00-00', 'Pohledavka', 1449, 'Ano', ''),
(9, 9, 'Vodafone kredit', '23232332', '22323', '2023-05-17', '0000-00-00', 'Pohledávka', 2323232, 'Ano', '111'),
(10, 34, 'www/domains/danovaevidencecepela.cz/delete3.phpwww/domains/danov', '0', 'adawww/domains/danovaevidencecepela.cz/delete3.phpwww/domains/da', '2041-09-11', '0000-00-00', '', 800000000000777, '', ''),
(11, 38, 'zuhhnbhvmg', '0', 'sda dwad wad a', '2023-09-13', '0000-00-00', '', 1230203, '', ''),
(14, 9, 'wewsewsedfewsfews', '1243653535353535353535', '24345242424', '2023-09-08', '0000-00-00', 'Pohledávka', 2.4234432424242e18, 'Ne', ''),
(16, 9, '4543524', '453453345', '345354453', '2023-09-08', '2023-10-30', 'Dluh', 767867, 'Ano', ''),
(20, 9, '123', '123', '123', '2023-08-29', '0000-00-00', 'Pohledávka', 123, 'Ano', '123'),
(21, 9, '321', '321', '321', '2023-09-05', '0000-00-00', 'Pohledávka', 321, 'Ano', '321'),
(22, 9, 'test', 'test', 'test', '2023-08-28', '0000-00-00', 'Pohledávka', 321, 'Ano', 'test'),
(23, 9, 'ok', 'ok', 'ok', '2023-09-04', '0000-00-00', 'Pohledávka', 555, 'Ne', 'ok'),
(24, 9, 'ok', 'ok', 'ok', '2023-09-06', '0000-00-00', 'Pohledávka', 666, 'Ano', 'ok'),
(25, 9, 'haha', 'haha', 'haha', '2023-09-05', '0000-00-00', 'Dluh', 321, 'Ano', 'haha'),
(27, 9, 'asas', 'sasa', 'asas', '2023-09-04', '0000-00-00', 'Pohledávka', 223, 'Ano', 'saasas'),
(28, 9, 'testclass', 'testclass', 'testclass', '2023-09-27', '0000-00-00', 'Pohledávka', 23, 'Ano', '12365'),
(31, 38, 'reeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', '123456789', 'tvojemáma s.r.o.', '2023-10-04', '0000-00-00', 'Dluh', 69696969696969, 'Ano', ''),
(32, 9, 'Kredit123', 'FP10', 'Vodafone', '2023-10-03', '0000-00-00', 'Dluh', 556, 'Ano', ''),
(33, 46, 'Kredit', 'FV2', 'O2', '2023-09-28', '2023-10-10', 'Dluh', 450, 'Ano', ''),
(34, 38, 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqeeeeqqqqqqqqqqqqqqqqqqqqqqqqqqq', '6+9', 'yo mom', '2023-10-03', '0000-00-00', 'Pohledávka', 789456321, 'Ne', 'ne'),
(35, 9, 'faktura účto', '123', 'Daně - VTVS', '2023-11-02', '0000-00-00', 'Dluh', 3050, 'Ano', 'účetnictví'),
(36, 9, '8411', '411', '19789', '2022-01-05', '0000-00-00', 'Dluh', 718747, 'Ano', ''),
(37, 46, 'oops', 'tyry', 'tyryr', '2023-11-02', '2023-11-02', 'Pohledávka', 40, 'Ano', ''),
(40, 46, 'awddwa', 'dwad', 'sefse', '2023-10-30', '0000-00-00', 'Pohledávka', 80780, 'Ano', ''),
(41, 46, 'awddwa', 'dwad', 'sefse', '2023-10-30', '0000-00-00', 'Pohledávka', 80780, 'Ano', '');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `incomeexpense`
--

INSERT INTO `incomeexpense` (`nazev`, `doklad`, `datum`, `prijemvydaj`, `castka`, `dan`, `uhrada`, `popis`, `id`, `userID`, `assetID`, `hiddenSlot`) VALUES
('fadas', '0', '2022-08-07', 'VÃ½daj', 80, '', '', 'DrobnÃ½ majetek', 1844, 34, NULL, ''),
('dasa', '2147483647', '0001-04-04', 'PÅ™Ã­jem', 21, 'Ne', 'Z ÃºÄtu', '(Nematoda) jsou kmen prvoÃºstÃ½ch Å¾ivoÄichÅ¯ s ÄervovitÃ½m, neÄlÃ¡nkovanÃ½m tÄ›lem. Jejich velikost se pohybuje od nÄ›kolika', 1845, 38, NULL, ''),
('fadsdadaÅ¡ÄÄÄÄÄÄÄÄÄÄ', '0', '0009-09-09', 'VÃ½daj', 4441, 'Ne', 'Z ÃºÄtu', '(Nematoda) jsou kmen prvoÃºstÃ½ch Å¾ivoÄichÅ¯ s ÄervovitÃ½m, neÄlÃ¡nkovanÃ½m tÄ›lem. Jejich velikost se pohybuje od nÄ›kolika', 1846, 38, NULL, ''),
('negrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegr', '231313', '0004-04-05', 'VÃ½daj', 3131313, 'Ano', 'Z ÃºÄtu', '', 1847, 38, NULL, ''),
(' HlavnÃ­ menu  Wikipedie Wikipedie: OtevÅ™enÃ¡ encyklopedie     ', '46', '0464-04-07', 'PÅ™Ã­jem', 646, 'Ano', 'Z ÃºÄtu', 'HlavnÃ­ menuWikipedieWikipedilÅ¯Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§Â§9999', 1848, 38, NULL, ''),
('011010101010001', '2147483647', '0100-01-01', 'VÃ½daj', 101010101, 'Ano', 'Z ÃºÄtu', '', 1849, 38, NULL, ''),
('44444444444444', '444444', '4444-04-04', 'PÅ™Ã­jem', 44444, 'Ano', 'Z ÃºÄtu', 'negrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegrnegr', 1850, 38, NULL, ''),
('5855555555555', '2147483647', '0055-05-05', 'Výdaj', 2147483647, 'Ano', 'Hotovost', '55555555555555555551666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666666677777', 1851, 38, NULL, ''),
('dadad', '0', '0165-06-26', '', 54637474, '', '', '', 1852, 38, NULL, ''),
('dadad', '0', '5645-04-05', '', 454545, '', '', '', 1853, 38, NULL, ''),
('fda', '0', '0001-12-23', '', 45151, '', '', '', 1854, 38, NULL, ''),
('fadfasda', '0', '3454-04-12', '', 545445, '', '', '', 1855, 38, NULL, ''),
('test3', '0', '9999-09-09', '', 1561651, '', '', '', 1858, 38, NULL, ''),
('fghfgh', 'fghgffhg', '2023-08-29', 'Příjem', 2323, 'Ne', 'Z účtu', 'gggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg', 1926, 38, NULL, ''),
('sgfsdg', 'gsdfgfg', '2023-09-04', 'Příjem', 2323, 'Ano', 'Z účtu', '', 1927, 38, NULL, ''),
('Lopata', 'a/35453', '2016-01-16', 'Výdaj', 2900000, 'Ne', 'Hotovost', '', 1941, 9, NULL, ''),
('Tzjkk', '7ugg', '2023-09-15', 'Příjem', 6883581, 'Ano', 'Z účtu', '', 1942, 9, NULL, ''),
('5245', '5745757', '1990-01-17', 'Výdaj', 5775577, 'Ano', 'Z účtu', '', 1946, 9, NULL, ''),
('m', 'a/35453', '1991-01-18', 'Příjem', 500000, 'Ano', 'Z účtu', '', 1959, 9, NULL, ''),
('název', 'doklad', '2023-09-04', 'Příjem', 29000, 'Ano', 'Z účtu', '', 1963, 9, NULL, ''),
('test', '24674345353', '2023-09-05', 'Příjem', 1900, 'Ne', 'Z účtu', '', 1964, 9, NULL, ''),
('test', 'a/35453', '2023-09-06', 'Výdaj', 300, 'Ano', 'Hotovost', '', 1965, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2025, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2026, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2027, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2028, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2029, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2030, 9, NULL, ''),
('3223š', '322323', '2023-08-29', 'Příjem', 232323, 'Ano', 'Z účtu', '322323', 2031, 9, NULL, ''),
('3434343434', '3434343434', '2023-09-04', 'Příjem', 214748, 'Ano', 'Z účtu', '4334343434', 2032, 9, NULL, ''),
('445465645', '4565465466', '2023-08-29', 'Příjem', 4, 'Ne', 'Z účtu', '45645646', 2033, 9, NULL, ''),
('TEST', '123', '2023-09-05', 'Příjem', 123, 'Ano', 'Hotovost', '123', 2034, 9, NULL, ''),
('@#@#', '@##@', '2023-09-05', 'Výdaj', 123, 'Ano', 'Z účtu', '@#@#', 2035, 9, NULL, ''),
('123', '123', '2023-09-07', 'Výdaj', 123, 'Ano', '', '', 2036, 9, NULL, 'Drobný majetek'),
('se', 'se', '2023-09-06', 'Příjem', 123, 'Ano', 'Z účtu', 'se', 2037, 9, NULL, ''),
('ed', 'ed', '2023-09-20', 'Příjem', 1233445, 'Ano', 'Z účtu', 'ed', 2038, 9, NULL, ''),
('fe', 'fe', '2023-09-04', 'Příjem', 222, 'Ano', 'Z účtu', 'fe', 2039, 9, NULL, ''),
('try', 'ry', '2023-08-31', 'Příjem', 23, 'Ano', 'Z účtu', 'try', 2040, 9, NULL, ''),
('try', 'ry', '2023-08-31', 'Příjem', 23, 'Ano', 'Z účtu', 'try', 2041, 9, NULL, ''),
('try', 'ry', '2023-08-31', 'Příjem', 23, 'Ano', 'Z účtu', 'try', 2042, 9, NULL, ''),
('d', 'd', '2023-09-04', 'Příjem', 234, 'Ano', 'Z účtu', 'd', 2044, 9, NULL, ''),
('22', '22', '2023-08-30', 'Příjem', 22, 'Ano', 'Hotovost', '22', 2045, 9, NULL, ''),
('1', '1', '2023-09-26', 'Příjem', 1, 'Ano', 'Z účtu', '1', 2046, 9, NULL, ''),
('3', '3', '2023-09-12', 'Příjem', 3, 'Ano', 'Z účtu', '3', 2047, 9, NULL, ''),
('4', '4', '2023-09-05', 'Příjem', 4, 'Ano', 'Z účtu', '4', 2048, 9, NULL, ''),
('5', '5', '2023-09-05', 'Příjem', 5, 'Ano', 'Z účtu', '5', 2049, 9, NULL, ''),
('ž', 'ž', '2023-08-30', 'Příjem', 6, 'Ano', 'Z účtu', 'ž', 2052, 9, NULL, ''),
('ý', 'ý', '2023-08-28', 'Příjem', 7, 'Ano', 'Hotovost', 'ý', 2053, 9, NULL, ''),
('sa', 'sa', '2023-09-06', 'Příjem', 21, 'Ano', 'Z účtu', 'sa', 2054, 9, NULL, ''),
('fs', 'dsaasd', '2023-09-14', 'Příjem', 23, 'Ano', 'Z účtu', 'sdsd', 2055, 9, NULL, ''),
('2233', '2233', '2023-09-05', 'Příjem', 2233, 'Ano', 'Hotovost', 'assssc', 2056, 9, NULL, ''),
('asdsad', 'sadasasd', '2023-09-05', 'Příjem', 22, 'Ano', 'Z účtu', 'aadad', 2057, 9, NULL, ''),
('12', '12', '2023-09-05', 'Výdaj', 12, 'Ano', 'Z účtu', '12', 2058, 9, NULL, ''),
('x', 'x', '2023-09-12', 'Příjem', 2, 'Ano', 'Z účtu', 'x', 2059, 9, NULL, ''),
('abc', '22', '2023-09-11', 'Příjem', 2345, 'Ne', 'Z účtu', 'buffed', 2065, 9, NULL, ''),
('a1', 'a1', '2023-09-06', 'Příjem', 222, 'Ano', 'Z účtu', '', 2066, 9, NULL, ''),
('testujuhiddenslot', '34445', '2022-01-05', 'Výdaj', 22, 'Ano', '', '', 2103, 9, NULL, 'Drobný majetek'),
('1', '12', '2023-10-08', 'Výdaj', 1, 'Ano', 'Z účtu', '1', 2105, 9, NULL, ''),
('najednoujo?m', 'najednoujo?', '2023-10-07', 'Příjem', 123, 'Ano', 'Z účtu', 'najednoujo?', 2106, 9, NULL, ''),
('Výplata únor', 'a/35453', '2022-02-08', 'Příjem', 2759000, 'Ano', 'Z účtu', '1', 2107, 9, NULL, ''),
('123434343434xDXE TZUI', '123', '2023-10-04', 'Výdaj', 3433, 'Ano', '', '', 2110, 9, NULL, 'Drobný majetek'),
('123', '123', '2019-01-12', 'Výdaj', 2300, 'Ano', '', '', 2111, 9, NULL, 'Drobný majetek'),
('pokud tohle martin čte, tak je gay', '696969', '2023-10-28', 'Příjem', 800000000, 'Ano', 'Hotovost', 'co já kurwa vím', 2112, 38, NULL, ''),
('ok', 'a/35453', '2023-09-25', 'Příjem', 29000, 'Ano', 'Z účtu', '', 2113, 9, NULL, ''),
('ok', 'a/35453', '2023-09-25', 'Příjem', 29000, 'Ano', 'Z účtu', '', 2114, 9, NULL, ''),
('okne', 'bac', '2023-10-02', 'Příjem', 80000, 'Ano', 'Z účtu', 'bac', 2115, 9, NULL, ''),
('zwuehe', 'aha123', '2023-10-09', 'Příjem', 964634, 'Ano', 'Z účtu', 'uwu1', 2116, 9, NULL, ''),
('fef', 'fefee', '2023-09-25', 'Příjem', 12, 'Ano', 'Z účtu', 'feefe', 2117, 9, NULL, ''),
('help', 'help', '2023-09-26', 'Výdaj', 4, 'Ano', 'Z účtu', 'help', 2119, 9, NULL, ''),
('ni', 'ni', '2023-09-26', 'Příjem', 29000, 'Ano', 'Z účtu', 'ni', 2120, 9, NULL, ''),
('faawf', 'fawf', '2023-10-02', 'Příjem', 424, 'Ano', 'Z účtu', '34324', 2121, 9, NULL, ''),
('r3d3', 'hihi', '2023-09-25', 'Příjem', 343434, 'Ano', 'Z účtu', 'r35R3r', 2122, 9, NULL, ''),
('ohoh', 'ohoh', '2023-09-26', 'Příjem', 334, 'Ano', 'Z účtu', '343434', 2123, 9, NULL, ''),
('undet', 'undet', '2023-10-02', 'Příjem', 2434, 'Ne', 'Hotovost', 'undet', 2124, 9, NULL, ''),
('hi', 'hi', '2020-01-09', 'Příjem', 20000, 'Ano', 'Hotovost', '2332', 2125, 9, NULL, ''),
('what', 'nono', '2023-09-19', 'Příjem', 2000, 'Ano', 'Z účtu', '', 2126, 9, NULL, ''),
('jaaj', 'age', '2023-09-26', 'Příjem', 2454545, 'Ano', 'Z účtu', '45454', 2127, 9, NULL, ''),
('ytrz', 'a/35453', '2023-09-25', 'Příjem', 33434, 'Ano', 'Z účtu', '', 2128, 9, NULL, ''),
('terggr', 'rggrr', '2022-01-11', 'Příjem', 344, 'Ano', 'Z účtu', '34šč', 2129, 9, NULL, ''),
('efaef', 'eaffae', '2023-09-25', 'Příjem', 332, 'Ano', 'Z účtu', '@33223', 2130, 9, NULL, ''),
('fsef', 'fsef', '2023-09-27', 'Příjem', 33, 'Ano', 'Z účtu', 'faefef', 2131, 9, NULL, ''),
('dawd', 'wdawdwa', '2023-09-26', 'Výdaj', 33434, 'Ano', 'Hotovost', 'awdwa', 2132, 9, NULL, ''),
('Elektrocentrála NZ UNI', 'FV4', '2023-10-04', 'Příjem', 4700, 'Ano', 'Z účtu', '', 2133, 9, NULL, ''),
('okbro', 'okbro', '2023-10-03', 'Výdaj', 250, 'Ano', '', '', 2134, 9, NULL, 'Drobný majetek'),
('adwdaw', '#$', '2023-05-04', 'Příjem', 434343434, 'Ano', 'Z účtu', 'aaaw', 2135, 9, NULL, ''),
('faf', 'afwwfaf', '2023-10-04', 'Výdaj', 212, 'Ano', '', '', 2136, 9, NULL, 'Drobný majetek'),
('DELL', 'FV1', '2023-10-30', 'Výdaj', 25000, 'Ano', '', '', 2137, 46, NULL, 'Drobný majetek'),
('NZ UNI', 'FV5', '2023-10-30', 'Příjem', 4700, 'Ano', 'Z účtu', '', 2139, 46, NULL, ''),
('rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', 'kok', '2023-10-24', 'Příjem', 2147483647, 'Ano', 'Z účtu', 'co já kurwa vím', 2140, 38, NULL, ''),
('NZ UNI', 'FV6', '2023-10-30', 'Příjem', 1000000, 'Ano', 'Z účtu', '', 2141, 46, NULL, ''),
('materiál', '1', '2023-11-02', 'Výdaj', 1000, 'Ano', 'Hotovost', 'materiál', 2142, 9, NULL, ''),
('44', '7487', '2023-10-30', 'Příjem', 551, 'Ano', 'Z účtu', '', 2143, 9, NULL, ''),
('Lopata', '', '2023-11-03', 'Příjem', 29000, '0', 'Banka', '', 2147, 46, NULL, ''),
('Lopata', '', '2023-11-03', 'Příjem', 29000, '0', 'Banka', '', 2149, 46, NULL, ''),
('test', '', '2023-11-01', 'Výdaj', 123, 'Ano', 'Hotovost', '', 2151, 46, NULL, ''),
('test', '', '2023-11-01', 'Výdaj', 123, 'Ano', 'Hotovost', '', 2152, 46, NULL, ''),
('test', '', '2023-11-01', 'Výdaj', 123, 'Ano', 'Hotovost', '', 2153, 46, NULL, ''),
('try', '', '2023-10-31', 'Příjem', 50000, 'Ano', 'Banka', 'try', 2154, 46, NULL, ''),
('try2', '', '2023-11-02', 'Příjem', 5000, 'Ano', 'Banka', 'try2', 2155, 46, NULL, ''),
('abv', 'abv', '2023-11-01', 'Výdaj', 4000, 'Ano', '', '', 2156, 46, NULL, 'Drobný majetek'),
('test1', '', '2023-11-03', 'Příjem', 12, 'Ano', 'Banka', 'test1', 2159, 46, NULL, ''),
('test1', '', '2023-11-03', 'Příjem', 12, 'Ano', 'Banka', 'test1', 2160, 46, NULL, ''),
('test1', '', '2023-11-03', 'Příjem', 12, 'Ano', 'Banka', 'test1', 2161, 46, NULL, ''),
('test1', '', '2023-11-03', 'Příjem', 12, 'Ano', 'Banka', 'test1', 2162, 46, NULL, ''),
('trz3', '', '2023-11-01', 'Příjem', 40, 'Ano', 'Banka', '40', 2163, 46, NULL, ''),
('trz3', '', '2023-11-01', 'Příjem', 40, 'Ano', 'Banka', '40', 2164, 46, NULL, ''),
('try', '', '2023-11-02', 'Příjem', 70000, 'Ano', 'Banka', '', 2165, 46, NULL, ''),
('afa', '', '2023-10-30', 'Příjem', 50, 'Ne', 'Banka', '00', 2167, 46, NULL, ''),
('afa', '', '2023-10-30', 'Příjem', 50, 'Ne', 'Banka', '00', 2168, 46, NULL, ''),
('test4', '', '2023-11-01', 'Příjem', 123, 'Ano', 'Hotovost', '', 2169, 46, NULL, ''),
('fea', 'fe', '2023-10-31', 'Výdaj', 1220, 'Ano', 'Banka', '', 2170, 46, NULL, 'Drobný majetek'),
('adwaw', '', '2023-11-02', 'Příjem', 22324, 'Ano', 'Hotovost', '4242', 2171, 46, NULL, ''),
('adwaw', '', '2023-11-02', 'Příjem', 22324, 'Ano', 'Hotovost', '4242', 2172, 46, NULL, ''),
('abc', '', '2023-10-31', 'Příjem', 20, 'Ano', 'Banka', '', 2173, 46, NULL, ''),
('rg', '', '2021-01-05', 'Příjem', 4535, 'Ano', 'Banka', '', 2174, 46, NULL, ''),
('123', '123', '2024-11-05', 'Výdaj', 123123, 'Ano', 'Banka', '', 2175, 1, NULL, ''),
('abc', '456', '2024-11-05', 'Výdaj', 8000, 'Ano', 'Banka', '', 2176, 1, NULL, ''),
('654321', '654321', '2024-11-05', 'Výdaj', 654321, 'Ne', 'Banka', '', 2177, 1, NULL, 'Odpis'),
('654321', '654321', '2024-11-05', 'Výdaj', 654321, 'Ne', 'Banka', '', 2178, 1, NULL, 'Odpis'),
('156+9+', '951', '2020-01-08', 'Výdaj', 951000, 'Ne', 'Banka', '', 2179, 1, NULL, 'Odpis'),
('53453', '453354', '2024-11-05', 'Příjem', 4355, 'Ano', 'Banka', '', 2295, 1, NULL, '');

-- --------------------------------------------------------

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
-- Vypisuji data pro tabulku `users`
--

INSERT INTO `users` (`id`, `username`, `ico`, `email`, `password`) VALUES
(1, 'new_acc5', 0, 'xcepelm00@vutbr.fit.cz', '196a5008c6b9707c761cfddf60fd73a6');

--
-- Indexy pro exportované tabulky
--

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
