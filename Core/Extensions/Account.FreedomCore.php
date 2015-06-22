<?php

Class Account
{
    public static $DBConnection;
    public static $AuthConnection;
    public static $TM;
	public static $UserID;
	public static $Username;
	public static $AuthStatus;


    public function __construct($VariablesArray)
    {
        Account::$DBConnection = $VariablesArray[0]::$Connection;
        Account::$AuthConnection = $VariablesArray[0]::$AConnection;
        Account::$TM = $VariablesArray[1];
    }

    public static function Get($Username)
    {
        $Statement = Account::$DBConnection->prepare('SELECT id, username, email, registration_date, pinned_character, freedomtag_name, freedomtag_id, selected_currency, balance, access_level FROM users WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

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

    public static function SetBalance($Username, $Balance)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET balance = :balance WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->bindParam(':balance', $Balance);
        $Statement->execute();
        return true;
    }

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

    public static function GetServicePrice($ServiceName)
    {
        $ServiceName = strtolower($ServiceName);
        $Statement = Account::$DBConnection->prepare('SELECT price FROM prices WHERE short_code = :service');
        $Statement->bindParam(':service', $ServiceName);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['price'];
    }

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

    public static function IsAuthorized($Username, $AccessRoleRequired)
    {
        $ValidUser = false;
        $ValidRole = false;
        if($_SESSION['username'] == $Username)
            $ValidUser = true;
        if($_SESSION['access_role'] == $AccessRoleRequired || $_SESSION['access_role'] > $AccessRoleRequired)
        {
            $ValidRole = true;
            $_SESSION['user_access'] = Account::VerifyAccessRole($AccessRoleRequired);
        }

        if($ValidUser && $ValidRole)
            return true;
        else
            return false;
    }

    public static function PinCharacter($Username, $CharacterGUID)
    {
        $Statement = Account::$DBConnection->prepare('UPDATE users SET pinned_character = :pin WHERE username = :user');
        $Statement->bindParam(':pin', $CharacterGUID);
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        return true;
    }

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

    public static function Create($Username, $Password, $Email)
    {
        $UsernameExists = false;
        $EmailExists = false;

        if(Account::VerifyUsername($Username))
            return -1;
        if(Account::VerifyEmail($Email))
            return -2;

        $RegistrationDate = date( 'Y-m-d H:i:s', time());
        $HashedPassword = Account::HashPassword('sha1', $Username.':'.$Password);
        $Statement = Account::$DBConnection->prepare('INSERT INTO users (username, password, email, registration_date) VALUES (:username, :password, :email, :regdate)');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $HashedPassword);
        $Statement->bindParam(':email', $Email);
        $Statement->bindParam(':regdate', $RegistrationDate);
        $Statement->execute();
        Account::CreateGameAccount($Username, $Password, $Email, $RegistrationDate);
        return 0;
    }

    private static function CreateGameAccount($Username, $Password, $Email, $RegistrationDate)
    {
        $HashedPassword = Account::HashPassword('sha1', strtoupper($Username).':'.strtoupper($Password));
        $Statement = Account::$AuthConnection->prepare('INSERT INTO account (username, sha_pass_hash, email, reg_mail, joindate, expansion) VALUES (:username, :password, :email, :email, :regdate, 3)');
        $Statement->bindParam(':username', strtoupper($Username));
        $Statement->bindParam(':password', strtoupper($HashedPassword));
        $Statement->bindParam(':email', $Email);
        $Statement->bindParam(':regdate', $RegistrationDate);
        $Statement->execute();
        return 0;
    }

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
            if($Result['pinned_character'] == null || String::IsNull($Result['pinned_character']))
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

    private static function HashPassword($Algorithm, $String)
    {
        return hash($Algorithm, $String);
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