
-- SkillLine.dbc
DROP TABLE IF EXISTS `freedomhead_skill`;
CREATE TABLE `freedomhead_skill` (
  `skillID` mediumint(11) unsigned NOT NULL,
  `categoryID` mediumint(11) NOT NULL,
  `name_loc0` varchar(255) NOT NULL,
  PRIMARY KEY  (`skillID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

<?php
  $dbc = dbc2array_("SkillLine.dbc", "nixsxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");
  print_insert('INSERT INTO `freedomhead_skill` VALUES', $dbc);
?>
