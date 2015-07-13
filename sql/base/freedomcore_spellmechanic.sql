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
-- Структура таблицы `freedomcore_spellmechanic`
--

CREATE TABLE IF NOT EXISTS `freedomcore_spellmechanic` (
  `id` mediumint(11) unsigned NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_spellmechanic`
--

INSERT INTO `freedomcore_spellmechanic` (`id`, `name_loc0`) VALUES
(1, 'charmed'),
(2, 'disoriented'),
(3, 'disarmed'),
(4, 'distracted'),
(5, 'fleeing'),
(6, 'gripped'),
(7, 'rooted'),
(8, 'slowed'),
(9, 'silenced'),
(10, 'asleep'),
(11, 'snared'),
(12, 'stunned'),
(13, 'frozen'),
(14, 'incapacitated'),
(15, 'bleeding'),
(16, 'healing'),
(17, 'polymorphed'),
(18, 'banished'),
(19, 'shielded'),
(20, 'shackled'),
(21, 'mounted'),
(22, 'infected'),
(23, 'turned'),
(24, 'horrified'),
(25, 'invulnerable'),
(26, 'interrupted'),
(27, 'dazed'),
(28, 'discovery'),
(29, 'invulnerable'),
(30, 'sapped'),
(31, 'enraged');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_spellmechanic`
--
ALTER TABLE `freedomcore_spellmechanic`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
