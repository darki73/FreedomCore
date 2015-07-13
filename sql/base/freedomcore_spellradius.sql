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
-- Структура таблицы `freedomcore_spellradius`
--

CREATE TABLE IF NOT EXISTS `freedomcore_spellradius` (
  `radiusID` smallint(5) unsigned NOT NULL,
  `radiusBase` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_spellradius`
--

INSERT INTO `freedomcore_spellradius` (`radiusID`, `radiusBase`) VALUES
(7, 2),
(8, 5),
(9, 20),
(10, 30),
(11, 45),
(12, 100),
(13, 10),
(14, 8),
(15, 3),
(16, 1),
(17, 13),
(18, 15),
(19, 18),
(20, 25),
(21, 35),
(22, 200),
(23, 40),
(24, 65),
(25, 70),
(26, 4),
(27, 50),
(28, 50000),
(29, 6),
(30, 500),
(31, 80),
(32, 12),
(33, 99),
(35, 55),
(36, 0),
(37, 7),
(38, 21),
(39, 34),
(40, 9),
(41, 150),
(42, 11),
(43, 16),
(44, 0.5),
(45, 10),
(46, 5),
(47, 15),
(48, 60),
(49, 90),
(50, 15),
(51, 60),
(52, 5),
(53, 60),
(54, 50000),
(55, 130),
(56, 38),
(57, 45),
(58, 50000),
(59, 32),
(60, 44),
(61, 14),
(62, 47),
(63, 23),
(64, 3.5),
(65, 80);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_spellradius`
--
ALTER TABLE `freedomcore_spellradius`
 ADD PRIMARY KEY (`radiusID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
