<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/ImageGenerator.Class.php');
new ImageGenerator($FCCore['DataDirectory'], $FCCore['DataDirectory'].$FCCore['English6x'], $FCCore['Locale'], $FCCore['IconsDirectory'], '6.x');
echo "<pre>";
ImageGenerator::Extract("ItemDisplayInfo.dbc", "nxxxxsxxxxxxxxxxxxxxxxx");
ImageGenerator::Extract("SpellIcon.dbc", "ns");
echo "</pre>";
?>