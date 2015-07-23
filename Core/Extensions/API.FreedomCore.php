<?php

Class API
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        API::$DBConnection = $VariablesArray[0]::$Connection;
        API::$CharConnection = $VariablesArray[0]::$CConnection;
        API::$WConnection = $VariablesArray[0]::$WConnection;
        API::$TM = $VariablesArray[1];
    }

    public static function Execute($ClassConstructor, $Class, $Method, $Parameters)
    {
        Manager::LoadExtension($Class, $ClassConstructor);
        return $Class::$Method($Parameters);
    }

}

?>
