<pre>
-- Prevent data corruption
SET NAMES 'utf8';
SET SQL_MODE = '';

<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/SQLGenerator.Class.php');
new SQLGenerator($FCCore['DataDirectory'].$FCCore['English'], $FCCore['Locale']);

SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievementcategory` VALUES', SQLGenerator::Unpack("Achievement_Category.dbc", "nisxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievement` VALUES', SQLGenerator::Unpack("Achievement.dbc", "niiisxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxiiiiisxxxxxxxxxxxxxxxxii"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_achievementcriteria` VALUES', SQLGenerator::Unpack("Achievement_Criteria.dbc", "niiiiiiiisxxxxxxxxxxxxxxxxiixii"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_glyphproperties` VALUES', SQLGenerator::Unpack("GlyphProperties.dbc", "niii"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_items', SQLGenerator::Unpack("Item.dbc", "niixiiii"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_char_titles` (id,name_loc0) VALUES', SQLGenerator::Unpack("CharTitles.dbc", "nxsxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_item_extended_cost` VALUES', SQLGenerator::Unpack("ItemExtendedCost.dbc", "niixiiiiiiiiiiix"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_skill_line_ability` VALUES', SQLGenerator::Unpack("SkillLineAbility.dbc", "xiiiixxixxiixx"));

// Connection RESET FFS !!!!! SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_skill` VALUES', SQLGenerator::Unpack("SkillLine.dbc", "nixsxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"));

SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spelldispeltype` VALUES', SQLGenerator::Unpack("SpellDispelType.dbc", "nsxxxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellmechanic` VALUES', SQLGenerator::Unpack("SpellMechanic.dbc", "nsxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_resistances` VALUES', SQLGenerator::Unpack("Resistances.dbc", "nxxsxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellcasttimes` VALUES', SQLGenerator::Unpack("SpellCastTimes.dbc", "nixx"));
SQLGenerator::PrintSQL('<br />', SQLGenerator::RemovePath(SQLGenerator::Unpack("SpellIcon.dbc", "ns")));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_lock` VALUES', SQLGenerator::Unpack("Lock.dbc", "niiiiixxxiiiiixxxiiiiixxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_icons` VALUES', SQLGenerator::Unpack("ItemDisplayInfo.dbc", "nxxxxsxxxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_itemset` VALUES', SQLGenerator::Unpack("ItemSet.dbc", "nsxxxxxxxxxxxxxxxxiiiiiiiiiixxxxxxxiiiiiiiiiiiiiiiiii"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spell` VALUES', SQLGenerator::Unpack("Spell.dbc", "niiixxxxxxxxxxxxxxxxxxxxxxxxiixxiixiixxiixixxxixxiiiiiiiiiiiiiiiiiiixxxiiiiiixxxiiixxxiiiiiiiiiiiiiiifffiiiiiiiiixxxiiifffxxxxxxxxxxxixxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxixxixxxxixxxfffxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellduration` VALUES', SQLGenerator::Unpack("SpellDuration.dbc", "nixx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellrange` VALUES', SQLGenerator::Unpack("SpellRange.dbc", "nffffxsxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_spellradius` VALUES', SQLGenerator::Unpack("SpellRadius.dbc", "nfxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_itemenchantmet` VALUES', SQLGenerator::Unpack("SpellItemEnchantment.dbc", "nxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxxxxxxxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_gemproperties` VALUES', SQLGenerator::Unpack("GemProperties.dbc", "nixxx"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_talent` VALUES', SQLGenerator::Unpack("Talent.dbc", "niiiiiiiixxxxixxixxxxix"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_talenttab` VALUES', SQLGenerator::Unpack("TalentTab.dbc", "nsxxxxxxxxxxxxxxxxxxiiix"));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_factiontemplate` VALUES', SQLGenerator::Factionize(SQLGenerator::Unpack("FactionTemplate.dbc", "nixiiiiiiiiiii")));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_factions` VALUES', SQLGenerator::GetSide(SQLGenerator::Unpack("Faction.dbc", "nixxxxxxxxxxxxxxxxixxxxsixxxxxxxxxxxxxxxsxxxxxxxxxxxxxxsx")));
SQLGenerator::PrintSQL('<br />INSERT INTO `freedomhead_zones` VALUES', SQLGenerator::MapGenerator(array(array("Map.dbc", "nxixxsxxxxxxxxxxxxxxxxixxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"),array("AreaTable.dbc", "nixxxxxxxxxsxxxxxxxxxxxxxxxxxxxxxxxx"),array("WorldMapArea.dbc", "xiisffffxxx"))));


//SQLGenerator::PrintSQL('<br />INSERT INTO `` VALUES', SQLGenerator::Unpack(".dbc", ""));
?>

<!-- File Format
-- 3.3.5a
    Achievement_Category - nisxxxxxxxxxxxxxxx
    Achievement - niiisxxxxxxxxxxxxxxxxsxxxxxxxxxxxxxxxxiiiiisxxxxxxxxxxxxxxxxii
-->