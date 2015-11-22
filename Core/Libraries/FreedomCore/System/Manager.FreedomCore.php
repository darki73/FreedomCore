<?php

Class Manager extends FreedomCore
{
	/**
    * Load FreedomCore System Extension
    * @param String $extension_name is a name of extension to be loaded
    * @return Returning status of operation
    */
	public static function LoadSystemExtension($ExtensionName)
	{
		if(file_exists(FREEDOMCORE_SYSTEM_DIR.$ExtensionName.'.FreedomCore.php'))
			require_once(FREEDOMCORE_SYSTEM_DIR.$ExtensionName.'.FreedomCore.php');
		else
			echo "<strong>Unable to Load Extension: </strong>".$ExtensionName."<br />Check if this Extension actually exists";
	}

	/**
    * Load FreedomCore Extension
    * @param String $extension_name is a name of extension to be loaded
    * @return Returning status of operation
    */
	public static function LoadExtension($ExtensionName, $AdditionalInfo = null)
	{
		if(file_exists(FREEDOMCORE_EXTENSIONS_DIR.$ExtensionName.'.FreedomCore.php'))
		{
			require_once(FREEDOMCORE_EXTENSIONS_DIR.$ExtensionName.'.FreedomCore.php');

			if(!class_exists($ExtensionName))
				die('<strong>Loaded extension: </strong>'.$ExtensionName.'<br /><strong>Error: </strong> Class Name does not match Extension name');

			if($AdditionalInfo != null)
				new $ExtensionName($AdditionalInfo);
		}
		else
		{
			echo "<strong>Unable to Load Extension: </strong>".$ExtensionName."<br />Check if this Extension actually exists";
			die();
		}

	}

	/**
	 * Load Third Party or Native Plugin
	 * @param $PluginName
	 */
	public static function LoadPlugin($PluginName)
	{
		if(file_exists(FREEDOMCORE_PLUGINS_DIR.$PluginName.'.FreedomCore.php'))
		{
			require_once(FREEDOMCORE_PLUGINS_DIR.$PluginName.'.FreedomCore.php');

			if(!class_exists($PluginName))
				die('<strong>Loaded plugin: </strong>'.$PluginName.'<br /><strong>Error: </strong> Class Name does not match Plugin name');
		}
		else
		{
			echo "<strong>Unable to Load Plugin: </strong>".$PluginName."<br />Check if this Plugin actually exists";
			die();
		}

	}

	/**
	 * Load FreedomCore System Interface
	 * @param String $InterfaceName is a name of interface to be loaded
	 * @return Returning status of operation
	 */
	public static function LoadSystemInterface($InterfaceName)
	{
		if(file_exists(FREEDOMCORE_INTERFACES_DIR.$InterfaceName.'.FreedomCore.php')){
			require_once(FREEDOMCORE_INTERFACES_DIR.$InterfaceName.'.FreedomCore.php');

			if(!interface_exists($InterfaceName))
				die('<strong>Loaded Interface: </strong>'.$InterfaceName.'<br /><strong>Error: </strong> Class Name does not match Interface name');
		} else {
			die("<strong>Unable to Load Interface: </strong>".$InterfaceName."<br />Check if this Interface actually exists");
		}
	}

	public static function Page($PageName, $PageTitle, $Enabled = true)
	{
		$Page = array(
			'name' => $PageName,
			'title' => $PageTitle,
			'enabled' => $Enabled,
		);
		return $Page;
	}

	public static function GetUrlData($URL)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $URL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_USERAGENT, 'FreedomCore CMS (Manager Class Download Function)');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		$data = curl_exec($curl);
		curl_close($curl);
		return $data;
	}
}

?>