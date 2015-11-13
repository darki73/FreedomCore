<?php
require_once('header.php');

$Smarty->assign('Github', Installer::GithubRepoStatus());
$Smarty->assign('InstallerVersion', Installer::InstallerVersion());

$Smarty->assign('StepID', 'Step #2');
$Smarty->display('installation/database');

?>