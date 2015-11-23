<?php
require_once('Core/Core.php');
$ClassConstructor = array($Database, $Smarty);
switch($_REQUEST['category'])
{
	case 'account':
        if(!Session::SessionStatus())
            Session::Start('FreedomCore', false);
		if(!Text::IsNull($_REQUEST['subcategory']))
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
                    Session::DestroySimpleSession();
                    //Session::Destroy(session_id());
                    //session_destroy();
                    header('Location: /');
                break;

                case 'management':
                    if($_SESSION['loggedin'] != true)
                        header('Location: /account/login');
                    $Smarty->translate('Account');
                    if(Text::IsNull($_REQUEST['lastcategory']))
                    {
                        $Smarty->assign('User', $User);
                        $Smarty->assign('Accounts', Account::GetGameAccounts($User['username']));
                        $Smarty->assign('Page', Page::Info('account_management', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management'))));
                        $Smarty->display('account/account_management');
                    }
                    else
                    {
                        if(isset($_REQUEST['accountName']))
                            $AccountID = str_replace('WoW', '', $_REQUEST['accountName']);
                        else
                            $AccountID = $User['id'];
                        $Smarty->assign('Account', Account::GetAccountByID($AccountID));
                        if(Account::VerifyAccountAccess($User['username'], $AccountID))
                        {
                            switch($_REQUEST['lastcategory'])
                            {
                                case 'dashboard':
                                    $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management'))));
                                    $Smarty->display('account/account_dashboard');
                                break;

                                case 'claim-code':
                                    Manager::LoadExtension('Soap', $ClassConstructor);
                                    if(!isset($_REQUEST['accountName']))
                                    {
                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Claim_Code').' - ')));
                                        $Smarty->display('account/claim_code');
                                    }
                                    else
                                    {
                                        if(isset($_REQUEST['errorCode']))
                                            $Smarty->assign('ErrorCode', $_REQUEST['errorCode']);
                                        $Smarty->assign('QueryData', array('account' => $_REQUEST['accountName'], 'character' => $_REQUEST['character']));
                                        $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'claimcode', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Claim_Code').' - ')));
                                        $Smarty->display('account/claim_code_step_one');
                                    }
                                break;

                                case 'claim-code-status':
                                    if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                                    {
                                        Manager::LoadExtension('Shop', $ClassConstructor);
                                        Manager::LoadExtension('Soap', $ClassConstructor);
                                        $ActivationStatus = Shop::CodeActivated($User['id'], $_REQUEST['key']);
                                        if(!$ActivationStatus)
                                            header('Location: /account/management/claim-code?accountName='.$_REQUEST['accountName'].'&character='.$_REQUEST['character'].'&errorCode=15012');
                                        else
                                            if($ActivationStatus['code_activated'] == 1)
                                                header('Location: /account/management/claim-code?accountName='.$_REQUEST['accountName'].'&character='.$_REQUEST['character'].'&errorCode=15011');
                                            else
                                            {
                                                $ItemData = Shop::GetItemData($ActivationStatus['purchased_item']);
                                                Soap::AddItemToList($ItemData['item_id'], 1);
                                                if(Soap::SendItem($_REQUEST['character'], $ItemData['item_name']))
                                                {
                                                    Shop::ChangeActivationState($User['id'], $_REQUEST['key']);
                                                    $Smarty->assign('ItemData', $ItemData);
                                                    $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'claimcode', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Claim_Code').' - ')));
                                                    $Smarty->display('account/claim_code_complete');
                                                }
                                                else
                                                    header('Location: /account/management/claim-code?accountName='.$_REQUEST['accountName'].'&character='.$_REQUEST['character'].'&errorCode=15015');
                                            }
                                    }
                                    else
                                        header('Location: /account/management/claim-code?accountName='.$_REQUEST['accountName'].'&character='.$_REQUEST['character'].'&errorCode=15010');
                                break;

                                case 'services':
                                    if(!Text::IsNull($_REQUEST['datatype']))
                                    {
                                        switch($_REQUEST['datatype'])
                                        {
                                            case 'character-services':
                                                if(!Text::IsNull($_REQUEST['service']))
                                                    switch($_REQUEST['service'])
                                                    {
                                                        case 'PCT':
                                                                echo "Not Yet Implemented!!!";
                                                            break;
                                                        case 'PCB':
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
                                                                if($_REQUEST['servicecat'] != 'history'){
                                                                    $CharacterData = Characters::GetCharacterData($_REQUEST['character']);
                                                                    $Smarty->assign('Character', $CharacterData);
                                                                }
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
                                                                        if($_REQUEST['service'] == 'PCB'){
                                                                            Manager::LoadExtension('Classes', $ClassConstructor);
                                                                            $Smarty->assign('BoostItems', Classes::getBoostClassData($CharacterData['class']));
                                                                            $Smarty->assign('ProfessionsBoost', Classes::getBoostProfessions($CharacterData['guid']));
                                                                        }
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/pcs_confirm');
                                                                        break;

                                                                    default:
                                                                        header('Location: /account/management');
                                                                        break;
                                                                }
                                                            }
                                                        break;
                                                        case 'FIR':
                                                            if(!isset($_REQUEST['servicecat'])) {
                                                                $Service = array(
                                                                    'name' => strtolower($_REQUEST['service']),
                                                                    'title' => $Smarty->GetConfigVars('Account_Management_Service_' . $_REQUEST['service']),
                                                                    'description' => $Smarty->GetConfigVars('Account_Management_Service_' . $_REQUEST['service'] . '_Description'),
                                                                    'service' => $_REQUEST['service'],
                                                                    'price' => Account::GetServicePrice($_REQUEST['service'])
                                                                );
                                                                $Smarty->assign('Service', $Service);
                                                                $Smarty->assign('Characters', Characters::GetCharacters($User['id']));
                                                                $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'servicespage', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_' . $Service['service']) . ' - ')));
                                                                $Smarty->display('account/fir');
                                                            } else {
                                                                Manager::LoadExtension('ItemsRestoration', [$Database, $Smarty]);
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
                                                                {
                                                                    $Character = Characters::GetCharacterData($_REQUEST['character']);
                                                                    $Smarty->assign('Character', $Character);
                                                                }

                                                                switch($_REQUEST['servicecat'])
                                                                {
                                                                    case 'description':
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'restoration', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/fir_description');
                                                                    break;

                                                                    case 'select-items':
                                                                        $Items = ItemsRestoration::GetCharactersDeletedItems($Character['guid']);
                                                                        $Smarty->assign('DItems', $Items);
                                                                        $Smarty->assign('Page', Page::Info('account_dashboard', array('bodycss' => 'restoration', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Service_'.$Service['service']).' - ')));
                                                                        $Smarty->display('account/fir_select');
                                                                    break;

                                                                    case 'complete':

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
                                                if(!Text::IsNull($_REQUEST['service']))
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
                                    if(Text::IsNull($_REQUEST['datatype']))
                                        header('Location: /account/management');
                                    else
                                    {
                                        $AccountID = str_replace('WoW', '', $_REQUEST['accountName']);
                                        $Account = Account::GetAccountByID($AccountID);
                                        $Character = Characters::GetCharacterData($_REQUEST['character']);
                                        $Service = array(
                                            'name' => $_REQUEST['service'],
                                            'title' => $Smarty->GetConfigVars('Account_Management_Service_'.strtoupper($_REQUEST['service'])),
                                            'description' => $Smarty->GetConfigVars('Account_Management_Service_'.strtoupper($_REQUEST['service']).'_Description'),
                                            'service' => strtoupper($_REQUEST['service']),
                                            'price' => Account::GetServicePrice($_REQUEST['service'])
                                        );
                                        $Smarty->assign('Service', $Service);
                                        $Smarty->assign('Account', $Account);
                                        $Smarty->assign('Character', $Character);
                                        switch($_REQUEST['datatype'])
                                        {

                                            case 'pay':
                                                if($_REQUEST['service'] == 'pcb'){
                                                    $Smarty->assign('specialization', $_REQUEST['specialization']);
                                                }
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

                                                    case 'PCB':
                                                        $PaymentState = 0;
                                                        Manager::LoadExtension('Classes', $ClassConstructor);
                                                        Classes::performCharacterBoost($Character);
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

                                case 'freedomtag-create':
                                    $Smarty->assign('Page', Page::Info('account_freedomtag', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_FreedomTag_PageTitle').' - ')));
                                    $Smarty->display('account/freedomtag_create');
                                break;

                                case 'freedomtag-verify':
                                    if(isset($_REQUEST['freedomTag'])){
                                        $FreedomTag = Account::CreateFreedomTag($User['id'], $_REQUEST['freedomTag']);
                                        $Smarty->assign('FreedomTag', $FreedomTag);
                                        $Smarty->assign('Page', Page::Info('account_freedomtag', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_FreedomTag_PageTitle').' - ')));
                                        $Smarty->display('account/freedomtag_verify');
                                    } else {
                                        die('This is a know issue.<br />For some reason <i>freedomTag</i> input does not get submitted while performing insert, but it does when you just print our request on page');
                                    }
                                break;

                                case 'orders':
                                    if(Text::IsNull($_REQUEST['datatype']))
                                    {
                                        $Smarty->assign('Payments', Account::GetPaymentHistory($User['id']));
                                        $Smarty->assign('Page', Page::Info('account_operations', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Orders_History').' - ')));
                                        $Smarty->display('account/account_orders_history');
                                    }
                                    else
                                    {
                                        if($_REQUEST['datatype'] == 'order-detail')
                                        {
                                            $Smarty->assign('Payment', Account::GetPaymentInfo($User['id'], $_REQUEST['orderID']));
                                            $Smarty->assign('Page', Page::Info('account_operations', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Orders_History').' - ')));
                                            $Smarty->display('account/account_orders_detail');
                                        }
                                        else
                                            header('Location: /account/management/orders');
                                    }
                                break;

                                case 'settings':
                                    if(Text::IsNull($_REQUEST['datatype']))
                                        header('Location: /account/management');
                                    else
                                    {
                                        switch($_REQUEST['datatype'])
                                        {
                                            case 'change-password':
                                                $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                                                $Smarty->assign('Page', Page::Info('account_parameters', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Change_Password').' - ')));
                                                $Smarty->display('account/account_settings_password');
                                            break;

                                            case 'verify-old-password':
                                                if(Account::VerifyOldPassword($_REQUEST['username'], $_REQUEST['oldPassword']))
                                                    echo 'true';
                                                else
                                                    echo 'false';
                                            break;

                                            case 'personal-information':
                                                $Smarty->assign('Page', Page::Info('account_parameters', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Contacts').' - ')));
                                                $Smarty->display('account/account_settings_personal_information');
                                            break;

                                            case 'modify-password':
                                                if(isset($User['username']) && isset($_REQUEST['newPassword']) && isset($_REQUEST['newPasswordVerify']) && isset($_REQUEST['csrftoken']))
                                                {
                                                    if($_REQUEST['newPassword'] == $_REQUEST['newPasswordVerify'])
                                                    {
                                                        if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                                                        {
                                                            Account::ChangePasswordForUser($User['username'], $_REQUEST['newPassword']);
                                                            session_destroy();
                                                            header('Location: /account/login');
                                                        }
                                                        header('Location: /account/management/settings/change-password');
                                                    }
                                                    else
                                                        header('Location: /account/management/settings/change-password');
                                                }
                                                else
                                                    header('Location: /account/login');
                                            break;

                                            case 'wallet':
                                                $Smarty->assign('Page', Page::Info('account_parameters', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Payment_Methods').' - ')));
                                                $Smarty->display('account/account_settings_wallet');
                                            break;

                                            case 'change-email':
                                                $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                                                $Smarty->assign('Page', Page::Info('account_parameters', array('bodycss' => '', 'pagetitle' => $Smarty->GetConfigVars('Account_Management_Change_Email').' - ')));
                                                $Smarty->display('account/account_settings_email');
                                            break;

                                            case 'modify-email':
                                                if($_REQUEST['newEmail'] == $_REQUEST['newEmailVerify'])
                                                    if(Account::VerifyOldPassword($_REQUEST['username'], $_REQUEST['password']))
                                                    {
                                                        if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                                                        {
                                                            Account::ChangeEmailForUser($_REQUEST['username'], $_REQUEST['newEmail']);
                                                            session_destroy();
                                                            header('Location: /account/login');
                                                        }
                                                        else
                                                            header('Location: /account/management/settings/change-email');
                                                    }
                                                    else
                                                        header('Location: /account/management/settings/change-email');
                                                else
                                                    header('Location: /account/management/settings/change-email');
                                            break;

                                            default:
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

                case 'activate':
                    if(isset($_REQUEST['email']) && isset($_REQUEST['username']) && isset($_REQUEST['code']))
                    {
                        $ActivationData = Account::GetActivationData($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['code']);
                        if(!$ActivationData)
                            header('Location: /');
                        else
                        {
                            Account::Activate($ActivationData);
                            header('Location: /');
                        }
                    }
                    else
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
                        if($_SESSION['generated_captcha'] == $_REQUEST['captchaInput'])
                        {
                            $ActivationCode = sha1(mt_rand(10000,99999).time().$_REQUEST['email'].$_REQUEST['username']);
                            $CreationStatus = Account::CreateTMPAccount($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email'], $ActivationCode);
                            if (!$CreationStatus)
                                header('Location: /account/create');
                            else
                            {
                                $Account = array(
                                    'username' => $_REQUEST['username'],
                                    'email' => $_REQUEST['email'],
                                    'activation_code' => $ActivationCode
                                );
                                $Smarty->translate('Account');
                                $Smarty->assign('Account', $Account);
                                $Smarty->assign('Website', $_SERVER['HTTP_HOST']);
                                $EMailTemplate = $Smarty->fetch('account/email_template.tpl');
                                Account::SendActivationEmail($_REQUEST['email'], $EMailTemplate);

                                unset($_SESSION['generated_captcha']);
                                echo "Confirmation Email Has been sent to your email";
                                header('Refresh:5; url=/', true, 303);
                            }
                        }
                        else
                            header('Location: /account/create');
                    }

                break;

				case 'captcha.jpg':
                    header("Content-Type:image/png");
                    Text::GenerateCaptcha();
				break;

				case 'performlogin':
                    if(!Text::IsNull($_REQUEST['accountName']) && !Text::IsNull($_REQUEST['password']) && !Text::IsNull($_REQUEST['persistLogin']) && !Text::IsNull($_REQUEST['csrftoken']) && !Text::IsNull($_REQUEST['captchaInput']))
                    {
                        if($_SESSION['generated_captcha'] == $_REQUEST['captchaInput'])
                        {
                            if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                            {
                                if(filter_var($_REQUEST['accountName'], FILTER_VALIDATE_EMAIL))
                                {
                                    $AuthorizeByEmail = Account::AuthorizeByEmail($_REQUEST['accountName'], $_REQUEST['password']);
                                    if($AuthorizeByEmail)
                                    {
                                        Session::UpdateSession(array('loggedin' => true, 'username' => $AuthorizeByEmail, 'remember_me' => $_REQUEST['persistLogin']));
                                        if(isset($_REQUEST['returnto']))
                                            header('Location: '.$_REQUEST['returnto']);
                                        else
                                            header('Location: /');

                                        unset($_SESSION['generated_captcha']);
                                    }
                                    else
                                    {
                                        Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                                        unset($_SESSION['generated_captcha']);
                                        header('Location: /account/login');
                                    }
                                }
                                else
                                {
                                    if(Account::Authorize($_REQUEST['accountName'], $_REQUEST['password']))
                                    {
                                        Session::UpdateSession(array('loggedin' => true, 'username' => $_REQUEST['accountName'], 'remember_me' => $_REQUEST['persistLogin']));
                                        if(isset($_REQUEST['returnto']))
                                            header('Location: '.$_REQUEST['returnto']);
                                        else
                                            header('Location: /');
                                        unset($_SESSION['generated_captcha']);
                                    }
                                    else
                                    {
                                        Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                                        unset($_SESSION['generated_captcha']);
                                        header('Location: /account/login');
                                    }
                                }
                            }
                            else
                            {
                                Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                                unset($_SESSION['generated_captcha']);
                                header('Location: /account/login');
                            }
                        }
                        else
                        {
                            unset($_SESSION['generated_captcha']);
                            header('Location: /account/login');
                        }
                    }
                    else
                    {
                        unset($_SESSION['generated_captcha']);
                        Session::UnsetKeys(array('loggedin', 'username', 'remember_me'));
                        header('Location: /account/login');
                    }
				break;
			}
		}
	break;

    case 'data':
        if(Text::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'menu.json':
                    Manager::LoadExtension('Menu', array($Database, $Smarty));
                    echo Menu::GenerateMenu();
                break;

                case 'armory':
                    switch($_REQUEST['lastcategory'])
                    {
                        case 'android':
                            if(isset($_REQUEST['username']) && isset($_REQUEST['password']) && isset($_REQUEST['downloadkey']))
                            {
                                if(Account::VerifyAndroidArmoryKey($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['downloadkey']))
                                {
                                    $ArmoryContent = [
                                        'file_version' => '1.1',
                                        'armory_server' => 'http://'.$_SERVER['HTTP_HOST'],
                                        'armory_key' => $_REQUEST['downloadkey'],
                                        'armory_user' => [
                                            'username' => $_REQUEST['username'],
                                            'password' => $_REQUEST['password'],
                                            'api_key' => Account::GetUserAPIKey($_REQUEST['username'])
                                        ]
                                    ];
                                    $ArmoryContent = json_encode($ArmoryContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                    $Length = strlen($ArmoryContent);
                                    header("Cache-Control: public");
                                    header("Content-Description: File Transfer");
                                    header("Content-Length: ".$Length.";");
                                    header("Content-Disposition: attachment; filename=armory-settings.json");
                                    header("Content-Type: application/json;");
                                    echo $ArmoryContent;
                                }
                                else
                                    echo 0;
                            }
                            else
                                echo -1;
                        break;

                        case 'verify':
                            echo "OK";
                        break;

                        case 'ios':

                        break;

                        default:
                            Page::GenerateErrorPage($Smarty, 404);
                        break;
                    }
                break;

                case 'refresh-balance':
                    echo Account::GetBalance($User['username'], true);
                break;

                case 'set-account-cookie':

                break;

                case 'ifandrename':
                    $FolderNewName = md5(uniqid(rand(), true));

                    $OldFolder = getcwd().DS.'Install';
                    $NewFolder = getcwd().DS.$FolderNewName;

                    rename($OldFolder, $NewFolder);

                    header('Location: /');
                break;
            }
        }
    break;

    case 'admin':
        $Smarty->translate('Account');
        $Smarty->translate('Administrator');
        if(Text::IsNull($_REQUEST['subcategory']))
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
                        Manager::LoadExtension('Shop', $ClassConstructor);
                        $Smarty->assign('ShopData', Shop::GetAdministratorShopData());
                        $Smarty->assign('ModulesStats', Account::GetRequiredModulesStatus());
                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Page_Title').' - ')));
                        $Smarty->display('admin/dashboard');
                    break;

                    case 'dashboard_old':
                        $Smarty->assign('ModulesStats', Account::GetRequiredModulesStatus());
                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Page_Title').' - ')));
                        $Smarty->display('admin/dashboard_old');
                        break;

                    case 'articles':
                        Manager::LoadExtension('News', $ClassConstructor);
                        if(!Text::IsNull($_REQUEST['lastcategory'])){
                            switch($_REQUEST['lastcategory']){

                                case 'add':
                                    $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Articles_Add').' - ')));
                                    $Smarty->display('admin/create_article');
                                break;

                                case 'create-article':
                                    if(Text::IsRequestSet($_REQUEST, ['imageName', 'postCommand_detail', 'subject'])){
                                        News::CreateArticle($_REQUEST);
                                        header('Location: /admin/dashboard');
                                    } else {
                                        header('Location: /admin/dashboard');
                                    }
                                break;

                                case 'tmp_image':
                                    if(is_array($_FILES)) {
                                        if (is_uploaded_file($_FILES['file_upload']['tmp_name'])) {
                                            $name = $_FILES['file_upload']['name'];
                                            $sourcePath = $_FILES['file_upload']['tmp_name'];
                                            $ImageData = Image::CreateSlideShowImage(Image::MoveUploadedImage($sourcePath, $name));
                                            echo json_encode($ImageData);
                                        }
                                    }
                                break;
                            }
                        } else {
                            header('Location: /admin/dashboard');
                        }
                    break;

                    case 'shop':
                        Manager::LoadExtension('Shop', $ClassConstructor);
                        $Smarty->translate('Shop');
                        if(Text::IsNull($_REQUEST['lastcategory'])){
                            $Smarty->assign('ShopData', Shop::GetAdministratorShopData());
                            $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop').' - ')));
                            $Smarty->display('admin/shop');
                        } else {
                            switch($_REQUEST['lastcategory']){

                                case 'add-item':
                                    if(Text::IsNull($_REQUEST['datatype'])){
                                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop_AddItem').' - '.$Smarty->GetConfigVars('Administrator_Shop').' - ')));
                                        $Smarty->display('admin/shop_additem');
                                    } else {
                                        switch($_REQUEST['datatype']){
                                            case 'process':
                                                Shop::AddItem($_REQUEST);
                                                header('Location: /admin/dashboard/');
                                            break;

                                            case 'get-data':
                                                $ItemData = Items::GetItemInfo($_REQUEST['itemid']);
                                                echo json_encode($ItemData);
                                            break;

                                            default:
                                                header('Location: /admin/shop/add-item');
                                            break;
                                        }
                                    }
                                break;

                                case 'delete-item':
                                    $Smarty->assign("ItemsList", Shop::GetAllItemsForAdministrator());
                                    $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop_DeleteItem').' - '.$Smarty->GetConfigVars('Administrator_Shop').' - ')));
                                    $Smarty->display('admin/shop_deleteitem');
                                break;

                                case 'edit-item':
                                    if(isset($_REQUEST['itemid'])){
                                        if(Text::IsNull($_REQUEST['itemid'])){
                                            $Smarty->assign("ItemsList", Shop::GetAllItemsForAdministrator());
                                            $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop_EditItem').' - '.$Smarty->GetConfigVars('Administrator_Shop').' - ')));
                                            $Smarty->display('admin/shop_edititem');
                                        } else {

                                            $Smarty->assign('ItemData', Shop::GetItem($_REQUEST['itemid']));
                                            $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop_EditItem').' - '.$Smarty->GetConfigVars('Administrator_Shop').' - ')));
                                            $Smarty->display('admin/shop_edititem_page');
                                        }
                                    } else {
                                        $Smarty->assign("ItemsList", Shop::GetAllItemsForAdministrator());
                                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Shop_EditItem').' - '.$Smarty->GetConfigVars('Administrator_Shop').' - ')));
                                        $Smarty->display('admin/shop_edititem');
                                    }
                                break;

                                case 'delete-item-complete':
                                    Shop::DeleteItem($_REQUEST['itemid']);
                                    header('Location: /admin/dashboard');
                                break;

                                case 'edit-item-complete':
                                    Shop::UpdateItem($_REQUEST);
                                    header('Location: /admin/shop/edit-item');
                                break;
                            }
                        }
                    break;

                    case 'security':
                        $Smarty->assign('ValidationResult', Security::VerifyFileList());
                        $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Antivirus').' - ')));
                        $Smarty->display('admin/security');
                    break;

                    case 'localization':
                        //print_r(File::DirectoryContent(getcwd().DS.'Core'.DS.'Languages'.DS, Utilities::GetLanguage()));
                        if(Text::IsNull($_REQUEST['lastcategory']))
                        {
                            $InstalledLanguages = File::GetSubDirectories(getcwd().DS.'Core'.DS.'Languages'.DS);
                            $Smarty->assign('InstalledLanguages', $InstalledLanguages);
                            $Smarty->assign('Page', Page::Info('admin', array('bodycss' => 'services-home', 'pagetitle' => $Smarty->GetConfigVars('Administrator_Localization').' - ')));
                            $Smarty->display('admin/localization');
                        }
                        else
                        {
                            if(Text::IsNull($_REQUEST['datatype']))
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
        if(!Text::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
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
        $ItemsCache = new Cache("Items");
        if(Text::IsNull($_REQUEST['subcategory']))
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
            if(Text::IsNull($_REQUEST['lastcategory']))
            {
                $ItemsCache->prepareCache($_REQUEST['subcategory']);
                $isFound = false;

                if($ItemsCache->isCacheExists()){
                    $isFound = true;
                    $Cache = $ItemsCache->readCache($_REQUEST['subcategory']);
                    $Item = $Cache['item'];
                    $ItemRelation = $Cache['relation'];
                } else {
                    $Item = Items::GetItemInfo($_REQUEST['subcategory']);
                    if(!$Item){
                        $isFound = false;
                    } else {
                        $isFound = true;
                        $ItemRelation = Items::GetItemRelatedInfo($_REQUEST['subcategory']);
                        $ItemsCache->prepareCache($_REQUEST['subcategory'], ['item' => $Item, 'relation' => $ItemRelation]);
                        $ItemsCache->saveCache();
                    }
                }

                if(!$isFound)
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
                    if($Item){
                        $Smarty->assign('Item', $Item);
                        $Smarty->display('blocks/item_tooltip');
                    } else {
                        echo "Not Found!";
                    }
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
        if(Text::IsNull($_REQUEST['subcategory']))
            Page::GenerateErrorPage($Smarty, 404);
        else
        {
            if(Characters::CheckCharacter($_REQUEST['subcategory']))
            {
                if(!Text::IsNull($_REQUEST['lastcategory']))
                {
                    $CharacterData = Characters::GetCharacterData($_REQUEST['subcategory']);
                    $Smarty->assign('Character', $CharacterData);
                    $Smarty->assign('SelectedCategory', $_REQUEST['lastcategory']);
                    switch($_REQUEST['lastcategory'])
                    {
                        case 'achievement':
                            if(Text::IsNull($_REQUEST['datatype']))
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
                                ob_end_flush();
                                Manager::LoadExtension('Achievements', $ClassConstructor);
                                $AllCategorues = Achievements::GetCategories();
                                $AInCat = Achievements::GetAchievementsInCategory($_REQUEST['datatype']);
                                $CompletedAchievements = Characters::GetCompletedAchievements($CharacterData['guid']);
                                $WorkingWith = $AllCategorues[Text::MASearch($AllCategorues, 'id', $_REQUEST['datatype'])];
                                $CA = array();
                                foreach($CompletedAchievements as $Achievement)
                                    if($Achievement['category'] == $WorkingWith['id'])
                                        $CA[] = $Achievement['achievement'];

                                foreach($AInCat as $Key=>$Value)
                                    foreach($CA as $CompA)
                                        if($Value['id'] == $CompA)
                                            unset($AInCat[$Key]);
                                $Smarty->assign('IncompleteAchievements', $AInCat);
                                $Smarty->assign('Categories', $AllCategorues);
                                $Smarty->assign('CompletedAchievements', $CompletedAchievements);
                                $Smarty->assign('Category', $_REQUEST['datatype']);
                                $Smarty->assign('WorkingWith', $WorkingWith);
                                $Smarty->display('blocks/achievements_category');
                                ob_start();
                            }
                        break;

                        case 'events':
                            Manager::LoadExtension('Events', $ClassConstructor);
                            $Smarty->assign('Events', Events::ClosestEvents());
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'events_page', 'pagetitle' => $Smarty->GetConfigVars('Game_Events').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                            $Smarty->display('pages/game_events');
                        break;

                        case 'reputation':
                            $Smarty->assign('Reputations', Characters::GetReputation($CharacterData['guid']));
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'reputation_page', 'pagetitle' => $Smarty->GetConfigVars('Profile_Character_Reputation').' - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                            $Smarty->display('pages/character_reputation');
                        break;

                        case 'profession':
                            $Professions = Characters::GetCharacterProfessions($CharacterData['guid']);
                            if(Text::IsNull($_REQUEST['datatype']))
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
                                    header('Location: //'.$_SERVER[HTTP_HOST].str_replace('//', '/',$_SERVER['REQUEST_URI'].$RedirectTo));
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
                                    $ProfessionInfo = $Professions[Text::MASearch($Professions, 'name', $_REQUEST['datatype'])];
                                    $AllRecipes = Characters::GetRecipesForProfession($ProfessionInfo['id']);
                                    $LearnedRecipes = Characters::GetLearnedRecipesForProfession($ProfessionInfo['id'], $CharacterData['guid']);
                                    $UnlearnedArray = array();
                                    foreach($AllRecipes as $All)
                                    {
                                        $Searcher = Text::MASearch($LearnedRecipes, 'spell', $All['spellID']);
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
                                    header('Location: //'.$_SERVER[HTTP_HOST].str_replace($_REQUEST['datatype'], '', $_SERVER['REQUEST_URI']));
                            }
                        break;

                        case 'tooltip':
                            $Smarty->assign('Specializations', Characters::GetSpecByTalents($CharacterData['guid']));
                            $Smarty->display('blocks/character_tooltip');
                        break;

                        case 'simple':
                            $RedirectTo = str_replace('/simple', '', str_replace('//', '/', $_SERVER['REQUEST_URI'].'/advanced'));
                            header('Location: '.$RedirectTo);
                        break;

                        case 'pvp':
                            $Smarty->assign('ArenaRating', Characters::GetPVPRaiting($CharacterData['guid']));
                            $Smarty->assign('Page', Page::Info('community', array('bodycss' => 'character-pvp', 'pagetitle' => 'PvP - '.$Smarty->GetConfigVars('Menu_Community').' - ')));
                            $Smarty->display('pages/character_pvp');
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
                            $Smarty->assign('Glyphs', Characters::GetCharacterGlyphs($CharacterData['guid']));
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
        if(Text::IsNull($_REQUEST['subcategory']))
            Page::GenerateErrorPage($Smarty, 404);
        else
        {
            if (!Text::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'test')
            {
                Text::PrettyPrint(Spells::GetSpellByID($_REQUEST['subcategory']));
                Text::PrettyPrint(Spells::SpellInfo($_REQUEST['subcategory']));
            }
            elseif (!Text::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
            {
                $Smarty->assign('Spell', Spells::SpellInfo($_REQUEST['subcategory']));
                $Smarty->display('blocks/spell_tooltip');
            }
            else
                header('Location: /');
        }
    break;

    case 'quest':
        if(Text::IsNull($_REQUEST['subcategory']))
            Page::GenerateErrorPage($Smarty, 404);
        else
        {
            if (!Text::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
            {
                $Smarty->assign('Quest', Items::QuestInfo($_REQUEST['subcategory']));
                $Smarty->display('blocks/quest_tooltip');
            }
            else
                header('Location: /');
        }
    break;

    case 'guild':
        if(Text::IsNull($_REQUEST['subcategory']))
            Page::GenerateErrorPage($Smarty, 404);
        else
        {
            if(Characters::CheckGuild($_REQUEST['subcategory']))
            {
                if (!Text::IsNull($_REQUEST['lastcategory']) && $_REQUEST['lastcategory'] == 'tooltip')
                {
                    $Smarty->assign('Guild', Characters::GetGuildData($_REQUEST['subcategory']));
                    $Smarty->display('blocks/guild_tooltip');
                }
                else
                {
                    Manager::LoadExtension('Guild', $ClassConstructor);
                    if(Text::IsNull($_REQUEST['lastcategory']))
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
                Page::GenerateErrorPage($Smarty, 404);
        }
    break;

    case 'sidebar':
        if(Text::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'realm-status':
                    Manager::LoadExtension('Realms', $ClassConstructor);
                    $Smarty->assign('Realms', Realms::GetAllRealms());
                break;

                case 'events':
                    Manager::LoadExtension('Events', $ClassConstructor);
                    $Smarty->assign('Events', Events::CurrentEvents());
                break;

                case 'debugger':
                    $GitHead = getcwd().DS.'.git'.DS.'FETCH_HEAD';
                    if(file_exists($GitHead))
                    {
                        $LocalVersion = file_get_contents(getcwd().DS.'.git'.DS.'FETCH_HEAD');
                        list($LocalVersion, $ServiceInfo) = explode('branch', $LocalVersion);
                    }
                    else
                        $LocalVersion = 'unknown';
                    $Smarty->assign('FreedomNetRevision', $LocalVersion);
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

        if(Text::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            switch($_REQUEST['subcategory'])
            {
                case 'dynamic':
                    if(Text::IsNull($_REQUEST['lastcategory']))
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
                    if(Text::IsNull($_REQUEST['lastcategory']))
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
		if(Text::IsNull($_REQUEST['subcategory']))
		{
			$Smarty->assign('Page', Page::Info('game', array('bodycss' => 'game-index', 'pagetitle' => $Smarty->GetConfigVars('Menu_Game').' - ')));
			$Smarty->display('game');
		}
		elseif(!Text::IsNull($_REQUEST['subcategory']))
		{
			if(Text::IsNull($_REQUEST['lastcategory']))
			{
				switch($_REQUEST['subcategory'])
				{

                    case 'events':
                        Manager::LoadExtension('Events', $ClassConstructor);
                        $Smarty->translate('Events');
                        $Events = Events::getEvents();
                        $Smarty->assign('CurrentEvent', Events::getCurrentEvent($Events));
                        $Smarty->assign('Events', Events::sortByDate($Events));
                        $Smarty->assign('Page', Page::Info('game', array('bodycss' => 'page view-page', 'pagetitle' => $Smarty->GetConfigVars('Events_Page_Title').' - ')));
                        $Smarty->display('pages/game_events');
                    break;

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
                    $ExistingRaces = Text::UnsetAllBut('race_link', $Races, 2);
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
                    $ExistingClasses = Text::UnsetAllBut('class_name', $Classes, 2);
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
                    $ExistingProfessions = Text::UnsetAllBut('profession_name', $Professions, 2);
                    if(!in_array($_REQUEST['lastcategory'], $ExistingProfessions))
                        header('Location: /game/profession');
                    $Profession = Professions::GetProfession($_REQUEST['lastcategory']);
                    $Smarty->assign('Profession', $Profession);
                    $Smarty->assign('Navigation', Professions::GetNavigation($Profession['id']));
                    $Smarty->assign('Page', Page::Info('profession', array('bodycss' => 'profession-page profession-'.$_REQUEST['lastcategory'].'', 'pagetitle' => $Profession['profession_translation'].' - '.$Smarty->GetConfigVars('Menu_Game').' - ')));
                    $Smarty->display('pages/game_profession');
                }
                elseif($_REQUEST['subcategory'] == 'events'){
                    Manager::LoadExtension('Events', $ClassConstructor);
                    $Smarty->translate('Events');
                    $EventName = $_REQUEST['lastcategory'];
                    $EventData = Events::getEventData($EventName);
                    if(!$EventData){
                        header('Location: /game/events');
                    } else {
                        $Events = Events::getEvents();
                        $PageEventData = [];
                        foreach($Events as $Event){
                            if(isset($Event['description'])){
                                $EventName = str_replace('\'', '', $Event['description']);
                                if(trim($EventData['name']) == trim($EventName))
                                    $PageEventData = $Event;
                            }
                        }
                        $Smarty->assign('DData', $PageEventData);
                        $Smarty->assign('Event', $EventData);
                        $Smarty->assign('Page', Page::Info('game', array('bodycss' => 'page view-page', 'pagetitle' => $EventData['name'].' - ')));
                        $Smarty->display('pages/game_event_data');
                    }
                }
			}
		}
	break;

    case 'blog':
        if(Text::IsNull($_REQUEST['subcategory']))
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
		if(Text::IsNull($_REQUEST['subcategory']))
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
        if(Text::IsNull($_REQUEST['subcategory']))
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
                    if(Text::IsNull($_REQUEST['lastcategory']))
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
                                if(!isset($_REQUEST['replyCommentId']))
                                    Items::AddComment($SearchFor, $SelectedCharacterForComments['name'], $_REQUEST['detail'], $Language);
                                else
                                    Items::AddReply($SearchFor, $SelectedCharacterForComments['name'], $_REQUEST['detail'], $Language, $_REQUEST['replyCommentId']);
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
                    if(Text::IsNull($_REQUEST['lastcategory']))
                        header('Location: /');
                    else
                    {
                        switch($_REQUEST['lastcategory'])
                        {
                            case 'load.json':
                                $CommentsInfo = array(
                                    'article_id' => $SearchFor,
                                    'base' => $_REQUEST['base'],
                                    'page' => $_REQUEST['page']
                                );
                                $Smarty->assign('Comments', News::GetComments($SearchFor));
                                $Smarty->display('blog/comments_load');
                                break;

                            case 'comment.json':
                                $AvailableLanguages = array('ru', 'it', 'pt', 'kr', 'de', 'es', 'fr', 'en');
                                $RemoveBlogInfo = str_replace('blog.', '', $_REQUEST['subcategory']);
                                $ArticleID = str_replace(substr($RemoveBlogInfo, 0, 3), '', $RemoveBlogInfo);
                                if(!isset($_REQUEST['replyCommentId']))
                                    News::AddComment($ArticleID, $SelectedCharacterForComments['name'], $_REQUEST['detail']);
                                else
                                    News::AddReply($ArticleID, $SelectedCharacterForComments['name'], $_REQUEST['detail'], $_REQUEST['replyCommentId']);
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
        Manager::LoadExtension('Media', $ClassConstructor);
        $Smarty->translate('Media');
        switch($_REQUEST['subcategory']){
            case 'videos':
                $TypeID = Media::getMediaTypeByName($_REQUEST['subcategory']);
                $Data = Media::getMediaRecord($_REQUEST['lastcategory'], $TypeID);
                $Smarty->assign('MediaData', $Data);
                $Smarty->assign('MediaVideos', Media::getMedia(1));
                Page::GeneratePage($Smarty, 'media', null, $Smarty->variable('Media_Videos'), 'pages/media_videos');
            break;

            case 'screenshots':

            break;

            case 'music':

            break;

            case 'wallpapers':

            break;

            default:
                $Smarty->assign('MediaData', Media::getAll());
                Page::GeneratePage($Smarty, 'media', null, $Smarty->variable('Menu_Media'), 'pages/media_index');
            break;
        }
	break;

    case 'shop':
        $Smarty->translate('Shop');
        Manager::LoadExtension('Shop', $ClassConstructor);
        if(Text::IsNull($_REQUEST['subcategory']))
        {
            if(isset($_REQUEST['categories']))
                $Smarty->assign('DisplayCategory', $_REQUEST['categories']);

            $Smarty->assign('SidebarItems', Shop::GetSidebar());
            $Smarty->assign('Page', Page::Info('shop', array('bodycss' => 'browse-template product-family-wow', 'pagetitle' => $Smarty->GetConfigVars('Menu_Shop').' - ')));
            $Smarty->display('shop');
        } elseif(!Text::IsNull($_REQUEST['subcategory']) && $_REQUEST['subcategory'] == 'payment') {
            if(Text::IsNull($_REQUEST['lastcategory'])){

            } else {
                switch($_REQUEST['lastcategory']){
                    case 'success':

                    break;

                    case 'failed':

                    break;

                    case 'canceled':

                    break;
                }
            }
        }
        else
        {
            $Category = explode('-', $_REQUEST['subcategory'])[0];
            if(isset($_REQUEST['subcategory']))
            {
                $WhichItem = str_replace('item-', '',
                    str_replace('complete-', '',
                        str_replace('pay-', '',
                            str_replace('buy-', '',
                                str_replace('pet-', '',
                                    str_replace('mount-', '', $_REQUEST['subcategory'])
                                )
                            )
                        )
                    )
                );
                $ItemData = Shop::GetItemData($WhichItem);
                $Smarty->assign('ItemData', $ItemData);
            }
            switch($Category)
            {
                case 'mount':
                    $Smarty->assign('Page', Page::Info('shop-mount', array('bodycss' => 'product-template video-enabled product-family-wow', 'pagetitle' => $ItemData['item_name'].' - ')));
                    $Smarty->display('shop/mount');
                break;

                case 'item':
                    $Smarty->assign('Page', Page::Info('shop-item', array('bodycss' => 'product-template video-enabled product-family-wow', 'pagetitle' => $ItemData['item_name'].' - ')));
                    $Smarty->display('shop/item');
                break;

                case 'buy':
                    if(Account::IsAuthorized($_SESSION['username'], 0))
                    {
                        $Smarty->assign('PurchaseCompleted', false);
                        $Smarty->assign('Accounts', Account::GetGameAccounts($_SESSION['username']));
                        $Smarty->assign('Page', Page::Info('shop-buy', array('bodycss' => 'product-template video-enabled product-family-wow', 'pagetitle' => $Smarty->GetConfigVars('Menu_Shop').' - ')));
                        $Smarty->display('shop/buy');
                    }
                    else
                        header('Location: /account/login');
                break;

                case 'pay':
                    if(Account::IsAuthorized($_SESSION['username'], 0))
                    {
                        $Smarty->assign('BuyingFor', $_REQUEST['gameAccountIds']);
                        $Smarty->assign('PurchaseCompleted', false);
                        $Smarty->assign('Page', Page::Info('shop-buy', array('bodycss' => 'product-template video-enabled product-family-wow', 'pagetitle' => $Smarty->GetConfigVars('Menu_Shop').' - ')));
                        $Smarty->display('shop/pay');
                    }
                    else
                        header('Location: /account/login');
                break;

                case 'complete':
                    if(Account::IsAuthorized($_SESSION['username'], 0))
                    {
                        if($User['balance'] >= $ItemData['price'])
                            $Smarty->assign('PurchaseCompleted', true);
                        else
                            header('Location: /shop/');
                        $ActivationCode = Shop::GenerateItemCode();
                        $Account = array(
                            'id' => $_REQUEST['gameAccountIds'],
                            'username' => $User['username'],
                            'email' => $User['email'],
                            'activation_code' => $ActivationCode
                        );
                        $NewBalance = $User['balance'] - $ItemData['price'];
                        Account::SetBalance($User['username'], $NewBalance);
                        $Smarty->assign('Website', $_SERVER['HTTP_HOST']);
                        $Smarty->assign('Account', $Account);
                        Shop::InsertPurchaseData($ItemData['short_code'], $_REQUEST['gameAccountIds'], $ActivationCode);
                        Account::InsertPaymentDetails($User['id'], $ItemData['short_code'], $ItemData['price'], $ActivationCode);
                        $Smarty->assign('ActivationCode', $ActivationCode);
                        $EMailTemplate = $Smarty->fetch('shop/email_template.tpl');
                        Shop::SendCodeEmail($User['email'], $EMailTemplate);
                        $Smarty->assign('BuyingFor', $_REQUEST['gameAccountIds']);
                        $Smarty->assign('Page', Page::Info('shop-buy', array('bodycss' => 'product-template video-enabled product-family-wow', 'pagetitle' => $Smarty->GetConfigVars('Menu_Shop').' - ')));
                        $Smarty->display('shop/complete');

                    }
                    else
                        header('Location: /account/login');
                break;

                case 'pet':
                    Text::PrettyPrint($_REQUEST);
                break;

                default:
                    header('Location: /shop');
                break;
            }
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
        if(Text::IsNull($_REQUEST['subcategory']))
            header('Location: /');
        else
        {
            $AvailableLanguages = array('ru', 'it', 'pt', 'kr', 'de', 'es', 'fr', 'en');
            if(in_array($_REQUEST['subcategory'], $AvailableLanguages))
            {
                $_SESSION['preferredlanguage'] = $_REQUEST['subcategory'];
                Session::UpdateSession($_SESSION);
                header('Location: '.$_SERVER['HTTP_REFERER']);
            }
            else
                header('Location: '.$_SERVER['HTTP_REFERER']);
        }
        break;

    case 'error':
        if(Text::IsNull($_REQUEST['category']))
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
        if(Text::IsNull($_REQUEST['subcategory']))
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
                if(Text::IsNull($_REQUEST['lastcategory']))
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

                    $BossInfo = $ZoneInfo['bosses'][Text::MASearch($ZoneInfo['bosses'], 'boss_link', $_REQUEST['lastcategory'])];

                    if(in_array($_REQUEST['lastcategory'], $BossesArray))
                    {
                        if(Text::IsNull($_REQUEST['datatype']))
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
        if(Text::IsNull($_REQUEST['subcategory']))
            Page::GenerateErrorPage($Smarty, 404);
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

    case 'forum':
        $Smarty->translate('Forums');
        Manager::LoadExtension('Forums', $ClassConstructor);
        if(Text::IsNull($_REQUEST['subcategory']))
        {
            $Smarty->assign('Forums', Forums::GetForums());
            $Smarty->assign('Page', Page::Info('forum', array('bodycss' => 'forums forums-home', 'pagetitle' => $Smarty->GetConfigVars('Forum_Page_Title').' - ')));
            $Smarty->display('pages/forums_list_categories');
        }
        else
        {
            if(is_numeric($_REQUEST['subcategory']))
            {
                if(Forums::CheckForumExistance($_REQUEST['subcategory']))
                {
                    if(Text::IsNull($_REQUEST['lastcategory']))
                    {
                        $Topics = Forums::GetTopics($_REQUEST['subcategory']);
                        if(Text::Match($Topics['topics'][0]['id'], ''))
                            $Topics['topics'] = array();
                        $Smarty->assign('Forum', $Topics);
                        $Smarty->assign('Page', Page::Info('forum', array('bodycss' => 'forums view-forum', 'pagetitle' => $Topics['forum_name'].' - ')));
                        $Smarty->display('pages/forums_list_topics');
                    }
                    else
                        if(Text::IsNull($_REQUEST['datatype']))
                        {
                            if(Text::Match($_REQUEST['lastcategory'], 'topic'))
                            {
                                $Topics = Forums::GetTopics($_REQUEST['subcategory']);
                                if(Text::Match($Topics['topics'][0]['id'], ''))
                                    $Topics['topics'] = array();
                                $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                                $Smarty->assign('Forum', $Topics);
                                $Smarty->assign('Page', Page::Info('forum', array('bodycss' => 'forums view-topic create-topic logged-in', 'pagetitle' => $Smarty->GetConfigVars('Forum_Create_New_Topic').' - '.$Smarty->GetConfigVars('Forum_Page_Title').' - ')));
                                $Smarty->display('forum/create_topic');
                            }
                            else
                                Page::GenerateErrorPage($Smarty, 404);
                        }
                        else
                        {
                            if(Text::Match($_REQUEST['datatype'], 'post'))
                            {
                                if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                                {
                                   $TopicID = Forums::CreateTopic($_REQUEST['subcategory'], $SelectedCharacterForComments['name'], $_REQUEST['subject'], $_REQUEST['postCommand_detail']);
                                    header('Location: /forum/topic/'.$TopicID);
                                }
                                else
                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                            }
                            else
                                Page::GenerateErrorPage($Smarty, 404);
                        }
                }
                else
                    Page::GenerateErrorPage($Smarty, 404);
            }
            else
                if(Text::Match($_REQUEST['subcategory'], 'topic'))
                {
                    if(Text::IsNull($_REQUEST['datatype']))
                    {
                        $TopicData = Forums::GetTopicData($_REQUEST['lastcategory']);
                        Forums::UpdateTopicViews($TopicData['category']['id'], $TopicData['topic']['id']);
                        $Smarty->assign('CSRFToken', Session::GenerateCSRFToken());
                        $Smarty->assign('TopicData', $TopicData);
                        $Smarty->assign('Page', Page::Info('forum', array('bodycss' => 'forums view-topic logged-in', 'pagetitle' => $TopicData['topic']['name'].' - ')));
                        $Smarty->display('pages/forums_view_topic');
                    }
                    else
                    {
                        switch($_REQUEST['datatype'])
                        {
                            case 'post':
                                if(Session::ValidateCSRFToken($_REQUEST['csrftoken']))
                                {
                                    Text::Request();
                                }
                                break;

                            case 'up':
                                    Text::Request();
                                break;

                            case 'report':
                                    Text::Request();
                                break;
                        }
                    }
                }
                elseif(Text::Match($_REQUEST['subcategory'], 'quote'))
                    echo Forums::QuotePost($_REQUEST['forumID'], $_REQUEST['topicID'], $_REQUEST['postID']);
                else
                    Page::GenerateErrorPage($Smarty, 404);
        }
    break;

    default:
        header('Location: /');
        break;
}

?>