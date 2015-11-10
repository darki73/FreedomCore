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

    public static function GenerateErrorPage($Smarty, $Code)
    {
        $ErrorDescription = ErrorHandler::ListenForError($Code);
        $Smarty->assign('Error', $ErrorDescription);
        $Smarty->assign('Page', Page::Info('error_'.$ErrorDescription['code'], array('bodycss' => 'server-error', 'pagetitle' => $ErrorDescription['code'].' - ')));
        $Smarty->display('pages/error_page');
    }

	public static function GeneratePage($TemplatesManager, $PageType, $BodyCSS, $HeaderString, $PageTemplate)
	{
		$TemplatesManager->assign('Page', Page::Info($PageType, array('bodycss' => $BodyCSS, 'pagetitle' => $HeaderString.' - ')));
		$TemplatesManager->display($PageTemplate);
	}
}

?>