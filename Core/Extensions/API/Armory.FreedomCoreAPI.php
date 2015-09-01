<?php

Class ArmoryAPI extends API
{
    public static function GetResetStatus($JSONP)
    {
        $WorldStates = [20001, 20002, 20003, 20006, 20007];
        $Response = [];
        $Statement = parent::$CharConnection->prepare('SELECT * from worldstates WHERE entry IN(20001, 20002, 20003, 20006, 20007);');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(Database::IsEmpty($Statement)){

        }
        else{
            foreach($Result as $WS){
                foreach($WorldStates as $ID){
                    if($ID == $WS['entry'])
                    {
                        $Response[] = [ArmoryAPI::GetWorldstateByID($ID) => $WS['value']];
                        break;
                    }
                    else
                    {
                        $Response[] = [ArmoryAPI::GetWorldstateByID($WS['entry']) => '0'];
                        break;
                    }
                }
            }
        }

        return parent::Encode($Response, $JSONP, "wsrt");
    }


    private static function GetWorldstateByID($ID){
        $WorldStates = [
            20001 => "arena_points_reset",
            20002 => "weekly_quests_reset",
            20003 => "random_bg_reset",
            20006 => "daily_quests_reset",
            20007 => "monthly_quests_reset"
        ];
        return $WorldStates[$ID];
    }
}

?>