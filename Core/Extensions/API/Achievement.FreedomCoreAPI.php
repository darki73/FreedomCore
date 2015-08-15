<?php

Class AchievementAPI extends API
{
    public static function GetSimpleAchievement($AchievementID)
    {
        $Statement = parent::$DBConnection->prepare('SELECT a.id, a.faction as factionID, a.name_loc0 as title, a.description_loc0 as description, a.points, a.reward_loc0 as reward, LOWER(si.iconname) as icon FROM freedomcore_achievement a LEFT JOIN freedomcore_spellicons si ON a.icon = si.id WHERE a.id = :aid');
        $Statement->bindParam(':aid', $AchievementID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if ($Statement->rowCount() > 0)
        {
            $GetAchievementCriterias = AchievementAPI::GetAchievementCriteria($AchievementID);
            foreach($GetAchievementCriterias as $Criteria)
                $Result['criteria'][] = $Criteria;
            return parent::Encode($Result);
        }
        else
            return parent::GenerateResponse(404, true);

    }

    private static function GetAchievementCriteria($AchievementID)
    {
        $Statement = parent::$DBConnection->prepare('SELECT ac.id, ac.name_loc0 as description, ac.order as orderIndex FROM freedomcore_achievementcriteria ac WHERE refAchievement = :aid');
        $Statement->bindParam(':aid', $AchievementID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < count($Result); $i++)
            $Result[$i]['max'] = '1';
        return $Result;
    }
}

?>