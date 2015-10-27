<?php
define('IDS', DIRECTORY_SEPARATOR);
define('BASE_DIRECTORY', str_replace(IDS.'Update', IDS, getcwd()));
chdir(BASE_DIRECTORY);

require_once('Core'.IDS.'Core.php');


if(!Account::IsAuthorized($_SESSION['username'], 4))
    header('Location: /');

Manager::LoadExtension('Updater', [$Database, $Smarty]);
$Smarty->translate('Updater');

?>