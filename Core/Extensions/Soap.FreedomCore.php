<?php

Class Soap
{
    private static $DBConnection;
    private static $AConnection;
    private static $CConnection;
    private static $TM;
    private static $SConnection;
    private static $ItemsArray = array();

    public function __construct($VariablesArray)
    {
        Soap::$DBConnection = $VariablesArray[0]::$Connection;
        Soap::$AConnection = $VariablesArray[0]::$AConnection;
        Soap::$CConnection = $VariablesArray[0]::$CConnection;
        Soap::$TM = $VariablesArray[1];
    }


    private static function CreateConnection()
    {
        global $FCCore;
        Soap::$SConnection = new SoapClient(null, array(
            'location'      =>  'http://'.$FCCore['soap']['host'].':'.$FCCore['soap']['port'].'/',
            'uri'           =>  'urn:TC',
            'user_agent'    =>  'trinitycore',
            'style'         =>  SOAP_RPC,
            'login'         =>  $FCCore['soap']['sender_name'],
            'password'      =>  $FCCore['soap']['sender_pass'],
            'trace'         =>  1,
            'exceptions'    => 0
        ));
    }

    private static function CloseConnection()
    {
        Soap::$SConnection = null;
    }

    private static function Execute($Command)
    {
        Soap::CreateConnection();
        $Result = Soap::$SConnection->executeCommand(new SoapParam($Command, 'command'));
        Soap::CloseConnection();
        Soap::FlushItemsArray();
        if(is_soap_fault($Result))
            return false;
        else
            return true;
    }

    public static function AddItemToList($ItemID, $ItemCount)
    {
        Soap::$ItemsArray[] = array('id' => $ItemID, 'count' => $ItemCount);
    }

    private static function FlushItemsArray()
    {
        Soap::$ItemsArray = array();
    }

    public static function SendItem($PlayerName, $Subject)
    {
        $Command = '.send items '.$PlayerName.' "'.$Subject.'" "'.Soap::BuildMessageBody($PlayerName, $Subject).'" ';
        $ItemsString = '';
        foreach(Soap::$ItemsArray as $Item)
            $ItemsString .= $Item['id'].':'.$Item['count'].' ';
        $Command = $Command.$ItemsString;
        if(Soap::Execute($Command))
            return true;
        else
            return false;
    }

    private static function BuildMessageBody($PlayerName, $Subject)
    {
        $MessageBody = '';
        $MessageBody .= $PlayerName.",\n\n";
        $MessageBody .= 'Thanks for your purchase of '.$Subject."\n\n";
        $MessageBody .= '';

        return $MessageBody;
    }

    public static function SendMoney($PlayerName, $Subject, $Text, $Money)
    {
        $Command = '.send money '.$PlayerName.' "'.$Subject.'" "'.$Text.'" '.$Money;
        Soap::Execute($Command);
    }
}

?>