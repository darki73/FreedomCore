<?php
require_once('header.php');

$Smarty->assign('UpdateData', Updater::GetUpdateData());
$Smarty->display('updater/welcome');

?>