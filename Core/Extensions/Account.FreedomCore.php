<?php

Class Account
{
    public static $DBConnection;
    public static $AuthConnection;
    public static $TM;
	public static $UserID;
	public static $Username;
	public static $AuthStatus;
    public static $VariablesArray;

    /**
     * This is a class constructor
     * @param $VariablesArray - Array with Smarty Object and all of the DB Objects
     */
    public function __construct($VariablesArray)
    {
        Account::$DBConnection = $VariablesArray[0]::$Connection;
        Account::$AuthConnection = $VariablesArray[0]::$AConnection;
        Account::$VariablesArray = $VariablesArray;
        Account::$TM = $VariablesArray[1];
    }

    /**
     * This method allows you to get user information based on his username
     *
     * @param $Username
     *
     * @return mixed
     */
    public static function Get($Username)
    {
        $Statement = Account::$DBConnection->prepare('SELECT id, username, email, registration_date, pinned_character, freedomtag_name, freedomtag_id, selected_currency, balance, access_level FROM users WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

    /**
     * This method checks whether user has access to specified Virtual Directory
     *
     * @param $Username
     * @param $AccountID
     *
     * @return bool
     */
    public static function VerifyAccountAccess($Username, $AccountID)
    {
        $Username = strtoupper($Username);
        $Statement = Account::$AuthConnection->prepare('SELECT id FROM account WHERE username = :username AND id = :id');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':id', $AccountID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['id'] == $AccountID)
            return true;
        else
            return false;
    }

    /**
     * This method is used to get user's balance
     *
     * @param      $Username
     * @param bool $BalanceJson
     *
     * @return string
     */
    public static function GetBalance($Username, $BalanceJson = false)
    {
        $Statement = Account::$DBConnection->prepare('SELECT selected_currency, balance FROM users WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!$BalanceJson)
            return $Result;
        else
            return Account::CreateBalanceJSON($Result['selected_currency'], $Result['balance']);
    }

    /**
     * This method is used to update user's balance after purchase was made
     *
     * @param $Username
     * @param $Balance
     *
     * @return bool
     */
    public static function SetBalance($Username, $Balance)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET balance = :balance WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->bindParam(':balance', $Balance);
        $Statement->execute();
        return true;
    }

    /**
     * This method is used to send activation email to user after registration
     *
     * @param $Email - Users email address
     * @param $HTMLCode - Prepared by Smarty Template Engine HTML Code
     */
    public static function SendActivationEmail($Email, $HTMLCode)
    {
        $Subject = 'Account Registration';
        $Headers = 'From: noreply@'.$_SERVER['HTTP_HOST']."\r\n";
        $Headers .= 'X-Mailer: FreedomCore Notification Service';
        $Headers .= 'MIME-Version: 1.0'."\r\n";
        $Headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
        mail($Email, $Subject, $HTMLCode, $Headers);
    }

    /**
     * This method creates temp account in users_activation table to avoid unactivated accounts in database
     *
     * @param $Username - Username
     * @param $Password - Plain Text Password
     * @param $Email - Users Email
     * @param $Code - Activation Code
     *
     * @return bool - Created/Not Created/Error Occured
     */
    public static function CreateTMPAccount($Username, $Password, $Email, $Code)
    {
        $TMPUsernameUsed = false;
        $TMPEmailUsed = false;
        $UsernameUsed = false;
        $EmailUsed = false;
        if(Account::CheckForTMPUsername($Username))
            $TMPUsernameUsed = true;
        if(Account::CheckForTMPEmail($Email))
            $TMPEmailUsed = true;
        if(Account::VerifyUsername($Username))
            $UsernameUsed = true;
        if(Account::VerifyEmail($Email))
            $EmailUsed = true;

        if(!$TMPUsernameUsed && !$TMPEmailUsed && !$UsernameUsed && !$EmailUsed)
        {
            $RegistrationDate = date( 'Y-m-d H:i:s', time());
            $HashedPassword = Account::HashPassword('sha1', $Username.':'.$Password);
            $GameHashedPassword = strtoupper(Account::HashPassword('sha1', strtoupper($Username).':'.strtoupper($Password)));
            $Statement = Account::$DBConnection->prepare('INSERT INTO users_activation (username, site_password, game_password, email, registration_date, activation_code, activated) VALUES (:username, :password, :gamepassword, :email, :registrationdate, :code, 0)');
            $Statement->bindParam(':username', $Username);
            $Statement->bindParam(':password', $HashedPassword);
            $Statement->bindParam(':gamepassword', $GameHashedPassword);
            $Statement->bindParam(':email', $Email);
            $Statement->bindParam(':registrationdate', $RegistrationDate);
            $Statement->bindParam(':code', $Code);
            if($Statement->execute())
                return true;
            else
                return false;
        }
        else
            return false;
    }

    /**
     * This method checks whether provided data is correct
     *
     * @param $Username - Username
     * @param $Email - Users Email
     * @param $Code - Activation Code
     *
     * @return bool
     */
    public static function GetActivationData($Username, $Email, $Code)
    {
        $Statement = Account::$DBConnection->prepare('SELECT * FROM users_activation WHERE username = :username AND email = :email AND activation_code = :code');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':email', $Email);
        $Statement->bindParam(':code', $Code);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['username'] == $Username)
            return $Result;
        else
            return false;
    }

    /**
     * This method creates Website Account and Game Account if data provided by user was correct
     *
     * @param $Result
     */
    public static function Activate($Result)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users_activation SET activated = 1 WHERE username = :username AND email = :email AND activation_code = :code');
        $Statement->bindParam(':username', $Result['username']);
        $Statement->bindParam(':email', $Result['email']);
        $Statement->bindParam(':code', $Result['activation_code']);
        $Statement->execute();
        Account::Create($Result['username'], $Result['site_password'], $Result['email']);
        Account::CreateGameAccount($Result['username'], $Result['game_password'], $Result['email'], $Result['registration_date']);
    }

    /**
     * This method checks whether entered username already exists in table for unactivated account
     *
     * @param $Username
     *
     * @return bool
     */
    private static function CheckForTMPUsername($Username)
    {
        $Statement = Account::$DBConnection->prepare('SELECT username FROM users_activation WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['username'] == $Username)
            return true;
        else
            return false;
    }

    /**
     * This method checks whether entered email already exists in table for unactivated account
     *
     * @param $Email
     *
     * @return bool
     */
    private static function CheckForTMPEmail($Email)
    {
        $Statement = Account::$DBConnection->prepare('SELECT email FROM users_activation WHERE email = :email');
        $Statement->bindParam(':email', $Email);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['email'] == $Email)
            return true;
        else
            return false;
    }

    /**
     * This method is used to populate users_payment_history table with latest purchase data
     *
     * @param $UserID
     * @param $ServiceID
     * @param $Price
     *
     * @return bool
     */
    public static function InsertPaymentDetails($UserID, $ServiceID, $Price, $ItemCode = null)
    {
        if($ItemCode == null)
            $ItemCode = '';
        $Status = 1;
        $Date = date('Y-m-d H:i:s', time());
        $Statement = Account::$DBConnection->prepare('INSERT INTO users_payments_history (userid, service, price, date, digital_key, status) VALUES (:uid, :service, :price, :date, :code, :status)');
        $Statement->bindParam(':uid', $UserID);
        $Statement->bindParam(':service', $ServiceID);
        $Statement->bindParam(':price', $Price);
        $Statement->bindParam(':date', $Date);
        $Statement->bindParam(':code', $ItemCode);
        $Statement->bindParam(':status', $Status);
        $Statement->execute();
        return true;
    }

    /**
     * This method allows user to change his password for Website and Game accounts
     *
     * @param $Username
     * @param $Password
     *
     * @return bool
     */
    public static function ChangePasswordForUser($Username, $Password)
    {
        $AccountPassword = Account::HashPassword('sha1', $Username.':'.$Password);
        $GameUsername = strtoupper($Username);
        $GamePassword = Account::HashPassword('sha1', strtoupper($Username).':'.strtoupper($Password));
        Account::ChangeAccountPassword($Username, $AccountPassword);
        Account::ChangeGamePassword($GameUsername, $GamePassword);
        return true;
    }

    /**
     * This method allows user to change his email for Website and Game accounts
     *
     * @param $Username
     * @param $Email
     *
     * @return bool
     */
    public static function ChangeEmailForUser($Username, $Email)
    {
        $GameUsername = strtoupper($Username);
        Account::ChangeAccountEmail($Username, $Email);
        Account::ChangeGameEmail($GameUsername, $Email);
        return true;
    }

    /**
     * Private method used by Account::ChangeEmailForUser method to change Website Account Email
     *
     * @param $Username
     * @param $Email
     *
     * @return bool
     */
    private static function ChangeAccountEmail($Username, $Email)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET email = :email WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':email', $Email);
        $Statement->execute();
        return true;
    }

    /**
     * Private method used by Account::ChangeEmailForUser method to change Game Account Email
     *
     * @param $Username
     * @param $Email
     *
     * @return bool
     */
    private static function ChangeGameEmail($Username, $Email)
    {
        $Statement = Account::$AuthConnection->prepare('UPDATE account SET email = :email WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':email', $Email);
        $Statement->execute();
        return true;
    }

    /**
     * Private method used by Account::ChangePasswordForUser method to change Website Account Password
     *
     * @param $Username
     * @param $Password
     *
     * @return bool
     */
    private static function ChangeAccountPassword($Username, $Password)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET password = :password WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $Password);
        $Statement->execute();
        return true;
    }

    /**
     * Private method used by Account::ChangePasswordForUser method to change Game Account Password
     *
     * @param $Username
     * @param $Password
     *
     * @return bool
     */
    private static function ChangeGamePassword($Username, $Password)
    {
        $Statement = Account::$AuthConnection->prepare('UPDATE account SET sha_pass_hash = :password WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $Password);
        $Statement->execute();
        return true;
    }

    /**
     * This method allows to get user payment history for User Management Panel for particular service
     *
     * @param $UserID
     * @param $ServiceID
     *
     * @return mixed
     */
    public static function GetServicePaymentHistory($UserID, $ServiceID)
    {
        $Statement = Account::$DBConnection->prepare('SELECT * FROM users_payments_history WHERE userid = :uid AND service = :service');
        $Statement->bindParam(':uid', $UserID);
        $Statement->bindParam(':service', $ServiceID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Payment)
        {
            $Result[$ArrayIndex]['status'] = Account::PaymentStatusConverter($Payment['status']);
        }

        return $Result;
    }

    /**
     * This method is used during password change process to verify old password
     *
     * @param $Username
     * @param $Password
     *
     * @return bool
     */
    public static function VerifyOldPassword($Username, $Password)
    {
        $StringToHash = $Username.':'.$Password;
        $HashedPassword = Account::HashPassword('sha1', $StringToHash);
        $Statement = Account::$DBConnection->prepare('SELECT username FROM users WHERE username = :username AND password = :password');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $HashedPassword);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['username'] == $Username)
            return true;
        else
            return false;
    }

    /**
     * This method allows to get user payment history for User Management Panel for all services
     *
     * @param $UserID
     *
     * @return mixed
     */
    public static function GetPaymentHistory($UserID)
    {
        $Statement = Account::$DBConnection->prepare('SELECT * FROM users_payments_history WHERE userid = :uid');
        $Statement->bindParam(':uid', $UserID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Payment)
        {
            $Result[$ArrayIndex]['status'] = Account::PaymentStatusConverter($Payment['status']);
            if(strlen($Payment['service']) > 3)
                $Result[$ArrayIndex]['item_data'] = Account::GetItemDataByServiceName($Payment['service']);
            $ArrayIndex++;
        }

        return $Result;
    }

    private static function GetItemDataByServiceName($ServiceName)
    {
        Manager::LoadExtension('Shop', Account::$VariablesArray);
        $ItemData = Shop::GetItemData($ServiceName);
        return $ItemData;
    }

    /**
     * This method allows to get user payment info for particular Payment ID
     *
     * @param $UserID
     * @param $PaymentID
     *
     * @return mixed
     */
    public static function GetPaymentInfo($UserID, $PaymentID)
    {
        $Statement = Account::$DBConnection->prepare('SELECT * FROM users_payments_history WHERE userid = :uid AND id = :payment');
        $Statement->bindParam(':uid', $UserID);
        $Statement->bindParam(':payment', $PaymentID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['status'] = Account::PaymentStatusConverter($Result['status']);
        if(strlen($Result['service']) > 3)
            $Result['item_data'] = Account::GetItemDataByServiceName($Result['service']);
        return $Result;
    }

    /**
     * Converts INT Status into String Status
     *
     * @param $StatusID
     *
     * @return mixed
     */
    private static function PaymentStatusConverter($StatusID)
    {
        $Statuses = array(
            0 => Account::$TM->GetConfigVars('Account_Management_Payment_Status_Queued'),
            1 => Account::$TM->GetConfigVars('Account_Management_Payment_Status_Completed'),
        );
        return $Statuses[$StatusID];
    }

    /**
     * Get user Game Account data by Account ID
     *
     * @param $AccountID
     *
     * @return mixed
     */
    public static function GetAccountByID($AccountID)
    {
        $Statement = Account::$AuthConnection->prepare('SELECT * FROM account WHERE id = :id');
        $Statement->bindParam(':id', $AccountID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['expansion_name'] = Account::ExpansionByID($Result['expansion']);
        $Result['previous_expansions'] = Account::ExpansionByID($Result['expansion'], true);
        return $Result;
    }

    /**
     * Select users accounts based on username
     *
     * @param $Username
     *
     * @return mixed
     */
    public static function GetGameAccounts($Username)
    {
        $Username = strtoupper($Username);
        $Statement = Account::$AuthConnection->prepare('SELECT * FROM account WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Account)
        {
            $Result[$ArrayIndex]['expansion_name'] = Account::ExpansionByID($Account['expansion']);
            $ArrayIndex++;
        }
        return $Result;
    }

    /**
     * This method is used to create FreedomTag
     *
     * @param $UserID
     * @param $FreedomTag
     *
     * @return array
     */
    public static function CreateFreedomTag($UserID, $FreedomTag)
    {
        $FreedomTagID = 1;
        if(!Account::IsFreedomTagFree($FreedomTag))
            $FreedomTagID = Account::GetLastFreedomTagID($FreedomTag);

        Account::UpdateUsersFreedomTag($UserID, $FreedomTag, $FreedomTagID);
        return array('tag' => $FreedomTag, 'id' => $FreedomTagID);
    }

    /**
     * Checks whether this FreedomTag already exists
     *
     * @param $FreedomTag
     *
     * @return bool
     */
    private static function IsFreedomTagFree($FreedomTag)
    {
        $Statement = Account::$DBConnection->prepare('SELECT freedomtag_name FROM users WHERE freedomtag_name = :ftname');
        $Statement->bindParam(':ftname', $FreedomTag);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Statement->rowCount() > 0)
            return false;
        else
            return true;
    }

    /**
     * If selected FreedomTag Exists, this method will get last DB id for that FreedomTag
     *
     * @param $FreedomTag
     *
     * @return mixed
     */
    private static function GetLastFreedomTagID($FreedomTag)
    {
        $Statement = Account::$DBConnection->prepare('SELECT MAX(freedomtag_id)+1 as new_id FROM users WHERE freedomtag_name = :ftname');
        $Statement->bindParam(':ftname', $FreedomTag);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result['new_id'];
    }

    /**
     * This method updates FreedomTag
     *
     * @param $UserID
     * @param $FreedomTagName
     * @param $FreedomTagID
     *
     * @return bool
     */
    private static function UpdateUsersFreedomTag($UserID, $FreedomTagName, $FreedomTagID)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET freedomtag_name = :ftname, freedomtag_id = :ftid WHERE id = :userid');
        $Statement->bindParam(':ftname', $FreedomTagName);
        $Statement->bindParam(':ftid', $FreedomTagID);
        $Statement->bindParam(':userid', $UserID);
        $Statement->execute();
        return true;
    }

    /**
     * Translates INT Expansion ID into String
     *
     * @param      $ExpansionID
     * @param bool $GetAllExpansions
     *
     * @return array
     */
    private static function ExpansionByID($ExpansionID, $GetAllExpansions = false)
    {
        $Expansions = array(
            0 => 'Classic',
            1 => 'The Burning Crusade',
            2 => 'Wrath of the Lich King',
            3 => 'Cataclysm',
            4 => 'Mists of Pandaria',
            5 => 'Warlords of Draenor',
        );
        if(!$GetAllExpansions)
            return $Expansions[$ExpansionID];
        else
            return array_reverse(array_slice($Expansions, 0, $ExpansionID));
    }

    /**
     * This method fetches Service Price from database
     *
     * @param $ServiceName
     *
     * @return mixed
     */
    public static function GetServicePrice($ServiceName)
    {
        $ServiceName = strtolower($ServiceName);
        $Statement = Account::$DBConnection->prepare('SELECT price FROM prices WHERE short_code = :service');
        $Statement->bindParam(':service', $ServiceName);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['price'];
    }

    /**
     * This method generates JSON String for Wallet Balance
     *
     * @param $SelectedCurrency
     * @param $CurrentBalance
     *
     * @return string
     */
    private static function CreateBalanceJSON($SelectedCurrency, $CurrentBalance)
    {
        $AvailableCurrencies = array('RUB', 'EUR', 'USD', 'GBP');
        $BaseCurrency = "USD";
        $BalanceArray = array();
        foreach($AvailableCurrencies as $Currency)
        {
            if($BaseCurrency == $SelectedCurrency && $Currency == $SelectedCurrency)
                $BalanceArray[$Currency] = array(
                    "currency" => $Currency,
                    "balance" => $CurrentBalance,
                    "pendingBalance" => 0,
                    "balanceQuotaUsage" => null,
                    "balanceIncludingPending" => $CurrentBalance + 0
                );
            else
            {
                $ConvertRate = new CurrencyConverter($BaseCurrency, $Currency);
                $ConvertedBalance = $ConvertRate->toForeign($CurrentBalance);
                $BalanceArray[$Currency] = array(
                    "currency" => $Currency,
                    "balance" => round($ConvertedBalance, 2),
                    "pendingBalance" => 0,
                    "balanceQuotaUsage" => null,
                    "balanceIncludingPending" => round($ConvertedBalance, 2) + 0
                );
            }
        }
        $ServerData = array(
            "serverHostname" => $_SERVER['SERVER_NAME'],
            "serverPort" => $_SERVER['SERVER_PORT'],
            "serverScheme" => 'http',
            "countryCodeAlpha3" => $SelectedCurrency
        );
        $BalanceArray = array_merge($BalanceArray, $ServerData);
        return json_encode($BalanceArray);
    }

    /**
     * This method checks whether user authorized or not
     *
     * @param $Username
     * @param $AccessRoleRequired
     *
     * @return bool
     */
    public static function IsAuthorized($Username, $AccessRoleRequired)
    {
        $ValidUser = false;
        $ValidRole = false;
        if(isset($_SESSION['username']) && $_SESSION['username'] != '')
        {
            if($_SESSION['username'] == $Username)
                $ValidUser = true;
            if($_SESSION['access_role'] == $AccessRoleRequired || $_SESSION['access_role'] > $AccessRoleRequired)
            {
                $ValidRole = true;
                $_SESSION['user_access'] = Account::VerifyAccessRole($AccessRoleRequired);
            }
        }

        if($ValidUser && $ValidRole)
            return true;
        else
            return false;
    }

    /**
     * This method is used to get User API Key for Website APIs
     *
     * @param $Username
     * @return bool
     */
    public static function GetAPIKey($Username)
    {
        $Statement = Account::$DBConnection->prepare('SELECT api_key FROM api_keys WHERE username = :username');
        $Statement->bindParam(':username', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Statement->rowCount() > 0)
            return $Result['api_key'];
        else
            return false;
    }

    public static function CreateAPIKey($Username)
    {
        $CreateAPIKey = substr( "abcdefghijklmnopqrstuvwxyz" ,mt_rand( 0 ,25 ) ,1 ) .substr( md5( time( ) ) ,1 );
        $Statement = Account::$DBConnection->prepare('INSERT INTO api_keys (username, api_key) VALUES(:username, :apikey)');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':apikey', $CreateAPIKey);
        $Statement->execute();
        return $CreateAPIKey;
    }

    /**
     * This method is used to PIN Character for user userplate if he hasn't picked on yet
     *
     * @param $Username
     * @param $CharacterGUID
     *
     * @return bool
     */
    public static function PinCharacter($Username, $CharacterGUID)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET pinned_character = :pin WHERE username = :user');
        $Statement->bindParam(':pin', $CharacterGUID);
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        return true;
    }

    /**
     * Access Roles ID => String
     *
     * @param $AccessRoleCode
     *
     * @return mixed
     */
    private static function VerifyAccessRole($AccessRoleCode)
    {
        $Roles = array(
            '0' => 'User',
            '1' => '',
            '2' => '',
            '3' => 'Administrator',
            '4' => 'System Administrator'
        );

        return $Roles[$AccessRoleCode];
    }

    /**
     * Creates User Website Account
     *
     * @param $Username
     * @param $Password
     * @param $Email
     *
     * @return int
     */
    public static function Create($Username, $Password, $Email)
    {
        $UsernameExists = false;
        $EmailExists = false;

        if(Account::VerifyUsername($Username))
            return -1;
        if(Account::VerifyEmail($Email))
            return -2;

        $RegistrationDate = date( 'Y-m-d H:i:s', time());
        $Statement = Account::$DBConnection->prepare('INSERT INTO users (username, password, email, registration_date) VALUES (:username, :password, :email, :regdate)');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $Password);
        $Statement->bindParam(':email', $Email);
        $Statement->bindParam(':regdate', $RegistrationDate);
        $Statement->execute();
        return 0;
    }

    /**
     * Creates User Game Account
     *
     * @param $Username
     * @param $Password
     * @param $Email
     * @param $RegistrationDate
     *
     * @return int
     */
    private static function CreateGameAccount($Username, $Password, $Email, $RegistrationDate)
    {
        $Username = strtoupper($Username);
        $Password = strtoupper($Password);
        $Statement = Account::$AuthConnection->prepare('INSERT INTO account (username, sha_pass_hash, email, reg_mail, joindate, expansion) VALUES (:username, :password, :email, :email, :regdate, 2)');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $Password);
        $Statement->bindParam(':email', $Email);
        $Statement->bindParam(':regdate', $RegistrationDate);
        $Statement->execute();
        return 0;
    }

    /**
     * Performs Authorization Based on Username and Password
     *
     * @param $Username
     * @param $Password
     *
     * @return bool
     */
    public static function Authorize($Username, $Password)
    {
        $StringToHash = $Username.':'.$Password;
        $HashedPassword = Account::HashPassword('sha1', $StringToHash);
        $Statement = Account::$DBConnection->prepare('SELECT id, username, access_level, pinned_character FROM users WHERE username = :user AND password = :hashedpassword');
        $Statement->bindParam('user', $Username);
        $Statement->bindParam('hashedpassword', $HashedPassword);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!is_null($Result['username']))
        {
            if($Result['pinned_character'] == null || Text::IsNull($Result['pinned_character']))
            {
                $CharID = Characters::PickRandomChar($Result['id']);
                if($CharID != false)
                    Account::PinCharacter($Result['username'], $CharID);
            }
            $_SESSION['access_role'] = $Result['access_level'];
            return true; // Successfull Athorization
        }
        else
            return false;
    }

    /**
     * Performs Authorization based on Email and Password
     *
     * @param $Email
     * @param $Password
     *
     * @return bool
     */
    public static function AuthorizeByEmail($Email, $Password)
    {
        $Statement = Account::$DBConnection->prepare('SELECT username FROM users WHERE email = :email');
        $Statement->bindParam(':email', $Email);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Statement->rowCount() > 0)
        {
            if(Account::Authorize($Result['username'], $Password))
                return $Result['username'];
            else
                return false;
        }
        else
            return false;
    }

    /**
     * Method checks if specified Email is already in use
     *
     * @param $Email
     *
     * @return bool
     */
    private static function VerifyEmail($Email)
    {
        $Statement = Account::$DBConnection->prepare('SELECT email FROM users WHERE email = :selectedemail');
        $Statement->bindParam(':selectedemail', $Email);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!is_null($Result['email']))
            return true; // Email is already in use
        else
            return false; // Email free for registration
    }

    /**
     * Method checks if specified Username is already in use
     *
     * @param $Username
     *
     * @return bool
     */
    private static function VerifyUsername($Username)
    {
        $Statement = Account::$DBConnection->prepare('SELECT username FROM users WHERE username = :selectedusername');
        $Statement->bindParam(':selectedusername', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!is_null($Result['username']))
            return true; // Username is already in use
        else
            return false; // Username free for registration
    }

    /**
     * Method creates hash based on specified hashing method and provided string
     *
     * @param $Algorithm
     * @param $String
     *
     * @return string
     */
    public static function HashPassword($Algorithm, $String)
    {
        return hash($Algorithm, $String);
    }

    /**
     * Methods checks for loaded modules required to achieve maximum performance from website
     *
     * @return array
     */
    public static function GetRequiredModulesStatus()
    {
        $RequiredModules = ['mod_rewrite', 'mod_gzip', 'mod_expires'];
        $LoadedModules = [];
        foreach($RequiredModules as $Module)
            if(in_array($Module, apache_get_modules()))
                $LoadedModules[] = ['module' => $Module, 'status' => true];
            else
                $LoadedModules[] = ['module' => $Module, 'status' => false];
        return $LoadedModules;
    }

    /**
     * This method checks for existing Armory Key for specified user
     *
     * @param $Username - Users Username
     * @param $Password - Users Password
     * @param $ArmoryKey - Personal Armory Key
     * @return bool
     */
    public static function VerifyAndroidArmoryKey($Username, $Password, $ArmoryKey)
    {
        $StringToHash = $Username.':'.$Password;
        $HashedPassword = Account::HashPassword('sha1', $StringToHash);
        $Statement = Account::$DBConnection->prepare('SELECT armory_key FROM api_android_armory WHERE username = :username AND password = :password AND armory_key = :akey');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $HashedPassword);
        $Statement->bindParam(':akey', $ArmoryKey);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return true;
    }
}

Class CurrencyConverter
{
    private $fxRate;

    public function __construct($currencyBase, $currencyForeign)
    {
        $url = 'http://download.finance.yahoo.com/d/quotes.csv?s='
            .$currencyBase .$currencyForeign .'=X&f=l1';

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_HEADER, 0);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $this->fxRate = doubleval(curl_exec($c));
        curl_close($c);
    }

    public function toBase($amount)
    {
        if($this->fxRate == 0)
            return 0;

        return  $amount / $this->fxRate;
    }

    public function toForeign($amount)
    {
        if($this->fxRate == 0)
            return 0;

        return $amount * $this->fxRate;
    }
}

?>