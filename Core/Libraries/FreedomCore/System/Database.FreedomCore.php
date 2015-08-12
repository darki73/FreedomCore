<?php

Class Database
{
	public static $Connection;
    public static $AConnection;
    public static $CConnection;
    public static $WConnection;

	public function __construct($FCCore)
	{
        if(isset($FCCore['Database']['host']))
            try
            {
                Database::$Connection = new PDO("mysql:host=".$FCCore['Database']['host'].";dbname=".$FCCore['Database']['database'].";charset=".$FCCore['Database']['encoding'], $FCCore['Database']['username'], $FCCore['Database']['password'], array(PDO::ATTR_PERSISTENT => false));
                Database::$Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                Database::$AConnection = new PDO("mysql:host=".$FCCore['AuthDB']['host'].";dbname=".$FCCore['AuthDB']['database'].";charset=".$FCCore['AuthDB']['encoding'], $FCCore['AuthDB']['username'], $FCCore['AuthDB']['password'], array(PDO::ATTR_PERSISTENT => false));
                Database::$AConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                Database::$CConnection = new PDO("mysql:host=".$FCCore['CharDB']['host'].";dbname=".$FCCore['CharDB']['database'].";charset=".$FCCore['CharDB']['encoding'], $FCCore['CharDB']['username'], $FCCore['CharDB']['password'], array(PDO::ATTR_PERSISTENT => false));
                Database::$CConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                Database::$WConnection = new PDO("mysql:host=".$FCCore['WorldDB']['host'].";dbname=".$FCCore['WorldDB']['database'].";charset=".$FCCore['WorldDB']['encoding'], $FCCore['WorldDB']['username'], $FCCore['WorldDB']['password'], array(PDO::ATTR_PERSISTENT => false));
                Database::$WConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e)
            {
                echo "<pre>";
                print_r($e->getMessage());
                die();
            }
	}
}

global $FCCore, $Database, $InstallationIsInProgress;
$Database = new Database($FCCore);
$InstallationIsInProgress = true;

if(isset($FCCore['Database']['host']) && $FCCore['Database']['host'] != '')
{
    if(!isset($_SESSION['installation_in_progress']))
    {
        if(session_status() == PHP_SESSION_NONE)
        {
//            $Session = new Session($Database);
//            $InstallationIsInProgress = false;
//            if(isset($_COOKIE['FreedomCore']))
//                Session::Start('FreedomCore', false);
            $InstallationIsInProgress = false;
            Session::StartSimpleSession();
        }
    }
    else
        $InstallationIsInProgress = true;
}
else
{
    session_start();
    $_SESSION['preferredlanguage'] = '';
    $_SESSION['installation_in_progress'] = true;
    if(strpos($_SERVER['REQUEST_URI'], '/install') === false)
        header('Location: /install');
}
?>