<?php
require_once('header.php');

$Smarty->assign('Github', Installer::GithubRepoStatus());
$Smarty->assign('InstallerVersion', Installer::InstallerVersion());
$Smarty->assign('ServerInfo', Installer::ServerInfo());
$Smarty->assign('Modules', Installer::CheckPHPModules());

$Smarty->assign('StepID', 'Step #1');
$Smarty->display('installation/begin');

?>