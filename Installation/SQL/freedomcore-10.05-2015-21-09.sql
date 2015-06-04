-- phpMyAdmin SQL Dump
-- version 4.4.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 10 2015 г., 18:08
-- Версия сервера: 5.6.24-log
-- Версия PHP: 5.6.7

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
-- Структура таблицы `classabilities`
--

CREATE TABLE IF NOT EXISTS `classabilities` (
  `id` int(11) NOT NULL,
  `class_name` varchar(45) DEFAULT NULL,
  `ability_name` varchar(45) DEFAULT NULL,
  `ability_description` varchar(45) DEFAULT NULL,
  `ability_icon` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COMMENT='			';

--
-- Дамп данных таблицы `classabilities`
--

INSERT INTO `classabilities` (`id`, `class_name`, `ability_name`, `ability_description`, `ability_icon`) VALUES
(1, 'warrior', 'Warrior_First_Ability_Title', 'Warrior_First_Ability_Description', 'ability_defend'),
(2, 'warrior', 'Warrior_Second_Ability_Title', 'Warrior_Second_Ability_Description', 'ability_meleedamage'),
(3, 'warrior', 'Warrior_Third_Ability_Title', 'Warrior_Third_Ability_Description', 'spell_nature_ancestralguardian'),
(4, 'warrior', 'Warrior_Forth_Ability_Title', 'Warrior_Forth_Ability_Description', 'ability_warrior_offensivestance'),
(5, 'paladin', 'Paladin_First_Ability_Title', 'Paladin_First_Ability_Description', 'spell_holy_avengersshield'),
(6, 'paladin', 'Paladin_Second_Ability_Title', 'Paladin_Second_Ability_Description', 'spell_holy_crusaderstrike'),
(7, 'paladin', 'Paladin_Third_Ability_Title', 'Paladin_Third_Ability_Description', 'spell_holy_devotionaura'),
(8, 'paladin', 'Paladin_Forth_Ability_Title', 'Paladin_Forth_Ability_Description', 'spell_holy_layonhands'),
(9, 'hunter', 'Hunter_First_Ability_Title', 'Hunter_First_Ability_Description', 'ability_hunter_beasttaming'),
(10, 'hunter', 'Hunter_Second_Ability_Title', 'Hunter_Second_Ability_Description', 'inv_weapon_bow_02'),
(11, 'hunter', 'Hunter_Third_Ability_Title', 'Hunter_Third_Ability_Description', 'spell_frost_chainsofice'),
(12, 'hunter', 'Hunter_Forth_Ability_Title', 'Hunter_Forth_Ability_Description', 'ability_upgrademoonglaive'),
(13, 'rogue', 'Rogue_First_Ability_Title', 'Rogue_First_Ability_Description', 'ability_stealth'),
(14, 'rogue', 'Rogue_Second_Ability_Title', 'Rogue_Second_Ability_Description', 'inv_weapon_shortblade_15'),
(15, 'rogue', 'Rogue_Third_Ability_Title', 'Rogue_Third_Ability_Description', 'spell_shadow_shadowward'),
(16, 'rogue', 'Rogue_Forth_Ability_Title', 'Rogue_Forth_Ability_Description', 'ability_rogue_eviscerate'),
(17, 'priest', 'Priest_First_Ability_Title', 'Priest_First_Ability_Description', 'spell_holy_greaterheal'),
(18, 'priest', 'Priest_Second_Ability_Title', 'Priest_Second_Ability_Description', 'spell_shadow_shadowform'),
(19, 'priest', 'Priest_Third_Ability_Title', 'Priest_Third_Ability_Description', 'spell_holy_powerwordshield'),
(20, 'priest', 'Priest_Forth_Ability_Title', 'Priest_Forth_Ability_Description', 'spell_shadow_shadowworddominate'),
(21, 'death-knight', 'Death_Knight_First_Ability_Title', 'Death_Knight_First_Ability_Description', 'spell_nature_shamanrage'),
(22, 'death-knight', 'Death_Knight_Second_Ability_Title', 'Death_Knight_Second_Ability_Description', 'spell_deathknight_empowerruneblade'),
(23, 'death-knight', 'Death_Knight_Third_Ability_Title', 'Death_Knight_Third_Ability_Description', 'spell_shadow_raisedead'),
(24, 'death-knight', 'Death_Knight_Forth_Ability_Title', 'Death_Knight_Forth_Ability_Description', 'spell_deathknight_frozenruneweapon'),
(25, 'shaman', 'Shaman_First_Ability_Title', 'Shaman_First_Ability_Description', 'spell_nature_healingwavegreater'),
(26, 'shaman', 'Shaman_Second_Ability_Title', 'Shaman_Second_Ability_Description', 'spell_shaman_unleashweapon_wind'),
(27, 'shaman', 'Shaman_Third_Ability_Title', 'Shaman_Third_Ability_Description', 'spell_nature_lightning'),
(28, 'shaman', 'Shaman_Forth_Ability_Title', 'Shaman_Forth_Ability_Description', 'spell_shaman_dropall_02'),
(29, 'mage', 'Mage_First_Ability_Title', 'Mage_First_Ability_Description', 'spell_fire_flamebolt'),
(30, 'mage', 'Mage_Second_Ability_Title', 'Mage_Second_Ability_Description', 'spell_frost_icestorm'),
(31, 'mage', 'Mage_Third_Ability_Title', 'Mage_Third_Ability_Description', 'spell_nature_polymorph'),
(32, 'mage', 'Mage_Forth_Ability_Title', 'Mage_Forth_Ability_Description', 'spell_arcane_portaldalaran'),
(33, 'warlock', 'Warlock_First_Ability_Title', 'Warlock_First_Ability_Description', 'spell_shadow_summonfelhunter'),
(34, 'warlock', 'Warlock_Second_Ability_Title', 'Warlock_Second_Ability_Description', 'spell_shadow_lifedrain02'),
(35, 'warlock', 'Warlock_Third_Ability_Title', 'Warlock_Third_Ability_Description', 'spell_shadow_shadowbolt'),
(36, 'warlock', 'Warlock_Forth_Ability_Title', 'Warlock_Forth_Ability_Description', 'spell_shadow_twilight'),
(37, 'monk', 'Monk_First_Ability_Title', 'Monk_First_Ability_Description', 'ability_monk_chiwave'),
(38, 'monk', 'Monk_Second_Ability_Title', 'Monk_Second_Ability_Description', 'ability_monk_expelharm'),
(39, 'monk', 'Monk_Third_Ability_Title', 'Monk_Third_Ability_Description', 'ability_monk_standingkick'),
(40, 'monk', 'Monk_Forth_Ability_Title', 'Monk_Forth_Ability_Description', 'ability_monk_clashingoxcharge'),
(41, 'druid', 'Druid_First_Ability_Title', 'Druid_First_Ability_Description', 'ability_druid_mastershapeshifter'),
(42, 'druid', 'Druid_Second_Ability_Title', 'Druid_Second_Ability_Description', 'spell_nature_resistnature'),
(43, 'druid', 'Druid_Third_Ability_Title', 'Druid_Third_Ability_Description', 'ability_druid_demoralizingroar'),
(44, 'druid', 'Druid_Forth_Ability_Title', 'Druid_Forth_Ability_Description', 'inv_misc_monsterclaw_03');

-- --------------------------------------------------------

--
-- Структура таблицы `classes`
--

CREATE TABLE IF NOT EXISTS `classes` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `class_name` varchar(45) DEFAULT NULL,
  `class_description_classes` varchar(45) DEFAULT NULL,
  `can_be_tank` int(11) DEFAULT NULL,
  `can_be_heal` int(11) DEFAULT NULL,
  `can_be_dps` int(11) DEFAULT NULL,
  `melee_damage` int(11) DEFAULT NULL,
  `ranged_physical` int(11) DEFAULT NULL,
  `ranged_arcane` int(11) DEFAULT NULL,
  `class_description_personal_header` varchar(45) DEFAULT NULL,
  `class_description_personal_top` varchar(45) DEFAULT NULL,
  `class_description_personal` varchar(45) DEFAULT NULL,
  `indicator_first_type` varchar(45) DEFAULT NULL,
  `indicator_second_type` varchar(45) DEFAULT NULL,
  `first_specialization_image` varchar(45) DEFAULT NULL,
  `second_specialization_image` varchar(45) DEFAULT NULL,
  `third_specialization_image` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `classes`
--

INSERT INTO `classes` (`id`, `class_id`, `class_name`, `class_description_classes`, `can_be_tank`, `can_be_heal`, `can_be_dps`, `melee_damage`, `ranged_physical`, `ranged_arcane`, `class_description_personal_header`, `class_description_personal_top`, `class_description_personal`, `indicator_first_type`, `indicator_second_type`, `first_specialization_image`, `second_specialization_image`, `third_specialization_image`) VALUES
(1, 1, 'warrior', 'Warrior_Description_Classes', 1, 0, 1, 1, 0, 0, 'Warrior_Description_Personal_Header', 'Warrior_Description_Personal_Top', 'Warrior_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Rage', 'ability_warrior_savageblow', 'ability_warrior_innerrage', 'ability_warrior_defensivestance'),
(2, 2, 'paladin', 'Paladin_Description_Classes', 1, 1, 1, 1, 0, 0, 'Paladin_Description_Personal_Header', 'Paladin_Description_Personal_Top', 'Paladin_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Mana', 'spell_holy_holybolt', 'ability_paladin_shieldofthetemplar', 'spell_holy_auraoflight'),
(3, 3, 'hunter', 'Hunter_Description_Classes', 0, 0, 1, 0, 1, 0, 'Hunter_Description_Personal_Header', 'Hunter_Description_Personal_Top', 'Hunter_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Concentration', 'ability_hunter_bestialdisciplin', 'ability_hunter_focusedaim', 'ability_hunter_camouflage'),
(4, 4, 'rogue', 'Rogue_Description_Classes', 0, 0, 1, 1, 0, 0, 'Rogue_Description_Personal_Header', 'Rogue_Description_Personal_Top', 'Rogue_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Energy', 'ability_rogue_eviscerate', 'ability_backstab', 'ability_stealth'),
(5, 5, 'priest', 'Priest_Description_Classes', 0, 1, 1, 0, 0, 1, 'Priest_Description_Personal_Header', 'Priest_Description_Personal_Top', 'Priest_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Mana', 'spell_holy_powerwordshield', 'spell_holy_guardianspirit', 'spell_shadow_shadowwordpain'),
(6, 6, 'death-knight', 'Death_knight_Description_Classes', 1, 0, 1, 1, 0, 0, 'Death_knight_Description_Personal_Header', 'Death_knight_Description_Personal_Top', 'Death_knight_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Runes', 'spell_deathknight_bloodpresence', 'spell_deathknight_frostpresence', 'spell_deathknight_unholypresence'),
(7, 7, 'shaman', 'Shaman_Description_Classes', 0, 1, 1, 1, 0, 1, 'Shaman_Description_Personal_Header', 'Shaman_Description_Personal_Top', 'Shaman_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Mana', 'spell_nature_lightning', 'spell_nature_lightningshield', 'spell_nature_magicimmunity'),
(8, 8, 'mage', 'Mage_Description_Classes', 0, 0, 1, 0, 0, 1, 'Mage_Description_Personal_Header', 'Mage_Description_Personal_Top', 'Mage_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Mana', 'spell_holy_magicalsentry', 'spell_fire_firebolt02', 'spell_frost_frostbolt02'),
(9, 9, 'warlock', 'Warlock_Description_Classes', 0, 0, 1, 0, 0, 1, 'Warlock_Description_Personal_Header', 'Warlock_Description_Personal_Top', 'Warlock_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Mana', 'spell_shadow_deathcoil', 'spell_shadow_metamorphosis', 'spell_shadow_rainoffire'),
(10, 10, 'monk', 'Monk_Description_Classes', 1, 1, 1, 1, 0, 0, 'Monk_Description_Personal_Header', 'Monk_Description_Personal_Top', 'Monk_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Monk', 'spell_monk_brewmaster_spec', 'spell_monk_mistweaver_spec', 'spell_monk_windwalker_spec'),
(11, 11, 'druid', 'Druid_Description_Classes', 1, 1, 1, 1, 0, 1, 'Druid_Description_Personal_Header', 'Druid_Description_Personal_Top', 'Druid_Description_Personal', 'Class_Indicator_Health', 'Class_Indicator_Druid', 'spell_nature_starfall', 'ability_druid_catform', 'ability_racial_bearform');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `patch_notes`
--

INSERT INTO `patch_notes` (`id`, `patch_version`, `patch_name_ru`, `patch_name_en`, `patch_menu_icon`, `patch_content_header`) VALUES
(1, 6, 'Warlords of Draenor', 'Warlords of Draenor', '2SRZ0EXYD2JG1413225800109.jpg', 'Y8Y08I5RIA1X1412878335390.jpg'),
(2, 6.1, 'Изменения Гарнизона', 'Garrison Changes', 'SJT32YA9VO3P1424887227438.jpg', 'E5RBK25967ZM1423181836865.jpg');

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

-- --------------------------------------------------------

--
-- Структура таблицы `races`
--

CREATE TABLE IF NOT EXISTS `races` (
  `id` int(11) NOT NULL,
  `race_id` int(11) DEFAULT NULL,
  `race` varchar(45) DEFAULT NULL,
  `can_join_alliance` int(11) DEFAULT NULL,
  `can_join_horde` int(11) DEFAULT NULL,
  `race_head_description` varchar(45) DEFAULT NULL,
  `race_top_description` varchar(45) DEFAULT NULL,
  `race_bottom_description` varchar(45) DEFAULT NULL,
  `start_location_title` varchar(45) DEFAULT NULL,
  `start_location_description` varchar(45) DEFAULT NULL,
  `capital_title` varchar(45) DEFAULT NULL,
  `capital_description` varchar(45) DEFAULT NULL,
  `mount_title` varchar(45) DEFAULT NULL,
  `mount_description` varchar(45) DEFAULT NULL,
  `leader_title` varchar(45) DEFAULT NULL,
  `leader_description` varchar(45) DEFAULT NULL,
  `racial_ability_one_title` varchar(45) DEFAULT NULL,
  `racial_ability_one_desc` varchar(45) DEFAULT NULL,
  `racial_ability_one_image` varchar(45) DEFAULT NULL,
  `racial_ability_two_title` varchar(45) DEFAULT NULL,
  `racial_ability_two_desc` varchar(45) DEFAULT NULL,
  `racial_ability_two_image` varchar(45) DEFAULT NULL,
  `racial_ability_three_title` varchar(45) DEFAULT NULL,
  `racial_ability_three_desc` varchar(45) DEFAULT NULL,
  `racial_ability_three_image` varchar(45) DEFAULT NULL,
  `racial_ability_four_title` varchar(45) DEFAULT NULL,
  `racial_ability_four_desc` varchar(45) DEFAULT NULL,
  `racial_ability_four_image` varchar(45) DEFAULT NULL,
  `racial_ability_five_title` varchar(45) DEFAULT NULL,
  `racial_ability_five_desc` varchar(45) DEFAULT NULL,
  `racial_ability_five_image` varchar(45) DEFAULT NULL,
  `racial_ability_six_title` varchar(45) DEFAULT NULL,
  `racial_ability_six_desc` varchar(45) DEFAULT NULL,
  `racial_ability_six_image` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `races`
--

INSERT INTO `races` (`id`, `race_id`, `race`, `can_join_alliance`, `can_join_horde`, `race_head_description`, `race_top_description`, `race_bottom_description`, `start_location_title`, `start_location_description`, `capital_title`, `capital_description`, `mount_title`, `mount_description`, `leader_title`, `leader_description`, `racial_ability_one_title`, `racial_ability_one_desc`, `racial_ability_one_image`, `racial_ability_two_title`, `racial_ability_two_desc`, `racial_ability_two_image`, `racial_ability_three_title`, `racial_ability_three_desc`, `racial_ability_three_image`, `racial_ability_four_title`, `racial_ability_four_desc`, `racial_ability_four_image`, `racial_ability_five_title`, `racial_ability_five_desc`, `racial_ability_five_image`, `racial_ability_six_title`, `racial_ability_six_desc`, `racial_ability_six_image`) VALUES
(1, 1, 'human', 1, 0, 'Human_Head_Description', 'Human_Top_Description', 'Human_Bottom_Description', 'Human_Start_Location_Title', 'Human_Start_Location_Desc', 'Human_Capital_Title', 'Human_Capital_Desc', 'Human_Mount_Title', 'Human_Mount_Desc', 'Human_Leader_Title', 'Human_Leader_Desc', 'Human_Racial_One_Title', 'Human_Racial_One_Desc', 'spell_shadow_charm', 'Human_Racial_Two_Title', 'Human_Racial_Two_Desc', 'inv_misc_note_02', 'Human_Racial_Three_Title', 'Human_Racial_Three_Desc', 'inv_enchant_shardbrilliantsmall', '', '', '', '', '', '', '', '', ''),
(2, 2, 'orc', 0, 1, 'Orc_Head_Description', 'Orc_Top_Description', 'Orc_Bottom_Description', 'Orc_Start_Location_Title', 'Orc_Start_Location_Desc', 'Orc_Capital_Title', 'Orc_Capital_Desc', 'Orc_Mount_Title', 'Orc_Mount_Desc', 'Orc_Leader_Title', 'Orc_Leader_Desc', 'Orc_Racial_One_Title', 'Orc_Racial_One_Desc', 'racial_orc_berserkerstrength', 'Orc_Racial_Two_Title', 'Orc_Racial_Two_Desc', 'inv_helmet_23', 'Orc_Racial_Three_Title', 'Orc_Racial_Three_Desc', 'ability_warrior_warcry', '', '', '', '', '', '', '', '', ''),
(3, 3, 'dwarf', 1, 0, 'Dwarf_Head_Description', 'Dwarf_Top_Description', 'Dwarf_Bottom_Description', 'Dwarf_Start_Location_Title', 'Dwarf_Start_Location_Desc', 'Dwarf_Capital_Title', 'Dwarf_Capital_Desc', 'Dwarf_Mount_Title', 'Dwarf_Mount_Desc', 'Dwarf_Leader_Title', 'Dwarf_Leader_Desc', 'Dwarf_Racial_One_Title', 'Dwarf_Racial_One_Desc', 'spell_shadow_unholystrength', 'Dwarf_Racial_Two_Title', 'Dwarf_Racial_Two_Desc', 'spell_frost_wizardmark', 'Dwarf_Racial_Three_Title', 'Dwarf_Racial_Three_Desc', 'inv_misc_map08', 'Dwarf_Racial_Four_Title', 'Dwarf_Racial_Four_Desc', 'inv_hammer_05', '', '', '', '', '', ''),
(4, 4, 'night-elf', 1, 0, 'NightElf_Head_Description', 'NightElf_Top_Description', 'NightElf_Bottom_Description', 'NightElf_Start_Location_Title', 'NightElf_Start_Location_Desc', 'NightElf_Capital_Title', 'NightElf_Capital_Desc', 'NightElf_Mount_Title', 'NightElf_Mount_Desc', 'NightElf_Leader_Title', 'NightElf_Leader_Desc', 'NightElf_Racial_One_Title', 'NightElf_Racial_One_Desc', 'ability_ambush', 'NightElf_Racial_Two_Title', 'NightElf_Racial_Two_Desc', 'spell_nature_wispsplode', 'NightElf_Racial_Three_Title', 'NightElf_Racial_Three_Desc', 'spell_nature_spiritarmor', 'NightElf_Racial_Four_Title', 'NightElf_Racial_Four_Desc', 'ability_racial_shadowmeld', 'NightElf_Racial_Five_Title', 'NightElf_Racial_Five_Desc', 'spell_holy_elunesgrace', '', '', ''),
(5, 5, 'undead', 0, 1, 'Undead_Head_Description', 'Undead_Top_Description', 'Undead_Bottom_Description', 'Undead_Start_Location_Title', 'Undead_Start_Location_Desc', 'Undead_Capital_Title', 'Undead_Capital_Desc', 'Undead_Mount_Title', 'Undead_Mount_Desc', 'Undead_Leader_Title', 'Undead_Leader_Desc', 'Undead_Racial_One_Title', 'Undead_Racial_One_Desc', 'spell_shadow_raisedead', 'Undead_Racial_Two_Title', 'Undead_Racial_Two_Desc', 'spell_shadow_detectinvisibility', 'Undead_Racial_Three_Title', 'Undead_Racial_Three_Desc', 'ability_racial_cannibalize', 'Undead_Racial_Four_Title', 'Undead_Racial_Four_Desc', 'spell_shadow_demonbreath', 'Undead_Racial_Five_Title', 'Undead_Racial_Five_Desc', 'spell_shadow_fingerofdeath', '', '', ''),
(6, 6, 'tauren', 0, 1, 'Tauren_Head_Description', 'Tauren_Top_Description', 'Tauren_Bottom_Description', 'Tauren_Start_Location_Title', 'Tauren_Start_Location_Desc', 'Tauren_Capital_Title', 'Tauren_Capital_Desc', 'Tauren_Mount_Title', 'Tauren_Mount_Desc', 'Tauren_Leader_Title', 'Tauren_Leader_Desc', 'Tauren_Racial_One_Title', 'Tauren_Racial_One_Desc', 'ability_warstomp', 'Tauren_Racial_Two_Title', 'Tauren_Racial_Two_Desc', 'spell_nature_spiritarmor', 'Tauren_Racial_Three_Title', 'Tauren_Racial_Three_Desc', 'spell_nature_unyeildingstamina', 'Tauren_Racial_Four_Title', 'Tauren_Racial_Four_Desc', 'inv_misc_flower_01', 'Tauren_Racial_Five_Title', 'Tauren_Racial_Five_Desc', 'inv_misc_head_tauren_01', '', '', ''),
(7, 7, 'gnome', 1, 0, 'Gnome_Head_Description', 'Gnome_Top_Description', 'Gnome_Bottom_Description', 'Gnome_Start_Location_Title', 'Gnome_Start_Location_Desc', 'Gnome_Capital_Title', 'Gnome_Capital_Desc', 'Gnome_Mount_Title', 'Gnome_Mount_Desc', 'Gnome_Leader_Title', 'Gnome_Leader_Desc', 'Gnome_Racial_One_Title', 'Gnome_Racial_One_Desc', 'ability_rogue_trip', 'Gnome_Racial_Two_Title', 'Gnome_Racial_Two_Desc', 'spell_nature_wispsplode', 'Gnome_Racial_Three_Title', 'Gnome_Racial_Three_Desc', 'inv_enchant_essenceeternallarge', 'Gnome_Racial_Four_Title', 'Gnome_Racial_Four_Desc', 'inv_misc_gear_01', 'Gnome_Racial_Five_Title', 'Gnome_Racial_Five_Desc', 'inv_weapon_shortblade_05', '', '', ''),
(8, 8, 'troll', 0, 1, 'Troll_Head_Description', 'Troll_Top_Description', 'Troll_Bottom_Description', 'Troll_Start_Location_Title', 'Troll_Start_Location_Desc', 'Troll_Capital_Title', 'Troll_Capital_Desc', 'Troll_Mount_Title', 'Troll_Mount_Desc', 'Troll_Leader_Title', 'Troll_Leader_Desc', 'Troll_Racial_One_Title', 'Troll_Racial_One_Desc', 'racial_troll_berserk', 'Troll_Racial_Two_Title', 'Troll_Racial_Two_Desc', 'inv_misc_idol_02', 'Troll_Racial_Three_Title', 'Troll_Racial_Three_Desc', 'spell_nature_regenerate', 'Troll_Racial_Four_Title', 'Troll_Racial_Four_Desc', 'inv_weapon_bow_12', 'Troll_Racial_Five_Title', 'Troll_Racial_Five_Desc', 'inv_misc_pelt_bear_ruin_02', '', '', ''),
(9, 9, 'goblin', 0, 1, 'Goblin_Head_Description', 'Goblin_Top_Description', 'Goblin_Bottom_Description', 'Goblin_Start_Location_Title', 'Goblin_Start_Location_Desc', 'Goblin_Capital_Title', 'Goblin_Capital_Desc', 'Goblin_Mount_Title', 'Goblin_Mount_Desc', 'Goblin_Leader_Title', 'Goblin_Leader_Desc', 'Goblin_Racial_One_Title', 'Goblin_Racial_One_Desc', 'ability_racial_rocketjump', 'Goblin_Racial_Two_Title', 'Goblin_Racial_Two_Desc', 'inv_gizmo_rocketlauncher', 'Goblin_Racial_Three_Title', 'Goblin_Racial_Three_Desc', 'ability_racial_packhobgoblin', 'Goblin_Racial_Four_Title', 'Goblin_Racial_Four_Desc', 'ability_racial_bestdealsanywhere', 'Goblin_Racial_Five_Title', 'Goblin_Racial_Five_Desc', 'ability_racial_timeismoney', 'Goblin_Racial_Six_Title', 'Goblin_Racial_Six_Desc', 'ability_racial_betterlivingthroughchemistry'),
(10, 10, 'blood-elf', 0, 1, 'BloodElf_Head_Description', 'BloodElf_Top_Description', 'BloodElf_Bottom_Description', 'BloodElf_Start_Location_Title', 'BloodElf_Start_Location_Desc', 'BloodElf_Capital_Title', 'BloodElf_Capital_Desc', 'BloodElf_Mount_Title', 'BloodElf_Mount_Desc', 'BloodElf_Leader_Title', 'BloodElf_Leader_Desc', 'BloodElf_Racial_One_Title', 'BloodElf_Racial_One_Desc', 'spell_arcane_studentofmagic', 'BloodElf_Racial_Two_Title', 'BloodElf_Racial_Two_Desc', 'spell_shadow_teleport', 'BloodElf_Racial_Three_Title', 'BloodElf_Racial_Three_Desc', 'inv_enchant_shardglimmeringlarge', 'BloodElf_Racial_Four_Title', 'BloodElf_Racial_Four_Desc', 'spell_shadow_antimagicshell', '', '', '', '', '', ''),
(11, 11, 'draenei', 1, 0, 'Draenei_Head_Description', 'Draenei_Top_Description', 'Draenei_Bottom_Description', 'Draenei_Start_Location_Title', 'Draenei_Start_Location_Desc', 'Draenei_Capital_Title', 'Draenei_Capital_Desc', 'Draenei_Mount_Title', 'Draenei_Mount_Desc', 'Draenei_Leader_Title', 'Draenei_Leader_Desc', 'Draenei_Racial_One_Title', 'Draenei_Racial_One_Desc', 'spell_holy_holyprotection', 'Draenei_Racial_Two_Title', 'Draenei_Racial_Two_Desc', 'spell_shadow_detectinvisibility', 'Draenei_Racial_Three_Title', 'Draenei_Racial_Three_Desc', 'inv_helmet_21', 'Draenei_Racial_Four_Title', 'Draenei_Racial_Four_Desc', 'spell_misc_conjuremanajewel', '', '', '', '', '', ''),
(12, 22, 'worgen', 1, 0, 'Worgen_Head_Description', 'Worgen_Top_Description', 'Worgen_Bottom_Description', 'Worgen_Start_Location_Title', 'Worgen_Start_Location_Desc', 'Worgen_Capital_Title', 'Worgen_Capital_Desc', 'Worgen_Mount_Title', 'Worgen_Mount_Desc', 'Worgen_Leader_Title', 'Worgen_Leader_Desc', 'Worgen_Racial_One_Title', 'Worgen_Racial_One_Desc', 'ability_mount_blackdirewolf', 'Worgen_Racial_Two_Title', 'Worgen_Racial_Two_Desc', 'spell_shadow_antishadow', 'Worgen_Racial_Three_Title', 'Worgen_Racial_Three_Desc', 'ability_druid_dash', 'Worgen_Racial_Four_Title', 'Worgen_Racial_Four_Desc', 'ability_mount_whitedirewolf', 'Worgen_Racial_Five_Title', 'Worgen_Racial_Five_Desc', 'ability_druid_rake', 'Worgen_Racial_Six_Title', 'Worgen_Racial_Six_Desc', 'ability_hunter_pet_wolf'),
(13, 24, 'pandaren', 1, 1, 'Pandaren_Head_Description', 'Pandaren_Top_Description', 'Pandaren_Bottom_Description', 'Pandaren_Start_Location_Title', 'Pandaren_Start_Location_Desc', 'Pandaren_Capital_Title', 'Pandaren_Capital_Desc', 'Pandaren_Mount_Title', 'Pandaren_Mount_Desc', 'Pandaren_Leader_Title', 'Pandaren_Leader_Desc', 'Pandaren_Racial_One_Title', 'Pandaren_Racial_One_Desc', 'class_monk', 'Pandaren_Racial_Two_Title', 'Pandaren_Racial_Two_Desc', 'ability_racial_epicurean', 'Pandaren_Racial_Three_Title', 'Pandaren_Racial_Three_Desc', 'ability_racial_gourmand', 'Pandaren_Racial_Four_Title', 'Pandaren_Racial_Four_Desc', 'ability_racial_innerpeace', 'Pandaren_Racial_Five_Title', 'Pandaren_Racial_Five_Desc', 'ability_racial_bouncy', 'Pandaren_Racial_Six_Title', 'Pandaren_Racial_Six_Desc', 'ability_racial_quiveringpain');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `classabilities`
--
ALTER TABLE `classabilities`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `factions`
--
ALTER TABLE `factions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `patch_notes`
--
ALTER TABLE `patch_notes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `raceclassrelation`
--
ALTER TABLE `raceclassrelation`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `classabilities`
--
ALTER TABLE `classabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT для таблицы `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT для таблицы `factions`
--
ALTER TABLE `factions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT для таблицы `patch_notes`
--
ALTER TABLE `patch_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `raceclassrelation`
--
ALTER TABLE `raceclassrelation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `races`
--
ALTER TABLE `races`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
