-- phpMyAdmin SQL Dump
-- version 4.4.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 29 2015 г., 12:16
-- Версия сервера: 5.6.24-log
-- Версия PHP: 5.6.9

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
-- Структура таблицы `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(11) NOT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `forum_type` int(11) DEFAULT NULL COMMENT '1 - Support\n2 - Community\n3 - Gaming Process\n4 - PVP\n5 - Classes\n6 - Realms\n7 - Bug Tracker\n',
  `forum_name` varchar(90) DEFAULT NULL,
  `forum_description` varchar(90) DEFAULT NULL,
  `forum_icon` varchar(90) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='	';

--
-- Дамп данных таблицы `forums`
--

INSERT INTO `forums` (`id`, `forum_id`, `forum_type`, `forum_name`, `forum_description`, `forum_icon`) VALUES
(1, 1, 1, 'Forum_Category_Support_Support_Title', 'Forum_Category_Support_Support_Description', 'support.gif'),
(2, 2, 1, 'Forum_Category_Technical_Title', 'Forum_Category_Technical_Description', 'technical_support.gif'),
(3, 3, 1, 'Forum_Category_Localization_Title', 'Forum_Category_Localization_Description', 'localization.gif'),
(4, 4, 2, 'Forum_Category_Community_Roleplay_Title', 'Forum_Category_Community_Roleplay_Description', 'roleplay.gif'),
(5, 5, 2, 'Forum_Category_Community_Common_Title', 'Forum_Category_Community_Common_Description', 'common.gif'),
(6, 6, 2, 'Forum_Category_Community_SearchingPvE_Title', 'Forum_Category_Community_SearchingPvE_Description', 'searchingforgroup.gif'),
(7, 7, 2, 'Forum_Category_Community_SearchingPvP_Title', 'Forum_Category_Community_SearchingPvP_Description', 'searchingforgroup.gif'),
(8, 8, 3, 'Forum_Category_GameProcess_NoobHelp_Title', 'Forum_Category_GameProcess_NoobHelp_Description', 'noobhelp.gif'),
(9, 9, 3, 'Forum_Category_GameProcess_Quests_Title', 'Forum_Category_GameProcess_Quests_Description', 'quests.gif'),
(10, 10, 3, 'Forum_Category_GameProcess_Professions_Title', 'Forum_Category_GameProcess_Professions_Description', 'professions.gif'),
(11, 11, 3, 'Forum_Category_GameProcess_Achievements_Title', 'Forum_Category_GameProcess_Achievements_Description', 'achievements.gif'),
(12, 12, 3, 'Forum_Category_GameProcess_Raids_Title', 'Forum_Category_GameProcess_Raids_Description', 'raids.gif'),
(13, 13, 3, 'Forum_Category_GameProcess_Interface_Title', 'Forum_Category_GameProcess_Interface_Description', 'interface.gif'),
(14, 14, 4, 'Forum_Category_PvP_RBG_Title', 'Forum_Category_PvP_RBG_Description', 'arena.gif'),
(15, 15, 4, 'Forum_Category_PvP_World_Title', 'Forum_Category_PvP_World_Description', 'bgpvp.gif'),
(16, 16, 5, 'Forum_Category_Classes_Warrior_Title', 'Forum_Category_Empty_Description', 'warrior.gif'),
(17, 17, 5, 'Forum_Category_Classes_Priest_Title', 'Forum_Category_Empty_Description', 'priest.gif'),
(18, 18, 5, 'Forum_Category_Classes_Druid_Title', 'Forum_Category_Empty_Description', 'druid.gif'),
(19, 19, 5, 'Forum_Category_Classes_Mage_Title', 'Forum_Category_Empty_Description', 'mage.gif'),
(20, 20, 5, 'Forum_Category_Classes_Monk_Title', 'Forum_Category_Empty_Description', 'monk.png'),
(21, 21, 5, 'Forum_Category_Classes_Hunter_Title', 'Forum_Category_Empty_Description', 'hunter.gif'),
(22, 22, 5, 'Forum_Category_Classes_Paladin_Title', 'Forum_Category_Empty_Description', 'paladin.gif'),
(23, 23, 5, 'Forum_Category_Classes_Rogue_Title', 'Forum_Category_Empty_Description', 'rogue.gif'),
(24, 24, 5, 'Forum_Category_Classes_DK_Title', 'Forum_Category_Empty_Description', 'dk.gif'),
(25, 25, 5, 'Forum_Category_Classes_Warlock_Title', 'Forum_Category_Empty_Description', 'warlock.gif'),
(26, 26, 5, 'Forum_Category_Classes_Shaman_Title', 'Forum_Category_Empty_Description', 'shaman.gif');

-- --------------------------------------------------------

--
-- Структура таблицы `forum_comments`
--

CREATE TABLE IF NOT EXISTS `forum_comments` (
  `id` int(11) NOT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `posted_by` varchar(45) DEFAULT NULL,
  `post_time` int(11) DEFAULT NULL,
  `post_message` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `forum_topics`
--

CREATE TABLE IF NOT EXISTS `forum_topics` (
  `id` int(11) NOT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `posted_by` varchar(45) DEFAULT NULL,
  `topic` varchar(200) DEFAULT NULL,
  `views` int(11) DEFAULT '0',
  `post_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `forums`
--
ALTER TABLE `forums`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forum_topics`
--
ALTER TABLE `forum_topics`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `forums`
--
ALTER TABLE `forums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
