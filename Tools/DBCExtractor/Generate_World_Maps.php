<?php
set_time_limit(300);
ini_set("memory_limit", "2500M");
require_once('Configuration.php');
require_once('Classes/MapsGenerator.Class.php');
new MapsGenerator($FCCore['DataDirectory'], $FCCore['DataDirectory'].$FCCore['English'], $FCCore['Locale']);
echo "<pre>";
MapsGenerator::GenerateWorld();
echo "</pre>";
?>