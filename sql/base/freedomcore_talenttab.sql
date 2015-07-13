-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:54
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
-- Структура таблицы `freedomcore_talenttab`
--

CREATE TABLE IF NOT EXISTS `freedomcore_talenttab` (
  `id` mediumint(11) unsigned NOT NULL DEFAULT '0',
  `name_loc0` varchar(32) NOT NULL DEFAULT '',
  `classes` mediumint(11) unsigned DEFAULT '0',
  `pets` mediumint(11) unsigned DEFAULT '0',
  `order` tinyint(1) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_talenttab`
--

INSERT INTO `freedomcore_talenttab` (`id`, `name_loc0`, `classes`, `pets`, `order`) VALUES
(81, 'Arcane', 128, 0, 0),
(161, 'Arms', 1, 0, 0),
(182, 'Assassination', 8, 0, 0),
(201, 'Discipline', 16, 0, 0),
(261, 'Elemental', 64, 0, 0),
(283, 'Balance', 1024, 0, 0),
(302, 'Affliction', 256, 0, 0),
(361, 'Beast Mastery', 4, 0, 0),
(382, 'Holy', 2, 0, 0),
(398, 'Blood', 32, 0, 0),
(409, 'Tenacity', 0, 2, 0),
(410, 'Ferocity', 0, 1, 0),
(411, 'Cunning', 0, 4, 0),
(41, 'Fire', 128, 0, 1),
(164, 'Fury', 1, 0, 1),
(181, 'Combat', 8, 0, 1),
(202, 'Holy', 16, 0, 1),
(263, 'Enhancement', 64, 0, 1),
(281, 'Feral Combat', 1024, 0, 1),
(303, 'Demonology', 256, 0, 1),
(363, 'Marksmanship', 4, 0, 1),
(383, 'Protection', 2, 0, 1),
(399, 'Frost', 32, 0, 1),
(61, 'Frost', 128, 0, 2),
(163, 'Protection', 1, 0, 2),
(183, 'Subtlety', 8, 0, 2),
(203, 'Shadow', 16, 0, 2),
(262, 'Restoration', 64, 0, 2),
(282, 'Restoration', 1024, 0, 2),
(301, 'Destruction', 256, 0, 2),
(362, 'Survival', 4, 0, 2),
(381, 'Retribution', 2, 0, 2),
(400, 'Unholy', 32, 0, 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_talenttab`
--
ALTER TABLE `freedomcore_talenttab`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pos` (`classes`,`pets`,`order`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
