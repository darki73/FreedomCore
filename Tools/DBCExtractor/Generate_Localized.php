<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/SQLGenerator.Class.php');
new SQLGenerator($FCCore['DataDirectory'].$FCCore['Localized'], $FCCore['Locale']);
$L = $FCCore['Locale'];
?>

<pre>

-- Prevent data corruption
SET NAMES 'utf8';
SET SQL_MODE = '';
ALTER TABLE freedomhead_achievement
	ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`,
	ADD COLUMN `description_loc<?php echo $L; ?>` varchar(255) AFTER `description_loc0`,
	ADD COLUMN `reward_loc<?php echo $L; ?>` varchar(255) AFTER `reward_loc0`;
ALTER TABLE freedomhead_achievementcriteria
	ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) AFTER `name_loc0`;
ALTER TABLE freedomhead_achievementcategory
	ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) AFTER `name_loc0`;
ALTER TABLE freedomhead_char_titles ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_skill ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_spelldispeltype ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_spellmechanic ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_resistances ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_itemset ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_spellrange ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_zones ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`;
ALTER TABLE freedomhead_factions
	ADD COLUMN `name_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `name_loc0`,
	ADD COLUMN `description1_loc<?php echo $L; ?>` text AFTER `description1_loc0`,
	ADD COLUMN `description2_loc<?php echo $L; ?>` text AFTER `description2_loc0`;
ALTER TABLE freedomhead_itemenchantmet ADD COLUMN `text_loc<?php echo $L; ?>` text NOT NULL AFTER `text_loc0`;
ALTER TABLE freedomhead_spell
	ADD COLUMN `spellname_loc<?php echo $L; ?>` varchar(255) NOT NULL AFTER `spellname_loc0`,
	ADD COLUMN `rank_loc<?php echo $L; ?>` text NOT NULL AFTER `rank_loc0`,
	ADD COLUMN `tooltip_loc<?php echo $L; ?>` text NOT NULL AFTER `tooltip_loc0`,
	ADD COLUMN `buff_loc<?php echo $L; ?>` text NOT NULL AFTER `buff_loc0`;
ALTER TABLE freedomhead_talenttab ADD COLUMN `name_loc<?php echo $L; ?>` varchar(32) NOT NULL AFTER `name_loc0`;


<?php

SQLGenerator::PrintUpdate('', SQLGenerator::Unpack("Achievement_Category.dbc", SQLGenerator::Localize("nxSxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_achievement', SQLGenerator::Unpack("Achievement.dbc", SQLGenerator::Localize("nxxxSxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L", 2=>"description_loc$L", 3=>"reward_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_achievementcriteria', SQLGenerator::Unpack("Achievement_Criteria.dbc", SQLGenerator::Localize("nxxxxxxxxSxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_char_titles', SQLGenerator::Unpack("CharTitles.dbc", SQLGenerator::Localize("nxSxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_skill', SQLGenerator::Unpack("SkillLine.dbc", SQLGenerator::Localize("nxxSxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"skillID"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_spelldispeltype', SQLGenerator::Unpack("SpellDispelType.dbc", SQLGenerator::Localize("nSxxxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_spellmechanic', SQLGenerator::Unpack("SpellMechanic.dbc", SQLGenerator::Localize("nSxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_resistances', SQLGenerator::Unpack("Resistances.dbc", SQLGenerator::Localize("nxxSxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_itemset', SQLGenerator::Unpack("ItemSet.dbc", SQLGenerator::Localize("nSxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"itemsetID"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_spellrange', SQLGenerator::Unpack("SpellRange.dbc", SQLGenerator::Localize("nxxxxxSxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"rangeID"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_zones', SQLGenerator::Unpack("AreaTable.dbc", SQLGenerator::Localize("nxxxxxxxxxxSxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"areatableID"), array(1=>"name_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_factions', SQLGenerator::Unpack("Faction.dbc", SQLGenerator::Localize("nxxxxxxxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxx"), true), array(0=>"factionID"), array(1=>"name_loc$L", 2=>"description1_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_itemenchantmet', SQLGenerator::Unpack("SpellItemEnchantment.dbc", SQLGenerator::Localize("nxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"itemenchantmetID"), array(1=>"text_loc$L"));
// HTTP SERVER ERROR!!!!!!!! SQLGenerator::PrintUpdate('freedomhead_spell', SQLGenerator::Unpack("Spell.dbc", SQLGenerator::Localize("nxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxSxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"spellID"), array(1=>"spellname_loc$L", 2=>"rank_loc$L", 3=>"tooltip_loc$L", 4=>"buff_loc$L"));
SQLGenerator::PrintUpdate('freedomhead_talenttab', SQLGenerator::Unpack("TalentTab.dbc", SQLGenerator::Localize("nSxxxxxxxxxxxxxxxxxxxxxx"), true), array(0=>"id"), array(1=>"name_loc$L"));

//SQLGenerator::PrintUpdate('', SQLGenerator::Unpack(".dbc", SQLGenerator::Localize(""), true), );

?>