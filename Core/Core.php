<?php
header('X-Frame-Options: SAMEORIGIN');
require_once('Core/Classes/Autoloader.Class.php');
Autoloader::Initialize();
new ErrorHandler($Smarty);
$Session = new Session($Database, $Smarty);
Session::Start('FreedomCore', false);
Manager::LoadExtension('Account', array($Database, $Smarty));
if(isset($_SESSION['username']))
{
    Manager::LoadExtension('Characters', array($Database, $Smarty));
    Manager::LoadExtension('Items', array($Database, $Smarty));
    $User = Account::Get($_SESSION['username']);
    $Smarty->assign('Characters', Characters::GetCharacters($User['id']));
    $Smarty->assign('User', $User);
}
?>