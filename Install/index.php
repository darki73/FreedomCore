<?php
require_once('header.php');

$Smarty->assign('Github', Installer::GithubRepoStatus());
$Smarty->display('installation/welcome');