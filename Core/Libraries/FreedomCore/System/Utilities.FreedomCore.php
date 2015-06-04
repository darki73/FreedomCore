<?php

Class Utilities extends FreedomCore
{
	public static function GetLanguage($IsCode = false)
	{

		if(isset($_SESSION['preferredlanguage']))
			$PreferedLanguage = $_SESSION['preferredlanguage'];
		else
			$PreferedLanguage = null;
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		    $BrowserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        else
            $BrowserLanguage = 'ru';

		if($IsCode)
		{
			if($PreferedLanguage != null)
				return $PreferedLanguage;
			else
				return $BrowserLanguage;
		}
		else
		{
			if($PreferedLanguage != null)
				return Utilities::LoopLanguages($PreferedLanguage);
			else
				return Utilities::LoopLanguages($BrowserLanguage);
		}
	}

    private static function LoopLanguages($LanguageCode)
    {
        $Language = "";

        switch($LanguageCode)
        {
            case 'ru':
                $Language = "Russian.language";
                break;

            case 'it':
                $Language = "Italian.language";
                break;

            case 'pt':
                $Language = "Portugese.language";
                break;

            case 'kr':
                $Language = "Korean.language";
                break;

            case 'de':
                $Language = "German.language";
                break;

            case 'es':
                $Language = "Spanish.language";
                break;

            case 'fr':
                $Language = "French.language";
                break;

            default:
                $Language = "Russian.language";
                break;
        }

        return $Language;
    }
    public static function GetMemoryUsage($decimals = 2)
    {
        $result = 0;

        if (function_exists('memory_get_usage'))
        {
            $result = memory_get_usage() / 1024;
        }

        else
        {
            if (function_exists('exec'))
            {
                $output = array();

                if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN')
                {
                    exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);

                    $result = preg_replace('/[\D]/', '', $output[5]);
                }

                else
                {
                    exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);

                    $output = explode('  ', $output[0]);

                    $result = $output[1];
                }
            }
        }

        return number_format(intval($result) / 1024, $decimals, '.', '');
    }

	public static function PageLoadTime($StartTimer = false)
	{
		static $Start;

	    if ($StartTimer)
	    	$Start = microtime(true);
	    else
	    {
	        $Difference = round((microtime(true) - $Start), 4);
	        $Start = null;
	        return $Difference*1000;
	    }
	}

	public static function LocaleToLang($LocaleID)
	{
		switch($LocaleID)
		{
			case '0':
				$Language = array("English.language", "en");
			break;

			case '2':
				$Language = array("French.language", "fr");
			break;

			case '3':
				$Language = array("German.language", "de");
			break;

			case '6':
				$Language = array("Spanish.language", "es");
			break;

			case '7':
				$Language = array("Russian.language", "ru");
			break;

			case '8':
				$Language = array("Portugese.language", "pt");
			break;

			case '9':
				$Language = array("Italian.language", "it");
			break;
		}
		$_SESSION['preferredlanguage'] = $Language;
	}

	public static function LanguageCode()
	{
		return Utilities::GetLanguage(true);
	}

	public static function GetCurrentTimestamp()
	{
		$now = new DateTime();
		return $now->getTimestamp();
	}

	public static function GenerateRandomString($length = 10) 
	{
	    $Characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $FinalString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $FinalString .= $Characters[rand(0, strlen($Characters) - 1)];
	    }
	    return $FinalString;
	}

	public static function RemoveDirectory($Path)
	{
		$Iterator = new RecursiveDirectoryIterator($Path, RecursiveDirectoryIterator::SKIP_DOTS);
		$Files = new RecursiveIteratorIterator($Iterator, RecursiveIteratorIterator::CHILD_FIRST);
		foreach($Files as $File) {
		    if ($File->getFilename() === '.' || $File->getFilename() === '..') {
		        continue;
		    }
		    if ($File->isDir()){
		        rmdir($File->getRealPath());
		    } else {
		        unlink($File->getRealPath());
		    }
		}
		rmdir($Path);
	}
}

?>