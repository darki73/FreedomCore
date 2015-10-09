<pre>
-- Prevent data corruption
SET NAMES 'utf8';
SET SQL_MODE = '';

<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/SQLGenerator.Class.php');
new SQLGenerator($FCCore['DataDirectory'].$FCCore['English6x'], $FCCore['Locale']);

//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievementcategory` VALUES', SQLGenerator::Unpack("Achievement_Category.dbc", "nisx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievement` VALUES', SQLGenerator::Unpack("Achievement.dbc", "niiissiiiiisxii"));
// Missing Achievement Criteria
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_glyphproperties` VALUES', SQLGenerator::Unpack("GlyphProperties.dbc", "niiix"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_items', SQLGenerator::Unpack("Item.dbc", "niixiiixi")); Almost done
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_char_titles` (id,name_loc0) VALUES', SQLGenerator::Unpack("CharTitles.dbc", "nxsxxx"));
// Missing Item Extended Cost
// Missing Skill Line Ability
// Missing Spell Dispel Type
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellmechanic` VALUES', SQLGenerator::Unpack("SpellMechanic.dbc", "ns"));
// Missing Resistances
// Missing Spell Casting Requirements
//SQLGenerator::PrintSQL('<br />', SQLGenerator::RemovePath(SQLGenerator::Unpack("SpellIcon.dbc", "ns")));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_lock` VALUES', SQLGenerator::Unpack("Lock.dbc", "niiiiixxxiiiiixxxiiiiixxxxxxxxxxx"));
// Changed Structure for Item Display Info
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_itemset` VALUES', SQLGenerator::Unpack("ItemSet.dbc", "nsiiiiiiiiiiiiiiiiiii")); Unfinished

?>