<?php
require_once('Core/Core.php');

$ClassConstructor = array($Database, $Smarty);
Manager::LoadExtension('API', $ClassConstructor);

if(!isset($_REQUEST['jsonp']))
    $_REQUEST['jsonp'] = "";

if(!Text::IsNull($_REQUEST['endpoint']))
{
    if($_REQUEST['endpoint'] != 'launcher')
        API::VerifyRequestEligibility(5); // Allow 1 request every 5 seconds
    if($_REQUEST['endpoint'] != 'key' && $_REQUEST['endpoint'] != 'launcher')
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
            case 'launcher':
                API::EnableAPIExtension('Launcher');
                switch($_REQUEST['method']){

                    case 'friends':
                        API::EnableAPIExtension('Friends');
                        FriendsAPI::GetCharacterFriends($_REQUEST['datatype']);
                    break;

                    case 'authorize':
                        if(Text::IsRequestSet($_REQUEST, ['username', 'password'])){
                            LauncherAPI::AuthorizeUser($_REQUEST['username'], $_REQUEST['password']);
                        } else {
                            echo Text::SimpleJson(1403, 'status', '0');
                        }
                    break;

                    case 'server-supported':
                        $ServerData = LauncherAPI::ServerSupported();
                        if($ServerData['code'] == 1200)
                            echo Text::SimpleJson(1200, 'status', '1');
                        else
                            echo Text::SimpleJson(1403, 'status', '0');
                    break;

                    case 'agent-version':
                        if(!Text::IsRequestSet($_REQUEST, ['assembly_version'])){
                            API::GenerateResponse(1404, true);
                        } else {
                            echo json_encode(LauncherAPI::GetAgentVersion($_REQUEST['assembly_version']));
                        }
                    break;

                    case 'get-agent-db':
                        if(!Text::IsRequestSet($_REQUEST, ['assembly_version'])){
                            API::GenerateResponse(1404, true);
                        } else {
                            $AgentDB = [
                                'product'           =>  'FreedomNetAgent',
                                'launcher_url'      =>  'http://'.$_SERVER['HTTP_HOST'].'/Launcher',
                                'update_method'     =>  'checkupdate',
                                'install_dir'       =>  '',
                                'data_dir'          =>  '',
                                'server_url'        =>  $_SERVER['HTTP_HOST'],
                                'local_version'     =>  '155.'.LauncherAPI::VersionToString($_REQUEST['assembly_version']),
                                'build'             =>  LauncherAPI::VersionToString($_REQUEST['assembly_version']).'.0000',
                                'installed_locales' =>  [
                                    'ru'    =>  'Русский',
                                    'en'    =>  'English',
                                    'es'    =>  'Espanol',
                                    'fr'    =>  'French',
                                    'it'    =>  'Italian',
                                    'kr'    =>  'Korean',
                                    'pl'    =>  'Polish',
                                    'pt'    =>  'Portugese',
                                    'de'    =>  'Deutsch'
                                ]
                            ];
                            Text::toJson($AgentDB, ['JSON_UNESCAPED_UNICODE', 'JSON_PRETTY_PRINT']);
                        }
                    break;

                    case 'checkupdate':
                        if(!Text::IsRequestSet($_REQUEST, ['assembly_version'])){
                            API::GenerateResponse(1404, true);
                        } else {
                            if(Text::IsRequestSet($_REQUEST, ['first_launch'])){
                                echo Text::SimpleJson(1201, 'status', LauncherAPI::StringToVersion(LauncherAPI::CheckForUpdate($_REQUEST['assembly_version'], false, true)));
                            } else {
                                $UpdateStatus = LauncherAPI::CheckForUpdate($_REQUEST['assembly_version'], true);
                                if(!$UpdateStatus)
                                    echo Text::SimpleJson(1200, 'status', 'No Update Needed');
                                else
                                    echo Text::SimpleJson(1201, 'status', $UpdateStatus);
                            }
                        }
                    break;

                    case 'get-latest-update':
                        echo LauncherAPI::StringToVersion(LauncherAPI::LatestUpdate());
                    break;

                    case 'checkupdate-build':
                        if(!Text::IsRequestSet($_REQUEST, ['last_build'])){
                            API::GenerateResponse(1404, true);
                        } else {
                            $UpdateStatus = LauncherAPI::CheckForUpdateByBuild($_REQUEST['last_build'], true);
                            if(!$UpdateStatus)
                                echo Text::SimpleJson(1200, 'status', 'No Update Needed');
                            else
                                echo Text::SimpleJson(1201, 'status', $UpdateStatus);
                        }
                    break;

                    case 'downloadupdatelist':
                        if(Text::IsRequestSet($_REQUEST, ['assembly_version', 'updating_to'])){
                            if(LauncherAPI::VersionToString($_REQUEST['updating_to']) < LauncherAPI::VersionToString($_REQUEST['assembly_version']))
                                echo Text::SimpleJson(1403, 'status', 'Can\'t upgrade to lower version');
                            else {
                                $Build = LauncherAPI::BuildUpdateList($_REQUEST['updating_to']);
                                if(!$Build)
                                    echo Text::SimpleJson(1500, 'status', 'Specified Update Version does not exists');
                                else
                                    Text::toJson(LauncherAPI::BuildUpdateList($_REQUEST['updating_to']), ['JSON_UNESCAPED_UNICODE']);
                            }
                        } else {
                            echo "Not Set!";
                        }
                    break;
                }
            break;

            case 'account':
                API::EnableAPIExtension('Account');
                switch($_REQUEST['method'])
                {
                    case 'authorize':
                        AccountAPI::Authorize($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['jsonp']);
                        break;

                    case 'android':
                        AccountAPI::Android($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['jsonp']);
                        break;

                    case 'deauthorize':
                        AccountAPI::Deauthorize($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['jsonp']);
                        break;

                    case 'characters':
                        AccountAPI::GetCharacters($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['jsonp']);
                        break;
                }
                break;

            case 'achievement':
                API::EnableAPIExtension('Achievement');
                switch($_REQUEST['method'])
                {
                    case 'simple':
                        AchievementAPI::GetSimpleAchievement($_REQUEST['datatype'], $_REQUEST['jsonp']);
                        break;
                }
                break;

            case 'armory':
                API::EnableAPIExtension('Armory');
                switch($_REQUEST['method']){
                    case 'wsrt':
                        ArmoryAPI::GetResetStatus($_REQUEST['jsonp']);
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