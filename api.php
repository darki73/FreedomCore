<?php
require_once('Core/Core.php');

$ClassConstructor = array($Database, $Smarty);
Manager::LoadExtension('API', $ClassConstructor);
API::VerifyRequestEligibility(5); // Allow 1 request every 5 seconds
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
                API::EnableAPIExtension('Spell');
                switch($_REQUEST['method'])
                {
                    case 'simple':
                        SpellAPI::GetSimpleSpell($_REQUEST['datatype']);
                    break;
                }
            break;

            case 'data':
                API::EnableAPIExtension('Data');
                switch($_REQUEST['method'])
                {
                    case 'character':
                        switch($_REQUEST['datatype'])
                        {
                            case 'races':
                                DataAPI::CharacterRaces();
                            break;

                            case 'classes':
                                DataAPI::CharacterClasses();
                            break;

                            default:
                                API::GenerateResponse(403, true);
                             break;
                        }
                    break;

                    case 'item':
                        switch($_REQUEST['datatype'])
                        {
                            case 'classes':
                                DataAPI::ItemClasses();
                            break;

                            default:
                                API::GenerateResponse(403, true);
                            break;
                        }
                    break;

                    default:
                        API::GenerateResponse(403, true);
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