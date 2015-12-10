<?php
ini_set("memory_limit", "2500M");
set_time_limit(3000);

require_once('Configuration.php');
require_once('Database.Class.php');

require_once('Storage/FileStorage.FreedomCore.php');
require_once('Storage/FileStructure.FreedomCore.php');
require_once('Storage/SQLBuilder.FreedomCore.php');
require_once('Storage/Tables.FreedomCore.php');

require_once('Readers/DBCReader.FreedomCore.php');
require_once('Readers/DB2Reader.FreedomCore.php');

?>