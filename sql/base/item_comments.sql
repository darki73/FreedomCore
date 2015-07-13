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
-- Структура таблицы `item_comments`
--

CREATE TABLE IF NOT EXISTS `item_comments` (
`id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `discussion_key` varchar(45) DEFAULT NULL,
  `comment_by` varchar(45) DEFAULT NULL,
  `comment_text` varchar(1000) DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  `reply_to` int(11) DEFAULT NULL,
  `language_code` varchar(5) DEFAULT NULL,
  `votes_up` int(11) DEFAULT NULL,
  `votes_down` int(11) DEFAULT NULL,
  `replied_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `item_comments`
--
ALTER TABLE `item_comments`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `item_comments`
--
ALTER TABLE `item_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
