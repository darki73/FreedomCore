<pre>
-- Prevent data corruption
SET NAMES 'utf8';
SET SQL_MODE = '';

<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/SQLGenerator.Class.php');
new SQLGenerator($FCCore['DataDirectory'].$FCCore['English548'], $FCCore['Locale']);

//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievementcategory` VALUES', SQLGenerator::Unpack("Achievement_Category.dbc", "nisx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievement` VALUES', SQLGenerator::Unpack("Achievement.dbc", "niiissiiiiisiix"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievementcriteria` VALUES', SQLGenerator::Unpack("Achievement_Criteria.dbc", "niiiiiiiisxxxxxxxxxxxxxxxxiixii"));  --- Unknown fields | Not Working !!!!!
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_glyphproperties` VALUES', SQLGenerator::Unpack("GlyphProperties.dbc", "niii"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_items', SQLGenerator::Unpack("Item.db2", "niixiiii")); --- DB2 File | Not Working !!!!!
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_char_titles` (id,name_loc0) VALUES', SQLGenerator::Unpack("CharTitles.dbc", "nxsxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_item_extended_cost` VALUES', SQLGenerator::Unpack("ItemExtendedCost.dbc", "niixiiiiiiiiiiix")); --- DB2 File | Not Working !!!!!
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_skill_line_ability` VALUES', SQLGenerator::Unpack("SkillLineAbility.dbc", "xiiiixxixxiixx"));  --- Unknown fields | Not Working !!!!!

// Connection RESET FFS !!!!! SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_skill` VALUES', SQLGenerator::Unpack("SkillLine.dbc", "nixsxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"));

//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spelldispeltype` VALUES', SQLGenerator::Unpack("SpellDispelType.dbc", "nsxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellmechanic` VALUES', SQLGenerator::Unpack("SpellMechanic.dbc", "ns"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_resistances` VALUES', SQLGenerator::Unpack("Resistances.dbc", "nxxs"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellcasttimes` VALUES', SQLGenerator::Unpack("SpellCastTimes.dbc", "nixx"));
//SQLGenerator::PrintSQL('<br />', SQLGenerator::RemovePath(SQLGenerator::Unpack("SpellIcon.dbc", "ns")));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_lock` VALUES', SQLGenerator::Unpack("Lock.dbc", "niiiiixxxiiiiixxxiiiiixxxxxxxxxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_icons` VALUES', SQLGenerator::Unpack("ItemDisplayInfo.dbc", "nxxxxsxxxxxxxxxxxxxxxxxxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_itemset` VALUES', SQLGenerator::Unpack("ItemSet.dbc", "nsiiiiiiiiiixxxxxxxiiiiiiiiiiiiiiiiii"));

//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spell` VALUES', SQLGenerator::Unpack("Spell.dbc", "niiixxxxxxxxxxxxxxxxxxxxxxxxiixxiixiixxiixixxxixxiiiiiiiiiiiiiiiiiiixxxiiiiiixxxiiixxxiiiiiiiiiiiiiiifffiiiiiiiiixxxiiifffxxxxxxxxxxxixxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxixxixxxxixxxfffxxxxxxxxxxxxxxx")); -- NOT YET PARSED!!!!
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellduration` VALUES', SQLGenerator::Unpack("SpellDuration.dbc", "nixx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellrange` VALUES', SQLGenerator::Unpack("SpellRange.dbc", "nffffxsx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellradius` VALUES', SQLGenerator::Unpack("SpellRadius.dbc", "nfxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_itemenchantmet` VALUES', SQLGenerator::Unpack("SpellItemEnchantment.dbc", "nxxxxxxxxxxsxxxxxxxxxxxxxx"));
//SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_gemproperties` VALUES', SQLGenerator::Unpack("GemProperties.dbc", "nixxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_talent` VALUES', SQLGenerator::Unpack("Talent.dbc", "niiiiiiiixxxxixxixxxxix"));


?>