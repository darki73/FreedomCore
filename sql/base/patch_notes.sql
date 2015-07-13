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
-- Структура таблицы `patch_notes`
--

CREATE TABLE IF NOT EXISTS `patch_notes` (
`id` int(11) NOT NULL,
  `patch_version` double DEFAULT NULL,
  `patch_name_ru` varchar(45) DEFAULT NULL,
  `patch_name_en` varchar(45) DEFAULT NULL,
  `patch_menu_icon` varchar(45) DEFAULT NULL,
  `patch_content_header` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `patch_notes`
--
ALTER TABLE `patch_notes`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `patch_notes`
--
ALTER TABLE `patch_notes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
