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
                API::EnableAPIExtension('Achievement');
                switch($_REQUEST['method'])
                {
                    case 'simple':
                        AchievementAPI::GetSimpleAchievement($_REQUEST['datatype']);
                    break;
                }
            break;

            case 'auction':
                API::GenerateResponse(501, true);
            break;

            case 'character':
                API::GenerateResponse(501, true);
            break;

            case 'item':
                API::EnableAPIExtension('Item');
                switch($_REQUEST['method'])
                {
                    case 'single':
                        ItemAPI::GetSingleItem($_REQUEST['datatype']);
                    break;

                    case 'set':
                        ItemAPI::GetItemSet($_REQUEST['datatype']);
                    break;
                }
            break;

            case 'guild':
                API::GenerateResponse(501, true);
            break;

            case 'pvp':
                API::GenerateResponse(501, true);
            break;

            case 'quest':
                API::GenerateResponse(501, true);
            break;

            case 'realm':
                API::GenerateResponse(501, true);
            break;

            case 'recipe':
                API::GenerateResponse(501, true);
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