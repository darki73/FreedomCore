-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:51
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
-- Структура таблицы `freedomcore_resistances`
--

CREATE TABLE IF NOT EXISTS `freedomcore_resistances` (
  `id` mediumint(11) unsigned NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_resistances`
--

INSERT INTO `freedomcore_resistances` (`id`, `name_loc0`) VALUES
(0, 'Physical'),
(1, 'Holy'),
(2, 'Fire'),
(3, 'Nature'),
(4, 'Frost'),
(5, 'Shadow'),
(6, 'Arcane');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_resistances`
--
ALTER TABLE `freedomcore_resistances`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
