<?php
require_once('Core/Core.php');

$ClassConstructor = array($Database, $Smarty);
Manager::LoadExtension('API', $ClassConstructor);

if(!String::IsNull($_REQUEST['endpoint']))
{
    API::VerifyRequestEligibility(5); // Allow 1 request every 5 seconds
    if($_REQUEST['endpoint'] != 'key')
        if(!isset($_REQUEST['key']))
            API::GenerateResponse(403, true);
        else
        {
            if(!API::VerifyIPKey($_REQUEST['key']))
                API::GenerateResponse(403, true);
        }
}
if(isset($_SESSION['username']) && $_SESSION['username'] != '')
{
    $UserAPIKey = Account::GetAPIKey($_SESSION['username']);
    $Smarty->assign('UserAPIKey', $UserAPIKey);
}
switch($_SERVER['REQUEST_METHOD'])
{
    case 'POST':
        switch($_REQUEST['endpoint'])
        {
            case 'key':
                switch ($_REQUEST['method']) {
                    case 'generate':
                        echo Account::CreateAPIKey($_REQUEST['username']);
                        break;

                    default:
                        API::GenerateResponse(403, true);
                        break;
                }
            break;

            default:
                unset($_POST);
                API::GenerateResponse(596, true);
            break;
        }
    break;
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
                        AchievementAPI::GetSimpleAchievement($_REQUEST['datatype'], $_REQUEST['jsonp']);
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
                        ItemAPI::GetSingleItem($_REQUEST['datatype'], $_REQUEST['jsonp']);
                    break;

                    case 'set':
                        ItemAPI::GetItemSet($_REQUEST['datatype'], $_REQUEST['jsonp']);
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
                        SpellAPI::GetSimpleSpell($_REQUEST['datatype'], $_REQUEST['jsonp']);
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
                                DataAPI::CharacterRaces($_REQUEST['jsonp']);
                            break;

                            case 'classes':
                                DataAPI::CharacterClasses($_REQUEST['jsonp']);
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
                                DataAPI::ItemClasses($_REQUEST['jsonp']);
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
                header('Content-Type: text/html; charset=utf-8');
                $Smarty->translate('Developer');
                $Smarty->assign('Page', Page::Info('dev', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Developer_Page_Title').' - ')));
                $Smarty->display('developer');
            break;
        }
    break;
}

?>