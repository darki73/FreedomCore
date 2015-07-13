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
-- Структура таблицы `freedomcore_spelldispeltype`
--

CREATE TABLE IF NOT EXISTS `freedomcore_spelldispeltype` (
  `id` mediumint(11) unsigned NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_spelldispeltype`
--

INSERT INTO `freedomcore_spelldispeltype` (`id`, `name_loc0`) VALUES
(0, 'None'),
(1, 'Magic'),
(2, 'Curse'),
(3, 'Disease'),
(4, 'Poison'),
(5, 'Stealth'),
(6, 'Invisibility'),
(7, 'All(M+C+D+P)'),
(8, 'Special - npc only'),
(9, 'Enrage'),
(10, 'ZG Trinkets'),
(11, 'ZZOLD UNUSED');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_spelldispeltype`
--
ALTER TABLE `freedomcore_spelldispeltype`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
