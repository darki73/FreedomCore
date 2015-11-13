<?php
require_once('header.php');

Updater::PushUpdates();
$Smarty->display('updater/complete');
header('Refresh: 10; url=/Update');
?>