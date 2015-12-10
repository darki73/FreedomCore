<?php
require_once('Classes/Loader.php');

$Patch = '6.2.3';
$Build = 20726;

$Storage = new FileStorage($Patch, $Build);
$TablesManager = new Tables($Patch, $Build);



echo "<pre>";
//if ($handle = opendir($Storage->DBClientFiles)) {
//
//    while (false !== ($entry = readdir($handle))) {
//
//        if ($entry != "." && $entry != "..") {
//            $Data = $Storage->getFileData('DBC', $entry);
//            if($Data == false)
//                continue;
//            $Data->CreateDatabaseStructure()->PopulateDatabase();
//        }
//    }
//
//    closedir($handle);
//}
//$Data = $Storage->getFileData('DBC', 'ItemSetSpell.dbc');
//$Data->CreateDatabaseStructure();


print_r($TablesManager->generateItemSetTable());

echo "</pre>";

?>