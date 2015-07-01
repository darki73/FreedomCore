<?php

Class Zones
{
    public static $DBConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Zones::$DBConnection = $VariablesArray[0]::$Connection;
        Zones::$WConnection = $VariablesArray[0]::$WConnection;
        Zones::$TM = $VariablesArray[1];
    }

    public static function GetZonesForLandingPage()
    {
        $Statement = Zones::$DBConnection->prepare('SELECT * FROM raidsandinstances');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach ($Result as $Instance)
        {
            $Result[$ArrayIndex]['name'] = Zones::$TM->GetConfigVars($Instance['name']);
            $Result[$ArrayIndex]['instance_type'] = Zones::ZoneTypeByID($Instance['instance_type']);
            $Result[$ArrayIndex]['group_name'] = Zones::$TM->GetConfigVars($Instance['group_name']);
            $ArrayIndex++;
        }
        return $Result;
    }

    public static function GetZoneInfoByName($ZoneName)
    {
        $Statement = Zones::$DBConnection->prepare('SELECT rai.*, fz.name_loc0 as zone_name FROM raidsandinstances rai LEFT JOIN freedomcore_zones fz ON rai.zone = fz.areatableID WHERE rai.link_name = :zonename');
        $Statement->bindParam(':zonename', $ZoneName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['name'] = Zones::$TM->GetConfigVars($Result['name']);
        $Result['instance_type'] = Zones::ZoneTypeByID($Result['instance_type']);
        $Result['expansion_required'] = Zones::Expansion($Result['expansion_required']);
        $Result['bosses'] = Zones::BossesForZone($Result['map']);
        return $Result;
    }

    public static function GetZoneInfoByID($ZoneID)
    {
        $Statement = Zones::$DBConnection->prepare('SELECT rai.*, fz.name_loc0 as zone_name FROM raidsandinstances rai LEFT JOIN freedomcore_zones fz ON rai.zone = fz.areatableID WHERE rai.zone = :zoneid');
        $Statement->bindParam(':zoneid', $ZoneID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['name'] = Zones::$TM->GetConfigVars($Result['name']);
        $Result['instance_type'] = Zones::ZoneTypeByID($Result['instance_type']);
        $Result['expansion_required'] = Zones::Expansion($Result['expansion_required']);
        $Result['tooltip_description'] = Zones::$TM->GetConfigVars($Result['tooltip_description']);
        $Result['zone_description'] = Zones::$TM->GetConfigVars($Result['zone_description']);
        return $Result;
    }

    public static function DownloadScreenshots($ZoneInfo, $ChosenLang)
    {
        $ScreenshotDir = str_replace('/', DS, getcwd()).DS.'Templates'.DS.'FreedomCore'.DS.'images'.DS.'wiki'.DS.'zone'.DS.'screenshots'.DS;
        $ThumbDir = str_replace('/', DS, getcwd()).DS.'Templates'.DS.'FreedomCore'.DS.'images'.DS.'wiki'.DS.'zone'.DS.'thumbnails'.DS;
        $ItemName = $ZoneInfo['link_name'].'.jpg';
        if(!File::Exists($ScreenshotDir.$ItemName))
            File::Download('http://eu.battle.net/wow/static/images/wiki/zone/screenshots/'.$ItemName, $ScreenshotDir.$ItemName);
        if(!File::Exists($ThumbDir.$ItemName))
            File::Download('http://eu.battle.net/wow/static/images/wiki/zone/thumbnails/'.$ItemName, $ThumbDir.$ItemName);
    }

    public static function DownloadMap($ZoneInfo, $ChosenLang)
    {
        $ItemName = $ZoneInfo['link_name'].'.jpg';
        $MapDir = str_replace('/', DS, getcwd()).DS.'Templates'.DS.'FreedomCore'.DS.'images'.DS.'dungeon-maps'.DS.$ChosenLang.DS.$ZoneInfo['link_name'].DS;
        if(!File::Exists($MapDir.$ItemName))
        {
            File::Download('http://media.blizzard.com/wow/media/artwork/dungeon-maps/'.$ChosenLang.'/'.$ZoneInfo['link_name'].'/'.$ItemName, $MapDir.$ItemName);
            File::Download('http://media.blizzard.com/wow/media/artwork/dungeon-maps/'.$ChosenLang.'/'.$ZoneInfo['link_name'].'/'.$ZoneInfo['link_name'].'1-large.jpg', $MapDir.$ZoneInfo['link_name'].'1-large.jpg');
        }
    }

    public static function DownloadBossIcon($CreatureID)
    {
        $StorageDir = str_replace('/', DS, getcwd()).DS.'Uploads'.DS.'Core'.DS.'NPC'.DS.'Cache'.DS;
        $CreatureName = 'creature'.$CreatureID.'.jpg';
        $DownloadStatus = true;
        if(!File::Exists($StorageDir.$CreatureName))
            $DownloadStatus = File::Download('http://media.blizzard.com/wow/renders/npcs/portrait/'.$CreatureName, $StorageDir.$CreatureName);

        return $DownloadStatus;
    }

    private static function BossesForZone($MapID)
    {
        $Statement = Zones::$WConnection->prepare("
            SELECT
              ct.entry,
	          c.modelid,
	          c.curhealth,
	          c.curmana,
	          ct.name,
              ct.subname,
              ct.maxlevel,
              c.spawntimesecs
            FROM
                creature c
            LEFT JOIN creature_template ct ON
                c.id = ct.entry
            WHERE
                    ct.rank BETWEEN 1 AND 3
                AND
                    c.map = :mapid
                AND
                    c.spawntimesecs BETWEEN 6380 AND 604801
                AND
					ct.ScriptName LIKE '%boss_%'
        ");
        $Statement->bindParam(':mapid', $MapID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Boss)
        {
            $Result[$ArrayIndex]['boss_link'] = strtolower(str_replace(' ', '-', str_replace("'", '', $Boss['name'])));
            if(!Zones::DownloadBossIcon($Boss['entry']))
                unset($Result[$ArrayIndex]);
            $ArrayIndex++;
        }
        return $Result;
    }

    public static function GetNPCInfo($NPCID)
    {
        $Statement = Zones::$WConnection->prepare('
            SELECT
                ct.entry,
                ct.difficulty_entry_1,
                ct.difficulty_entry_2,
                ct.difficulty_entry_3,
                ct.name,
                ct.subname,
                ct.minlevel,
                ct.maxlevel,
                ct.rank,
                ct.lootid,
                ct.HealthModifier * cls.basehp0 as health,
                ct.type,
                ct.ScriptName
            FROM
                creature_template ct
            LEFT JOIN creature_classlevelstats cls ON
                    ct.unit_class = cls.class
                AND
                    ct.maxlevel = cls.level
            WHERE
                entry = :npc
        ');
        $Statement->bindParam(':npc', $NPCID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $Result['type'] = Zones::NPCType($Result['type']);
        $Result['health'] = String::NiceNumbers($Result['health']);
        for($i = 1; $i <= 3; $i++)
        {
            if($Result['difficulty_entry_'.$i] != 0)
                $Result['difficulty_entry_'.$i] = Zones::GetNPCInfo($Result['difficulty_entry_'.$i]);
        }

        return $Result;
    }

    public static function GetBossAchievements($BossID)
    {
        $Statement = Zones::$DBConnection->prepare('
            SELECT
                fac.refAchievement as Achievement,
                fa.name_loc0 as Name,
                fa.description_loc0 as Description,
                fa.points as Points,
                fact.name_loc0 as Category,
                LOWER(fsi.iconname) as Icon
            FROM
                freedomcore_achievementcriteria fac
            LEFT JOIN freedomcore_achievement fa ON
                fac.refAchievement = fa.id
            LEFT JOIN freedomcore_achievementcategory fact ON
                fa.category = fact.id
            LEFT JOIN freedomcore_spellicons fsi ON
                fa.icon = fsi.id
            WHERE
                fa.category NOT IN (125, 14807, 14823, 15062)
	          AND
                fac.value1 = :bossid;
        ');
        $Statement->bindParam(':bossid', $BossID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function GetBossLoot($LootID)
    {
        $Statement = Zones::$WConnection->prepare('SELECT * FROM creature_loot_template WHERE Entry = :lootid');
        $Statement->bindParam(':lootid', $LootID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        $GetDataForItems = array();
        foreach($Result as $ItemDrop)
        {
            if($ItemDrop['Reference'] != 0)
                unset($Result[$ArrayIndex]);
            else
            {
                $GetDataForItems[] = $ItemDrop['Item'];
            }
            $ArrayIndex++;
        }
        $Result = array_values($Result);
        $ItemArray = implode(', ', $GetDataForItems);
        return Zones::GetItemArrayData($ItemArray);
    }

    private static function GetItemArrayData($ItemArray)
    {
        global $FCCore;
        $SQL = 'SELECT
                it.entry,
                it.class,
                it.subclass,
                it.name,
                it.displayid,
                it.Quality,
                it.BuyPrice,
                it.SellPrice,
                it.bonding,
                it.RequiredLevel,
                it.InventoryType,
                LOWER(fi.iconname) as icon
            FROM
                item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE
                entry IN ('.$ItemArray.')';
        $Statement = Zones::$WConnection->prepare($SQL);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Item)
        {
            $Result[$ArrayIndex]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
            $Result[$ArrayIndex]['class'] = Items::ItemClass($Item['class']);
            $Result[$ArrayIndex]['BuyPrice'] = String::MoneyToCoins($Item['BuyPrice']);
            $Result[$ArrayIndex]['SellPrice'] = String::MoneyToCoins($Item['SellPrice']);
            $Result[$ArrayIndex]['bond_translation'] = Items::BondTranslation($Item['bonding']);
            $Result[$ArrayIndex]['it_translation'] = Items::InventoryTypeTranslation($Item['InventoryType']);
            $ArrayIndex++;
        }
        return $Result;
    }

    private static function NPCType($TypeID)
    {
        $Types = array(
            1 => array('id' => '1', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Beast')),
            2 => array('id' => '2', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Dragonkin')),
            3 => array('id' => '3', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Demon')),
            4 => array('id' => '4', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Elemental')),
            5 => array('id' => '5', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Giant')),
            6 => array('id' => '6', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Undead')),
            7 => array('id' => '7', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Humanoid')),
            8 => array('id' => '8', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Critter')),
            9 => array('id' => '9', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_Mechanical')),
            10 => array('id' => '10', 'translation' => Zones::$TM->GetConfigVars('NPC_Type_ChaosBorn'))
        );
        return $Types[$TypeID];
    }

    private static function Expansion($ID)
    {
        $Expansions = array(
            0 => array('expansion' => '0', 'translation' => Zones::$TM->GetConfigVars('Expansion_Classic')),
            1 => array('expansion' => '1', 'translation' => Zones::$TM->GetConfigVars('Expansion_TBC')),
            2 => array('expansion' => '2', 'translation' => Zones::$TM->GetConfigVars('Expansion_WotLK')),
            3 => array('expansion' => '3', 'translation' => Zones::$TM->GetConfigVars('Expansion_Cata')),
            4 => array('expansion' => '4', 'translation' => Zones::$TM->GetConfigVars('Expansion_MoP')),
            5 => array('expansion' => '5', 'translation' => Zones::$TM->GetConfigVars('Expansion_WoD'))
        );
        return $Expansions[$ID];
    }

    private static function ZoneTypeByID($TypeID)
    {
        $Types = array(
            1 => array('type' => '1', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Five_Man')),
            2 => array('type' => '2', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Five_Man_Heroic')),
            3 => array('type' => '3', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Ten_Man')),
            4 => array('type' => '4', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Ten_Man_Heroic')),
            5 => array('type' => '5', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_TwentyFive_Man')),
            6 => array('type' => '6', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_TwentyFive_Man_Heroic')),
            7 => array('type' => '7', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_LRF')),
            8 => array('type' => '8', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Flex')),
            9 => array('type' => '9', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Mythic')),
            10 => array('type' => '10', 'translation' => Zones::$TM->GetConfigVars('Raids_Instance_Type_Forty_Man'))
        );
        return $Types[$TypeID];
    }
}

?>