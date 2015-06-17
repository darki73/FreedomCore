<?php
require_once('Core/Core.php');

$ClassConstructor = array($Smarty, $Database);

switch($_REQUEST['category'])
{
    case 'character':
        if(!String::IsNull($_REQUEST['subcategory']))
        {
            echo "<pre>";
            print_r(API::Execute($ClassConstructor, 'Characters', 'GetCharacterData', $_REQUEST['subcategory']));
        }
    break;
}

?>