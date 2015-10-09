<?php

Class Realms
{
    private static $DBConnection;
    private static $AConnection;
    private static $CConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Realms::$DBConnection = $VariablesArray[0]::$Connection;
        Realms::$AConnection = $VariablesArray[0]::$AConnection;
        Realms::$CConnection = $VariablesArray[0]::$CConnection;
        Realms::$TM = $VariablesArray[1];
    }

    public static function GetAllRealms()
    {
        $Statement = Realms::$AConnection->prepare('SELECT id, name, address, port, icon, flag, timezone, population, gamebuild FROM realmlist');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Server)
        {
            $CheckConnection = @fsockopen ($Server['address'], $Server['port'], $errno, $errstr, 0.5);
            if($CheckConnection) { $Result[$Index]['status'] = "up"; } else { $Result[$Index]['status'] = "down"; }
            $Result[$Index]['type'] = Realms::RealmTypeByID($Server['icon']);
            $Result[$Index]['language'] = Realms::LanguageByID($Server['timezone']);
            $Result[$Index]['uptime'] = Realms::GetUptimeForRealm($Server['id']);
            $Result[$Index]['online'] = Realms::GetOnlineForRealm($Server['id']);
            $Index++;
        }
        return $Result;
    }

    private static function GetOnlineForRealm($RealmID)
    {
        $Statement = Realms::$CConnection->prepare('SELECT count(guid) as now_online FROM characters WHERE online = 1');
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['now_online'];
    }

    private static function GetUptimeForRealm($RealmID)
    {
        $Statement = Realms::$AConnection->prepare('SELECT starttime FROM uptime WHERE realmid = :rid LIMIT 1');
        $Statement->bindParam(':rid', $RealmID);
        $Statement->execute();
        return Text::GetTimeDiff($Statement->fetch(PDO::FETCH_ASSOC)['starttime']);
    }

    private static function RealmTypeByID($TypeID)
    {
        $Types = array(
            '0' => 'PVE',
            '1' => 'PvP',
            '4' => 'Normal',
            '6' => 'RP',
            '8' => 'RP PvP',
        );

        return $Types[$TypeID];
    }

    private static function LanguageByID($LanguageID)
    {
        $Languages = array(
            '1' => 'Development',
            '12' => 'Русский',
            '8' => 'English',
            '9' => 'German',
            '11' => 'Espanol',
        );

        return $Languages[$LanguageID];
    }
}

?>