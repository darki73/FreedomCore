-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:56
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
-- Структура таблицы `raceclassrelation`
--

CREATE TABLE IF NOT EXISTS `raceclassrelation` (
`id` int(11) NOT NULL,
  `race` varchar(45) DEFAULT NULL,
  `class_warrior` int(11) DEFAULT NULL,
  `class_paladin` int(11) DEFAULT NULL,
  `class_hunter` int(11) DEFAULT NULL,
  `class_rogue` int(11) DEFAULT NULL,
  `class_priest` int(11) DEFAULT NULL,
  `class_death-knight` int(11) DEFAULT NULL,
  `class_shaman` int(11) DEFAULT NULL,
  `class_mage` int(11) DEFAULT NULL,
  `class_warlock` int(11) DEFAULT NULL,
  `class_druid` int(11) DEFAULT NULL,
  `class_monk` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `raceclassrelation`
--

INSERT INTO `raceclassrelation` (`id`, `race`, `class_warrior`, `class_paladin`, `class_hunter`, `class_rogue`, `class_priest`, `class_death-knight`, `class_shaman`, `class_mage`, `class_warlock`, `class_druid`, `class_monk`) VALUES
(1, 'pandaren', 1, 0, 1, 1, 1, 0, 1, 1, 0, 0, 1),
(2, 'goblin', 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0),
(3, 'worgen', 1, 0, 1, 1, 1, 1, 0, 1, 1, 1, 0),
(4, 'draenei', 1, 1, 1, 0, 1, 1, 1, 1, 0, 0, 1),
(5, 'blood-elf', 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 1),
(6, 'dwarf', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1),
(7, 'orc', 1, 0, 1, 1, 0, 1, 1, 1, 1, 0, 1),
(8, 'gnome', 1, 0, 0, 1, 1, 1, 0, 1, 1, 0, 1),
(9, 'tauren', 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1),
(10, 'human', 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 1),
(11, 'troll', 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(12, 'night-elf', 1, 0, 1, 1, 1, 1, 0, 1, 0, 1, 1),
(13, 'undead', 1, 0, 1, 1, 1, 1, 0, 1, 1, 0, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `raceclassrelation`
--
ALTER TABLE `raceclassrelation`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `raceclassrelation`
--
ALTER TABLE `raceclassrelation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
