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


?>