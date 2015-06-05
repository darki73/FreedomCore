<?php

Class Items
{
    public static $DBConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Items::$DBConnection = $VariablesArray[0]::$Connection;
        Items::$WConnection = $VariablesArray[0]::$WConnection;
        Items::$TM = $VariablesArray[1];
    }

    private static function GetItemIcon($ItemID)
    {
        global $FCCore;
        $Statement = Items::$WConnection->prepare('
            SELECT
                LOWER(fi.iconname) as icon
            FROM
              item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE
                entry = :itemid
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['icon'];
    }

    private static function GetSkillReqs($SkillID, $SkillPoints)
    {
        $Statement = Items::$DBConnection->prepare('SELECT * FROM freedomcore.freedomcore_spell WHERE effect2MiscValue = :skillid AND rank_loc0 != ""');
        $Statement->bindParam(':skillid', $SkillID);
        $Statement->execute();
        $Result = $Statement->fetchAll();
        $RidingSkills = array(75 => 33388, 150 => 33391, 225 =>  34090, 300 => 34091, 375 => 90265);
        foreach ($Result as $Skill)
        {
            if(in_array($Skill['spellID'], $RidingSkills))
                if($Skill['spellID'] == $RidingSkills[$SkillPoints])
                    return $Skill;
        }
    }

    public static function GetItemInfo($ItemID)
    {
        global $FCCore;
        $Statement = Items::$WConnection->prepare('
            SELECT
                it.*,
                LOWER(fi.iconname) as icon
            FROM
                item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE
                entry = :itemid
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(empty($Result) || $ItemID != $Result['entry'])
            return false;
        else
        {
            $Result['subclass'] = Items::ItemSubClass($Result['class'], $Result['subclass']);
            $Result['class'] = Items::ItemClass($Result['class']);
            $Result['BuyPrice'] = String::MoneyToCoins($Result['BuyPrice']);
            $Result['SellPrice'] = String::MoneyToCoins($Result['SellPrice']);
            $Result['bond_translation'] = Items::BondTranslation($Result['bonding']);
            $Result['it_translation'] = Items::InventoryTypeTranslation($Result['InventoryType']);
            if($Result['RequiredSkill'] != 0)
            {
                $Result['SkillData'] = Items::GetSkillReqs($Result['RequiredSkill'], $Result['RequiredSkillRank']);
            }
            $StatsCount = 0;
            for($i = 1; $i <= 10; $i++)
            {
                if($Result['stat_type'.$i] != 0)
                    $StatsCount++;
            }
            if($StatsCount > 0)
            {
                for($i = 1; $i <= $StatsCount; $i++)
                {
                    $Result['stat_translation'.$i] = Items::StatTranslation($Result['stat_type'.$i]);
                }
            }
            for($i = 1; $i <= 3; $i++)
            {
                if($Result['socketColor_'.$i] != 0)
                    $Result['socket'.$i] = Items::SocketDescription($Result['socketColor_'.$i]);
            }
            for($i = 1; $i <= 5; $i++)
            {
                if($Result['spellid_'.$i] != 0 && $Result['spellid_'.$i] != -1)
                {
                    $Result['spt_translation'.$i] = Items::SpellTrigger($Result['spelltrigger_'.$i]);
                    $Result['spell_data'.$i] = Spells::SpellInfo($Result['spellid_'.$i]);
                    if(strstr($Result['name'], $Result['spell_data'.$i]['Name']))
                        $Result['spell_data'.$i]['SearchForCreature'] = Items::FindCreatureBySpell($Result['spellid_'.$i]);
                }
            }
            return $Result;
        }
    }

    private static function FindCreatureBySpell($SpellID)
    {
        global $FCCore;
        $Statement = Items::$DBConnection->prepare('
            SELECT
                effect1MiscValue as creature
            FROM
                freedomcore_spell
            WHERE
                spellID = :spellid;
        ');
        $Statement->bindParam(':spellid', $SpellID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['creature'];
    }

    public static function GetItemRelatedInfo($ItemID)
    {
        $RelatedInfo = array();

        $QuestRelation = Items::GetQuestRelation($ItemID);
        $SoldBy = Items::GetSellerRelation($ItemID);
        $DropBy = Items::GetDropRelation($ItemID);
        $Comments = Items::GetCommentsForItem($ItemID);
        if($QuestRelation != false)
            $RelatedInfo['rewardFromQuests'][] = $QuestRelation;
        if($SoldBy != false)
            $RelatedInfo['vendors'] = $SoldBy;
        if($DropBy != false)
            $RelatedInfo['dropCreatures'] = $DropBy;

        $RelatedInfo['comments'] = $Comments;

        return $RelatedInfo;
    }

    public static function GetLastCommentID($ItemID)
    {
        $Statement = News::$DBConnection->prepare('SELECT max(id) as maxcomment FROM item_comments WHERE item_id = :itemid');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result['maxcomment'];
    }

    public static function AddComment($ArticleID, $PostedBy, $CommentText, $LanguageCode)
    {
        $DateAndTime = date('Y-m-d H:i:s');
        $Statement = News::$DBConnection->prepare('INSERT INTO item_comments (item_id, comment_by, comment_text, comment_date, language_code) VALUES (:itemid, :poster, :comment, :dt, :language)');
        $Statement->bindParam(':itemid', $ArticleID);
        $Statement->bindParam(':poster', $PostedBy);
        $Statement->bindParam(':comment', $CommentText);
        $Statement->bindParam(':dt', $DateAndTime);
        $Statement->bindParam(':language', $LanguageCode);
        $Statement->execute();
    }

    private static function GetQuestRelation($ItemID)
    {
        $Statement = Items::$WConnection->prepare('
            SELECT
                *
            FROM
                world.quest_template
            WHERE
                    RewardItemId1 = :itemid
                OR
                    RewardItemId2 = :itemid
                OR
                    RewardItemId3 = :itemid
                OR
                    RewardItemId4 = :itemid
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!empty($Result))
        {
            if($Result['RewardItemId1'] != 0)
                $Result['RewardItemIcon1'] = Items::GetItemIcon($Result['RewardItemId1']);
            if($Result['RewardItemId2'] != 0)
                $Result['RewardItemIcon2'] = Items::GetItemIcon($Result['RewardItemId2']);
            if($Result['RewardItemId3'] != 0)
                $Result['RewardItemIcon3'] = Items::GetItemIcon($Result['RewardItemId3']);
            if($Result['RewardItemId4'] != 0)
                $Result['RewardItemIcon4'] = Items::GetItemIcon($Result['RewardItemId4']);
            return $Result;
        }
        else
            return false;
    }

    public static function GetAllItemsInSubCategory($CategoryID, $SubCategoryID, $Offset)
    {
        global $FCCore;
        $Result = array();
        $Statement = Items::$WConnection->prepare('
            SELECT
                it.*,
                LOWER(fi.iconname) as icon
            FROM
                item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE
                    it.class = :class
                AND
                    it.subclass = :subclass
            ORDER BY it.ItemLevel DESC
            LIMIT 50 OFFSET '.$Offset.'
        ');
        $Statement->bindParam(':class', $CategoryID);
        $Statement->bindParam(':subclass', $SubCategoryID);
        $Statement->execute();
        $Result['item_list'] = $Statement->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < 1; $i++)
        {
            $Result['category_data'] = array(
                'name' => Items::ItemClass($Result['item_list'][$i]['class']),
                'subname' => Items::ItemSubClass($Result['item_list'][$i]['class'], $Result['item_list'][$i]['subclass'])
            );
        }
        $Index = 0;
        foreach($Result['item_list'] as $Item)
        {
            $Result['item_list'][$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
            $Result['item_list'][$Index]['class'] = Items::ItemClass($Item['class']);
            $Index++;
        }
        return array('count' => Items::SelectCount('item_template', array('class' => $CategoryID, 'subclass' => $SubCategoryID)), 'items' => $Result);
    }

    private static function SelectCount($Table, $Data = array())
    {
        $SQL = 'SELECT count(*) as count FROM '.$Table;
        if(!empty($Data))
        {
            $i = 0;
            $SQL .= ' WHERE';
            foreach($Data as $Key=>$Variable)
            {
                if($i == 0)
                    $SQL.= ' '.$Key." = '".$Variable."'";
                else
                    $SQL.= ' AND '.$Key." = '".$Variable."'";
                $i++;
            }
        }
        $Statement = Items::$WConnection->prepare($SQL);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public static function GetAllItemsInCategory($CategoryID, $Offset)
    {
        global $FCCore;
        $Result = array();
        $Statement = Items::$WConnection->prepare('
            SELECT
                it.*,
                LOWER(fi.iconname) as icon
            FROM
                item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE
                    it.class = :class
            ORDER BY it.ItemLevel DESC
            LIMIT 50 OFFSET '.$Offset.'
        ');
        $Statement->bindParam(':class', $CategoryID);
        $Statement->execute();
        $Result['item_list'] = $Statement->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < 1; $i++)
        {
            $Result['category_data'] = array(
                'name' => Items::ItemClass($Result['item_list'][$i]['class']),
            );
        }
        $Index = 0;
        foreach($Result['item_list'] as $Item)
        {
            $Result['item_list'][$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
            $Result['item_list'][$Index]['class'] = Items::ItemClass($Item['class']);
            $Index++;
        }
        return array('count' => Items::SelectCount('item_template', array('class' => $CategoryID)), 'items' => $Result);
    }

    public static function GetAllItems($Offset)
    {
        global $FCCore;
        $Result = array();
        $Statement = Items::$WConnection->prepare('
            SELECT
                it.*,
                LOWER(fi.iconname) as icon
            FROM
                item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            ORDER BY it.ItemLevel DESC
            LIMIT 50 OFFSET '.$Offset.'
        ');
        $Statement->execute();
        $Result['item_list'] = $Statement->fetchAll(PDO::FETCH_ASSOC);
        for($i = 0; $i < 1; $i++)
        {
            $Result['category_data'] = array(
                'name' => Items::ItemClass($Result['item_list'][$i]['class']),
            );
        }
        $Index = 0;
        foreach($Result['item_list'] as $Item)
        {
            $Result['item_list'][$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
            $Result['item_list'][$Index]['class'] = Items::ItemClass($Item['class']);
            $Index++;
        }
        return array('count' => Items::SelectCount('item_template'), 'items' => $Result);
    }

    private static function GetSellerRelation($ItemID)
    {
        $Statement = Items::$WConnection->prepare('
            SELECT
                ct.entry,
                ct.name,
                ct.subname,
                ct.maxlevel,
                c.map
            FROM
                world.npc_vendor nv
            LEFT JOIN world.creature_template ct ON
                nv.entry = ct.entry
            LEFT JOIN world.creature c ON
                ct.entry = c.id
            WHERE
                item = :itemid;
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($Result))
            return $Result;
        else
            return false;
    }

    private static function GetDropRelation($ItemID)
    {
        return false;
    }

    private static function GetCommentsForItem($ItemID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT * FROM item_comments WHERE item_id = :itemid ORDER BY id DESC');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($Result))
            return $Result;
        else
            return array();
    }

    private static function SocketDescription($SocketColorID)
    {
        $Sockets = array(
            '1' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Meta')),
            '2' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Red')),
            '4' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Yellow')),
            '8' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Blue'))
        );

        return $Sockets[$SocketColorID];
    }

    private static function InventoryTypeTranslation($TypeID)
    {
        $InventoryType = array(
            '0' => Items::$TM->GetConfigVars('Item_InventoryType_Non_Equipable'),
            '15' => Items::$TM->GetConfigVars('Item_InventoryType_Ranged'),
            '1' => Items::$TM->GetConfigVars('Item_InventoryType_Head'),
            '16' => Items::$TM->GetConfigVars('Item_InventoryType_Back'),
            '2' => Items::$TM->GetConfigVars('Item_InventoryType_Neck'),
            '17' => Items::$TM->GetConfigVars('Item_InventoryType_Two_Hand'),
            '3' => Items::$TM->GetConfigVars('Item_InventoryType_Shoulder'),
            '18' => Items::$TM->GetConfigVars('Item_InventoryType_Bag'),
            '4' => Items::$TM->GetConfigVars('Item_InventoryType_Shirt'),
            '19' => Items::$TM->GetConfigVars('Item_InventoryType_Tabard'),
            '5' => Items::$TM->GetConfigVars('Item_InventoryType_Chest'),
            '20' => Items::$TM->GetConfigVars('Item_InventoryType_Robe'),
            '6' => Items::$TM->GetConfigVars('Item_InventoryType_Waist'),
            '21' => Items::$TM->GetConfigVars('Item_InventoryType_Main_Hand'),
            '7' => Items::$TM->GetConfigVars('Item_InventoryType_Legs'),
            '22' => Items::$TM->GetConfigVars('Item_InventoryType_Off_Hand'),
            '8' => Items::$TM->GetConfigVars('Item_InventoryType_Feet'),
            '23' => Items::$TM->GetConfigVars('Item_InventoryType_Holdable'),
            '9' => Items::$TM->GetConfigVars('Item_InventoryType_Wrists'),
            '24' => Items::$TM->GetConfigVars('Item_InventoryType_Ammo'),
            '10' => Items::$TM->GetConfigVars('Item_InventoryType_Hands'),
            '25' => Items::$TM->GetConfigVars('Item_InventoryType_Thrown'),
            '11' => Items::$TM->GetConfigVars('Item_InventoryType_Finger'),
            '26' => Items::$TM->GetConfigVars('Item_InventoryType_Ranged_Right'),
            '12' => Items::$TM->GetConfigVars('Item_InventoryType_Trinket'),
            '27' => Items::$TM->GetConfigVars('Item_InventoryType_Quiver'),
            '13' => Items::$TM->GetConfigVars('Item_InventoryType_Weapon'),
            '28' => Items::$TM->GetConfigVars('Item_InventoryType_Relic'),
            '14' => Items::$TM->GetConfigVars('Item_InventoryType_Shield')
        );

        return $InventoryType[$TypeID];
    }

    private static function GetSpellData($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT spellID, durationID, spellname_loc0, tooltip_loc0 FROM freedomcore.freedomcore_spell where spellID = :spellid');
        $Statement->bindParam(':spellid', $SpellID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function SpellTrigger($TriggerID)
    {
        $Triggers = array(
            '0' => Items::$TM->GetConfigVars('Item_Spell_Trigger_Use'),
            '1' => Items::$TM->GetConfigVars('Item_Spell_Trigger_On_Equip'),
            '2' => Items::$TM->GetConfigVars('Item_Spell_Trigger_On_Hit'),
            '4' => Items::$TM->GetConfigVars('Item_Spell_Trigger_Soulstone'),
            '5' => Items::$TM->GetConfigVars('Item_Spell_Trigger_UseNoDelay'),
            '6' => Items::$TM->GetConfigVars('Item_Spell_Trigger_Learn')
        );

        return $Triggers[$TriggerID];
    }

    private static function BondTranslation($BondID)
    {
        $BondType = array(
            '0' => Items::$TM->GetConfigVars('Item_Bond_No_Bounds'),
            '1' => Items::$TM->GetConfigVars('Item_Bond_BoP'),
            '2' => Items::$TM->GetConfigVars('Item_Bond_BoE'),
            '3' => Items::$TM->GetConfigVars('Item_Bond_BoU'),
            '4' => Items::$TM->GetConfigVars('Item_Bond_Quest_Item'),
            '5' => Items::$TM->GetConfigVars('Item_Bond_Quest_Item'),
        );

        return $BondType[$BondID];
    }

    private static function StatTranslation($StatID)
    {
        $Stats = array(
            '0' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_MANA'),
            '1' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HEALTH'),
            '3' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_AGILITY'),
            '4' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            '5' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_INTELLECT'),
            '6' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPIRIT'),
            '7' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            '12' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_DEFENSE_SKILL_RATING'),
            '13' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_DODGE_RATING'),
            '14' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_PARRY_RATING'),
            '15' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_BLOCK_RATING'),
            '16' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_MELEE_RATING'),
            '17' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_RANGED_RATING'),
            '18' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_SPELL_RATING'),
            '19' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_MELEE_RATING'),
            '20' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_RANGED_RATING'),
            '21' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_SPELL_RATING'),
            '22' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_TAKEN_MELEE_RATING'),
            '23' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_TAKEN_RANGED_RATING'),
            '24' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_TAKEN_SPELL_RATING'),
            '25' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_MELEE_RATING'),
            '26' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_RANGED_RATING'),
            '27' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_SPELL_RATING'),
            '28' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HASTE_MELEE_RATING'),
            '29' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HASTE_RANGED_RATING'),
            '30' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HASTE_SPELL_RATING'),
            '31' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_RATING'),
            '32' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_RATING'),
            '33' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_TAKEN_RATING'),
            '34' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_RATING'),
            '35' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_RESILIENCE_RATING'),
            '36' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HASTE_RATING'),
            '37' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_EXPERTISE_RATING'),
            '38' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_ATTACK_POWER'),
            '39' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_RANGED_ATTACK_POWER'),
            '40' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_FERAL_ATTACK_POWER'),
            '41' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_HEALING_DONE'),
            '42' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_DAMAGE_DONE'),
            '43' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_MANA_REGENERATION'),
            '44' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_ARMOR_PENETRATION_RATING'),
            '45' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_POWER'),
            '46' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HEALTH_REGEN'),
            '47' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_PENETRATION'),
            '48' => Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_BLOCK_VALUE')
        );
        return $Stats[$StatID];
    }

    public static function ItemClass($ClassID)
    {
        $Classes = array(
            '0' => array('class' => '0', 'translation' => Items::$TM->GetConfigVars('Item_Class_Consumable')),
            '1' => array('class' => '1', 'translation' => Items::$TM->GetConfigVars('Item_Class_Container')),
            '2' => array('class' => '2', 'translation' => Items::$TM->GetConfigVars('Item_Class_Weapon')),
            '3' => array('class' => '3', 'translation' => Items::$TM->GetConfigVars('Item_Class_Gem')),
            '4' => array('class' => '4', 'translation' => Items::$TM->GetConfigVars('Item_Class_Armor')),
            '5' => array('class' => '5', 'translation' => Items::$TM->GetConfigVars('Item_Class_Reagent')),
            '6' => array('class' => '6', 'translation' => Items::$TM->GetConfigVars('Item_Class_Projectile')),
            '7' => array('class' => '7', 'translation' => Items::$TM->GetConfigVars('Item_Class_Trade_Goods')),
            '8' => array('class' => '8', 'translation' => Items::$TM->GetConfigVars('Item_Class_Generic')),
            '9' => array('class' => '9', 'translation' => Items::$TM->GetConfigVars('Item_Class_Recipe')),
            '10' => array('class' => '10', 'translation' => Items::$TM->GetConfigVars('Item_Class_Money')),
            '11' => array('class' => '11', 'translation' => Items::$TM->GetConfigVars('Item_Class_Quiver')),
            '12' => array('class' => '12', 'translation' => Items::$TM->GetConfigVars('Item_Class_Quest')),
            '13' => array('class' => '13', 'translation' => Items::$TM->GetConfigVars('Item_Class_Key')),
            '14' => array('class' => '14', 'translation' => Items::$TM->GetConfigVars('Item_Class_Permanent')),
            '15' => array('class' => '15', 'translation' => Items::$TM->GetConfigVars('Item_Class_Miscellaneous')),
            '16' => array('class' => '16', 'translation' => Items::$TM->GetConfigVars('Item_Class_Glyph'))
        );

        return $Classes[$ClassID];
    }

    public static function ItemSubClass($ClassID, $SubClassID)
    {
        $SubClassesByClasses = array(
            '0' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Consumable')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Potion')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Elixir')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Flask')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Scroll')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Food_Drink')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Item_Enhancement')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Bandage')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Other'))
            ),
            '1' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Bag')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Soul_Bag')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Herb_Bag')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Enchanting_Bag')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Engineering_Bag')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Gem_Bag')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Mining_Bag')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Leatherworking_Bag')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Inscription_Bag'))
            ),
            '2' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Axe_1H')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Axe_2H')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Bow')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Gun')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Mace_1H')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Mace_2H')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Polearm')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Sword_1H')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Sword_2H')),
                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Obsolete')),
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Staff')),
                '11' => array('subclass' => '11', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Exotic')),
                '12' => array('subclass' => '12', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Exotic')),
                '13' => array('subclass' => '13', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Fist_Weapon')),
                '14' => array('subclass' => '14', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Miscellaneous')),
                '15' => array('subclass' => '15', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Dagger')),
                '16' => array('subclass' => '16', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Thrown')),
                '17' => array('subclass' => '17', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Spear')),
                '18' => array('subclass' => '18', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Crossbow')),
                '19' => array('subclass' => '19', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Wand')),
                '20' => array('subclass' => '20', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Fishing_Pole'))
            ),
            '3' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Red')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Blue')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Yellow')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Purple')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Green')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Orange')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Meta')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Simple')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Prismatic'))
            ),
            '4' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Miscellaneous')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Cloth')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Leather')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Mail')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Plate')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Buckler')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Shield')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Libram')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Idol')),
                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Totem')),
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Sigil'))
            ),
            '5' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_Class_Reagent'))
            ),
            '6' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Wand')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Bolt')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Arrow')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Bullet')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Thrown'))
            ),
            '7' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Trade_Goods')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Parts')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Explosives')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Devices')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Jewelcrafting')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Cloth')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Leather')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Metal_Stone')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Meat')),
                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Herb')),
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Elemental')),
                '11' => array('subclass' => '11', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Other')),
                '12' => array('subclass' => '12', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Enchanting')),
                '13' => array('subclass' => '13', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Materials')),
                '14' => array('subclass' => '14', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Armor_Enchantment')),
                '15' => array('subclass' => '15', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Weapon_Enchantment'))
            ),
            '8' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Generic'))
            ),
            '9' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Book')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Leatherworking')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Tailoring')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Engineering')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Blacksmithing')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Cooking')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Alchemy')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Character_Professions_First_aid')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Enchanting')),
                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Fishing')),
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Jewelcrafting'))
            ),
            '10' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Money'))
            ),
            '11' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Quiver')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Quiver')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Quiver')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Ammo_Pouch'))
            ),
            '12' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Quest'))
            ),
            '13' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Key')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Lockpick'))
            ),
            '14' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Permanent'))
            ),
            '15' => array(
                '0' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Junk')),
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Reagent')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Pet')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Holiday')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Other')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Mount'))
            ),
            '16' => array(
                '1' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Class_Warrior')),
                '2' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Class_Paladin')),
                '3' => array('subclass' => '3', 'translation' => Items::$TM->GetConfigVars('Class_Hunter')),
                '4' => array('subclass' => '4', 'translation' => Items::$TM->GetConfigVars('Class_Rogue')),
                '5' => array('subclass' => '5', 'translation' => Items::$TM->GetConfigVars('Class_Priest')),
                '6' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Class_Death_Knight')),
                '7' => array('subclass' => '7', 'translation' => Items::$TM->GetConfigVars('Class_Shaman')),
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Class_Mage')),
                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Class_Warlock')),
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Class_Monk')),
                '11' => array('subclass' => '11', 'translation' => Items::$TM->GetConfigVars('Class_Druid'))
            )
        );

        $SubClass = $SubClassesByClasses[$ClassID];
        return $SubClass[$SubClassID];
    }
}

