<?php

Class Session
{

	public static $DBConnection;
    private static $SessionCreated = false;
    private static $Salt;

	public function __construct($Database)
	{
		session_set_save_handler(array($this, 'Open'), array($this, 'Close'), array($this, 'Read'), array($this, 'Write'), array($this, 'Destroy'), array($this, 'GarbageCollector'));
		register_shutdown_function('session_write_close');
		Session::$DBConnection = $Database::$Connection;
        $FileLocation = getcwd().DS.'Core'.DS.'Libraries'.DS.'FreedomCore';
        $Files = scandir($FileLocation);
        for($i = 0; $i < 4; $i++)
            unset($Files[$i]);
        if(!empty($Files))
        {
            Session::$Salt = file_get_contents($FileLocation.DS.$Files[4]);
        }
        else
        {
            $RandomFileName = substr( "abcdefghijklmnopqrstuvwxyz" ,mt_rand( 0 ,25 ) ,1 ) .substr( md5( time( ) ) ,1 );
            $RandomSalt = Session::GenerateRandomSalt();
            file_put_contents($FileLocation.DS.$RandomFileName, $RandomSalt);
            Session::$Salt = $RandomSalt;
        }
	}


	public static function UpdateSession($Data)
	{
        if (session_status() != PHP_SESSION_NONE)
            foreach($Data as $key=>$value)
                $_SESSION[$key] = $value;
	}

    public static function SessionStatus()
    {
        if(session_status() == PHP_SESSION_NONE)
            return false;
        else
            return true;
    }

    private static function GenerateRandomSalt()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789*+|/-';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $charactersLength; $i++)
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        return $randomString;
    }

	public static function GenerateCSRFToken()
	{
		Text::PrettyPrint('');
		$InitialString = "abcdefghijklmnopqrstuvwxyz1234567890";
		$PartOne = substr(str_shuffle($InitialString),0,8);
		$PartTwo = substr(str_shuffle($InitialString),0,4);
		$PartThree = substr(str_shuffle($InitialString),0,4);
		$PartFour = substr(str_shuffle($InitialString),0,4);
		$PartFive = substr(str_shuffle($InitialString),0,12);
		$FinalCode = $PartOne.'-'.$PartTwo.'-'.$PartThree.'-'.$PartFour.'-'.$PartFive;
		$_SESSION['generated_csrf'] = $FinalCode;
		return $FinalCode;
	}

	public static function ValidateCSRFToken($Token)
	{
		if(isset($Token) && $Token == $_SESSION['generated_csrf'])
		{
			unset($_SESSION['generated_csrf']);
			return true;
		}
		else
			return false;
	}

	public static function UnsetKeys($Keys)
	{
		foreach($Keys as $Key)
			unset($_SESSION[$Key]);
	}

    public static function StartSimpleSession()
    {
        session_start();
        Session::$SessionCreated = true;
    }

	public static function Start($SessionName, $Secure)
	{
		$HTTPOnly = true;
		$Session_Hash = 'sha512';

		if(in_array($Session_Hash, hash_algos()))
			ini_set('session.hash_function', $Session_Hash);
		ini_set('session.hash_bits_per_character', 6);
		ini_set('session.use_only_cookies', 1);

		$CookieParameters = session_get_cookie_params();

        session_set_cookie_params($CookieParameters["lifetime"], $CookieParameters["path"], $CookieParameters["domain"], $Secure, $HTTPOnly);
		session_name($SessionName);
		session_start();
		//session_regenerate_id(true);

		Session::$SessionCreated = true;
	}

	static function Open()
	{
		if(is_null(Session::$DBConnection))
		{
			die("Unable to establish connection with database for Secure Session!");
			return false;
		}
		else
			return true;
	}

	static function Close()
	{
		Session::$DBConnection = null;
		return true;
	}

	static function Read($SessionID)
	{
		$Statement = Session::$DBConnection->prepare("SELECT data FROM sessions WHERE id = :sessionid LIMIT 1");
		$Statement->bindParam(':sessionid', $SessionID);
		$Statement->execute();
		$Result = $Statement->fetch(PDO::FETCH_ASSOC);
		$Key = Session::GetKey($SessionID);
		$Data = Session::Decrypt($Result['data'], $Key);
		return $Data;
	}

	static function Write($SessionID, $SessionData)
	{
		$Key = Session::GetKey($SessionID);
		$Data = Session::Encrypt($SessionData, $Key);

		$TimeNow = time();

		$Statement = Session::$DBConnection->prepare('REPLACE INTO sessions (id, set_time, data, session_key) VALUES (:sessionid, :creation_time, :session_data, :session_key)');
		$Statement->bindParam(':sessionid', $SessionID);
		$Statement->bindParam(':creation_time', $TimeNow);
		$Statement->bindParam(':session_data', $Data);
		$Statement->bindParam(':session_key', $Key);
		$Statement->execute();
		return true;
	}

    public static function DestroySimpleSession()
    {
        Session::$SessionCreated = false;
        setcookie("FreedomCoreLanguage", null, time()-3600);
        setcookie("FreedomCore", null, time()-3600);
        session_destroy();
    }

	static function Destroy($SessionID)
	{
		$Statement = Session::$DBConnection->prepare('DELETE FROM sessions WHERE id = :sessionid');
		$Statement->bindParam(':sessionid', $SessionID);
		$Statement->execute();
        Session::$SessionCreated = false;
        setcookie("FreedomCoreLanguage", null, time()-3600);
        setcookie("FreedomCore", null, time()-3600);
		return true;
	}

	private static function GarbageCollector($Max)
	{
		$Statement = Session::$DBConnection->prepare('DELETE FROM sessions WHERE set_time < :maxtime');
		$OldSessions = time()-$Max;
		$Statement->bindParam(':maxtime', $OldSessions);
		$Statement->execute();
		return true;
	}

	private static function GetKey($SessionID)
	{
		$Statement = Session::$DBConnection->prepare('SELECT session_key FROM sessions WHERE id = :sessionid LIMIT 1');
		$Statement->bindParam(':sessionid', $SessionID);
		$Statement->execute();
		$Result = $Statement->fetch(PDO::FETCH_ASSOC);
		if($Result['session_key'] != '')
			return $Result['session_key'];
		else
			return hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
	}

	private static function Encrypt($SessionData, $SessionKey)
	{
        $Salt = Session::$Salt;
		$SessionKey = substr(hash('sha256', $Salt.$SessionKey.$Salt), 0, 32);
		$Get_IV_Size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$IV = mcrypt_create_iv($Get_IV_Size, MCRYPT_RAND);
		$Encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $SessionKey, $SessionData, MCRYPT_MODE_ECB, $IV));
		return $Encrypted;
	}

	private static function Decrypt($SessionData, $SessionKey)
	{
        $Salt = Session::$Salt;
		$SessionKey = substr(hash('sha256', $Salt.$SessionKey.$Salt), 0, 32);
		$Get_IV_Size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$IV = mcrypt_create_iv($Get_IV_Size, MCRYPT_RAND);
		$Decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $SessionKey, base64_decode($SessionData), MCRYPT_MODE_ECB, $IV);
		return $Decrypted;
	}
}

?>