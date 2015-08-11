<?php
require_once('Core/Core.php');

$ClassConstructor = array($Database, $Smarty);
Manager::LoadExtension('API', $ClassConstructor);
switch($_SERVER['REQUEST_METHOD'])
{
    case 'POST':
    case 'PUT':
    case 'DELETE':
        unset($_POST);
        API::GenerateResponse(596, true);
    break;

    case 'HEAD':
    case 'OPTIONS':
        API::GenerateResponse(405, true);
    break;

    case 'GET':
        switch($_REQUEST['endpoint'])
        {
            case 'achievement':

                break;

            case 'auction':

                break;

            case 'character':

                break;

            case 'item':

                break;

            case 'guild':

                break;

            case 'pvp':

                break;

            case 'quest':

                break;

            case 'realm':

                break;

            case 'recipe':

                break;

            case 'spell':

                break;

            case 'data':
                API::EnableAPIExtension('Data', $ClassConstructor);
                switch($_REQUEST['method'])
                {
                    case 'character':
                        switch($_REQUEST['datatype'])
                        {
                            case 'races':
                                DataAPI::Races();
                            break;

                            case 'classes':
                                DataAPI::Classes();
                            break;

                            default:
                                API::GenerateResponse(403, true);
                             break;
                        }
                    break;

                    case 'item':

                    break;

                    default:

                    break;
                }

            break;

            default:
                API::GenerateResponse(403, true);
            break;
        }
    break;
}

?>