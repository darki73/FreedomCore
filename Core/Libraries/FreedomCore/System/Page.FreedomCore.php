<?php

Class Page
{
	public static function Info($PageType, $FreedomCore = null)
	{
		$ChosenLanguage = explode('/', $_SERVER['REQUEST_URI']);
		if(isset($_SESSION['currentlocaleid']))
			$LocaleID = $_SESSION['currentlocaleid'];
		else
			$LocaleID = 0;
		if($ChosenLanguage[1] != NULL)
			$Language = $ChosenLanguage[1];
		else
			$Language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$Page = array(
			'type'			=> $PageType,
			'localeid'		=> $LocaleID,
			'language'		=> $Language,
			'readabletime'	=> date('D, d M Y H:i:s O', time()),
			'timestamp'		=> Utilities::GetCurrentTimestamp(),
		);
		if(!is_null($FreedomCore))
			return array_merge($Page, $FreedomCore);
		else
			return $Page;
	}
}

?>