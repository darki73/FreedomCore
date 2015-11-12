<?php
require_once('header.php');

$UpdateData = Updater::GetUpdateData();
$UpdatesList = Manager::GetUrlData("https://project.freedomcore.ru/freedomcore.php?action=post_install&version=".$UpdateData['updating_to']['id']);
$UpdatesList = json_decode($UpdatesList, true);

$DownloadDirectory = getcwd().DS.'Update'.DS.'data'.DS;
$Count = count($UpdatesList['updates']);
$Iterations = 0;

foreach($UpdatesList['updates'] as $Update)
{
    $LoadedData = Manager::GetUrlData($Update['download']);
    $FileHandler = fopen($DownloadDirectory.str_replace('.freedomcore', '.php', $Update['filename']), 'w');
    fwrite($FileHandler, $LoadedData);
    fclose($FileHandler);
    if($Iterations == $Count - 1)
        header('Location: /Update/complete');

}

?>