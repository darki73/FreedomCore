<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/ImageGenerator.Class.php');
new ImageGenerator($FCCore['DataDirectory'], $FCCore['DataDirectory'].$FCCore['English335a'], $FCCore['Locale'], $FCCore['IconsDirectory'], '3.3.5a');
echo "<pre>";
ImageGenerator::Extract("ItemDisplayInfo.dbc", "nxxxxsxxxxxxxxxxxxxxxxxxx");
ImageGenerator::Extract("SpellIcon.dbc", "ns");
echo "</pre>";
?>