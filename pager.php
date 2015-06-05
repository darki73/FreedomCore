<?php
require_once('/Core/Core.php');
$ClassConstructor = array($Database, $Smarty);
switch($_REQUEST['category'])
{
	case 'account':
		if(!String::IsNull($_REQUEST['subcategory']))
		{
			switch($_REQUEST['subcategory'])
			{
				case 'login':
					$Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
					$Smarty->assign('Page', Page::Info('login', array('bodycss' => 'login-template web wow', 'pagetitle' => $Smarty->GetConfigVars('Account_Login').' - ')));
					$Smarty->display('pages/account_login');
				break;

                case 'pin':
                    $Headers = apache_request_headers();
                    $IsAjax = (isset($Headers['X-Requested-With']) && $Headers['X-Requested-With'] == 'XMLHttpRequest');
                    if($IsAjax)
                        Account::PinCharacter($_SESSION['username'], $_REQUEST['lastcategory']);
                    else
                        header('Location: /');
                break;

				case 'create':
					$Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
					$Smarty->assign('Page', Page::Info('login', array('bodycss' => 'login-template web wow', 'pagetitle' => $Smarty->GetConfigVars('Account_Create').' - ')));
					$Smarty->display('pages/account_create');
				break;

                case 'signout':
                    Session::Destroy(session_id());
                    session_destroy();
                    setcookie("FreedomCoreLanguage", null, time()-3600);
                    header('Location: /');
                break;

				case 'createaccount':
                    if($_REQUEST['password'] != $_REQUEST['rpassword'])
                    {
                        $_SESSION['accountcreationstatus'] = 'Account_Password_Mismatch';
                        header('Location: /account/create');
                    }
                    else
                    {
                        $CreationStatus = Account::Create($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email']);

                        if($CreationStatus == -1)
                            $_SESSION['accountcreationstatus'] = 'Account_Username_Exists';
                        elseif($CreationStatus == -2)
                            $_SESSION['accountcreationstatus'] = 'Account_Email_Exists';
                        else
                        {
                            Account::Authorize($_REQUEST['username'], $_REQUEST['password']);
                            Session::UpdateSession(array('loggedin' => true, 'username' => $_REQUEST['username'], 'remember_me' => true));
                            header('Location: /');
                        }
                    }

                break;

				case 'captcha.jpg':
					header("Content-type: image/jpg");
					String::GenerateCaptcha();
				break;

				case 'performlogin':
                    if(!String::IsNull($_REQUEST['accountName']) && !String::IsNull($_REQUEST['password']) && !String::IsNull($_REQUEST['persistLogin']) && !String::IsNull($_REQUEST['csrftoken']))
                    {
                        if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                            if(Account::Authorize($_REQUEST['accountName'], $_REQUEST['password']))
                            {
                                Session::UpdateSession(array('loggedin' => true, 'username' => $_REQUEST['accountName'], 'remember_me' => $_REQUEST['persistLogin']));
                                if(isset($_REQUEST['returnto']))
                                    header('Location: '.$_REQUEST['returnto']);
                                else
                                    header('Location: /');
                            }
                            else
                            {
                                header('Location: /account/login');
                                Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                            }
                        else
                        {
                            header('Location: /account/login');
                            Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                        }
                    }
				break;
			}
		}
	break;

    case 'data':
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'menu.json':
                    Manager::LoadExtension('Menu', array($Database, $Smarty));
                    echo Menu::GenerateMenu();
                break;
            }
        }
    break;

    case 'admin':
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            if(!Account::IsAuthorized($_SESSION['username'], 3))
                header('Location: /');
            else
            {
                switch($_REQUEST['subcategory'])
                {
                    case 'dashboard':

                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Page_Title').' - ')));
                        $Smarty->display('admin/dashboard');
                    break;

                    case 'security':
                        $Smarty->assign('ValidationResult', Security::VerifyFileList());
                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Antivirus').' - ')));
                        $Smarty->display('admin/security');
                    break;

                    case 'renewsecuritylist':
                       Security::WriteToFile(true);
                       echo "1";
                    break;
                }
            }
        }
    break;

    case 'achievement':
        if(!String::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
        {
            $Headers = apache_request_headers();
            $IsAjax = (isset($Headers['X-Requested-With']) && $Headers['X-Requested-With'] == 'XMLHttpRequest');
            if($IsAjax)
            {
                Manager::LoadExtension('Achievements', $ClassConstructor);
                $Smarty->assign('Achievement', Achievements::GetAchievement($_REQUEST['subcategory']));
                $Smarty->display('blocks/achievement_tooltip');
            }
            else
                header('Location: /');
        }
        else
            header('Location: /');
    break;

    case 'item':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $DisplayPageElements = 50;
            if(isset($_REQUEST['classId']))
            {
                if(isset($_REQUEST['subClassId']))
                {
                    $Items = Items::GetAllItemsInSubCategory($_REQUEST['classId'], $_REQUEST['subClassId'], 50);
                    if(isset($_REQUEST['page']))
                        $SelectedPage = $_REQUEST['page'];
                    else
                        $SelectedPage = 1;
                    $Smarty->assign('SelectedPage', $SelectedPage);
                    $Smarty->assign('Requests', array('class' => 1, 'subclass' => 1));
                    if($SelectedPage == 1)
                        $Items = Items::GetAllItemsInSubCategory($_REQUEST['classId'], $_REQUEST['subClassId'], 0);
                    else
                        $Items = Items::GetAllItemsInSubCategory($_REQUEST['classId'], $_REQUEST['subClassId'], ($SelectedPage*50-50));
                    $Smarty->assign('Items', $Items['items']['item_list']);
                    $Smarty->assign('PageData', $Items['items']['category_data']);
                    $Smarty->assign('ResultsFound', $Items['count']);
                    $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'item-index', 'pagetitle' => $Smarty->GetConfigVars('Item_Category').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/items_index');
                }
                else
                {
                    if(isset($_REQUEST['page']))
                        $SelectedPage = $_REQUEST['page'];
                    else
                        $SelectedPage = 1;
                    $Smarty->assign('SelectedPage', $SelectedPage);
                    $Smarty->assign('Requests', array('class' => 1, 'subclass' => 0));
                    if($SelectedPage == 1)
                        $Items = Items::GetAllItemsInCategory($_REQUEST['classId'], 0);
                    else
                        $Items = Items::GetAllItemsInCategory($_REQUEST['classId'], ($SelectedPage*50-50));
                    $Smarty->assign('Items', $Items['items']['item_list']);
                    $Smarty->assign('PageData', $Items['items']['category_data']);
                    $Smarty->assign('ResultsFound', $Items['count']);
                    $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'item-index', 'pagetitle' => $Smarty->GetConfigVars('Item_Category').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/items_index');
                }
            }
            else
            {
                if(isset($_REQUEST['page']))
                    $SelectedPage = $_REQUEST['page'];
                else
                    $SelectedPage = 1;
                $Smarty->assign('SelectedPage', $SelectedPage);
                $Smarty->assign('Requests', array('class' => 1, 'subclass' => 0));
                if($SelectedPage == 1)
                    $Items = Items::GetAllItems(0);
                else
                    $Items = Items::GetAllItems(($SelectedPage*50-50));
                $Smarty->assign('Items', $Items['items']['item_list']);
                $Smarty->assign('PageData', $Items['items']['category_data']);
                $Smarty->assign('ResultsFound', $Items['count']);
                $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'item-index', 'pagetitle' => $Smarty->GetConfigVars('Item_Category').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                $Smarty->display('pages/items_index');
            }
        }
        else
        {
            if(String::IsNull($_REQUEST['lastcategory']))
            {
                $Item = Items::GetItemInfo($_REQUEST['subcategory']);
                $ItemRelation = Items::GetItemRelatedInfo($_REQUEST['subcategory']);
                if(!$Item)
                    echo "Not Found!";
                else
                {
                    if($Item['class']['class'] == 2 || $Item['class']['class'] == 4 || $Item['class']['class'] == 15)
                    {
                        if($Item['subclass']['subclass'] != 0 && $Item['class']['class'] != 15)
                        {
                            $StorageDir = str_replace('/', DS, getcwd()).DS.'Uploads'.DS.'Core'.DS.'Items'.DS.'Cache'.DS.'ModelViewer'.DS;
                            $ItemName = 'item'.$Item['entry'].'.jpg';
                            if(!File::Exists($StorageDir.$ItemName))
                                File::Download('http://media.blizzard.com/wow/renders/items/item'.$Item['entry'].'.jpg', $StorageDir.$ItemName);
                        }
                        elseif($Item['class']['class'] == 15 && $Item['subclass']['subclass'] == 5)
                        {
                            $StorageDir = str_replace('/', DS, getcwd()).DS.'Uploads'.DS.'Core'.DS.'Items'.DS.'Cache'.DS.'ModelViewer'.DS;
                            for($i = 1; $i <= 5; $i++)
                            {
                                if(isset($Item['spell_data'.$i]['SearchForCreature']))
                                {
                                    $ItemName = 'creature'.$Item['spell_data'.$i]['SearchForCreature'].'.jpg';
                                    if(!File::Exists($StorageDir.$ItemName))
                                        File::Download('http://media.blizzard.com/wow/renders/npcs/rotate/creature'.$Item['spell_data'.$i]['SearchForCreature'].'.jpg', $StorageDir.$ItemName);
                                }
                            }
                        }
                    }
                    $Smarty->assign('Item', $Item);
                    $Smarty->assign('ItemRelation', $ItemRelation);
                    $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'item-'.$Item['entry'], 'pagetitle' => $Item['name'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/item_info');
                }
            }
            else
            {
                if($_REQUEST['lastcategory'] == 'tooltip')
                {
                    $Item = Items::GetItemInfo($_REQUEST['subcategory']);
                    $Smarty->assign('Item', $Item);
                    $Smarty->display('blocks/item_tooltip');
                }
                elseif(strstr($_REQUEST['lastcategory'], '.frag'))
                {
                    $Smarty->assign('RandomCommentCode', md5(uniqid(rand(), true)));
                    $Fragment = str_replace('.frag', '', $_REQUEST['lastcategory']);
                    $Smarty->assign('SelectedItem', $_REQUEST['subcategory']);
                    $ItemRelation = Items::GetItemRelatedInfo($_REQUEST['subcategory']);
                    $Smarty->assign('Relations', $ItemRelation[$Fragment]);
                    $Smarty->display('fragments/'.$Fragment);
                }
                else
                    header('Location: /');
            }
        }
    break;

    case 'character':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $ErrorDescription = ErrorHandler::ListenForError(404);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        else
        {
            if(Characters::CheckCharacter($_REQUEST['subcategory']))
            {
                if(!String::IsNull($_REQUEST['lastcategory']))
                {
                    $CharacterData = Characters::GetCharacterData($_REQUEST['subcategory']);
                    $Smarty->assign('Character', $CharacterData);
                    $Smarty->assign('SelectedCategory', $_REQUEST['lastcategory']);
                    switch($_REQUEST['lastcategory'])
                    {
                        case 'achievement':
                            if(String::IsNull($_REQUEST['datatype']))
                            {
                                Manager::LoadExtension('Achievements', $ClassConstructor);
                                $Smarty->assign('AStatus', Achievements::GetAchievementsStats());
                                $Smarty->assign('Categories', Achievements::GetCategories());
                                $Smarty->assign('CompletedAchievements', Characters::GetCompletedAchievements($CharacterData['guid']));
                                $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'achievement_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Achievements').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                                $Smarty->display('pages/character_achievements');
                            }
                            else
                            {
                                ob_flush();
                                Manager::LoadExtension('Achievements', $ClassConstructor);
                                $Smarty->assign('IncompleteAchievements', Achievements::GetAchievementsInCategory($_REQUEST['datatype']));
                                $Smarty->assign('Categories', Achievements::GetCategories());
                                $Smarty->assign('CompletedAchievements', Characters::GetCompletedAchievements($CharacterData['guid']));
                                $Smarty->assign('Category', $_REQUEST['datatype']);
                                $Smarty->display('blocks/achievements_category');
                            }
                        break;

                        case 'reputation':
                            $Smarty->assign('Reputations', Characters::GetReputation($CharacterData['guid']));
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'reputation_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Reputation').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                            $Smarty->display('pages/character_reputation');
                        break;

                        case 'profession':
                            $Professions = Characters::GetCharacterProfessions($CharacterData['guid']);
                            if(String::IsNull($_REQUEST['datatype']))
                            {
                                if(empty($Professions))
                                {
                                    $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'professions_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Professions').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                                    $Smarty->display('pages/character_no_professions');
                                }
                                else
                                {
                                    $RedirectTo = '';
                                    foreach($Professions as $Profession)
                                    {
                                        if($Profession['primary'] == 1)
                                        {
                                            $RedirectTo = '/'.$Profession['name'];
                                            break;
                                        }
                                        else
                                        {
                                            $Smarty->assign('SelectedProfession', $_REQUEST['datatype']);
                                            $Smarty->assign('Professions', $Professions);
                                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'professions_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Professions').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                                            $Smarty->display('pages/character_no_professions');
                                        }
                                    }
                                    header('Location: http://'.$_SERVER[HTTP_HOST].str_replace('//', '/',$_SERVER['REQUEST_URI'].$RedirectTo));
                                }
                            }
                            else
                            {
                                $CharProfessions = array();
                                foreach($Professions as $Profession)
                                {
                                    $CharProfessions[] = $Profession['name'];
                                }
                                if(in_array($_REQUEST['datatype'], $CharProfessions))
                                {
                                    $ProfessionInfo = $Professions[String::MASearch($Professions, 'name', $_REQUEST['datatype'])];
                                    $AllRecipes = Characters::GetRecipesForProfession($ProfessionInfo['id']);
                                    $LearnedRecipes = Characters::GetLearnedRecipesForProfession($ProfessionInfo['id'], $CharacterData['guid']);
                                    $UnlearnedArray = array();
                                    foreach($AllRecipes as $All)
                                    {
                                        $Searcher = String::MASearch($LearnedRecipes, 'spell', $All['spellID']);
                                        if(!$Searcher)
                                            $UnlearnedArray[] = $All;
                                    }
                                    $Smarty->assign('SelectedProfession', $_REQUEST['datatype']);
                                    $Smarty->assign('ProfessionInfo', $ProfessionInfo);
                                    $Smarty->assign('Professions', $Professions);
                                    $Smarty->assign('TotalRecipes', count($AllRecipes));
                                    $Smarty->assign('UnRecipes', $UnlearnedArray);
                                    $Smarty->assign('LearnedRecipes', $LearnedRecipes);
                                    $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'professions_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Professions').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                                    $Smarty->display('pages/character_professions');
                                }
                                else
                                    header('Location: http://'.$_SERVER[HTTP_HOST].str_replace($_REQUEST['datatype'], '', $_SERVER['REQUEST_URI']));
                            }
                        break;

                        case 'tooltip':
                            $Smarty->display('blocks/character_tooltip');
                        break;

                        case 'simple':
                            $RedirectTo = str_replace('/simple', '', str_replace('//', '/', $_SERVER['REQUEST_URI'].'/advanced'));
                            header('Location: '.$RedirectTo);
                        break;


                        case 'advanced':
                            $Professions = Characters::GetCharacterProfessions($CharacterData['guid']);
                            $Smarty->assign('PageType', $_REQUEST['lastcategory']);
                            $Smarty->assign('Specializations', Characters::GetSpecByTalents($CharacterData['guid']));
                            $Smarty->assign('Inventory', Characters::GetGearForCharacter($CharacterData['guid']));
                            $Smarty->assign('ArenaRating', Characters::GetPVPRaiting($CharacterData['guid']));
                            $Smarty->assign('Professions', $Professions);
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'profile_page', 'pagetitle' => $_REQUEST['subcategory'].' - ')));
                            $Smarty->display('pages/character_main_page_advanced');
                        break;
                    }
                }
                else
                {
                    $RedirectTo = str_replace('//', '/', $_SERVER['REQUEST_URI'].'/advanced');
                    header('Location: '.$RedirectTo);
                }
            }
            else
            {
                $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'server-error', 'pagetitle' => '')));
                $Smarty->display('pages/character_notfound');
            }
        }
    break;

    case 'spell':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $ErrorDescription = ErrorHandler::ListenForError(404);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        else
        {
            if (!String::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
            {
                $Smarty->assign('Spell', Spells::SpellInfo($_REQUEST['subcategory']));
                $Smarty->display('blocks/spell_tooltip');
            }
            else
                header('Location: /');
        }
    break;

    case 'quest':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $ErrorDescription = ErrorHandler::ListenForError(404);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        else
        {
            if (!String::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
            {
                $Smarty->assign('Quest', Items::QuestInfo($_REQUEST['subcategory']));
                $Smarty->display('blocks/quest_tooltip');
            }
            else
                header('Location: /');
        }
    break;

    case 'guild':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $ErrorDescription = ErrorHandler::ListenForError(404);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        else
        {
            if(Characters::CheckGuild($_REQUEST['subcategory']))
            {
                if (!String::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
                {
                    $Smarty->assign('Guild', Characters::GetGuildData($_REQUEST['subcategory']));
                    $Smarty->display('blocks/guild_tooltip');
                }
                else
                {
                    Manager::LoadExtension('Guild', $ClassConstructor);
                    if(String::IsNull($_REQUEST['lastcategory']))
                    {
                        $GuildData = Guild::GetGuildData($_REQUEST['subcategory']);
                        if(!$GuildData)
                        {
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'server-error', 'pagetitle' => '')));
                            $Smarty->display('pages/guild_notfound');
                        }
                        else
                        {
                            if(isset($_REQUEST['character']))
                                $Smarty->assign('returnto', $_REQUEST['character']);
                            else
                                $Smarty->assign('returnto', false);
                            $Smarty->assign('Guild', $GuildData);
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'guild_page', 'pagetitle' => $_REQUEST['subcategory'].' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                            $Smarty->display('pages/guild_main_page');
                        }
                    }
                    else
                    {
                        switch($_REQUEST['lastcategory'])
                        {
                            case 'roster':

                            break;

                            case 'news':

                            break;

                            case 'events':

                            break;

                            default:
                                $RedirectTo = '/'.$_REQUEST['category'].'/'.$_REQUEST['subcategory'];
                                if(isset($_REQUEST['character']))
                                    $RedirectTo .= '/?character='.$_REQUEST['character'];
                                header('Location: '.$RedirectTo);
                                break;
                        }
                    }
                }
            }
            else
            {
                $ErrorDescription = ErrorHandler::ListenForError(404);
                $Smarty->assign('Error', $ErrorDescription);
                $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
                $Smarty->display('pages/error_page');
            }
        }
    break;

    case 'sidebar':
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
            $Smarty->display('sidebar/'.$_REQUEST['subcategory']);
    break;

    case 'render':
        Manager::LoadExtension('ModelViewer', $ClassConstructor);
        function DisplayCharacterModel()
        {
            $CharacterData = Characters::GetCharacterData($_REQUEST['datatype']);
            $Inventory = Characters::GetGearForCharacter($CharacterData['guid']);

            ModelViewer::Initialize(450, 450);
            ModelViewer::SetCharacterData($CharacterData['race_data']['name'], $CharacterData['gender']);
            foreach($Inventory as $Item)
            {
                if(isset($Item['data']))
                    ModelViewer::EquipItem($Item['slot'], $Item['data']['displayid']);
            }
            echo ModelViewer::GetCharacterHtml();
        }

        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'dynamic':
                    if(String::IsNull($_REQUEST['lastcategory']))
                        header('Location: /');
                    else
                    {
                        switch($_REQUEST['lastcategory'])
                        {
                            case 'character':
                                if(Characters::CheckCharacter($_REQUEST['datatype']))
                                {
                                    DisplayCharacterModel();
                                }
                                else
                                    header('Location: /');
                            break;

                            case 'face':

                            break;

                            case 'in-armor':

                            break;

                            default:
                                header('Location: /');
                            break;
                        }
                    }
                break;

                case 'static':
                    if(String::IsNull($_REQUEST['lastcategory']))
                        header('Location: /');
                    else
                    {
                        switch($_REQUEST['lastcategory'])
                        {
                            case 'character':

                            break;

                            case 'face':

                            break;

                            case 'in-armor':
                                DisplayCharacterModel();
                            break;

                            default:
                                header('Location: /');
                            break;
                        }
                    }
                break;

                default:
                    header('Location: /');
                break;
            }
        }
    break;

	case 'game':
		if($_REQUEST['subcategory'] == 'race')
			$Smarty->translate('Races');
		elseif($_REQUEST['subcategory'] == 'class')
			$Smarty->translate('Classes');
		Manager::LoadExtension("Races", $ClassConstructor);
		Manager::LoadExtension("Classes", $ClassConstructor);
		if(String::IsNull($_REQUEST['subcategory']))
		{
			$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'game-index', 'pagetitle' => $Smarty->GetConfigVars('Menu_Game').' - ')));
			$Smarty->display('game');
		}
		elseif(!String::IsNull($_REQUEST['subcategory']))
		{
			if(String::IsNull($_REQUEST['lastcategory']))
			{
				switch($_REQUEST['subcategory'])
				{
					case 'race':
						$Smarty->assign('AllianceRaces', Races::GetAlliance());
						$Smarty->assign('HordeRaces', Races::GetHorde());
						$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'game-race-index', 'pagetitle' => $Smarty->GetConfigVars('Game_Races').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
						$Smarty->display('pages/game_races');
					break;

					case 'class':
						$Smarty->translate('Classes');
						$Smarty->assign('Classes', Classes::GetAll());
						$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'game-classes-index', 'pagetitle' => $Smarty->GetConfigVars('Game_Classes').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
						$Smarty->display('pages/game_classes');
					break;

					case 'patch-notes':
						Manager::LoadExtension("Patches", $ClassConstructor);
						$Smarty->assign('MenuData', Patches::GetMenu());
						$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'game-patchnotes', 'pagetitle' => $Smarty->GetConfigVars('Game_Patch_Notes').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
						$Smarty->display('pages/game_patch_notes');
					break;
				}
			}
			else
			{
				if($_REQUEST['subcategory'] == 'race')
				{
                    $Races = array_merge(Races::GetAlliance(), Races::GetHorde());
                    $ExistingRaces = String::UnsetAllBut('race_link', $Races, 2);
                    if(!in_array($_REQUEST['lastcategory'], $ExistingRaces))
                        header('Location: /game/race');

					$Race = Races::GetRace($_REQUEST['lastcategory']);
					$Smarty->assign('Race', $Race);
					$Smarty->assign('RaceNavigation', Races::GetNavigation($Race['id']));
					$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'race-'.$_REQUEST['lastcategory'].'', 'pagetitle' => $Race['race_full_name'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
					$Smarty->display('pages/game_race');
				}
				elseif($_REQUEST['subcategory'] == 'class')
				{
                    $Classes = Classes::GetAll();
                    $ExistingClasses = String::UnsetAllBut('class_name', $Classes, 2);
                    if(!in_array($_REQUEST['lastcategory'], $ExistingClasses))
                        header('Location: /game/class');
					$Class = Classes::GetClass($_REQUEST['lastcategory']);
					$Smarty->assign('Class', $Class);
					$Smarty->assign('ClassNavigation', Classes::GetNavigation($Class['id']));
					$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'class-'.$_REQUEST['lastcategory'].'', 'pagetitle' => $Class['class_full_name'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
					$Smarty->display('pages/game_class');
				}
			}
		}
	break;

    case 'blog':
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            Manager::LoadExtension('News', $ClassConstructor);
            $Article = News::GetArticle($_REQUEST['subcategory']);
            if($Article == null)
                header('Location: /');
            else
            {
                $Smarty->assign('Articles', News::GetAllNews());
                $Smarty->assign('Article', $Article);
                $Smarty->assign('Page', Page::Info('blog', array('bodycss' => 'blog-article news', 'pagetitle' => $Article['title'].' - ')));
                $Smarty->display('blog');
            }
        }
        break;

	case 'community':
		if(String::IsNull($_REQUEST['subcategory']))
		{
			$Smarty->assign('Page', Page::Info('community', array('bodycss' => 'community-home', 'pagetitle' => $Smarty->GetConfigVars('Menu_Community').' - ')));
			$Smarty->display('community');
		}
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'status':
                    Manager::LoadExtension('Realms', $ClassConstructor);
                    $Smarty->assign('Realms', Realms::GetAllRealms());
                    $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'realm-status', 'pagetitle' => $Smarty->GetConfigVars('Menu_Community').' - ')));
                    $Smarty->display('pages/community_status');
                break;
            }
        }
	break;

    case 'discussion':
        Manager::LoadExtension('News', $ClassConstructor);
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            $ExplodeCategory = explode('.', $_REQUEST['subcategory']);
            $Language = $ExplodeCategory[0];
            $Category = $ExplodeCategory[1];
            $SearchFor = $ExplodeCategory[2];

            switch($Category)
            {
                case 'item':
                    if(String::IsNull($_REQUEST['lastcategory']))
                        header('Location: /');
                    else
                    {
                        switch ($_REQUEST['lastcategory'])
                        {
                            case 'load.json':
                                $ItemRelation = Items::GetItemRelatedInfo($SearchFor);
                                $Smarty->assign('Comments', $ItemRelation['comments']);
                                $Smarty->display('blog/comments_load');
                            break;

                            case 'comment.json':
                                Items::AddComment($SearchFor, $_SESSION['username'], $_REQUEST['detail'], $Language);
                                $Response = array(
                                    "commentId" => Items::GetLastCommentID($SearchFor),
                                    "articleId" => $SearchFor
                                );
                                echo json_encode($Response);
                            break;
                        }
                    }
                break;

                case 'blog':
                    if(String::IsNull($_REQUEST['lastcategory']))
                        header('Location: /');
                    else
                    {
                        switch($_REQUEST['lastcategory'])
                        {
                            case 'load.json':
                                $CommentsInfo = array(
                                    'article_id' => $_REQUEST['page'],
                                    'base' => $_REQUEST['base']
                                );

                                $Smarty->assign('Comments', News::GetComments($_REQUEST['page']));
                                $Smarty->display('blog/comments_load');
                                break;

                            case 'comment.json':
                                News::AddComment(str_replace('blog.', '', $_REQUEST['subcategory']), $_SESSION['username'], $_REQUEST['detail']);
                                $ArticleID = str_replace('blog.', '', $_REQUEST['subcategory']);
                                $Response = array(
                                    "commentId" => News::GetLastCommentID($ArticleID),
                                    "articleId" => $ArticleID
                                );
                                echo json_encode($Response);
                                break;

                            case 'deletecomment.json':

                                break;
                        }
                    }
                break;
            }
        }
    break;

	case 'media':
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
//		if(String::IsNull($_REQUEST['subcategory']))
//		{
//			$Smarty->assign('Page', Page::Info('media', array('bodycss' => '')));
//			$Smarty->display('media');
//		}
	break;

    case 'shop':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $Smarty->assign('Page', Page::Info('shop', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Menu_Shop').' - ')));
            $Smarty->display('shop');
        }
    break;

	case 'fragment':
		switch($_REQUEST['subcategory'])
		{
			case 'i18n.frag':
				$Smarty->display('fragment_i18n');
			break;

			case 'login.frag':
                if(isset($_REQUEST['returnto']))
                    $Smarty->assign('ReturnTo', $_REQUEST['returnto']);
                $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                $Smarty->assign('Page', Page::Info('login', array('bodycss' => 'login-template web wow', 'pagetitle' => $Smarty->GetConfigVars('Account_Login').' - ')));
                $Smarty->display('fragment_login');
			break;
		}
	break;

    case 'search':
        if(!isset($_REQUEST['q']))
        {
            $Smarty->assign('Page', Page::Info('search', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Search').' - ')));
            $Smarty->display('search');
        }
        else
        {
            Manager::LoadExtension('News', $ClassConstructor);
            Manager::LoadExtension('Search', $ClassConstructor);
            $Smarty->assign('SearchResults', Search::PerformSearch($_REQUEST['q']));
            if(isset($_REQUEST['f']))
                $Smarty->assign('SearchCategory', htmlentities($_REQUEST['f']));
            else
                $Smarty->assign('SearchCategory', '');
            $Smarty->assign('SearchedFor', htmlentities($_REQUEST['q']));
            $Smarty->assign('Page', Page::Info('search', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Search').' - ')));
            $Smarty->display('pages/search_results');
        }
        break;

    case 'changelanguage':
        if(String::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            $AvailableLanguages = array('ru', 'it', 'pt', 'kr', 'de', 'es', 'fr');
            if(in_array($_REQUEST['subcategory'], $AvailableLanguages))
            {
                $_SESSION['preferredlanguage'] = $_REQUEST['subcategory'];
                header('Location: /');
            }
            else
                header('Location: /');
        }
        break;

    case 'error':
        if(String::IsNull($_REQUEST['category']))
            header('Location: /');
        else
        {
            $ErrorDescription = ErrorHandler::ListenForError($_SERVER['REDIRECT_STATUS']);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        break;

    default:
        header('Location: /');
        break;
}

?>