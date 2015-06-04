<?php

Class Guild
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;


    public function __construct($VariablesArray)
    {
        Guild::$DBConnection = $VariablesArray[0]::$Connection;
        Guild::$CharConnection = $VariablesArray[0]::$CConnection;
        Guild::$WConnection = $VariablesArray[0]::$WConnection;
        Guild::$TM = $VariablesArray[1];
    }

    public static function GetGuildData($GuildName)
    {
        $Statement = Guild::$CharConnection->prepare('SELECT g.*, count(gm.guid) guild_population FROM guild g LEFT JOIN guild_member gm ON g.guildid = gm.guildid WHERE g.name = :name');
        $Statement->bindParam(':name', $GuildName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['name'] == $GuildName)
        {
            $Result['guild_side'] = Characters::GetSideByRaceID(Characters::GetCharacterByGUID($Result['leaderguid'])['race']);
            return $Result;
        }
        else
            return false;
    }
}

?>