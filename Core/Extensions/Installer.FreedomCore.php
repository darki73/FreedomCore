<?php

Class Installer
{
    public static $DBConnection;
    public static $CConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Installer::$DBConnection = $VariablesArray[0]::$Connection;
        Installer::$CConnection = $VariablesArray[0]::$CConnection;
        Installer::$WConnection = $VariablesArray[0]::$WConnection;
        Installer::$TM = $VariablesArray[1];
    }

    public static function Import($File)
    {
        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = Installer::$DBConnection->prepare($Line);
            if($Statement->execute())
                return true;
            else
                return false;
        }
    }
}