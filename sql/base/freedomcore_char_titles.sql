-- phpMyAdmin SQL Dump
-- version 4.2.9.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 13 2015 г., 03:48
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
-- Структура таблицы `freedomcore_char_titles`
--

CREATE TABLE IF NOT EXISTS `freedomcore_char_titles` (
  `id` mediumint(11) unsigned NOT NULL,
  `name_loc0` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

--
-- Дамп данных таблицы `freedomcore_char_titles`
--

INSERT INTO `freedomcore_char_titles` (`id`, `name_loc0`) VALUES
(1, 'Private %s'),
(2, 'Corporal %s'),
(3, 'Sergeant %s'),
(4, 'Master Sergeant %s'),
(5, 'Sergeant Major %s'),
(6, 'Knight %s'),
(7, 'Knight-Lieutenant %s'),
(8, 'Knight-Captain %s'),
(9, 'Knight-Champion %s'),
(10, 'Lieutenant Commander %s'),
(11, 'Commander %s'),
(12, 'Marshal %s'),
(13, 'Field Marshal %s'),
(14, 'Grand Marshal %s'),
(15, 'Scout %s'),
(16, 'Grunt %s'),
(17, 'Sergeant %s'),
(18, 'Senior Sergeant %s'),
(19, 'First Sergeant %s'),
(20, 'Stone Guard %s'),
(21, 'Blood Guard %s'),
(22, 'Legionnaire %s'),
(23, 'Centurion %s'),
(24, 'Champion %s'),
(25, 'Lieutenant General %s'),
(26, 'General %s'),
(27, 'Warlord %s'),
(28, 'High Warlord %s'),
(42, 'Gladiator %s'),
(43, 'Duelist %s'),
(44, 'Rival %s'),
(45, 'Challenger %s'),
(46, 'Scarab Lord %s'),
(47, 'Conqueror %s'),
(48, 'Justicar %s'),
(53, '%s, Champion of the Naaru'),
(62, 'Merciless Gladiator %s'),
(63, '%s of the Shattered Sun'),
(64, '%s, Hand of A''dal'),
(71, 'Vengeful Gladiator %s'),
(72, 'Battlemaster %s'),
(74, 'Elder %s'),
(75, 'Flame Warden %s'),
(76, 'Flame Keeper %s'),
(77, '%s the Exalted'),
(78, '%s the Explorer'),
(79, '%s the Diplomat'),
(80, 'Brutal Gladiator %s'),
(81, '%s the Seeker'),
(82, 'Arena Master %s'),
(83, 'Salty %s'),
(84, 'Chef %s'),
(85, '%s the Supreme'),
(86, '%s of the Ten Storms'),
(87, '%s of the Emerald Dream'),
(89, 'Prophet %s'),
(90, '%s the Malefic'),
(91, 'Stalker %s'),
(92, '%s of the Ebon Blade'),
(93, 'Archmage %s'),
(94, 'Warbringer %s'),
(95, 'Assassin %s'),
(96, 'Grand Master Alchemist %s'),
(97, 'Grand Master Blacksmith %s'),
(98, 'Iron Chef %s'),
(99, 'Grand Master Enchanter %s'),
(100, 'Grand Master Engineer %s'),
(101, 'Doctor %s'),
(102, 'Grand Master Angler %s'),
(103, 'Grand Master Herbalist %s'),
(104, 'Grand Master Scribe %s'),
(105, 'Grand Master Jewelcrafter %s'),
(106, 'Grand Master Leatherworker %s'),
(107, 'Grand Master Miner %s'),
(108, 'Grand Master Skinner %s'),
(109, 'Grand Master Tailor %s'),
(110, '%s of Quel''Thalas'),
(111, '%s of Argus'),
(112, '%s of Khaz Modan'),
(113, '%s of Gnomeregan'),
(114, '%s the Lion Hearted'),
(115, '%s, Champion of Elune'),
(116, '%s, Hero of Orgrimmar'),
(117, 'Plainsrunner %s'),
(118, '%s of the Darkspear'),
(119, '%s the Forsaken'),
(120, '%s the Magic Seeker'),
(121, 'Twilight Vanquisher %s'),
(122, '%s, Conqueror of Naxxramas'),
(123, '%s, Hero of Northrend'),
(124, '%s the Hallowed'),
(125, 'Loremaster %s'),
(126, '%s of the Alliance'),
(127, '%s of the Horde'),
(128, '%s the Flawless Victor'),
(129, '%s, Champion of the Frozen Wastes'),
(130, 'Ambassador %s'),
(131, '%s the Argent Champion'),
(132, '%s, Guardian of Cenarius'),
(133, 'Brewmaster %s'),
(134, 'Merrymaker %s'),
(135, '%s the Love Fool'),
(137, 'Matron %s'),
(138, 'Patron %s'),
(139, 'Obsidian Slayer %s'),
(140, '%s of the Nightfall'),
(141, '%s the Immortal'),
(142, '%s the Undying'),
(143, '%s Jenkins'),
(144, 'Bloodsail Admiral %s'),
(145, '%s the Insane'),
(146, '%s of the Exodar'),
(147, '%s of Darnassus'),
(148, '%s of Ironforge'),
(149, '%s of Stormwind'),
(150, '%s of Orgrimmar'),
(151, '%s of Sen''jin'),
(152, '%s of Silvermoon'),
(153, '%s of Thunder Bluff'),
(154, '%s of the Undercity'),
(155, '%s the Noble'),
(156, 'Crusader %s'),
(157, 'Deadly Gladiator %s'),
(158, '%s, Death''s Demise'),
(159, '%s the Celestial Defender'),
(160, '%s, Conqueror of Ulduar'),
(161, '%s, Champion of Ulduar'),
(163, 'Vanquisher %s'),
(164, 'Starcaller %s'),
(165, '%s the Astral Walker'),
(166, '%s, Herald of the Titans'),
(167, 'Furious Gladiator %s'),
(168, '%s the Pilgrim'),
(169, 'Relentless Gladiator %s'),
(170, 'Grand Crusader %s'),
(171, '%s the Argent Defender'),
(172, '%s the Patient'),
(173, '%s the Light of Dawn'),
(174, '%s, Bane of the Fallen King'),
(175, '%s the Kingslayer'),
(176, '%s of the Ashen Verdict'),
(177, 'Wrathful Gladiator %s');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `freedomcore_char_titles`
--
ALTER TABLE `freedomcore_char_titles`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
