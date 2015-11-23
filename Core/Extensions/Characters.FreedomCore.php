<?php

Class Characters
{
    public static $DBConnection;
    public static $CharConnection;
    public static $WConnection;
    public static $TM;

    private static $CharacterLevel;
    private static $CharacterClass;
    private static $CharacterRace;

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
                $Result[$Index]['apoints'] = Characters::GetAchievementPoints($Character['guid'])['points'];
                $Index++;
            }
            return $Result;
        }
        else
            return 0;
    }

    public static function VerifyEligibility($Character, $Service)
    {
        $Eligible = false;
        $HasMail = false;
        $IsOnline = false;
        $Reasons = array();

        if(Characters::CheckCharacterInbox($Character))
            $HasMail = true;
        if(Characters::CheckIfCharacterOnline($Character))
            $IsOnline = true;

        if(!$HasMail && !$IsOnline)
            return array('eligible' => true, 'reasons' => array());
        else
            if($HasMail && !$IsOnline)
                return array('eligible' => false, 'reasons' => array(Characters::VerificationTranslation('20016Title')));
            elseif(!$HasMail && $IsOnline)
                return array('eligible' => false, 'reasons' => array(Characters::VerificationTranslation('20034Title')));
            elseif($HasMail && $IsOnline)
                return array('eligible' => false, 'reasons' => array(Characters::VerificationTranslation('20034Title')));
    }

    public static function GetShortProfileInfo($Character)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT guid, class, race, gender, level FROM characters WHERE name = :name');
        $Statement->bindParam(':name', $Character);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['class_name'] = Characters::GetClassByID($Result['class'])['translation'];
        $Result['race_name'] = Characters::GetRaceByID($Result['race'])['translation'];
        $Result['apoints'] = Characters::GetAchievementPoints($Result['guid'])['points'];

        return $Result;
    }

    private static function VerificationTranslation($Reason)
    {
        $ErrorTypes = array(
            '20012Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20012Title'),
            '20012Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20012Desc'),
            '20016Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20016Title'),
            '20016Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20016Desc'),
            '20032Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20032Title'),
            '20032Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20032Desc'),
            '20033Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20033Title'),
            '20033Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20033Desc'),
            '20034Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20034Title'),
            '20034Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20034Desc'),
            '20057Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20057Title'),
            '20057Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20057Desc'),
            '20064Title' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20064Title'),
            '20064Desc' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_20064Desc'),
            'unknown' => Characters::$TM->GetConfigVars('Account_Management_Service_Error_unknown')
        );
        if(array_key_exists($Reason, $ErrorTypes))
            return $ErrorTypes[$Reason];
        else
            return $ErrorTypes['unknown'];
    }

    private static function CheckCharacterInbox($CharacterName)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT m.*, c.name FROM mail m, characters c WHERE m.receiver = c.guid AND c.name = :name');
        $Statement->bindParam(':name', $CharacterName);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
            return true;
    }

    private static function CheckIfCharacterOnline($CharacterName)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT online FROM characters WHERE name = :name');
        $Statement->bindParam(':name', $CharacterName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Result['online'] == 0)
            return false;
        else
            return true;
    }

    public static function GetCharacterGlyphs($CharacterGuid)
    {
        $Statement = Characters::$CharConnection->prepare('SELECT * FROM character_glyphs WHERE guid = :guid');
        $Statement->bindParam(':guid', $CharacterGuid);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Glyphs)
        {
            unset($Result[$ArrayIndex]['guid']);
            unset($Result[$ArrayIndex]['talentGroup']);
            $ArrayIndex++;
        }
        $ArrayIndex = 0;
        foreach($Result as $Glyph)
        {
            for($i = 1; $i < 7; $i++)
                if($Glyph['glyph'.$i] != 0)
                {
                    $GlyphData = Spells::GetGlyphData($Glyph['glyph'.$i]);
                    $Result[$ArrayIndex]['glyph'.$i] = Spells::SpellInfo($GlyphData['spellid']);
                    $Result[$ArrayIndex]['glyph'.$i]['Name'] = str_replace('Glyph of', '', str_replace('Glyph of the', '', $Result[$ArrayIndex]['glyph'.$i]['Name']));
                    $Result[$ArrayIndex]['glyph'.$i]['icon'] = $GlyphData['icon'];
                }
            $ArrayIndex++;
        }
        return $Result;
    }

    private static function LevelStats($Race, $Class, $Level)
    {
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
        $LevelStatsStatement->bindParam(':race', $Race);
        $LevelStatsStatement->bindParam(':class', $Class);
        $LevelStatsStatement->bindParam(':level', $Level);
        $LevelStatsStatement->execute();
        $Result = $LevelStatsStatement->fetch(PDO::FETCH_ASSOC);
        $Result['level']    =   $Level;
        $Result['parrypoints'] = Characters::GetParryRatingByLevel($Level);
        $Result['blockpoints'] = Characters::GetBlockRatingByLevel($Level);
        $Result['critpoints'] = Characters::GetCritRatingByLevel($Level);
        $Result['hastepoints'] = Characters::GetHasteRatingByLevel($Level);
        return $Result;
    }

    public static function GetGearForCharacter($CharacterGuid)
    {
        $Statement = Characters::$CharConnection->prepare('
        SELECT
            ii.itemEntry,
            ii.enchantments,
            ci.slot
        FROM character_inventory ci
        LEFT JOIN item_instance ii ON
            ci.item = ii.guid
        WHERE
              ci.guid = :guid
            AND
              ii.itemEntry != 6948
            AND
              ci.bag = 0
            AND ci.slot < 19
        ORDER BY ci.slot
        ');
        $Statement->bindParam(':guid', $CharacterGuid);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Equipment = [];
        $ItemInstanceData = $Result;

        $FingerID = 11;
        $TrinketID = 12;
        $TwoHanderID = 17;
        foreach($Result as $Key=>$Item)
        {
            $ItemData = Items::GetItemInfo($Item['itemEntry']);
            if(Items::IsEquipment($ItemData['InventoryType'])){
                if($ItemData['InventoryType'] == 11){
                    $iData = Items::SiteSlotPositionByType($FingerID);
                    $FingerID = $FingerID + 100;
                    $Equipment[] = $ItemData['entry'].' = '.$iData['side'].' = '.$iData['placement'];
                }
                if($ItemData['InventoryType'] == 12){
                    $iData = Items::SiteSlotPositionByType($TrinketID);
                    $TrinketID = $TrinketID + 100;
                    $Equipment[] = $ItemData['entry'].' = '.$iData['side'].' = '.$iData['placement'];
                }
                if($ItemData['InventoryType'] == 17){
                    if($TwoHanderID > 120)
                        break;
                    $iData = Items::SiteSlotPositionByType($TwoHanderID);
                    $TwoHanderID = $TwoHanderID + 100;
                    $Equipment[] = $ItemData['entry'].' = '.$iData['side'].' = '.$iData['placement'];
                } else {
                    $iData = Items::SiteSlotPositionByType($ItemData['InventoryType']);
                    $Equipment[] = $ItemData['entry'].' = '.$iData['side'].' = '.$iData['placement'];
                }
            } else {
                unset($Result[$Key]);
            }
        }

        $Similarities = [];
        foreach($Equipment as $Item){
            $Exploded = explode('=', $Item);

            $ItemID = trim($Exploded[0]);
            $ItemSide = trim($Exploded[1]);
            $ItemPosition = trim($Exploded[2]);

            $Result = Text::Like($Equipment, $ItemID);
            if(count($Result) > 1){
                foreach($Result as $Key => $Value){
                    unset($Equipment[$Key]);
                }
                $Similarities[] = array_shift($Result);
            }
        }
        $Similarities = array_unique($Similarities);
        $Equipment = array_merge($Equipment, $Similarities);

        $EquippedItems = [];
        $LeftIndexes = [11, 12, 13, 14, 15, 16, 17, 18];
        $RightIndexes = [21, 22, 23, 24, 25, 26, 27,28];
        $BottomIndexes = [31, 32, 33];

        foreach($Equipment as $Item){
            $Exploded = explode('=', $Item);

            $ItemID = trim($Exploded[0]);
            $ItemSide = trim($Exploded[1]);
            $ItemPosition = trim($Exploded[2]);

            if($ItemSide == 'left')
                $Position = 10 + $ItemPosition;
            elseif($ItemSide == 'right')
                $Position = 20 + $ItemPosition;
            elseif($ItemSide == 'bottom')
                $Position = 30 + $ItemPosition;

            $EquippedItems[$Position]['site'] = ['side' => $ItemSide, 'position' => $ItemPosition];
            $EquippedItems[$Position]['data'] = Items::GetItemInfo($ItemID);
            $EntryID = Text::MASearch($ItemInstanceData, 'itemEntry', $ItemID);
            $Enchantments = $ItemInstanceData[$EntryID]['enchantments'];
            if(Items::isItemEnchanted($Enchantments))
                $EquippedItems[$Position]['data']['enchanted'] = 1;
            else
                $EquippedItems[$Position]['data']['enchanted'] = 0;

            if($EquippedItems[$Position]['data']['enchanted']){
                $SocketsCount = 1;
                $SpellsCount = 1;
                $EnchantmentsList = Items::getEnchantments($Enchantments);
                $EnchantmentsData = [];
                foreach($EnchantmentsList as $EnchantmentID)
                    if($EnchantmentID != $EquippedItems[$Position]['data']['socketBonus']){
                        $Data = Items::getEnchantmentData($EnchantmentID);
                        if($Data['is_socket']){
                            $EnchantmentsData['socket'.$SocketsCount] = $Data;
                            $SocketsCount++;
                        } else {
                            $EnchantmentsData['spell'.$SpellsCount] = $Data;
                            $SpellsCount++;
                        }
                    }

                $EquippedItems[$Position]['enchantments'] = $EnchantmentsData;
            }
        }

        foreach($LeftIndexes as $Index)
            if(!isset($EquippedItems[$Index])){
                $EquippedItems[$Index]['site'] = ['side' => 'left', 'position' => ($Index - 10)];
                $SearchResult = Text::Search(Items::SiteSlotPositionByType(null, true), ['side' => 'left', 'placement' => ($Index - 10)])[0];
                $EquippedItems[$Index]['data'] = ['InventoryType' => $SearchResult];
            }
        foreach($RightIndexes as $Index)
            if(!isset($EquippedItems[$Index])){
                $EquippedItems[$Index]['site'] = ['side' => 'right', 'position' => ($Index - 20)];
                $SearchResult = Text::Search(Items::SiteSlotPositionByType(null, true), ['side' => 'left', 'placement' => ($Index - 20)])[0];
                $EquippedItems[$Index]['data'] = ['InventoryType' => $SearchResult];
            }
        foreach($BottomIndexes as $Index)
            if(!isset($EquippedItems[$Index])){
                $EquippedItems[$Index]['site'] = ['side' => 'bottom', 'position' => ($Index - 30)];
                $SearchResult = Text::Search(Items::SiteSlotPositionByType(null, true), ['side' => 'left', 'placement' => ($Index - 30)])[0];
                $EquippedItems[$Index]['data'] = ['InventoryType' => $SearchResult];
            }

        ksort($EquippedItems);

        $ItemLevel = 0;
        $ItemsCount = 0;
        foreach($EquippedItems as $Item)
            if(isset($Item['data']['entry']) && $Item['data']['InventoryType'] != 19 && $Item['data']['InventoryType'] != 4)
                if(isset($Item['data']))
                {
                    $ItemLevel = $ItemLevel + $Item['data']['ItemLevel'];
                    $ItemsCount++;
                }

        $Result = $EquippedItems;

        $LevelData = Characters::LevelStats(Characters::$CharacterRace, Characters::$CharacterClass, Characters::$CharacterLevel);
        $StrengthValue = $LevelData['str'];
        $AgilityValue = $LevelData['agi'];
        $IntellectValue = $LevelData['inte'];
        $StaminaValue = $LevelData['sta'];
        $SpiritValue = $LevelData['spi'];
        $ArmorValue = 2 * $LevelData['agi'];
        $ParryValue = 0;
        $DodgeValue = 0;
        $BlockValue = 0;
        $CritValue = 0;
        $HasteValue = 0;
        $SpellPowerValue = 0;
        $ArmorPenetrationValue = 0;
        $SpellPenetrationValue = 0;

        $MainHandSpeed = 0;
        $OffHandSpeed = 0;
        $RangedSpeed = 0;
        $MainHandDps = 0;
        $OffHandDps = 0;
        $RangedDps = 0;

        $MHD = [];
        $OHD = [];
        $RHD = [];
        $isTwoHanders = false;

        foreach($Result as $Item) {
            if (isset($Item['data'])) {
                if (isset($Item['data']['armor']) && $Item['data']['armor'] != 0)
                    $ArmorValue = $ArmorValue + $Item['data']['armor'];

                for($i = 1; $i <=5; $i++)
                {
                    if(isset($Item['data']['stat_type'.$i]) && $Item['data']['stat_type'.$i] != 0)
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
                        elseif ($Item['data']['stat_type'.$i] == 45)
                            $SpellPowerValue = $SpellPowerValue + $Item['data']['stat_value'.$i];
                        elseif ($Item['data']['stat_type'.$i] == 48)
                            $BlockValue = $BlockValue + $Item['data']['stat_value'.$i];
                    }
                }

                if(isset($Item['data']['enchanted']) && $Item['data']['enchanted']){
                    for($i = 1; $i <=3; $i++){
                        if(isset($Item['enchantments']['socket'.$i])){
                            $SocketData = $Item['enchantments']['socket'.$i];

                            $BonusData = Items::GetValueVariable($SocketData['text_loc0']);
                            if(count($BonusData) > 1){
                                foreach($BonusData as $Bonus){
                                    $Value = $Bonus['value'];
                                    $Points = $Bonus['points'];
                                    $$Value = $$Value + $Points;
                                }
                            } else {
                                $Bonus = $BonusData[0];
                                $Value = $Bonus['value'];
                                $Points = $Bonus['points'];
                                $$Value = $$Value + $Points;
                            }
                        }
                    }
                }

                if($Item['site']['side'] == 'bottom' && $Item['site']['position'] == 1){
                    $MainHandSpeed = $Item['data']['delay'];
                    $MainHandDps = round(($Item['data']['dmg_min1'] + $Item['data']['dmg_max1'])/2/($Item['data']['delay']/1000), 2);

                    $MHD = [
                        'min' => $Item['data']['dmg_min1'],
                        'max' => $Item['data']['dmg_max1'],
                        'speed' => round($Item['data']['delay'] / 1000, 1),
                    ];
                }
                if($Item['site']['side'] == 'bottom' && $Item['site']['position'] == 2 && isset($Item['data']['entry'])){
                    if($Item['data']['InventoryType'] == 17)
                        $isTwoHanders = true;
                    $OffHandSpeed = $OffHandSpeed = $Item['data']['delay'];
                    $OffHandDps = round(($Item['data']['dmg_min1'] + $Item['data']['dmg_max1'])/2/($Item['data']['delay']/1000), 2);
                    $OHD = [
                        'min' => $Item['data']['dmg_min1'],
                        'max' => $Item['data']['dmg_max1'],
                        'speed' => round($Item['data']['delay'] / 1000, 1),
                        'name'  => $Item['data']['name']
                    ];
                }
                if($Item['site']['side'] == 'bottom' && $Item['site']['position'] == 3){
                    $RangedSpeed = $Item['data']['delay'];
                    $RangedDps = round(($Item['data']['dmg_min1'] + $Item['data']['dmg_max1'])/2/($Item['data']['delay']/1000), 2);
                    $RHD = [
                        'min' => $Item['data']['dmg_min1'],
                        'max' => $Item['data']['dmg_max1'],
                        'speed' => round($Item['data']['delay'] / 1000, 1),
                    ];
                }
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
        $Result['SpellPowerValue'] = $SpellPowerValue;
        $Result['ArmorPenetration'] = $ArmorPenetrationValue;
        $Result['SpellPenetration'] = $SpellPenetrationValue;


        //Main Hand Stats
        $Result['MainHandSpeed'] = $MainHandSpeed;
        $Result['MainHandDps'] = $MainHandDps;
        $Result['MainHandData'] = $MHD;


        //Off Hand Stats
        $Result['OffHandSpeed'] = $OffHandSpeed;
        $Result['OffHandDps'] = $OffHandDps;
        $Result['OffHandData'] = $OHD;

        //Ranged Stats
        $Result['RangedSpeed'] = $RangedSpeed;
        $Result['RangedDps'] = $RangedDps;
        $Result['RangedData'] = $RHD;

        $BaseAP = Characters::CalculateAttackPower(Characters::$CharacterClass, Characters::$CharacterLevel, $StrengthValue, $AgilityValue);
        if($isTwoHanders){
            $AttackPower = ($Result['OffHandDps'] + $BaseAP/3.5) * $OffHandSpeed / 1000;
        } else {
            $AttackPower = ($MainHandDps + $BaseAP/3.5) * $MainHandSpeed / 1000;
        }

        if(!empty($Result['MainHandData']))
            $Result['MainHandDamage'] = Items::CalculateWeaponDamage($Result['MainHandData'], $AttackPower);
        else
            $Result['MainHandDamage'] = ['minimum' => -1, 'maximum' => -1, 'dps' => -1, 'speed' => -1];

        if(!empty($Result['OffHandData']))
            $Result['OffHandDamage'] = Items::CalculateWeaponDamage($Result['OffHandData'], $AttackPower);
        else
            $Result['OffHandDamage'] = ['minimum' => -1, 'maximum' => -1, 'dps' => -1, 'speed' => -1];

        if(!empty($Result['RangedData']))
            $Result['RangedDamage'] = Items::CalculateWeaponDamage($Result['RangedData'], $AttackPower);
        else
            $Result['RangedDamage'] = ['minimum' => -1, 'maximum' => -1, 'dps' => -1, 'speed' => -1];

        $Result['AttackPower'] = round($AttackPower, 0);
        $Result['DamageReduction'] = Characters::DamageReductionByLevel(Characters::$CharacterLevel, $ArmorValue);
        $Result['TotalItemLevel'] = $ItemLevel;
        $Result['EquippedItems'] = $ItemsCount;
        $Result['hasOffhand'] = $isTwoHanders;
        return $Result;
    }

    public static function generateCharacterInventorySQL($CharacterGUID, $Items){
        $SQLArray = [];

        $RingInArray = false;
        $TrinketInArray = false;
        $OneHandInArray = false;

        $ItemsData = Items::getDataForItemInstance($Items);
        foreach($ItemsData as $ItData){
            $Item = $ItData['entry'];

            if($ItData['InventoryType'] == 11) {
                if(!$RingInArray){
                    $TypeID = $ItData['InventoryType'] + 20;
                    $RingInArray = true;
                } else {
                    $TypeID = $ItData['InventoryType'] + 21;
                }
            } elseif($ItData['InventoryType'] == 12) {
                if(!$TrinketInArray){
                    $TypeID = $ItData['InventoryType'] + 30;
                    $TrinketInArray = true;
                } else {
                    $TypeID = $ItData['InventoryType'] + 31;
                }
            } elseif($ItData['InventoryType'] == 13){
                if(!$OneHandInArray){
                    $TypeID = $ItData['InventoryType'] + 27;
                    $OneHandInArray = true;
                } else {
                    $TypeID = $ItData['InventoryType'] + 28;
                }
            } else {
                $TypeID = $ItData['InventoryType'];
            }

            $ItemSlot = Items::InventoryTypeToCharacterInventory($TypeID);

            $SQLArray[] = "INSERT INTO character_inventory (guid, bag, slot, item) VALUES ('$CharacterGUID', '0', '$ItemSlot', (SELECT guid FROM item_instance WHERE itemEntry = '$Item' AND owner_guid = '$CharacterGUID'))";
        }
        $SQLArray[] = "INSERT INTO character_inventory (guid, bag, slot, item) VALUES ('$CharacterGUID', '0', '23', (SELECT guid FROM item_instance WHERE itemEntry = '6948' AND owner_guid = '$CharacterGUID'))";
        return $SQLArray;
    }

    public static function generateCharacterSkillsSQL($CharacterGUID, $Professions){
        $SQLArray = [];

        foreach($Professions as $Skill){
            $SkillID = $Skill['skill'];
            $SkillValue = $Skill['new_value'];
            $SkillMax = $Skill['new_max'];

            $SQLArray[] = "UPDATE character_skills SET value = '$SkillValue', max = '$SkillMax' WHERE guid = '$CharacterGUID' AND skill = '$SkillID'";
        }

        if(empty(Text::Search($Professions, ['skill' => 762]))){
            $SQLArray[] = "INSERT INTO character_skills (guid, skill, value, max) VALUES ('$CharacterGUID', '762', '450', '450')";
        }

        return $SQLArray;
    }

    public static function generateCharacterSpellsSQL($CharacterGUID, $Spells){

        $NewSpells = [];
        $SQLArray = [];

        foreach($Spells as $Spell){
            $Status = Database::getSingleRow('Characters', 'SELECT * FROM character_spell WHERE guid = :guid AND spell = :spell', [['id' => ':guid', 'value' => $CharacterGUID], ['id' => ':spell', 'value' => $Spell]]);
            if(!$Status)
                $NewSpells[] = $Spell;
        }

        foreach($NewSpells as $Spell){
            $SQLArray[] = "INSERT INTO character_spell (guid, spell, active, disabled) VALUES ('$CharacterGUID', '$Spell', '1', '0')";
        }

        return $SQLArray;
    }

    public static function generateCharacterLevelUP($CharacterGUID){
        $SQLArray = [];

        $SQLArray[] = "UPDATE characters SET level = '80', position_x = '5726.00', position_y = '543.352', position_z = '653.00', map = '571', orientation = '4' WHERE guid = '$CharacterGUID'";
        $SQLArray[] = "DELETE FROM character_queststatus WHERE guid = '$CharacterGUID'";
        $SQLArray[] = "DELETE FROM character_inventory WHERE guid = '$CharacterGUID'";
        $SQLArray[] = "DELETE FROM item_instance WHERE owner_guid = '$CharacterGUID'";

        return $SQLArray;
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
                atm.weekGames,
                atm.weekWins,
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

    public static function SetAtLoginState($CharacterName, $State)
    {
        $Statement = Characters::$CharConnection->prepare('UPDATE characters SET at_login = :state WHERE name = :charname');
        $Statement->bindParam(':charname', $CharacterName);
        $Statement->bindParam(':state', $State);
        $Statement->execute();
        return true;
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
        $StatByClass = Characters::StatByClass($Result['class']);
        $Result['power_data'] = $StatByClass;
        $Result['power_data']['value'] = @$Result[$StatByClass['field']];
        Characters::$CharacterLevel = $Result['level'];
        Characters::$CharacterClass = $Result['class'];
        Characters::$CharacterRace = $Result['race'];
        return $Result;
    }

    private static function StatByClass($ClassID)
    {
        $Stats = [
            1 => ['id' => 1, 'field' => 'power2', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_rage_title'))],
            2 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))],
            3 => ['id' => 2, 'field' => 'power3', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_focus_title'))],
            4 => ['id' => 3, 'field' => 'power4', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_energy_title'))],
            5 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))],
            6 => ['id' => 6, 'field' => 'power7', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_runic_title'))],
            7 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))],
            8 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))],
            9 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))],
            10 => ['id' => 3, 'field' => 'power4', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_energy_title'))],
            11 => ['id' => 0, 'field' => 'power1', 'translation' => str_replace('{0}', '', Characters::$TM->GetConfigVars('MSG_Summary_Stats_mana_title'))]
        ];
        return $Stats[$ClassID];
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
            '1' => ($Level*3)+($Strength*2-20),
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
        $RankID = Text::FindClosestKey($Reputation, $ReputationValue);
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

    public static function getCharactersProfessions($CharacterGUID)
    {
        $Professions = [];

        $Data = Database::getMultiRow('Characters', 'SELECT * FROM character_skills WHERE guid = :guid', [['id' => ':guid', 'value' => $CharacterGUID]]);
        foreach($Data as $Skill){
            $Profession = self::GetProfessionData($Skill['skill']);
            if($Profession){
                $Skill['profession_name'] = $Profession['translation'];
                $Professions[] = $Skill;
            }
        }

        return $Professions;
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

    public static function GetRaceByID($RaceID)
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

    public static function GetClassByID($ClassID, $Menu = false)
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
        if(!$Menu)
            return $Classes[$ClassID];
        else
            return $Classes;
    }

    public static function GetSpecByTalents($CharacterGUID)
    {
        global $FCCore;
        $Statement = Characters::$DBConnection->prepare('
            SELECT
                lower(ftt.name_loc0) as name,
                ftt.name_loc0 as Description,
                ct.talentGroup,
                c.activeTalentGroup
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
            GROUP BY ct.talentGroup;
        ');
        $Statement->bindParam(':guid', $CharacterGUID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }
}

Class Raids
{

    public static function GetRaids($CharacterGUID, $Expansion, $Heroic)
    {
        $Raids = Raids::DataByExpansion($Expansion);
        $SingleBoss = array();
        $MultiBoss = array();
        foreach($Raids['bosses'] as $Key=>$Value)
        {
            if(is_array($Raids['bosses'][$Key]))
                $MultiBoss[$Key] = Raids::GetNormalRaidsManyBosses($CharacterGUID, $Expansion, $Key, $Raids['bosses'][$Key], $Raids['criteria'][$Key], $Heroic);
            else
                $SingleBoss[$Key] = Raids::GetNormalRaidsOneBoss($CharacterGUID, $Expansion, $Key, $Raids['bosses'][$Key], $Raids['criteria'][$Key], $Heroic);

        }
        $CombinedArray = array_merge($SingleBoss, $MultiBoss);
        $FinalArray = array();
        foreach($Raids['bosses'] as $Key=>$Value)
        {
            foreach($CombinedArray as $Instance)
            {
                if($Key == $Instance['data']['instance'])
                    $FinalArray[$Key] = $Instance;
            }
        }
        return $FinalArray;
    }

    public static function GetNormalRaidsOneBoss($CharacterGUID, $Expansion, $Instance, $Bosses, $Criteria, $Heroic)
    {
        if(is_array($Criteria))
        {
            $ParametersIDs = ':id_'.implode(',:id_', array_keys($Criteria));
            $IDsAndValues = array_combine(explode(",", $ParametersIDs), $Criteria);
            $IDsAndValues = Text::UnshiftAssoc($IDsAndValues, ':guid', $CharacterGUID);
        }
        else
        {
            $ParametersIDs = $Criteria;
            $IDsAndValues = array();;
            $IDsAndValues = Text::UnshiftAssoc($IDsAndValues, ':guid', $CharacterGUID);
        }
        $HTML = "";
        $RaidData = array();

        $CharactersSQL = "SELECT * FROM character_achievement_progress WHERE guid = :guid AND criteria IN (".$ParametersIDs.")";
        $CharactersData = Characters::$CharConnection->prepare($CharactersSQL);
        $CharactersData->execute($IDsAndValues);
        $Result = $CharactersData->fetchAll(PDO::FETCH_ASSOC);
        $CountResults = count($Result);
        $BossKills = 0;

        if(empty($Result))
        {
            $HTML .= Raids::CreateSimpleHTML($Instance, 'incomplete').PHP_EOL;
            $RaidData = Raids::CreateOneBossRaid($CharacterGUID, $Instance, $Expansion, $Bosses, 0);
        }
        else
        {
            foreach($Result as $InstanceData)
                $BossKills = $BossKills + $InstanceData['counter'];
            $HTML .= Raids::CreateSimpleHTML($Instance, 'completed').PHP_EOL;
            $RaidData = Raids::CreateOneBossRaid($CharacterGUID, $Instance, $Expansion, $Bosses, $BossKills);
        }
        return array('data' => $RaidData, 'html' => $HTML);
    }

    private static function CreateOneBossRaid($CharacterGUID, $Instance, $Expansion, $NpcID, $Counter)
    {
        $DummyRaid = array(
            "guid" => $CharacterGUID,
            "counter" => $Counter,
            "expansion" => $Expansion,
            "instance" => $Instance,
            'npcs' => array(Raids::GetNPCInfo($NpcID))
        );
        return $DummyRaid;
    }

    public static function GetNormalRaidsManyBosses($CharacterGUID, $Expansion, $Instance, $Bosses, $Criteria, $Heroic)
    {
        global $FCCore;
        $ParametersIDs = ':id_'.implode(',:id_', array_keys($Criteria));
        $IDsAndValues = array_combine(explode(",", $ParametersIDs), $Criteria);
        $IDsAndValues = Text::UnshiftAssoc($IDsAndValues, ':guid', $CharacterGUID);
        $HTML = "";
        $RaidData = array();
        $CharactersSQL = "SELECT cap.*, fa.value1 as bossid FROM character_achievement_progress cap LEFT JOIN ".$FCCore['Database']['database'].".freedomcore_achievementcriteria fa ON cap.criteria = fa.id WHERE guid = :guid AND criteria IN (".$ParametersIDs.")";
        $CharactersData = Characters::$CharConnection->prepare($CharactersSQL);
        $CharactersData->execute($IDsAndValues);
        $Result = $CharactersData->fetchAll(PDO::FETCH_ASSOC);
        $BossKills = 0;
        if(empty($Result))
        {
            $AchievementsPerBoss = count($Criteria)/count($Bosses);
            $BossID = 0;
            $CriteriaID = 0;
            $ArrayIndex = 0;
            $RaidData['guid'] = $CharacterGUID;
            $RaidData['expansion'] = $Expansion;
            $RaidData['instance'] = $Instance;
            foreach($Bosses as $Boss)
            {
                $BossCriteria = array_slice($Criteria, 0, 2);
                if(is_numeric($Boss))
                    if(strlen((string)$Boss) == 6)
                        $RaidData['npcs'][$ArrayIndex] = Raids::GetObjectInfo($Boss);
                    else
                        $RaidData['npcs'][$ArrayIndex] = Raids::GetNPCInfo($Boss);
                else
                    $RaidData['npcs'][$ArrayIndex] = array('name' => $Boss);
                $RaidData['npcs'][$ArrayIndex]['counter'] = 0;
                unset($Criteria[0]);
                unset($Criteria[1]);
                $Criteria = array_values($Criteria);
                $CriteriaID++;
                $ArrayIndex++;
            }
            $HTML .= Raids::CreateSimpleHTML($Instance, 'incomplete').PHP_EOL;
        }
        else
        {
            $BossesInInstance = count($Bosses);
            $DataForBosses = count($Result);
            $RaidData['guid'] = $CharacterGUID;
            $RaidData['expansion'] = $Expansion;
            $RaidData['instance'] = $Instance;
            $BossID = 0;
            foreach($Bosses as $Boss)
            {
                $BossKills = 0;
                foreach($Result as $Data)
                {
                    if($Boss == $Data['bossid'])
                    {
                        $BossCriteriaPortion = array_slice($Criteria, $BossID*2, 2);
                        if(in_array($Data['criteria'], $BossCriteriaPortion))
                        {
                            $BossKills = $BossKills + $Data['counter'];
                            if(is_numeric($Boss))
                                if(strlen((string)$Boss) == 6)
                                    $RaidData['npcs'][$BossID] = Raids::GetObjectInfo($Boss);
                                else
                                    $RaidData['npcs'][$BossID] = Raids::GetNPCInfo($Boss);
                            else
                                $RaidData['npcs'][$BossID] = array('name' => $Boss);
                            $RaidData['npcs'][$BossID]['counter'] = $BossKills;
                        }
                    }
                    else
                    {
                        if(is_numeric($Boss))
                            if(strlen((string)$Boss) == 6)
                                $RaidData['npcs'][$BossID] = Raids::GetObjectInfo($Boss);
                            else
                                $RaidData['npcs'][$BossID] = Raids::GetNPCInfo($Boss);
                        else
                            $RaidData['npcs'][$BossID] = array('name' => $Boss);
                        $RaidData['npcs'][$BossID]['counter'] = 0;
                    }
                }
                $BossID++;
            }
            if($BossesInInstance == $DataForBosses)
                $HTML .= Raids::CreateSimpleHTML($Instance, 'completed').PHP_EOL;
            else
                $HTML .= Raids::CreateSimpleHTML($Instance, 'in-progress').PHP_EOL;
        }
        return array('data' => $RaidData, 'html' => $HTML);
    }

    private static function CreateMultiBossRaid($CharacterGUID, $Instance, $Expansion, $NpcID, $Counter)
    {

    }

    private static function GetNPCInfo($NpcID)
    {
        $Statement = Characters::$WConnection->prepare('SELECT entry, name, minlevel-3 as instance_level FROM creature_template WHERE entry = :entry');
        $Statement->bindParam(':entry', $NpcID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetObjectInfo($ObjectID)
    {
        $Statement = Characters::$WConnection->prepare('SELECT entry, name FROM gameobject_template WHERE entry = :entry');
        $Statement->bindParam(':entry', $ObjectID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function CreateSimpleHTML($RaidName, $RaidStatus)
    {
        $String = '<td data-raid="'.$RaidName.'" class="status status-'.$RaidStatus.'"><div></div></td>'.PHP_EOL;
        $String .= '<td></td>'.PHP_EOL;
        return $String;
    }

    private static function DataByExpansion($ExpansionID)
    {
        $Data = array(
            '0' => array(
                'bosses' => array('mc' => 11502, 'bwl' => 11583, 'aq10' => 15339, 'aq40' => 15727),
                'criteria' => array('mc' => 595, 'bwl' => 594, 'aq10' => 598, 'aq40' => 596)
            ),
            '1' => array(
                'bosses' => array('kar' => 15690, 'mag' => 17257, 'gru' => 19044, 'ssc' => 21212, 'tk' => 19622, 'mh' => 17968, 'bt' => 22917, 'sp' => 25315),
                'criteria' => array('kar' => 599, 'mag' => 602, 'gru' => 601, 'ssc' => 603, 'tk' => 605, 'mh' => 604, 'bt' => 606, 'sp' => 607)
            ),
            '2' => array(
                'bosses' => array(
                    'voa' => array(31125, 33993, 35013, 38433),
                    'nax' => array(15956, 15953, 15952, 15954, 15936, 16011, 16061, 16060, 16062, 16028, 15931, 15932, 15928, 15989, 15990),
                    'os' => 28860,
                    'eoe' => 28859,
                    'uld' => array(33113, 33118, 33186, 33293, Characters::$TM->GetConfigVars('Raids_Ulduar_The_Assembly_Of_Iron'), 32930, 33515, 32845, 32865, 32906, 33350, 33271, 33288, 32871),
                    'ony' => 10184,
                    'toc' => array(34797, 34780, 195631, 34496, 34564),
                    'icc' => array(36612, 36855, 201873, 37813, 36626, 36627, 36678, 37970, 37955, 36789, 36853, 36597),
                    'rs' => 39863
                ),
                'criteria' => array(
                    'voa' => array(6395, 6396, 9952, 10542, 11902, 11903, 13107, 13108),
                    'nax' => array(5100, 5111, 5101, 5126, 5102, 5132, 5104, 5133, 5112, 5128, 5113, 5131, 5120, 5130, 5108, 5125, 5129, 7806, 5103, 5110, 5114, 5127, 5117, 5124, 5119, 5134, 5122, 5135, 5123, 5136),
                    'os' => array(5138, 5139),
                    'eoe' => array(5137, 5140),
                    'uld' => array(9938, 9954, 9940, 9956, 9939, 9955, 9941, 9957, 10580, 10581, 9943, 9959, 9950, 9966, 10560, 10560, 10558, 10558, 10559, 10559, 9947, 9963, 9948, 9964, 9951, 9967, 10565, 10566),
                    'ony' => 3271,
                    'toc' => array(12228, 12230, 12232, 12234, 12236, 12238, 12240, 12242, 12244, 12246),
                    'icc' => array(13089, 13092, 13093, 13105, 13094, 13111, 13095, 13112, 13096, 13115, 13097, 13118, 13110, 13127, 13098, 13121, 13101, 13130, 13099, 13124, 13102, 13133, 13103, 13136),
                    'rs' => array(13466, 13465)
                )
            )
        );

        return $Data[$ExpansionID];
    }
}

?>