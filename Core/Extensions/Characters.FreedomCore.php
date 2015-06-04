<?php

Class Characters
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;

    private static $CharacterLevel;
    private static $CharacterClass;

    public function __construct($VariablesArray)
    {
        Characters::$DBConnection = $VariablesArray[0]::$Connection;
        Characters::$CharConnection = $VariablesArray[0]::$CConnection;
        Characters::$WConnection = $VariablesArray[0]::$WConnection;
        Characters::$TM = $VariablesArray[1];
    }

    public static function GetCharacters($UserID)
    {
        $Statement = Characters::$CharConnection->prepare('
            SELECT
                cc.*,
                g.name as guild_name
            FROM
                characters cc
            LEFT JOIN guild_member gm ON
                gm.guid = cc.guid
            LEFT JOIN guild g ON
                g.guildid = gm.guildid
            WHERE
                cc.account = :aid
	    ');
        $Statement->bindParam(':aid', $UserID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($Result))
        {
            $Index = 0;
            foreach($Result as $Character)
            {
                $Result[$Index]['race_name'] = Characters::GetRaceByID($Character['race'])['translation'];
                $Result[$Index]['class_name'] = Characters::GetClassByID($Character['class'])['translation'];
                $Result[$Index]['side'] = Characters::GetSideByRaceID($Character['race'])['name'];
                $Index++;
            }
            return $Result;
        }
        else
            return 0;
    }

    public static function GetGearForCharacter($CharacterGuid)
    {
        $Statement = Characters::$CharConnection->prepare('
        SELECT
            ii.itemEntry,
            ci.slot
        FROM character_inventory ci
        LEFT JOIN item_instance ii ON
            ci.item = ii.guid
        WHERE
            ci.slot >= 0
          AND
            ci.slot <= 18
          AND
            ci.guid = :guid
        ORDER BY ci.slot
        ');
        $Statement->bindParam(':guid', $CharacterGuid);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;

        // Fill empty slots
        $SlotRange = range(0, 18);

        // Add item Data to existing slots
        foreach($Result as $Item)
        {
            if(in_array($Item['slot'], $SlotRange))
            {
                $Result[$Index]['data'] = Items::GetItemInfo($Item['itemEntry']);
                unset($SlotRange[$Item['slot']]);
                $Index++;
            }
        }
        foreach($SlotRange as $MissingSlot)
            $Result[] = array('itemEntry' => 0, 'slot' => $MissingSlot);

        $ItemLevel = 0;
        $ItemsCount = 0;
        foreach($Result as $Item)
            if($Item['slot'] != 18 && $Item['slot'] != 3)
                if(isset($Item['data']))
                {
                    $ItemLevel = $ItemLevel + $Item['data']['ItemLevel'];
                    $ItemsCount++;
                }

        $StrengthValue = 0;
        $AgilityValue = 0;
        $IntellectValue = 0;
        $StaminaValue = 0;
        $SpiritValue = 0;
        $ArmorValue = 0;
        $ParryValue = 0;
        $DodgeValue = 0;
        $BlockValue = 0;
        $CritValue = 0;
        $HasteValue = 0;

        $MainHandSpeed = 0;
        $OffHandSpeed = 0;
        $RangedSpeed = 0;

        foreach($Result as $Item)
        {
            if(isset($Item['data']))
            {
                if($Item['data']['armor'] != 0)
                    $ArmorValue = $ArmorValue + $Item['data']['armor'];
                for($i = 1; $i <=5; $i++)
                {
                    if($Item['data']['stat_type'.$i] != 0)
                    {
                        if ($Item['data']['stat_type'.$i] == 3)
                            $AgilityValue = $AgilityValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 4)
                            $StrengthValue = $StrengthValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 5)
                            $IntellectValue = $IntellectValue + $Item['data']['stat_value'.$i];
                        elseif($Item['data']['stat_type'.$i] == 6)
                            $IntellectValue = $IntellectValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 7)
                            $StaminaValue = $StaminaValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 13)
                            $DodgeValue = $DodgeValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 14)
                            $ParryValue = $ParryValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 32)
                            $CritValue = $CritValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 36)
                            $HasteValue = $HasteValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 48)
                            $BlockValue = $BlockValue + $Item['data']['stat_value'.$i];
                    }
                }
                if($Item['slot'] == 15)
                    $MainHandSpeed = $Item['data']['delay'];
                elseif($Item['slot'] == 16)
                    $OffHandSpeed = $Item['data']['delay'];
                elseif($Item['slot'] == 17)
                    $RangedSpeed = $Item['data']['delay'];
            }
        }

        $Result['StrengthValue'] = $StrengthValue;
        $Result['AgilityValue'] = $AgilityValue;
        $Result['IntellectValue'] = $IntellectValue;
        $Result['StaminaValue'] = $StaminaValue;
        $Result['SpiritValue'] = $SpiritValue;
        $Result['ArmorValue'] = $ArmorValue;
        $Result['ParryValue'] = $ParryValue;
        $Result['DodgeValue'] = $DodgeValue;
        $Result['BlockValue'] = $BlockValue;
        $Result['CritValue'] = $CritValue;
        $Result['HasteValue'] = $HasteValue;
        $Result['MainHandSpeed'] = $MainHandSpeed;
        $Result['OffHandSpeed'] = $OffHandSpeed;
        $Result['RangedSpeed'] = $RangedSpeed;
        $Result['AttackPower'] = Characters::CalculateAttackPower(Characters::$CharacterClass, Characters::$CharacterLevel, $StrengthValue, $AgilityValue);
        $Result['DamageReduction'] = Characters::DamageReductionByLevel(Characters::$CharacterLevel, $ArmorValue);
        $Result['TotalItemLevel'] = $ItemLevel;
        $Result['EquippedItems'] = $ItemsCount;
        return $Result;
    }

    public static function PickRandomChar($UserID)
    {
        $Characters = Characters::GetCharacters($UserID);
        if(!$Characters)
            return false;
        else
        {
            $CharIDs = array();
            foreach($Characters as $Character)
            {
                $CharIDs[] = $Character['guid'];
            }
            $RandomKey = array_rand($CharIDs, 1);
            return $CharIDs[$RandomKey];
        }
    }

    public static function GetPVPRaiting($CharacterGUID)
    {
        $Statement = Characters::$CharConnection->prepare('
            SELECT
                atm.personalRating,
                cat.type
            FROM
                arena_team_member atm
            LEFT JOIN arena_team cat ON
                atm.arenaTeamId = cat.arenaTeamId
            WHERE
                atm.guid = :guid
        ');
        $Statement->bindParam(':guid', $CharacterGUID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function GetGuildData($GuildName)
    {
        $Statement = Characters::$CharConnection->prepare('
            SELECT
                g.*,
                c.race
            FROM
                guild g
            LEFT JOIN characters c ON
                g.leaderguid = c.guid
            WHERE
                g.name = :guildname
        ');
        $Statement->bindParam(':guildname', $GuildName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['side'] = Characters::GetSideByRaceID($Result['race']);
        return $Result;
    }

    public static function GetCharacterByGUID($Guid)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT * FROM characters WHERE guid = :guid');
        $Statement->bindParam(':guid', $Guid);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function GetSideByRaceID($RaceID)
    {
        $HordeRaces = array(2, 5, 6, 8, 9, 10, 26);
        if(in_array($RaceID, $HordeRaces))
            return array('id' => '1', 'translation' => Characters::$TM->GetConfigVars('Guild_Side_Horde'), 'name' => 'horde');
        else
            return array('id' => '0', 'translation' => Characters::$TM->GetConfigVars('Guild_Side_Alliance'), 'name' => 'alliance');
    }

    public static function GetCharacterData($CharacterName)
    {
        global $FCCore;
        $Statement = Characters::$CharConnection->prepare('
            SELECT
                cc.guid,
                cc.account,
                cc.name,
                cc.race,
                cc.class,
                cc.gender,
                cc.level,
                cc.chosenTitle,
                cc.health,
                cc.power1,
                cc.power2,
                cc.power3,
                cc.power4,
                cc.power5,
                cc.online,
                fct.name_loc0 as title,
                g.name as guild_name
            FROM
                characters cc
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_char_titles fct ON
                cc.chosenTitle = fct.id
			LEFT JOIN guild_member gm ON
				cc.guid = gm.guid
			LEFT JOIN guild g ON
				gm.guildid = g.guildid
            WHERE
                cc.name = :charname
        ');
        $Statement->bindParam(':charname', $CharacterName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['race_data'] = Characters::GetRaceByID($Result['race']);
        $Result['class_data'] = Characters::GetClassByID($Result['class']);
        $Result['title'] = str_replace('%s', '', str_replace('%s, ', '', $Result['title']));
        $Result['side'] = Characters::GetSideByRaceID($Result['race'])['name'];
        $Result['side_id'] = Characters::GetSideByRaceID($Result['race'])['id'];
        $Result['side_translation'] = Characters::GetSideByRaceID($Result['race'])['translation'];
        $Result['apoints'] = Characters::GetAchievementPoints($Result['guid'])['points'];
        $Result['acount'] = Characters::GetAchievementPoints($Result['guid'])['count'];

        $LevelStatsStatement = Characters::$WConnection->prepare('
          SELECT
            pls.str,
            pls.agi,
            pls.sta,
            pls.inte,
            pls.spi,
            pcls.basehp,
            pcls.basemana
        FROM player_levelstats pls
        LEFT JOIN player_classlevelstats pcls ON
            pls.class = pcls.class
        WHERE
                pls.level = pcls.level
            AND
                pls.race = :race
            AND
                pls.class = :class
            AND
                pls.level = :level
        ');
        $LevelStatsStatement->bindParam(':race', $Result['race']);
        $LevelStatsStatement->bindParam(':class', $Result['class']);
        $LevelStatsStatement->bindParam(':level', $Result['level']);
        $LevelStatsStatement->execute();
        $Result['level_data'] = $LevelStatsStatement->fetch(PDO::FETCH_ASSOC);
        $Result['level_data']['parrypoints'] = Characters::GetParryRatingByLevel($Result['level']);
        $Result['level_data']['blockpoints'] = Characters::GetBlockRatingByLevel($Result['level']);
        $Result['level_data']['critpoints'] = Characters::GetCritRatingByLevel($Result['level']);
        $Result['level_data']['hastepoints'] = Characters::GetHasteRatingByLevel($Result['level']);
        Characters::$CharacterLevel = $Result['level'];
        Characters::$CharacterClass = $Result['class'];
        return $Result;
    }

    private static function GetHasteRatingByLevel($Level)
    {
        if($Level >= 90 && $Level < 100)
            return 131.16;
        elseif($Level >=85 && $Level <90)
            return 65.58;
        elseif($Level >= 80 && $Level < 85)
            return 32.79;
        elseif($Level < 80 && $Level >= 70)
            return 15.77;
        elseif($Level < 70)
            return 10;
    }
    private static function GetParryRatingByLevel($Level)
    {
        if($Level >= 80 && $Level < 85)
            return 45.25;
        elseif($Level < 80 && $Level >= 70)
            return 21.76;
        elseif($Level < 70)
            return 13.8;
    }

    private static function CalculateAttackPower($Class, $Level, $Strength, $Agility)
    {
        $AttackPowerByClass = array(
            '1' => ($Strength*2)+($Level*3)-20,
            '2' => ($Strength*2)+($Level*3)-20,
            '3' => $Strength+$Agility+($Level*2)-20,
            '4' => $Strength+($Agility*2)+($Level*2)-20,
            '5' => $Strength-10,
            '6' => ($Strength*2)+($Level*3)-20,
            '7' => ($Strength - 10)+(($Agility*2)-20)+($Level*2),
            '8' => $Strength-10,
            '9' => $Strength-10,
            '10' => $Strength+($Agility*2)+($Level*2)-20,
            '11' => ($Strength*2)+$Agility-20+40,
        );

        return $AttackPowerByClass[$Class];
    }

    private static function GetCritRatingByLevel($Level)
    {
        if($Level >= 90)
            return 598;
        elseif($Level >=85 && $Level <90)
            return 179;
        elseif($Level >= 80 && $Level < 85)
            return 45.91;
        elseif($Level < 80 && $Level >= 70)
            return 22.08;
        elseif($Level < 70)
            return 14;
    }

    private static function DamageReductionByLevel($Level, $Armor)
    {
        if($Level >= 1 && $Level <= 59)
            $DamageReduction = ($Armor / (85 * $Level + $Armor + 400)) * 100;
        elseif($Level >= 60 && $Level < 80)
            $DamageReduction = ($Armor / (467.5 * $Level + $Armor - 22167.5)) * 100;
        else
            $DamageReduction = ($Armor / ($Armor + 15232.5)) * 100;
        return $DamageReduction;
    }

    private static function GetBlockRatingByLevel($Level)
    {
        if($Level >= 80 && $Level < 85)
            return 16.39;
        elseif($Level < 80 && $Level >= 70)
            return 7.88;
        elseif($Level < 70)
            return 5;
    }

    private static function GetAchievementPoints($CharGuid)
    {
        global $FCCore;
        $Statement = Characters::$CharConnection->prepare('
            SELECT
                sum(fa.points) as total_points,
                count(fa.id) as completed
            FROM
                character_achievement ca
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_achievement fa ON
                ca.achievement = fa.id
            WHERE guid = :guid
        ');
        $Statement->bindParam(':guid', $CharGuid);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return array('points' => $Result['total_points'], 'count' => $Result['completed']);
    }

    public static function GetCompletedAchievements($CharGuid)
    {
        global $FCCore;
        $Statement = Characters::$CharConnection->prepare('
        SELECT
            ca.achievement,
            ca.date,
            fa.name_loc0 as name,
            fa.description_loc0 as description,
            fa.category,
            fac.parentAchievement,
            fa.points,
            fsi.iconname
        FROM
            character_achievement ca
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_achievement fa ON
            ca.achievement = fa.id
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_achievementcategory fac ON
            fa.category = fac.id
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_spellicons fsi ON
            fa.icon = fsi.id
        WHERE guid = :guid
        ORDER BY ca.date DESC');
        $Statement->bindParam(':guid', $CharGuid);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function CheckCharacter($CharacterName)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT name FROM characters WHERE name = :charname');
        $Statement->bindParam(':charname', $CharacterName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['name'] == $CharacterName)
            return true;
        else
            return false;
    }

    public static function GetReputation($CharacterGUID)
    {
        global $FCCore;
        $Statement = Characters::$CharConnection->prepare('
        SELECT
            cr.*,
            ff.name_loc0
        FROM
            character_reputation cr
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_factions ff ON
            cr.faction = ff.factionID
        WHERE
            guid = :guid
        AND ff.team != 0
        AND cr.flags IN (17, 81)
        ORDER BY ff.name_loc0
        ');
        $Statement->bindParam(':guid', $CharacterGUID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Faction)
        {
            $ReputationData = Characters::ReputationSplitter($Faction['standing'], $Faction['faction']);
            $Result[$Index]['rank'] = $ReputationData['rank'];
            $Result[$Index]['translation'] = $ReputationData['translation'];
            $Result[$Index]['max'] = $ReputationData['maximum'];
            $Result[$Index]['site_reputation'] = Characters::SubstituteByRang($ReputationData['rank'], $Faction['standing'], $Faction['faction']);
            $Index++;
        }
        return $Result;
    }

    private static function ReputationSplitter($ReputationValue, $FactionID)
    {
        $ReputationRangs = Characters::ReputationByFaction($FactionID);
        $Reputation = array(
            array('lowest' => $ReputationRangs[0], 'highest' => $ReputationRangs[0]+35999), // Hated
            array('lowest' => $ReputationRangs[1], 'highest' => $ReputationRangs[1]+2999), // Hostile
            array('lowest' => $ReputationRangs[2], 'highest' => $ReputationRangs[2]+2999), // Unfriendly
            array('lowest' => $ReputationRangs[3], 'highest' => $ReputationRangs[3]+2999), // Neutral
            array('lowest' => $ReputationRangs[4], 'highest' => $ReputationRangs[4]+5999), // Friendly
            array('lowest' => $ReputationRangs[5], 'highest' => $ReputationRangs[5]+11999), // Honored
            array('lowest' => $ReputationRangs[6], 'highest' => $ReputationRangs[6]+20999), // Revered
            array('lowest' => $ReputationRangs[7], 'highest' => $ReputationRangs[7]+999) // Exalted
        );
        $RankID = String::FindClosestKey($Reputation, $ReputationValue);
        return Characters::DataByReputationRankID($RankID);
    }

    private static function SubstituteByRang($RangID, $ReputationValue, $FactionID)
    {
        $ReputationRangs = Characters::ReputationByFaction($FactionID);
        if($RangID >= 0 && $RangID < 3) // Hated
            $Result = abs($ReputationRangs[$RangID]) - abs($ReputationValue);
        else
            $Result = $ReputationValue - $ReputationRangs[$RangID];
        return $Result;
    }

    public static function ReputationByFaction($FactionID)
    {
        $ProblematicFactions = array(
            '47' => array('-45100', '-9100', '-6100', '-3100', '-100', '5900', '17900', '38900'),
            '54' => array('-46000', '-10000', '-7000', '-4000', '-1000', '5000', '17000', '38000'),
            '68' => array('-42500', '-6500', '-3500', '-500', '2500', '8500', '20500', '41500'),
            '69' => array('-45100', '-9100', '-6100', '-3100', '-100', '5900', '17900', '38900'),
            '72' => array('-45100', '-9100', '-6100', '-3100', '-100', '5900', '17900', '38900'),
            '76' => array('-45100', '-9100', '-6100', '-3100', '-100', '5900', '17900', '38900'),
            '81' => array('-45100', '-9100', '-6100', '-3100', '-100', '5900', '17900', '38900'),
            '530' => array('-46000', '-10000', '-7000', '-4000', '-1000', '5000', '17000', '38000'),
            '911' => array('-42400', '-6400', '-3400', '-400', '2600', '8600', '20600', '41600'),
            '930' => array('-45000', '-9000', '-6000', '-3000', '0', '6000', '18000', '39000'),
        );

        $Factions = array( '-42000', '-6000', '-3000', '0', '3000', '9000', '21000', '42000');

        if(array_key_exists($FactionID, $ProblematicFactions))
            return $ProblematicFactions[$FactionID];
        else
            return $Factions;
    }

    public static function GetRecipesForProfession($ProfessionID)
    {
        $Statement = Characters::$DBConnection->prepare('
            SELECT
                fsla.spellID, fsla.skillID, fsla.req_skill_value, fsla.max_value, fsla.min_value, fs.reagent1, fs.reagent2, fs.reagent3, fs.reagent4, fs.reagent5, fs.reagent6, fs.reagent7, fs.reagent8, fs.reagentcount1, fs.reagentcount2, fs.reagentcount3, fs.reagentcount4, fs.reagentcount5, fs.reagentcount6, fs.reagentcount7, fs.reagentcount8, fs.effect1itemtype, fs.spellname_loc0 as recipe_name
            FROM
                freedomcore_skill_line_ability fsla
            LEFT JOIN freedomcore_spell fs ON
                fsla.spellID = fs.spellID
            WHERE
                fsla.skillID = :skillid
            ORDER BY fsla.req_skill_value DESC;
        ');
        $Statement->bindParam(':skillid', $ProfessionID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Recipe)
        {
            for($i = 1; $i < 9; $i++)
            {
                if($Recipe['reagent'.$i] != 0)
                {
                    $ItemInfo = Items::GetItemInfo($Recipe['reagent'.$i]);
                    $Result[$Index]['reagent'.$i.'_name'] = $ItemInfo['name'];
                    $Result[$Index]['reagent'.$i.'_icon'] = $ItemInfo['icon'];
                }
            }
            if($Recipe['effect1itemtype'] != 0)
            {
                $ItemInfo = Items::GetItemInfo($Recipe['effect1itemtype']);
                $Result[$Index]['resultingitem_name'] = $ItemInfo['name'];
                $Result[$Index]['resultingitem_icon'] = $ItemInfo['icon'];
            }
            $Index++;
        }
        return $Result;
    }

    public static function GetLearnedRecipesForProfession($ProfessionID, $CharacterGUID)
    {
        global $FCCore;
        $Statement = Characters::$CharConnection->prepare('
        SELECT
            cs.spell, fsla.skillID, fsla.req_skill_value, fsla.max_value, fsla.min_value, fs.reagent1, fs.reagent2, fs.reagent3, fs.reagent4, fs.reagent5, fs.reagent6, fs.reagent7, fs.reagent8, fs.reagentcount1, fs.reagentcount2, fs.reagentcount3, fs.reagentcount4, fs.reagentcount5, fs.reagentcount6, fs.reagentcount7, fs.reagentcount8, fs.effect1itemtype, fs.spellname_loc0 as recipe_name, it.Quality as effect1itemquality
        FROM
            character_spell cs
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_skill_line_ability fsla ON
            fsla.spellID = cs.spell
        LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_spell fs ON
            fsla.spellID = fs.spellID
        LEFT JOIN '.$FCCore['WorldDB']['database'] .'.item_template it ON
            fs.effect1itemtype = it.entry
        WHERE
                fsla.skillID = :profid
            AND
                cs.guid = :charguid
        ORDER BY fsla.req_skill_value DESC;
        ');
        $Statement->bindParam(':charguid', $CharacterGUID);
        $Statement->bindParam(':profid', $ProfessionID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Recipe)
        {
            for($i = 1; $i < 9; $i++)
            {
                if($Recipe['reagent'.$i] != 0)
                {
                    $ItemInfo = Items::GetItemInfo($Recipe['reagent'.$i]);
                    $Result[$Index]['reagent'.$i.'_name'] = $ItemInfo['name'];
                    $Result[$Index]['reagent'.$i.'_icon'] = $ItemInfo['icon'];
                }
            }
            if($Recipe['effect1itemtype'] != 0)
            {
                $ItemInfo = Items::GetItemInfo($Recipe['effect1itemtype']);
                $Result[$Index]['resultingitem_name'] = $ItemInfo['name'];
                $Result[$Index]['resultingitem_icon'] = $ItemInfo['icon'];
            }
            $Index++;
        }

        return $Result;
    }

    private static function DataByReputationRankID($RankID)
    {
        $Ranks = array(
            0 => array('rank' => '0', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Hated'), 'maximum' => '36000'),
            1 => array('rank' => '1', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Hostile'), 'maximum' => '3000'),
            2 => array('rank' => '2', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Unfriendly'), 'maximum' => '3000'),
            3 => array('rank' => '3', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Neutral'), 'maximum' => '3000'),
            4 => array('rank' => '4', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Friendly'), 'maximum' => '6000'),
            5 => array('rank' => '5', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Honored'), 'maximum' => '12000'),
            6 => array('rank' => '6', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Revered'), 'maximum' => '21000'),
            7 => array('rank' => '7', 'translation' => Characters::$TM->GetConfigVars('Reputation_Rank_Exalted'), 'maximum' => '999'),
        );

        return $Ranks[$RankID];
    }

    public static function GetCharacterProfessions($CharacterGuid)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT * FROM character_skills WHERE guid = :guid');
        $Statement->bindParam(':guid', $CharacterGuid);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        $CharacterProfessions = array();
        foreach($Result as $Profession)
        {
            $ProfessionData = Characters::GetProfessionData($Profession['skill']);
            if($ProfessionData != false)
            {
                $CharacterProfessions[] = array(
                    'id' => $Profession['skill'],
                    'name' => $ProfessionData['name'],
                    'primary' => $ProfessionData['primary'],
                    'translation' => $ProfessionData['translation'],
                    'current' => $Profession['value'],
                    'max' => $Profession['max'],
                    'title' => Characters::ProfessionTitle($Profession['max'])
                );
            }
        }

        return $CharacterProfessions;
    }

    private static function ProfessionTitle($ProfessionSkill)
    {
        $ProfessionLevel = array(
            '75' => Characters::$TM->GetConfigVars('Character_Professions_Title_Apprentice'),
            '150' => Characters::$TM->GetConfigVars('Character_Professions_Title_Journeyman'),
            '225' => Characters::$TM->GetConfigVars('Character_Professions_Title_Expert'),
            '300' => Characters::$TM->GetConfigVars('Character_Professions_Title_Artisan'),
            '375' => Characters::$TM->GetConfigVars('Character_Professions_Title_Master'),
            '450' => Characters::$TM->GetConfigVars('Character_Professions_Title_WotLK'),
            '525' => Characters::$TM->GetConfigVars('Character_Professions_Title_Cata'),
            '600' => Characters::$TM->GetConfigVars('Character_Professions_Title_Pandaria'),
            '700' => Characters::$TM->GetConfigVars('Character_Professions_Title_Draenor')
        );
        return $ProfessionLevel[$ProfessionSkill];
    }

    private static function GetProfessionData($ProfessionID)
    {
        $Professions = array(
            // Primary Professions
            '171' => array('primary' => 1, 'name' => 'alchemy', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Alchemy')),
            '164' => array('primary' => 1, 'name' => 'blacksmithing', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Blacksmithing')),
            '333' => array('primary' => 1, 'name' => 'enchanting', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Enchanting')),
            '202' => array('primary' => 1, 'name' => 'engineering', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Engineering')),
            '182' => array('primary' => 1, 'name' => 'herbalism', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Herbalism')),
            '773' => array('primary' => 1, 'name' => 'inscription', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Inscription')),
            '755' => array('primary' => 1, 'name' => 'jewelcrafting', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Jewelcrafting')),
            '165' => array('primary' => 1, 'name' => 'leatherworking', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Leatherworking')),
            '186' => array('primary' => 1, 'name' => 'mining', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Mining')),
            '393' => array('primary' => 1, 'name' => 'skinning', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Skinning')),
            '197' => array('primary' => 1, 'name' => 'tailoring', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Tailoring')),
            // Secondary Professions
            '794' => array('primary' => 0, 'name' => 'archaeology', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Archaeology')),
            '185' => array('primary' => 0, 'name' => 'cooking', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Cooking')),
            '129' => array('primary' => 0, 'name' => 'first-aid', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_First_aid')),
            '356' => array('primary' => 0, 'name' => 'fishing', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Fishing')),
            '762' => array('primary' => 0, 'name' => 'riding', 'translation' => Characters::$TM->GetConfigVars('Character_Professions_Riding')),

        );
        if(array_key_exists($ProfessionID, $Professions))
            return $Professions[$ProfessionID];
        else
            return false;
    }

    public static function CheckGuild($GuildName)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT name FROM guild WHERE name = :guildname');
        $Statement->bindParam(':guildname', $GuildName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['name'] == $GuildName)
            return true;
        else
            return false;
    }

    private static function GetRaceByID($RaceID)
    {
        $Races = array(
            '1' => array('translation' => Characters::$TM->GetConfigVars('Race_Human'),  'name' => 'human'),
            '2' => array('translation' => Characters::$TM->GetConfigVars('Race_Orc'), 'name' => 'orc'),
            '3' => array('translation' => Characters::$TM->GetConfigVars('Race_Dwarf'), 'name' => 'dwarf'),
            '4' => array('translation' => Characters::$TM->GetConfigVars('Race_Night_Elf'), 'name' => 'night-elf'),
            '5' => array('translation' => Characters::$TM->GetConfigVars('Race_Undead'), 'name' => 'undead'),
            '6' => array('translation' => Characters::$TM->GetConfigVars('Race_Tauren'), 'name' => 'tauren'),
            '7' => array('translation' => Characters::$TM->GetConfigVars('Race_Gnome'), 'name' => 'gnome'),
            '8' => array('translation' => Characters::$TM->GetConfigVars('Race_Troll'), 'name' => 'troll'),
            '9' => array('translation' => Characters::$TM->GetConfigVars('Race_Goblin'), 'name' => 'goblin'),
            '10' => array('translation' => Characters::$TM->GetConfigVars('Race_Blood_Elf'), 'name' => 'blood-elf'),
            '11' => array('translation' => Characters::$TM->GetConfigVars('Race_Draenei'), 'name' => 'draenei'),
            '12' => array('translation' => Characters::$TM->GetConfigVars('Race_Fel_Orc'), 'name' => 'fel-orc'),
            '13' => array('translation' => Characters::$TM->GetConfigVars('Race_Naga'), 'name' => 'naga'),
            '14' => array('translation' => Characters::$TM->GetConfigVars('Race_Broken'), 'name' => 'broken'),
            '15' => array('translation' => Characters::$TM->GetConfigVars('Race_Skeleton'), 'name' => 'skeleton'),
            '16' => array('translation' => Characters::$TM->GetConfigVars('Race_Vrykul'), 'name' => 'vrykul'),
            '17' => array('translation' => Characters::$TM->GetConfigVars('Race_Tuskarr'), 'name' => 'tuskarr'),
            '18' => array('translation' => Characters::$TM->GetConfigVars('Race_Forest_Troll'), 'name' => 'forest-troll'),
            '19' => array('translation' => Characters::$TM->GetConfigVars('Race_Taunka'), 'name' => 'taunka'),
            '20' => array('translation' => Characters::$TM->GetConfigVars('Race_Northrend_Skeleton'), 'name' => 'northrend-skeleton'),
            '21' => array('translation' => Characters::$TM->GetConfigVars('Race_Ice_Troll'), 'name' => 'ice-troll'),
            '22' => array('translation' => Characters::$TM->GetConfigVars('Race_Worgen'), 'name' => 'worgen'),
            '24' => array('translation' => Characters::$TM->GetConfigVars('Race_Pandaren'), 'name' => 'pandaren'),
            '25' => array('translation' => Characters::$TM->GetConfigVars('Race_Pandaren'), 'name' => 'pandaren'),
            '26' => array('translation' => Characters::$TM->GetConfigVars('Race_Pandaren'), 'name' => 'pandaren'),
        );

        return $Races[$RaceID];
    }

    private static function GetClassByID($ClassID)
    {
        $Classes = array(
            '1' => array('translation' => Characters::$TM->GetConfigVars('Class_Warrior'), 'name' => 'warrior'),
            '2' => array('translation' => Characters::$TM->GetConfigVars('Class_Paladin'), 'name' => 'paladin'),
            '3' => array('translation' => Characters::$TM->GetConfigVars('Class_Hunter'), 'name' => 'hunter'),
            '4' => array('translation' => Characters::$TM->GetConfigVars('Class_Rogue'), 'name' => 'rogue'),
            '5' => array('translation' => Characters::$TM->GetConfigVars('Class_Priest'), 'name' => 'priest'),
            '6' => array('translation' => Characters::$TM->GetConfigVars('Class_Death_Knight'), 'name' => 'death-knight'),
            '7' => array('translation' => Characters::$TM->GetConfigVars('Class_Shaman'), 'name' => 'shaman'),
            '8' => array('translation' => Characters::$TM->GetConfigVars('Class_Mage'), 'name' => 'mage'),
            '9' => array('translation' => Characters::$TM->GetConfigVars('Class_Warlock'), 'name' => 'warlock'),
            '10' => array('translation' => Characters::$TM->GetConfigVars('Class_Monk'), 'name' => 'monk'),
            '11' => array('translation' => Characters::$TM->GetConfigVars('Class_Druid'), 'name' => 'druid'),
        );
        return $Classes[$ClassID];
    }

    public static function GetSpecByTalents($CharacterGUID)
    {
        global $FCCore;
        $Statement = Characters::$DBConnection->prepare('
            SELECT
                lower(ftt.name_loc0) as name,
                ftt.name_loc0 as Description,
                ct.spec,
                c.activespec
            FROM
                freedomcore_talent ft
            LEFT JOIN '.$FCCore['CharDB']['database'].'.character_talent ct ON
                    ft.rank1 = ct.spell
                OR
                    ft.rank2 = ct.spell
                OR
                    ft.rank3 = ct.spell
                OR
                    ft.rank4 = ct.spell
                OR
                    ft.rank5 = ct.spell
            LEFT JOIN freedomcore_talenttab ftt ON
                	ft.tab = ftt.id
            JOIN '.$FCCore['CharDB']['database'].'.characters c ON
                ct.guid = c.guid
            WHERE ct.guid = :guid
            GROUP BY ct.spec;
        ');
        $Statement->bindParam(':guid', $CharacterGUID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }
}

?>