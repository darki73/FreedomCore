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

    public static function Import($Host, $User, $Password, $Database, $Encoding, $File)
    {
        $Connection = new PDO("mysql:host=".$Host.";dbname=".$Database.";charset=".$Encoding, $User, $Password, array(PDO::ATTR_PERSISTENT => false));
        $Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ImportStatus = false;
        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = $Connection->prepare($Line);
            if($Statement->execute())
                $ImportStatus = true;
            else
                $ImportStatus = false;
        }
        $Connection = null;
        return $ImportStatus;
    }

    public static function ImportCoreTable($Host, $User, $Password, $Database, $Encoding, $File)
    {
        $Connection = new PDO("mysql:host=".$Host.";dbname=".$Database.";charset=".$Encoding, $User, $Password, array(PDO::ATTR_PERSISTENT => false));
        $Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = $Connection->prepare($Line);
            if($Statement->execute())
                return true;
            else
                return false;
        }

        $Connection = null;
    }

    public static function CheckPHPModules()
    {
        $ModulesArray = array(
            array(
                'name' => 'pdo_mysql',
                'status' => extension_loaded('pdo_mysql')
            ),
            array(
                'name' => 'curl',
                'status' => extension_loaded('curl')
            ),
            array(
                'name' => 'mysqli',
                'status' => extension_loaded('mysqli')
            ),
            array(
                'name' => 'soap',
                'status' => extension_loaded('soap')
            ),
            array(
                'name' => 'gd',
                'status' => extension_loaded('gd')
            ),
            array(
                'name' => 'soap',
                'status' => extension_loaded('soap')
            ),
        );

        return $ModulesArray;
    }
}