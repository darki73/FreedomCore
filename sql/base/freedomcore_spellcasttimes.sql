-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:52
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
-- Структура таблицы `freedomcore_spellcasttimes`
--

CREATE TABLE IF NOT EXISTS `freedomcore_spellcasttimes` (
  `id` mediumint(11) unsigned NOT NULL,
  `base` mediumint(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_spellcasttimes`
--

INSERT INTO `freedomcore_spellcasttimes` (`id`, `base`) VALUES
(1, 0),
(2, 250),
(3, 500),
(4, 1000),
(5, 2000),
(6, 5000),
(7, 10000),
(8, 20000),
(9, 30000),
(10, 1000),
(11, 2000),
(12, 5000),
(13, 30000),
(14, 3000),
(15, 4000),
(16, 1500),
(18, -1000000),
(19, 2500),
(20, 2500),
(21, 2600),
(22, 3500),
(23, 1800),
(24, 2200),
(25, 2900),
(26, 3700),
(27, 4100),
(28, 3200),
(29, 4700),
(30, 4500),
(31, 2300),
(32, 7000),
(33, 2000),
(34, 3000),
(35, 12500),
(36, 600),
(37, 25000),
(38, 45000),
(39, 50000),
(50, 1300),
(70, 300000),
(90, 1700),
(91, 2800),
(110, 750),
(130, 1600),
(150, 3800),
(151, 2700),
(152, 3100),
(153, 3400),
(170, 8000),
(171, 6000),
(190, 100),
(191, 0),
(192, 15000),
(193, 12000),
(194, -1000000),
(195, 1100),
(196, 750),
(197, 850),
(198, 900),
(199, 333),
(200, 0),
(201, 19000),
(202, 1400),
(203, 14000),
(204, 9000),
(205, 0),
(206, 1250),
(207, 40000),
(208, 60000),
(209, 200);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_spellcasttimes`
--
ALTER TABLE `freedomcore_spellcasttimes`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
