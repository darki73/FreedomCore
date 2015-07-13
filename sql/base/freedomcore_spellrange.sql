-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:53
-- Версия сервера: 5.7.7-rc-log
-- Версия PHP: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `freedomcore`
--

-- --------------------------------------------------------

--
-- Структура таблицы `freedomcore_spellrange`
--

CREATE TABLE IF NOT EXISTS `freedomcore_spellrange` (
  `rangeID` mediumint(11) unsigned NOT NULL,
  `rangeMin` float NOT NULL,
  `rangeMinFriendly` float NOT NULL,
  `rangeMax` float NOT NULL,
  `rangeMaxFriendly` float NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_spellrange`
--

INSERT INTO `freedomcore_spellrange` (`rangeID`, `rangeMin`, `rangeMinFriendly`, `rangeMax`, `rangeMaxFriendly`, `name_loc0`) VALUES
(1, 0, 0, 0, 0, 'Self Only'),
(2, 0, 0, 5, 5, 'Combat Range'),
(3, 0, 0, 20, 20, 'Twenty yards'),
(4, 0, 0, 30, 30, 'Medium Range'),
(5, 0, 0, 40, 40, 'Long Range'),
(6, 0, 0, 100, 100, 'Vision Range'),
(7, 0, 0, 10, 10, 'Ten yards'),
(8, 10, 10, 20, 20, 'Min Range 10, 20'),
(9, 10, 10, 30, 30, 'Medium Range'),
(10, 10, 10, 40, 40, 'Long Range'),
(11, 0, 0, 15, 15, 'Fifteen yards'),
(12, 0, 0, 5, 5, 'Interact Range'),
(13, 0, 0, 50000, 50000, 'Anywhere'),
(14, 0, 0, 60, 60, 'Extra Long Range'),
(34, 0, 0, 25, 25, 'Twenty-Five yards'),
(35, 0, 0, 35, 35, 'Medium-Long Range'),
(36, 0, 0, 45, 45, 'Longer Range'),
(37, 0, 0, 50, 50, 'Extended Range'),
(38, 10, 10, 25, 25, 'Min-Range 10, 25'),
(54, 5, 5, 30, 30, 'Monster Shoot'),
(74, 0, 0, 30, 30, 'Ranged Weapon'),
(94, 8, 8, 40, 40, 'Sting'),
(95, 8, 8, 25, 25, 'Charge'),
(96, 0, 0, 2, 2, 'Trap'),
(114, 0, 0, 35, 35, 'Hunter Range'),
(134, 0, 0, 80, 80, 'Tower 80'),
(135, 0, 0, 100, 100, 'Tower 100'),
(136, 30, 30, 80, 80, 'Thirty-to-80'),
(137, 0, 0, 8, 8, 'Eight yards'),
(139, 5, 5, 45, 45, 'Long Range Hunter Shoot'),
(140, 0, 0, 6, 6, 'Six yards'),
(141, 0, 0, 7, 7, 'Seven yards'),
(150, 8, 8, 100, 100, 'Valgarde 8/100'),
(151, 5, 5, 45, 45, 'Long Range Hunter Shoot'),
(152, 0, 0, 150, 150, 'Super Long'),
(153, 0, 0, 60, 60, 'Charge, 60'),
(154, 10, 10, 80, 80, 'Tower 80, 10'),
(155, 0, 0, 45, 45, 'Hunter Range (Long)'),
(156, 30, 30, 200, 200, 'Boulder Range'),
(157, 0, 0, 90, 90, 'Ninety'),
(158, 15, 15, 150, 150, 'Super Long, 15 Min'),
(159, 0, 0, 40, 100, 'TEST - Long Range'),
(160, 0, 0, 30, 40, 'Medium/Long Range'),
(161, 0, 0, 20, 40, 'Short/Long Range'),
(162, 0, 0, 20, 30, 'Medium/Short Range'),
(163, 8, 8, 30, 30, 'Death Grip'),
(164, 10, 10, 70, 70, 'Catapult Range'),
(165, 0, 0, 14, 14, 'Fourteen yards'),
(166, 0, 0, 13, 13, 'Thirteen yards'),
(167, 40, 0, 150, 150, 'Super Long (Min Range)'),
(168, 0, 0, 38, 38, 'Medium-Long Range (38)'),
(169, 0, 0, 3, 3, 'Three Yards'),
(170, 0, 0, 55, 55, 'Fifty Five Yards'),
(171, 1, 1, 10, 10, 'Min Range 1, 10'),
(172, 0, 0, 11, 11, 'Eleven yards'),
(173, 5, 5, 50000, 50000, 'Anywhere (Combat Min Range)'),
(174, 0, 0, 1000, 1000, 'U L T R A'),
(176, 0, 0, 70, 70, 'Seventy yards'),
(177, 20, 20, 70, 70, 'Donut 20-70'),
(179, 5, 5, 15, 15, 'Min Range 5, 15'),
(180, 5, 5, 25, 25, 'Tournament - Ranged'),
(181, 0, 0, 200, 200, 'Two-Hundred Yard Range'),
(184, 5, 5, 25, 25, 'Min Range 5, 25'),
(187, 0, 0, 300, 300, 'Three Hundred Yards');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_spellrange`
--
ALTER TABLE `freedomcore_spellrange`
 ADD PRIMARY KEY (`rangeID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
