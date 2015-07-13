-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:43
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
-- Структура таблицы `factions`
--

CREATE TABLE IF NOT EXISTS `factions` (
`id` int(11) NOT NULL,
  `side` int(11) DEFAULT NULL COMMENT '0 - For Horde, 1 - For Alliance',
  `race_name` varchar(45) DEFAULT NULL,
  `race_description` varchar(45) DEFAULT NULL,
  `race_link` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `factions`
--

INSERT INTO `factions` (`id`, `side`, `race_name`, `race_description`, `race_link`) VALUES
(1, 0, 'Race_Pandaren', 'Race_Pandaren_Desc', 'pandaren'),
(2, 0, 'Race_Goblin', 'Race_Goblin_Desc', 'goblin'),
(3, 0, 'Race_Blood_Elf', 'Race_Blood_Elf_Desc', 'blood-elf'),
(4, 0, 'Race_Orc', 'Race_Orc_Desc', 'orc'),
(5, 0, 'Race_Tauren', 'Race_Tauren_Desc', 'tauren'),
(6, 0, 'Race_Troll', 'Race_Troll_Desc', 'troll'),
(7, 0, 'Race_Undead', 'Race_Undead_Desc', 'undead'),
(8, 1, 'Race_Pandaren', 'Race_Pandaren_Desc', 'pandaren'),
(9, 1, 'Race_Worgen', 'Race_Worgen_Desc', 'worgen'),
(10, 1, 'Race_Draenei', 'Race_Draenei_Desc', 'draenei'),
(11, 1, 'Race_Dwarf', 'Race_Dwarf_Desc', 'dwarf'),
(12, 1, 'Race_Gnome', 'Race_Gnome_Desc', 'gnome'),
(13, 1, 'Race_Human', 'Race_Human_Desc', 'human'),
(14, 1, 'Race_Night_Elf', 'Race_Night_Elf_Desc', 'night-elf');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `factions`
--
ALTER TABLE `factions`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `factions`
--
ALTER TABLE `factions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
