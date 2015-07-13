-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:47
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
-- Структура таблицы `freedomcore_achievementcategory`
--

CREATE TABLE IF NOT EXISTS `freedomcore_achievementcategory` (
  `id` mediumint(11) unsigned NOT NULL,
  `parentAchievement` mediumint(11) NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_achievementcategory`
--

INSERT INTO `freedomcore_achievementcategory` (`id`, `parentAchievement`, `name_loc0`) VALUES
(92, -1, 'General'),
(123, 122, 'Arenas'),
(130, 1, 'Character'),
(135, 128, 'Creatures'),
(140, 130, 'Wealth'),
(152, 21, 'Rated Arenas'),
(160, 155, 'Lunar Festival'),
(165, 95, 'Arena'),
(170, 169, 'Cooking'),
(178, 132, 'Secondary Skills'),
(14777, 97, 'Eastern Kingdoms'),
(14808, 168, 'Classic'),
(14821, 14807, 'Classic'),
(14861, 96, 'Classic'),
(14864, 201, 'Classic'),
(96, -1, 'Quests'),
(124, 122, 'Battlegrounds'),
(136, 128, 'Honorable Kills'),
(141, 1, 'Combat'),
(145, 130, 'Consumables'),
(153, 21, 'Battlegrounds'),
(171, 169, 'Fishing'),
(173, 132, 'Professions'),
(187, 155, 'Love is in the Air'),
(14778, 97, 'Kalimdor'),
(14801, 95, 'Alterac Valley'),
(14805, 168, 'The Burning Crusade'),
(14822, 14807, 'The Burning Crusade'),
(14862, 96, 'The Burning Crusade'),
(14865, 201, 'The Burning Crusade'),
(97, -1, 'Exploration'),
(125, 122, 'Dungeons'),
(128, 1, 'Kills'),
(137, 128, 'Killing Blows'),
(147, 130, 'Reputation'),
(154, 21, 'World'),
(159, 155, 'Noblegarden'),
(172, 169, 'First Aid'),
(14779, 97, 'Outland'),
(14802, 95, 'Arathi Basin'),
(14806, 168, 'Lich King Dungeon'),
(14823, 14807, 'Wrath of the Lich King'),
(14863, 96, 'Wrath of the Lich King'),
(14866, 201, 'Wrath of the Lich King'),
(95, -1, 'Player vs. Player'),
(122, 1, 'Deaths'),
(126, 122, 'World'),
(163, 155, 'Children''s Week'),
(191, 130, 'Gear'),
(14780, 97, 'Northrend'),
(14803, 95, 'Eye of the Storm'),
(14921, 168, 'Lich King Heroic'),
(14963, 14807, 'Secrets of Ulduar'),
(127, 122, 'Resurrection'),
(133, 1, 'Quests'),
(161, 155, 'Midsummer'),
(168, -1, 'Dungeons & Raids'),
(14804, 95, 'Warsong Gulch'),
(14922, 168, 'Lich King 10-Player Raid'),
(15021, 14807, 'Call of the Crusade'),
(162, 155, 'Brewfest'),
(169, -1, 'Professions'),
(14807, 1, 'Dungeons & Raids'),
(14881, 95, 'Strand of the Ancients'),
(14923, 168, 'Lich King 25-Player Raid'),
(15062, 14807, 'Fall of the Lich King'),
(132, 1, 'Skills'),
(158, 155, 'Hallow''s End'),
(201, -1, 'Reputation'),
(14901, 95, 'Wintergrasp'),
(14961, 168, 'Secrets of Ulduar 10-Player Raid'),
(134, 1, 'Travel'),
(155, -1, 'World Events'),
(14962, 168, 'Secrets of Ulduar 25-Player Raid'),
(14981, 155, 'Pilgrim''s Bounty'),
(15003, 95, 'Isle of Conquest'),
(81, -1, 'Feats of Strength'),
(131, 1, 'Social'),
(156, 155, 'Winter Veil'),
(15001, 168, 'Call of the Crusade 10-Player Raid'),
(1, -1, 'Statistics'),
(21, 1, 'Player vs. Player'),
(14941, 155, 'Argent Tournament'),
(15002, 168, 'Call of the Crusade 25-Player Raid'),
(15041, 168, 'Fall of the Lich King 10-Player Raid'),
(15042, 168, 'Fall of the Lich King 25-Player Raid');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_achievementcategory`
--
ALTER TABLE `freedomcore_achievementcategory`
 ADD PRIMARY KEY (`id`), ADD KEY `idx_achievement` (`parentAchievement`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
