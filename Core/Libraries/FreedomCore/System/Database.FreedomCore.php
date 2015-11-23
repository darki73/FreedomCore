<?php

Class Database
{
	public static $Connection;
    public static $AConnection;
    public static $CConnection;
    public static $WConnection;

    private static $SelectedConnection;

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

    public static function plainSQL($Connection, $Query){
        Database::SelectConnection($Connection);
        $Statement = Database::$SelectedConnection->prepare($Query);
        $Statement->execute();
    }

    public static function getSingleRow($Connection, $Query, $Parameters = null){
        Database::SelectConnection($Connection);
        $Statement = Database::$SelectedConnection->prepare($Query);
        if($Parameters != null)
            foreach($Parameters as $Parameter)
                $Statement->bindParam($Parameter['id'], $Parameter['value']);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function getMultiRow($Connection, $Query, $Parameters = null){
        Database::SelectConnection($Connection);
        $Statement = Database::$SelectedConnection->prepare($Query);
        if($Parameters != null)
            foreach($Parameters as $Parameter)
                $Statement->bindParam($Parameter['id'], $Parameter['value']);
        $Statement->execute();
        return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function SelectConnection($Connection)
    {
        if($Connection == 'Auth')
            Database::$SelectedConnection = Database::$AConnection;
        elseif($Connection == 'Characters')
            Database::$SelectedConnection = Database::$CConnection;
        elseif($Connection == 'World')
            Database::$SelectedConnection = Database::$WConnection;
        elseif($Connection == 'Site' || $Connection == 'Website')
            Database::$SelectedConnection = Database::$Connection;
    }

    public static function IsEmpty($Statement)
    {
        if($Statement->rowCount() > 0)
            return false;
        else
            return true;
    }

    public static function ClientVersion()
    {
        ob_start();
        phpinfo(INFO_MODULES);
        $Info = ob_get_contents();
        ob_end_clean();
        $Info = stristr($Info, 'Client API version');
        preg_match('/[1-9].[0-9].[1-9][0-9]/', $Info, $Match);
        $Client = $Match[0];
        return $Client;
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
    if(strpos($_SERVER['REQUEST_URI'], '/Install') === false)
        header('Location: /Install');
}
?>