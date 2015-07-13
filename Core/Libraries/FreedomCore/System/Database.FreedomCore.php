<?php

Class Database
{
	public static $Connection;
    public static $AConnection;
    public static $CConnection;
    public static $WConnection;

	public function __construct($FCCore)
	{
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

global $FCCore, $Database;
$Database = new Database($FCCore);
$Session = new Session($Database);
Session::Start('FreedomCore', false);
?>