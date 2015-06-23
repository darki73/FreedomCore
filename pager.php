<?php
require_once('Core/Core.php');
$ClassConstructor = array($Database, $Smarty);
switch($_REQUEST['category'])
{
	case 'account':
		if(!String::IsNull($_REQUEST['subcategory']))
		{
            if(isset($User['id']))
            {
                $AccountBalance = Account::GetBalance($User['username']);
                $Smarty->assign('AccountBalance', $AccountBalance);
            }

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

                case 'management':
                    $Smarty->translate('Account');
                    if(String::IsNull($_REQUEST['lastcategory']))
                    {
                        $Smarty->assign('User', $User);
                        $Smarty->assign('Accounts', Account::GetGameAccounts($User['username']));
                        $Smarty->assign('Page', Page::Info('account_management', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management'))));
                        $Smarty->display('account/account_management');
                    }
                    else
                    {
                        $AccountID = str_replace('WoW', '', $_REQUEST['accountName']);
                        $Smarty->assign('Account', Account::GetAccountByID($AccountID));
                        if(Account::VerifyAccountAccess($User['username'], $AccountID))
                        {
                            switch($_REQUEST['lastcategory'])
                            {
                                case 'dashboard':
                                    $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management'))));
                                    $Smarty->display('account/account_dashboard');
                                break;

                                case 'services':
                                    if(!String::IsNull($_REQUEST['datatype']))
                                    {
                                        switch($_REQUEST['datatype'])
                                        {
                                            case 'character-services':
                                                if(!String::IsNull($_REQUEST['service']))
                                                    switch($_REQUEST['service'])
                                                    {
                                                        case 'PCT':
                                                                echo "Not Yet Implemented!!!";
                                                            break;
                                                        case 'PFC':
                                                        case 'PRC':
                                                        case 'PCC':
                                                        case 'PNC':
                                                            if(!isset($_REQUEST['servicecat']))
                                                            {
                                                                $Service = array(
                                                                    'name' => strtolower($_REQUEST['service']),
                                                                    'title' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service']),
                                                                    'description' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_Description'),
                                                                    'service' => $_REQUEST['service'],
                                                                    'price' => Account::GetServicePrice($_REQUEST['service'])
                                                                );
                                                                $Smarty->assign('Service', $Service);
                                                                $Smarty->assign('Characters', Characters::GetCharacters($User['id']));
                                                                $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                $Smarty->display('account/pcs');
                                                            }
                                                            else
                                                            {
                                                                $Service = array(
                                                                    'name' => strtolower($_REQUEST['service']),
                                                                    'title' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service']),
                                                                    'description' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_Description'),
                                                                    'history' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_History'),
                                                                    'service' => $_REQUEST['service'],
                                                                    'price' => Account::GetServicePrice($_REQUEST['service'])
                                                                );
                                                                $Smarty->assign('Service', $Service);
                                                                if($_REQUEST['servicecat'] != 'history')
                                                                    $Smarty->assign('Character', Characters::GetCharacterData($_REQUEST['character']));
                                                                switch($_REQUEST['servicecat'])
                                                                {
                                                                    case 'history':
                                                                        $Smarty->assign('Payments', Account::GetServicePaymentHistory($User['id'], $Service['name']));
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_History').' - ')));
                                                                        $Smarty->display('account/pcs_history');
                                                                        break;

                                                                    case 'tos':
                                                                        $ServiceBasedTos = array(
                                                                            'First' =>  $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_ToS_One'),
                                                                            'Second' =>  $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_ToS_Two'),
                                                                            'Third' =>  $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_ToS_Three'),
                                                                            'Forth' =>  $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_ToS_Four'),
                                                                        );
                                                                        $Smarty->assign('ToS', $ServiceBasedTos);
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/pcs_tos');
                                                                        break;

                                                                    case 'confirm':
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/pcs_confirm');
                                                                        break;

                                                                    default:
                                                                        header('Location: /account/management');
                                                                        break;
                                                                }
                                                            }
                                                        break;

                                                        default:
                                                            header('Location: /account/management');
                                                            break;
                                                    }
                                                else
                                                    header('Location: /account/management');
                                            break;

                                            case 'is-character-eligible':
                                                header('Content-Type: application/json; charset=utf-8');
                                                $PlainResponse = Characters::VerifyEligibility($_REQUEST['character'], $_REQUEST['service']);
                                                echo json_encode($PlainResponse, JSON_UNESCAPED_UNICODE);
                                            break;

                                            case 'referrals':
                                                if(!String::IsNull($_REQUEST['service']))
                                                {
                                                    $Service = array(
                                                        'name' => strtolower($_REQUEST['service']),
                                                        'title' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service']),
                                                        'description' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_Description'),
                                                        'history' => $Smarty->GetConfigVars('Account_Management_Service_'.$_REQUEST['service'].'_History'),
                                                        'service' => $_REQUEST['service'],
                                                        'price' => Account::GetServicePrice($_REQUEST['service'])
                                                    );
                                                    $Smarty->assign('Service', $Service);
                                                    switch($_REQUEST['service'])
                                                    {

                                                        case 'RAF':
                                                            if(!isset($_REQUEST['servicecat']))
                                                            {
                                                                echo "<pre>";
                                                                print_r($_REQUEST);
                                                            }
                                                            else
                                                            {
                                                                switch($_REQUEST['servicecat'])
                                                                {
                                                                    case 'history':

                                                                    break;

                                                                    case 'description':
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/ref_raf_help');
                                                                    break;
                                                                }
                                                            }
                                                            break;

                                                        default:
                                                            echo "<pre>";
                                                            print_r($_REQUEST);
                                                            break;
                                                    }
                                                }
                                                else
                                                    header('Location: /account/management');
                                            break;

                                            default:
                                                header('Location: /account/management');
                                            break;
                                        }
                                    }
                                    else
                                        header('Location: /account/management');
                                break;


                                case 'payment':
                                    if(String::IsNull($_REQUEST['datatype']))
                                        header('Location: /account/management');
                                    else
                                    {
                                        $AccountID = str_replace('WoW', '', $_REQUEST['accountName']);
                                        $Service = array(
                                            'name' => $_REQUEST['service'],
                                            'title' => $Smarty->GetConfigVars('Account_Management_Service_'.strtoupper($_REQUEST['service'])),
                                            'description' => $Smarty->GetConfigVars('Account_Management_Service_'.strtoupper($_REQUEST['service']).'_Description'),
                                            'service' => strtoupper($_REQUEST['service']),
                                            'price' => Account::GetServicePrice($_REQUEST['service'])
                                        );
                                        $Smarty->assign('Service', $Service);
                                        $Smarty->assign('Account', Account::GetAccountByID($AccountID));
                                        $Smarty->assign('Character', Characters::GetCharacterData($_REQUEST['character']));
                                        switch($_REQUEST['datatype'])
                                        {

                                            case 'pay':
                                                $Smarty->assign('Request', $_REQUEST);
                                                $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'paymentpage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Payment_Pay').' - ')));
                                                $Smarty->display('account/payment_pay');
                                                break;

                                            case 'complete_payment':
                                                $PaymentState = '';
                                                switch($_REQUEST['service'])
                                                {
                                                    case 'PNC':
                                                        $PaymentState = 1;
                                                    break;

                                                    case 'PCC':
                                                        $PaymentState = 8;
                                                    break;

                                                    case 'PFC':
                                                        $PaymentState = 64;
                                                    break;

                                                    case 'PRC':
                                                        $PaymentState = 128;
                                                    break;
                                                }
                                                Account::InsertPaymentDetails($User['id'], strtolower($_REQUEST['service']), $_REQUEST['price']);
                                                Account::SetBalance($User['username'], $_REQUEST['newbalance']);
                                                Characters::SetAtLoginState($_REQUEST['character'], $PaymentState);
                                                header('Location: /account/management');
                                                break;
                                        }
                                    }
                                break;

                                default:
                                    echo "<pre>";
                                    print_r($_REQUEST);
                                break;
                            }
                        }
                        else
                            header('Location: /account/management');
                    }
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

                case 'refresh-balance':
                    echo Account::GetBalance($User['username'], true);
                break;

                case 'set-account-cookie':

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

                    case 'localization':
                        //print_r(File::DirectoryContent(getcwd().DS.'Core'.DS.'Languages'.DS, Utilities::GetLanguage()));
                        if(String::IsNull($_REQUEST['lastcategory']))
                        {
                            $InstalledLanguages = File::GetSubDirectories(getcwd().DS.'Core'.DS.'Languages'.DS);
                            $Smarty->assign('InstalledLanguages', $InstalledLanguages);
                            $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Localization').' - ')));
                            $Smarty->display('admin/localization');
                        }
                        else
                        {
                            if(String::IsNull($_REQUEST['datatype']))
                            {
                                $Language = array(
                                    'LanguageName' => ucfirst($_REQUEST['lastcategory']),
                                    'LanguageSubFolder' => ucfirst($_REQUEST['lastcategory']),
                                    'LanguageLink' => $_REQUEST['lastcategory'],
                                    'SubFolderFiles' => File::DirectoryContent(getcwd().DS.'Core'.DS.'Languages'.DS, ucfirst($_REQUEST['lastcategory']))
                                );
                                $Smarty->assign('LanguageData', $Language);
                                $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => ucfirst($_REQUEST['lastcategory']).' - '.$Smarty->GetConfigVars('Administrator_Localization').' - ')));
                                $Smarty->display('admin/localization_options');
                                //echo "Total Size: ".File::DirectorySize(getcwd().DS.'Core'.DS.'Languages'.DS);
                            }
                            else
                            {
                                if(strstr($_REQUEST['datatype'], 'edit_'))
                                {
                                    $FileName = str_replace('edit_', '', $_REQUEST['datatype']);
                                    if($FileName == $_REQUEST['lastcategory'])
                                    {
                                        if(isset($_REQUEST['page']))
                                            $CurrentPage = $_REQUEST['page'];
                                        else
                                            $CurrentPage = 0;
                                        $Language = array(
                                            'LanguageName' => ucfirst($_REQUEST['lastcategory']),
                                            'LanguageSubFolder' => ucfirst($_REQUEST['lastcategory']),
                                            'LanguageLink' => $_REQUEST['lastcategory'],
                                            'FileLink' => $_REQUEST['datatype'],
                                            'FileName' => ucfirst(str_replace('edit_', '', $_REQUEST['datatype']))
                                        );
                                        $Link = getcwd().DS.'Core'.DS.'Languages'.DS.DS.ucfirst($FileName).'.language';
                                        $FileData = File::ReadFileToArray($Link, '=');
                                        $Smarty->assign('Lines', File::ArrayChunk($FileData, 10));
                                        $Smarty->assign('Pages', round(count($FileData)/10));
                                        $Smarty->assign('CurrentPage', $CurrentPage);
                                        $Smarty->assign('LanguageData', $Language);
                                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => ucfirst($_REQUEST['datatype']).'.language - '.ucfirst($_REQUEST['lastcategory']).' - '.$Smarty->GetConfigVars('Administrator_Localization').' - ')));
                                        $Smarty->display('admin/localization_edit');
                                    }
                                    else
                                    {
                                        if(isset($_REQUEST['page']))
                                            $CurrentPage = $_REQUEST['page'];
                                        else
                                            $CurrentPage = 0;
                                        $Language = array(
                                            'LanguageName' => ucfirst($_REQUEST['lastcategory']),
                                            'LanguageSubFolder' => ucfirst($_REQUEST['lastcategory']),
                                            'LanguageLink' => $_REQUEST['lastcategory'],
                                            'FileLink' => $_REQUEST['datatype'],
                                            'FileName' => ucfirst(str_replace('edit_', '', $_REQUEST['datatype']))
                                        );
                                        $Link = getcwd().DS.'Core'.DS.'Languages'.DS.ucfirst($_REQUEST['lastcategory']).DS.ucfirst($FileName).'.language';
                                        $FileData = File::ReadFileToArray($Link, '=');
                                        $Smarty->assign('Lines', File::ArrayChunk($FileData, 10));
                                        $Smarty->assign('Pages', round(count($FileData)/10));
                                        $Smarty->assign('CurrentPage', $CurrentPage);
                                        $Smarty->assign('LanguageData', $Language);
                                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => ucfirst($_REQUEST['datatype']).'.language - '.ucfirst($_REQUEST['lastcategory']).' - '.$Smarty->GetConfigVars('Administrator_Localization').' - ')));
                                        $Smarty->display('admin/localization_edit');
                                    }
                                }
                                elseif(strstr($_REQUEST['datatype'], 'update_'))
                                {
                                    echo "<pre>";
                                    $Language = array(
                                        'LanguageName' => ucfirst($_REQUEST['lastcategory']),
                                        'LanguageSubFolder' => ucfirst($_REQUEST['lastcategory']),
                                        'LanguageLink' => $_REQUEST['lastcategory'],
                                        'FileLink' => $_REQUEST['datatype'],
                                        'FileName' => ucfirst(str_replace('update_', '', $_REQUEST['datatype']))
                                    );
                                    $FileName = str_replace('update_', '', $_REQUEST['datatype']);
                                    $Link = getcwd().DS.'Core'.DS.'Languages'.DS.ucfirst($_REQUEST['lastcategory']).DS.ucfirst($FileName).'.language';
                                    $FileData = File::ReadFileToArray($Link, '=');
                                    $RemapArray = File::RemapArray($FileData, 0, 1);
                                    $ReceivedData = json_decode($_REQUEST['data']);

                                    //print_r($RemapArray);
                                    //print_r($FileData);
                                    print_r(json_decode($_REQUEST['data']));
                                }
                            }
                        }
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
                    if(isset($_REQUEST['invType']))
                    {
                        $Items = Items::GetAllItemsInSubCategoryByInventoryType($_REQUEST['classId'], $_REQUEST['subClassId'], $_REQUEST['invType'], 50);
                        if(isset($_REQUEST['page']))
                            $SelectedPage = $_REQUEST['page'];
                        else
                            $SelectedPage = 1;
                        $Smarty->assign('SelectedPage', $SelectedPage);
                        $Smarty->assign('Requests', array('class' => 1, 'subclass' => 1, 'invtype' => 1));
                        if($SelectedPage == 1)
                            $Items = Items::GetAllItemsInSubCategoryByInventoryType($_REQUEST['classId'], $_REQUEST['subClassId'], $_REQUEST['invType'], 0);
                        else
                            $Items = Items::GetAllItemsInSubCategoryByInventoryType($_REQUEST['classId'], $_REQUEST['subClassId'], $_REQUEST['invType'], ($SelectedPage*50-50));
                        $Smarty->assign('Items', $Items['items']['item_list']);
                        $Smarty->assign('PageData', $Items['items']['category_data']);
                        $Smarty->assign('ResultsFound', $Items['count']);
                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'item-index', 'pagetitle' => $Smarty->GetConfigVars('Item_Category').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                        $Smarty->display('pages/items_index');
                    }
                    else
                    {
                        $Items = Items::GetAllItemsInSubCategory($_REQUEST['classId'], $_REQUEST['subClassId'], 50);
                        if(isset($_REQUEST['page']))
                            $SelectedPage = $_REQUEST['page'];
                        else
                            $SelectedPage = 1;
                        $Smarty->assign('SelectedPage', $SelectedPage);
                        $Smarty->assign('Requests', array('class' => 1, 'subclass' => 1, 'invtype' => 0));
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
                }
                else
                {
                    if(isset($_REQUEST['page']))
                        $SelectedPage = $_REQUEST['page'];
                    else
                        $SelectedPage = 1;
                    $Smarty->assign('SelectedPage', $SelectedPage);
                    $Smarty->assign('Requests', array('class' => 1, 'subclass' => 0, 'invtype' => 0));
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
                $Smarty->assign('Requests', array('class' => 0, 'subclass' => 0, 'invtype' => 0));
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
                elseif($_REQUEST['lastcategory'] == 'test')
                {
                    $Item = Items::GetItemInfo($_REQUEST['subcategory']);
                    $Spell = Spells::SpellInfo(54870);
                    echo "<pre>";
                    print_r($Spell);
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
                            $Smarty->translate('Raids');
                            $Raids = array(
                                'Classic' => Raids::GetRaids($CharacterData['guid'], 0, false),
                                'TBC' => Raids::GetRaids($CharacterData['guid'], 1, false),
                                'WotLK' => Raids::GetRaids($CharacterData['guid'], 2, true)
                            );
                            $Professions = Characters::GetCharacterProfessions($CharacterData['guid']);
                            $Smarty->assign('PageType', $_REQUEST['lastcategory']);
                            $Smarty->assign('Specializations', Characters::GetSpecByTalents($CharacterData['guid']));
                            $Smarty->assign('Inventory', Characters::GetGearForCharacter($CharacterData['guid']));
                            $Smarty->assign('ArenaRating', Characters::GetPVPRaiting($CharacterData['guid']));
                            $Smarty->assign('Professions', $Professions);
                            $Smarty->assign('Raids', $Raids);
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
        {
            switch($_REQUEST['subcategory'])
            {
                case 'realm-status':
                    Manager::LoadExtension('Realms', $ClassConstructor);
                    $Smarty->assign('Realms', Realms::GetAllRealms());
                break;
            }
            $Smarty->display('sidebar/'.$_REQUEST['subcategory']);
        }
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

                    case 'profession':
                        Manager::LoadExtension('Professions', $ClassConstructor);
                        $Smarty->translate('Professions');
                        $Smarty->assign('Professions', Professions::GetProfessionsList());
                        $Smarty->assign('Page', Page::Info('profession', array('bodycss' => 'profession-index', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Professions').' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                        $Smarty->display('pages/game_professions');
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
                elseif($_REQUEST['subcategory'] == 'profession')
                {
                    Manager::LoadExtension('Professions', $ClassConstructor);
                    $Professions = Professions::GetProfessionsList();
                    $ExistingProfessions = String::UnsetAllBut('profession_name', $Professions, 2);
                    if(!in_array($_REQUEST['lastcategory'], $ExistingProfessions))
                        header('Location: /game/profession');
                    $Profession = Professions::GetProfession($_REQUEST['lastcategory']);
                    $Smarty->assign('Profession', $Profession);
                    $Smarty->assign('Navigation', Professions::GetNavigation($Profession['id']));
                    $Smarty->assign('Page', Page::Info('profession', array('bodycss' => 'profession-page profession-'.$_REQUEST['lastcategory'].'', 'pagetitle' => $Profession['profession_translation'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/game_profession');
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

    case 'zone':
        Manager::LoadExtension('Zones', $ClassConstructor);
        $Smarty->translate('Raids');
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $Smarty->assign('Instances', Zones::GetZonesForLandingPage());
            $Smarty->assign('Page', Page::Info('zone', array('bodycss' => 'zone-index expansion-0', 'pagetitle' => $Smarty->GetConfigVars('Zones_InstancesRaidsCMs').' - ')));
            $Smarty->display('pages/zones');
        }
        else
        {
            if(is_numeric($_REQUEST['subcategory']))
            {
                if($_REQUEST['lastcategory'] == 'tooltip')
                {
                    $ZoneInfo = Zones::GetZoneInfoByID($_REQUEST['subcategory']);
                    $Smarty->assign('Zone', $ZoneInfo);
                    $Smarty->display('blocks/zone_tooltip');
                }
                else
                    header('Location: /');
            }
            else
            {
                $ZoneInfo = Zones::GetZoneInfoByName($_REQUEST['subcategory']);
                if(String::IsNull($_REQUEST['lastcategory']))
                {
                    $ChosenLang = Utilities::BlizzardLanguageFormat(Utilities::GetLanguage(true));
                    Zones::DownloadScreenshots($ZoneInfo, $ChosenLang);
                    Zones::DownloadMap($ZoneInfo, $ChosenLang);

                    $Smarty->assign('LanguageStyle', $ChosenLang);
                    $Smarty->assign('ZoneInfo', $ZoneInfo);
                    $Smarty->assign('Page', Page::Info('zone', array('bodycss' => 'zone-'.$ZoneInfo['link_name'], 'pagetitle' => $ZoneInfo['name'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/zone_info');
                }
                else
                {
                    $BossesArray = array();
                    foreach($ZoneInfo['bosses'] as $Boss)
                        $BossesArray[] = $Boss['boss_link'];

                    $BossInfo = $ZoneInfo['bosses'][String::MASearch($ZoneInfo['bosses'], 'boss_link', $_REQUEST['lastcategory'])];

                    if(in_array($_REQUEST['lastcategory'], $BossesArray))
                    {
                        if(String::IsNull($_REQUEST['datatype']))
                        {
                            $StorageDir = str_replace('/', DS, getcwd()).DS.'Uploads'.DS.'Core'.DS.'NPC'.DS.'ModelViewer'.DS;
                            $ItemName = 'creature'.$BossInfo['entry'].'.jpg';
                            if(!File::Exists($StorageDir.$ItemName))
                                File::Download('http://media.blizzard.com/wow/renders/npcs/rotate/creature'.$BossInfo['entry'].'.jpg', $StorageDir.$ItemName);
                            $NPCInfo = Zones::GetNPCInfo($BossInfo['entry']);
                            $Smarty->assign('NPC', $NPCInfo);
                            $Smarty->assign('ZoneInfo', $ZoneInfo);
                            $Smarty->assign('BossInfo', $BossInfo);
                            $Smarty->assign('Page', Page::Info('zone', array('bodycss' => 'zone-'.$ZoneInfo['link_name'].' boss-'.$_REQUEST['lastcategory'], 'pagetitle' => $BossInfo['name'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                            $Smarty->display('pages/npc_info');
                        }
                        else
                        {
                            $NPCInfo = Zones::GetNPCInfo($BossInfo['entry']);
                            switch(str_replace('.frag', '', $_REQUEST['datatype']))
                            {
                                case 'loot':
                                    $BossLoot = array();
                                    $BossLoot['dungortenman'] = Zones::GetBossLoot($NPCInfo['lootid']);
                                    $LootAmount = count($BossLoot['dungortenman']);
                                    for($i = 1; $i <= 3; $i++)
                                    {
                                        if($NPCInfo['difficulty_entry_'.$i] != 0)
                                        {
                                            if($i == 1)
                                            {
                                                $BossLoot['dungheroicortenman'] = Zones::GetBossLoot($NPCInfo['difficulty_entry_'.$i]['lootid']);
                                                $LootAmount = $LootAmount + count($BossLoot['dungheroicortenman']);
                                            }
                                            elseif($i == 2)
                                            {
                                                $BossLoot['twentyfive'] = Zones::GetBossLoot($NPCInfo['difficulty_entry_'.$i]['lootid']);
                                                $LootAmount = $LootAmount + count($BossLoot['twentyfive']);
                                            }
                                            elseif($i == 2)
                                            {
                                                $BossLoot['twentyfiveheroic'] = Zones::GetBossLoot($NPCInfo['difficulty_entry_'.$i]['lootid']);
                                                $LootAmount = $LootAmount + count($BossLoot['twentyfiveheroic']);
                                            }
                                        }
                                    }
                                    $Smarty->assign('LootAmount', $LootAmount);
                                    $Smarty->assign('BossLoot', $BossLoot);
                                break;

                                case 'achievements':
                                    $Achievements = Zones::GetBossAchievements($NPCInfo['entry']);
                                    $Smarty->assign('AchievementsCount', count($Achievements));
                                    $Smarty->assign('Achievements', $Achievements);
                                break;
                            }
                            $Smarty->display('fragments/boss/'.str_replace('.frag', '', $_REQUEST['datatype']));
                        }
                    }
                    else
                        header('Location: /zone/'.$ZoneInfo['link_name']);
                }
            }
        }
    break;

    case 'npc':
        if(String::IsNull($_REQUEST['subcategory']))
        {
            $ErrorDescription = ErrorHandler::ListenForError(404);
            $Smarty->assign('Error', $ErrorDescription);
            $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
            $Smarty->display('pages/error_page');
        }
        else
        {
            if($_REQUEST['lastcategory'] == 'tooltip')
            {
                Manager::LoadExtension('Zones', $ClassConstructor);
                $NPCInfo = Zones::GetNPCInfo($_REQUEST['subcategory']);
                $Smarty->assign('NPC', $NPCInfo);
                $Smarty->display('blocks/npc_tooltip');
            }
            else
                header('Location: /');
        }
    break;

    default:
        header('Location: /');
        break;
}

?>