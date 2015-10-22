<?php
Class Autoloader
{
    private static $IsDebugEnabled = false;
    public static $PageLoadTime;

    public static function Initialize($WithConfig = true)
    {
        if($WithConfig)
            Autoloader::LoadConfig();
        Autoloader::LoadComponents();
        if(Autoloader::$IsDebugEnabled)
            Autoloader::$PageLoadTime = Utilities::PageLoadTime(true);
    }

    private static function LoadConfig()
    {
        if(file_exists(getcwd().DIRECTORY_SEPARATOR.'Core'.DIRECTORY_SEPARATOR.'Configuration'.DIRECTORY_SEPARATOR.'Configuration.php'))
        {
            require_once('Core/Configuration/Configuration.php');
            if($FCCore['debug'])
                Autoloader::$IsDebugEnabled = true;
        }
        else
        {
            header('Location: /Install');
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