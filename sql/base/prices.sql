-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:55
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
-- Структура таблицы `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
`id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 - Service\n2 - Gear\n3 - Mount\n4 - Wallet Top UP',
  `short_code` varchar(45) DEFAULT NULL,
  `price` float DEFAULT NULL COMMENT 'ONLY IN USD!'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `prices`
--

INSERT INTO `prices` (`id`, `type`, `short_code`, `price`) VALUES
(1, 1, 'pct', 20),
(2, 1, 'pfc', 25),
(3, 1, 'prc', 20),
(4, 1, 'pcc', 15),
(5, 1, 'pnc', 8);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `prices`
--
ALTER TABLE `prices`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `prices`
--
ALTER TABLE `prices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
