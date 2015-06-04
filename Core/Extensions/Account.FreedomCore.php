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
        $Statement = Account::$DBConnection->prepare('SELECT id, username, email, registration_date, pinned_character, access_level FROM users WHERE username = :user');
        $Statement->bindParam(':user', $Username);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
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

?>