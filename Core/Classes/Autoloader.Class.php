<?php
Class Autoloader
{
	private static $IsDebugEnabled = false;
	public static $PageLoadTime;

	public static function Initialize()
	{
	     Autoloader::InitialCheck();
	     Autoloader::LoadConfig();
	     Autoloader::LoadComponents();
	     if(Autoloader::$IsDebugEnabled)
	     	Autoloader::$PageLoadTime = Utilities::PageLoadTime(true);
	}
	public static function InitialCheck()
	{
	     Autoloader::VerifyInstallation();
	     //Autoloader::VerifyPermissions();
	}
	private static function LoadConfig()
	{
		require_once('Core/Configuration/Configuration.php');
		if($FCCore['debug'])
			Autoloader::$IsDebugEnabled = true;
	}
	public static function VerifyInstallation()
	{
	     if (file_exists($_SERVER['DOCUMENT_ROOT']."/Installation/status.installed"))
	     {
	          echo "Вам необходимо удалить папку Installation из корневого каталога сайта";
	          die();
	     }
	}
	public static function LoadComponents()
	{
	     $Components = array('Classes');
	     foreach($Components as $Component)
	          Autoloader::LoadOrder($Component);
	}
	public static function LoadOrder($Dirname, $Exclude=null)
	{
	     $DirectoryFull = realpath(dirname(__FILE__));
	     $LoadOrderFile = $DirectoryFull."/LoadOrder.json";
	     $LoadOrder = json_decode(file_get_contents($LoadOrderFile), true);
	     foreach($LoadOrder['LoadOrder'] as $LoadItem)
	     {
	          if(!empty($LoadItem))
	          {
	               require_once($DirectoryFull.'/'.$LoadItem);
	          }
	     }
	}
}
?>