Class Spells
{
    public static function SpellInfo($SpellID)
    {
        $SpellArray = array();
        $SpellData = Spells::GetSpellByID($SpellID);
        $SpellArray['SpellID'] = $SpellData['spellID'];
        $SpellArray['Name'] = $SpellData['spellname_loc0'];
        $SpellArray['Description'] = Spells::ParseDescription($SpellData, $SpellData['tooltip_loc0']);
        $SpellArray['icon'] = $SpellData['icon'];

        return $SpellArray;
    }

    private static function ParseDescription($SpellData, $DescriptionString)
    {
        $DescriptionString = strtr($DescriptionString, array("\r" => '', "\n" => '<br />'));
        preg_match_all('!\$\d+\D\d?!', $DescriptionString, $SubSpells);
        if(empty($SubSpells[0]))
            preg_match_all('!\$\D\d?!', $DescriptionString, $SubSpells);
        $SubSpells = call_user_func_array('array_merge', $SubSpells);
        $SubSpellsData = Spells::ParseSubSpells($SubSpells);
        foreach($SubSpellsData as $SubSpell)
        {
            $ParseResult = Spells::ArgumentParser($SpellData, $SubSpell['SpellID'], $SubSpell['Argument'], $SubSpell['ArgumentValue']);
            $Replacement = '$'.$SubSpell['SpellID'].$SubSpell['Argument'].$SubSpell['ArgumentValue'];
            $DescriptionString = str_replace($Replacement, $ParseResult, $DescriptionString);
        }


        return $DescriptionString;
    }

    private static function ParseSubSpells($SubSpells)
    {
        $SpellSplitter = array('r', 'z', 'c', 's', 'o', 't', 'm', 'x', 'q', 'a', 'h', 'f', 'n', 'd', 'i', 'e', 'v', 'u', 'b', 'l', 'g');
        $SubSpellsData = array();
        foreach($SubSpells as $SubSpell)
            foreach($SpellSplitter as $SplitBy)
                if(strstr($SubSpell, $SplitBy))
                {
                    $Explode = explode($SplitBy, $SubSpell);;
                    $SubSpellsData[] = array('SpellID' => str_replace('$', '', $Explode[0]), 'Argument' => $SplitBy, 'ArgumentValue' => $Explode[1]);
                }
        return $SubSpellsData;
    }

    private static function ArgumentParser($SpellData, $Spell, $Argument, $Value)
    {
        $Modifiers = array('+', '-', '/', '*', '%', '^');
        $Data = '';
        switch($Argument)
        {
            case 's':
                if(is_numeric($Spell))
                    $SpellData = Spells::GetSpellByID($Spell);
                $BasePoints = $SpellData['effect1BasePoints']+1;
                $Data = abs($BasePoints).($SpellData['effect1DieSides'] > 1 ? ' - '.abs(($BasePoints+$SpellData['effect1DieSides'])) : '');
            break;

            case 'u':
                if(is_numeric($Spell))
                    $SpellData = Spells::GetSpellByID($Spell);
                if(isset($SpellData['effect1Aura']))
                    $BasePoints = $SpellData['effect1Aura']+1;
                $Data = abs($BasePoints);
            break;

            case 'd':
                if(is_numeric($Spell))
                {
                    $SpellData = Spells::GetSpellDurationBySpellID($Spell);
                    $BasePoints = ($SpellData['durationBase'] > 0 ? $SpellData['durationBase'] + 1 : 0);
                }
                else
                    $BasePoints = Spells::GetSpellDurationByDurationID($SpellData['durationID']);

                $Data = Spells::GetDuration($BasePoints).' '.Items::$TM->GetConfigVars('Item_Spell_DS');
            break;

            case 'a':
                if(is_numeric($Spell))
                    $SpellData = Spells::GetSpellRadiusBySpellID($Spell);

                $BasePoints = Spells::GetSpellRadiusByRadiusID($SpellData['effect'.$Value.'radius']);

                $Data = abs($BasePoints);
            break;
        }

        return $Data;
    }

    private static function GetSpellByID($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT fs.*, LOWER(fsi.iconname) as icon FROM freedomcore_spell fs, freedomcore_spellicons fsi WHERE fs.spellID = :spellid AND fsi.id = fs.spellicon LIMIT 1');
        $Statement->bindParam(':spellid', $SpellID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetSpellDurationBySpellID($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT durationBase FROM freedomcore_spell s, freedomcore_spellduration sd WHERE s.durationID = sd.durationID AND s.spellID=:spellID LIMIT 1');
        $Statement->bindParam(':spellID', $SpellID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetSpellDurationByDurationID($DurationID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT durationBase FROM freedomcore_spellduration WHERE durationID = :durationID LIMIT 1');
        $Statement->bindParam(':durationID', $DurationID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC)['durationBase'];
    }

    private static function GetSpellRadiusBySpellID($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT effect1radius, effect2radius, effect3radius FROM freedomcore_spell WHERE spellID = :SpellID LIMIT 1');
        $Statement->bindParam(':SpellID', $SpellID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetSpellRadiusByRadiusID($RadiusID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT radiusBase FROM freedomcore_spellradius WHERE radiusID = :RadiusID LIMIT 1');
        $Statement->bindParam(':RadiusID', $RadiusID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetSpellRange($RangeID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT * FROM freedomcore_spellrange WHERE rangeID = :rangeID LIMIT 1');
        $Statement->bindParam(':rangeID', $RangeID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetDuration($SpellDuration)
    {
        return round($SpellDuration/1000);
    }
}

?>