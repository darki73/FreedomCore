<?php
require_once('header.php');

if(isset($_ENV['installation_in_progress'])){
    $Input = $_REQUEST;
    $ConfigGlobal = '$FCCore';
    $Data = Installer::ProcessInput($Input);
    Installer::CreateConfigurationFile($Data, $ConfigGlobal);
    putenv('installation_in_progress');
    header("Refresh:0; url=/Install/complete?true");
}

$Smarty->assign('Github', Installer::GithubRepoStatus());
$Smarty->assign('InstallerVersion', Installer::InstallerVersion());
$Smarty->assign('ServerInfo', Installer::ServerInfo());

$Smarty->assign('StepID', 'Step #3');
$Smarty->display('installation/complete');
?>