<?php
require_once('Core/Libraries/Smarty/Smarty.class.php');
global $FreedomCore, $Directory, $FCCore, $Smarty;
$Directory = str_replace("\\", "/", getcwd());
Class Smarty_FreedomCore extends Smarty
{
	function __construct($Template)
	{
		global $FreedomCore, $Directory, $FCCore;
		parent::__construct();
		$this->template_dir = $Directory.'/Templates/'.$FCCore['Template'].'/';
		$this->compile_dir = $Directory.'/Cache/Compile/Templates/'.$FCCore['Template'].'/';
		$this->config_dir = $FreedomCore->getLanguageDir();
		$this->cache_dir = $FreedomCore->getCacheDir();
		$this->configLoad($FreedomCore->loadLanguage());
		// Debug Mode
		$this->debugging = $FCCore['SmartyDebug'];

		// Template Vars
		$this->left_delimiter = '{';
		$this->right_delimiter = '}';
		// Caching
		if($FCCore['SmartyCaching'])
		{
			$this->caching = true;
			$this->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
		}
		else
			$this->caching = false;
		// System Vars
		$this->assign('Language', Utilities::LanguageCode());
		$this->assign('AppName', $FCCore['ApplicationName']);
		$this->assign('AppDescription', $FCCore['ApplicationDescription']);
		$this->assign('AppKeywords', $FCCore['ApplicationKeywords']);
		$this->assign('Template', $FCCore['Template']);
		$this->assign('ExpansionTemplate', $FCCore['ExpansionTemplate']);

		// Social Links
		$this->assign('SLFacebook', $FCCore['Social']['Facebook']);
		$this->assign('SLTwitter', $FCCore['Social']['Twitter']);
		$this->assign('SLTwitter', $FCCore['Social']['Vkontakte']);
		$this->assign('SLSkype', $FCCore['Social']['Skype']);
		$this->assign('SLYoutube', $FCCore['Social']['Youtube']);
		$this->assign('FacebookAdmins', $FCCore['Facebook']['admins']);
		$this->assign('FacebookPage', $FCCore['Facebook']['pageid']);

		// Google Analytics
		$this->assign('GoogleAnalytics', array('Account' => $FCCore['GoogleAnalytics']['Account'], 'Domain' => $FCCore['GoogleAnalytics']['Domain']));
	}
	function display($template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL)
	{
		global $FCCore;
		if($FCCore['debug'])
        {
            $this->assign('PageLoadTime', Utilities::PageLoadTime());
            $this->assign('MemoryUsage', Utilities::GetMemoryUsage());
            $this->assign('Debug', true);
        }
        else
            $this->assign('Debug', false);
		Smarty::Display($template.".tpl");
	}

	function translate($TranslationFile)
	{
		global $FreedomCore;
		$Language = str_replace('.language', '', $FreedomCore->loadLanguage());
		Smarty::configLoad($Language.DS.$TranslationFile.'.language');
	}
}
$Smarty = new Smarty_FreedomCore($FCCore['Template']);
?>