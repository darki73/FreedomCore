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