<?php

Class Items
{
    public static $DBConnection;
    public static $WConnection;
    public static $CConnection;
    public static $TM;

    public static $SocketBonuses;
    public static $GemProperties;

    public function __construct($VariablesArray)
    {
        Items::$DBConnection = $VariablesArray[0]::$Connection;
        Items::$WConnection = $VariablesArray[0]::$WConnection;
        Items::$CConnection = $VariablesArray[0]::$CConnection;
        Items::$TM = $VariablesArray[1];
        if(empty($GemProperties))
            Items::LoadGemProperties();
        //Items::LoadSocketBonus();
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
        $Statement = Items::$DBConnection->prepare('SELECT * FROM freedomcore_spell WHERE effect2MiscValue = :skillid AND rank_loc0 != ""');
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
            $Result['BuyPrice'] = Text::MoneyToCoins($Result['BuyPrice']);
            $Result['SellPrice'] = Text::MoneyToCoins($Result['SellPrice']);
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
            if($Result['socketBonus'] != 0)
                $Result['socketBonusDescription'] = Items::SocketBonus($Result['socketBonus']);
            $Result['itemsetinfo'] = Items::GetItemSetInfo($ItemID);
            if($Result['GemProperties'] != 0){
                $Result['gem_bonus'] = Items::getGemBonus($Result['GemProperties']);
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

    public static function GetItemSetInfo($ItemID)
    {
        $Statement = Items::$DBConnection->prepare('
        SELECT 
            * 
        FROM 
            freedomcore_itemset fis
        WHERE
                fis.item1 = :itemid OR fis.item2 = :itemid OR fis.item3 = :itemid OR fis.item4 = :itemid OR fis.item5 = :itemid OR fis.item6 = :itemid OR fis.item7 = :itemid OR fis.item8 = :itemid OR fis.item9 = :itemid OR fis.item10 = :itemid
                
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);

        $ItemSetBonusWhenEquipped = array();
        $ItemsInSet = 0;
        for($i = 1; $i <= 10; $i++)
            if($Result['item'.$i] != 0)
            {
                $Result['item'.$i] = Items::GetItemSetItemInfo($Result['item'.$i]);
                $ItemsInSet++;
            }
        for($i = 1; $i <= 8; $i++)
            if($Result['spell'.$i] != 0)
                $Result['spell'.$i] = Items::GetItemSetSpellInfo($Result['spell'.$i]);
        for($i = 1; $i <= 8; $i++)
            if($Result['bonus'.$i] != 0)
                $ItemSetBonusWhenEquipped[] = $Result['bonus'.$i];
        for ($i = 0; $i < count($ItemSetBonusWhenEquipped); $i++)
            $Result['setbonus'.$i] = Items::$TM->GetConfigVars('Item_Set_Bonus').' ('.$ItemSetBonusWhenEquipped[$i].') '.$Result['spell'.($i+1)]['Description'];
        $Result['itemsinset'] = $ItemsInSet;
        return $Result;
    }

    private static function GetItemSetItemInfo($ItemID)
    {
        global $FCCore;
        $Statement = Items::$WConnection->prepare('SELECT entry, name, Quality, RequiredLevel, ItemLevel, LOWER(fi.iconname) as icon FROM item_template it LEFT JOIN '.$FCCore['Database']['database'].'.freedomcore_icons fi ON
                it.displayid = fi.id WHERE entry = :itemid');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function GetItemSetSpellInfo($SpellID)
    {
        return Spells::SpellInfo($SpellID);
    }

    public static function GetItemRelatedInfo($ItemID)
    {
        $RelatedInfo = array();

        $QuestRelation = Items::GetQuestRelation($ItemID);
        $SoldBy = Items::GetSellerRelation($ItemID);
        $DropBy = Items::GetDropRelation($ItemID);
        $Comments = Items::GetCommentsForItem($ItemID);
        $Disenchant = Items::GetDisenchantTable($ItemID);
        if($QuestRelation != false)
            $RelatedInfo['rewardFromQuests'][] = $QuestRelation;
        if($SoldBy != false)
            $RelatedInfo['vendors'] = $SoldBy;
        if($DropBy != false)
            $RelatedInfo['dropCreatures'] = $DropBy;
        if($Disenchant != false)
            $RelatedInfo['disenchantItems'] = $Disenchant;

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

    public static function AddReply($ArticleID, $PostedBy, $CommentText, $LanguageCode, $ReplyTo)
    {
        $DateAndTime = date('Y-m-d H:i:s');
        $Statement = News::$DBConnection->prepare('INSERT INTO item_comments (item_id, comment_by, comment_text, comment_date, language_code, replied_to) VALUES (:itemid, :poster, :comment, :dt, :language, :rt)');
        $Statement->bindParam(':itemid', $ArticleID);
        $Statement->bindParam(':poster', $PostedBy);
        $Statement->bindParam(':comment', $CommentText);
        $Statement->bindParam(':dt', $DateAndTime);
        $Statement->bindParam(':language', $LanguageCode);
        $Statement->bindParam(':rt', $ReplyTo);
        $Statement->execute();
    }

    private static function GetQuestRelation($ItemID)
    {
        $Statement = Items::$WConnection->prepare('
            SELECT
                *
            FROM
                quest_template
            WHERE
                    RewardItem1 = :itemid
                OR
                    RewardItem2 = :itemid
                OR
                    RewardItem3 = :itemid
                OR
                    RewardItem4 = :itemid
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(!empty($Result))
        {
            if($Result['RewardItem1'] != 0)
                $Result['RewardItemIcon1'] = Items::GetItemIcon($Result['RewardItem1']);
            if($Result['RewardItem2'] != 0)
                $Result['RewardItemIcon2'] = Items::GetItemIcon($Result['RewardItem2']);
            if($Result['RewardItem3'] != 0)
                $Result['RewardItemIcon3'] = Items::GetItemIcon($Result['RewardItem3']);
            if($Result['RewardItem4'] != 0)
                $Result['RewardItemIcon4'] = Items::GetItemIcon($Result['RewardItem4']);
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
        if(!empty($Result['item_list']))
        {
            for($i = 0; $i < 1; $i++)
            {
                $Result['category_data'] = array(
                    'name' => Items::ItemClass($Result['item_list'][$i]['class']),
                    'subname' => Items::ItemSubClass($Result['item_list'][$i]['class'], $Result['item_list'][$i]['subclass'])
                );
            }
        }
        $Index = 0;
        if(!empty($Result['item_list']))
        {
            foreach($Result['item_list'] as $Item)
            {
                $Result['item_list'][$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
                $Result['item_list'][$Index]['class'] = Items::ItemClass($Item['class']);
                $Index++;
            }
        }
        return array('count' => Items::SelectCount('item_template', array('class' => $CategoryID, 'subclass' => $SubCategoryID)), 'items' => $Result);
    }

    public static function GetAllItemsInSubCategoryByInventoryType($CategoryID, $SubCategoryID, $InventoryType, $Offset)
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
                AND
                    it.InventoryType = :invtype
            ORDER BY it.ItemLevel DESC
            LIMIT 50 OFFSET '.$Offset.'
        ');
        $Statement->bindParam(':class', $CategoryID);
        $Statement->bindParam(':subclass', $SubCategoryID);
        $Statement->bindParam(':invtype', $InventoryType);
        $Statement->execute();
        $Result['item_list'] = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($Result['item_list']))
        {
            for($i = 0; $i < 1; $i++)
            {
                $Result['category_data'] = array(
                    'name' => Items::ItemClass($Result['item_list'][$i]['class']),
                    'subname' => Items::ItemSubClass($Result['item_list'][$i]['class'], $Result['item_list'][$i]['subclass']),
                    'inventorytype' => array('id' => $InventoryType, 'translation' => Items::InventoryTypeTranslation($InventoryType))
                );
            }
        }
        $Index = 0;
        if(!empty($Result['item_list']))
        {
            foreach($Result['item_list'] as $Item)
            {
                $Result['item_list'][$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
                $Result['item_list'][$Index]['class'] = Items::ItemClass($Item['class']);
                $Index++;
            }
        }
        return array('count' => Items::SelectCount('item_template', array('class' => $CategoryID, 'subclass' => $SubCategoryID, 'InventoryType' => $InventoryType)), 'items' => $Result);
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
                npc_vendor nv
            LEFT JOIN creature_template ct ON
                nv.entry = ct.entry
            LEFT JOIN creature c ON
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

    private static function GetDisenchantTable($ItemID)
    {
        $Statement = Items::$WConnection->prepare('SELECT dlt.* FROM disenchant_loot_template dlt, item_template it WHERE it.DisenchantID = dlt.entry AND it.entry = :itemID');
        $Statement->bindParam(':itemID', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Item)
        {
            if($Result[$Index]['Chance'] == 100)
                $Result[$Index]['Chance_Translation'] = Items::$TM->GetConfigVars('Item_Disenchant_Probability_Guaranteed');
            elseif($Result[$Index]['Chance'] >= 85 && $Result[$Index]['Chance'] < 100)
                $Result[$Index]['Chance_Translation'] = Items::$TM->GetConfigVars('Item_Disenchant_Probability_High');
            elseif($Result[$Index]['Chance'] >= 50 && $Result[$Index]['Chance'] < 85)
                $Result[$Index]['Chance_Translation'] = Items::$TM->GetConfigVars('Item_Disenchant_Probability_Medium');
            elseif($Result[$Index]['Chance'] > 0 && $Result[$Index]['Chance'] < 50)
                $Result[$Index]['Chance_Translation'] = Items::$TM->GetConfigVars('Item_Disenchant_Probability_Low');
            elseif($Result[$Index]['Chance'] == 0)
                $Result[$Index]['Chance_Translation'] = Items::$TM->GetConfigVars('Item_Disenchant_Probability_Zero');
            $Result[$Index]['Disenchants_Into'] = Items::GetItemSetItemInfo($Result[$Index]['Item']);
            $Index++;
        }
        return $Result;
    }

    private static function GetCommentsForItem($ItemID)
    {
        global $FCCore;
        $Statement = Items::$CConnection->prepare('
          SELECT
            ic.*,
            ch.race as poster_race,
            ch.class as poster_class,
            ch.gender as poster_gender
          FROM
            '.$FCCore['Database']['database'].'.item_comments ic, characters ch
          WHERE
                ic.comment_by = ch.name
            AND
                item_id = :itemid
          ORDER BY id DESC
        ');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($Result))
        {
            $ArrayIndex = 0;
            foreach($Result as $Comment)
            {
                $Result[$ArrayIndex]['nested_comments'] = Items::GetNestedComments($ItemID, $Comment['id']);
                $ArrayIndex++;
            }
            return $Result;
        }
        else
            return array();
    }

    private static function GetNestedComments($ItemID, $ParentCommendID)
    {
        global $FCCore;
        $Statement = Items::$CConnection->prepare('
            SELECT
                ic.*,
                ch.race as poster_race,
                ch.class as poster_class,
                ch.gender as poster_gender
            FROM
                '.$FCCore['Database']['database'].'.item_comments ic, characters ch
            WHERE
                ic.comment_by = ch.name
            AND
                replied_to = :rt
            AND
                item_id = :iid

        ');
        $Statement->bindParam(':rt', $ParentCommendID);
        $Statement->bindParam(':iid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function SocketDescription($SocketColorID)
    {
        $Sockets = array(
            '1' => array('subclass' => '6', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Meta'), 'type' => 'META', 'css_position' => 1),
            '2' => array('subclass' => '0', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Red'), 'type' => 'RED', 'css_position' => 2),
            '4' => array('subclass' => '2', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Yellow'), 'type' => 'YELLOW', 'css_position' => 4),
            '8' => array('subclass' => '1', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Blue'), 'type' => 'BLUE', 'css_position' => 8)
        );

        return $Sockets[$SocketColorID];
    }
    
    public static function SiteSlotPositionByType($Type, $Return = false)
    {
        $Positions = [
            1 => ['side' => 'left', 'placement' => '1'],
            2 => ['side' => 'left', 'placement' => '2'],
            3 => ['side' => 'left', 'placement' => '3'],
            16 => ['side' => 'left', 'placement' => '4'],
            5 => ['side' => 'left', 'placement' => '5'],
            4 => ['side' => 'left', 'placement' => '6'],
            19 => ['side' => 'left', 'placement' => '7'],
            9 => ['side' => 'left', 'placement' => '8'],

            10 => ['side' => 'right', 'placement' => '1'],
            6 => ['side' => 'right', 'placement' => '2'],
            7 => ['side' => 'right', 'placement' => '3'],
            8 => ['side' => 'right', 'placement' => '4'],
            11 => ['side' => 'right', 'placement' => '5'],
            111 => ['side' => 'right', 'placement' => '6'],
            12 => ['side' => 'right', 'placement' => '7'],
            112 => ['side' => 'right', 'placement' => '8'],

            13 => ['side' => '', 'placement' => ''],
            14 => ['side' => 'bottom', 'placement' => '2'],
            15 => ['side' => '', 'placement' => ''],
            17 => ['side' => 'bottom', 'placement' => '1'],
            20 => ['side' => '', 'placement' => ''],
            21 => ['side' => 'bottom', 'placement' => '1'],
            22 => ['side' => 'bottom', 'placement' => '2'],
            25 => ['side' => 'bottom', 'placement' => '3'],
            26 => ['side' => 'bottom', 'placement' => '3'],
            28 => ['side' => 'bottom', 'placement' => '2'],
            117 => ['side' => 'bottom', 'placement' => '2'],
        ];
        if(!$Return)
            return $Positions[$Type];
        else
            return $Positions;
    }

    public static function IsEquipment($SlotID)
    {
        $Equipment = [15, 1, 16, 2, 17, 3, 4, 19, 5, 6, 21, 7, 22, 8, 9, 10, 25, 26, 11, 12, 13, 28, 14, 111, 112, 117];
        if(in_array($SlotID, $Equipment))
            return true;
        else
            return false;
    }

    public static function InventoryTypeToCharacterInventory($TypeID){
        $InventoryTypes = [
            1   =>  0, // Head
            2   =>  1, // Neck
            3   =>  2, // Shoulders
            4   =>  3, // Body | Shirt
            5   =>  4, // Chest
            6   =>  5, // Waist
            7   =>  6, // Legs
            8   =>  7, // Feet
            9   =>  8, // Wrists
            10  =>  9, // Hands
            31  => 10, // Finger 1
            32  => 11, // Finger 2
            40  => 15, // One Hand | Main Hand
            41  => 16, // One Hand | Offhand
            42  => 12, // Trinket 1
            43  => 13, // Trinket 2
            14  => 16, // Shield | Offhand
            16  => 14, // Back
            17  => 15, // Two Hand | Main Hand
            20  =>  4, // Robe | Chest
            21  => 15, // Right Hand | Main Hand
            26  => 17, // Ranged | Guns
            23  => 16, // Tome | Offhand
            25  => 17, // Thrown | Ranged
            28  => 17, // Relic | Ranged
        ];
        if(array_key_exists($TypeID, $InventoryTypes))
            return $InventoryTypes[$TypeID];
    }

    public static function InventoryTypeTranslation($TypeID)
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

    public static function CalculateWeaponDamage($Weapon, $AP){
        $MinimumRange = (($Weapon['min'] / $Weapon['speed']) + ($AP / 14)) * $Weapon['speed'];
        $MaximumRange = (($Weapon['max'] / $Weapon['speed']) + ($AP / 14)) * $Weapon['speed'];
        $WeaponDPS = (($MinimumRange + $MaximumRange) / 2) / $Weapon['speed'];
        return ['minimum' => round($MinimumRange, 0), 'maximum' => round($MaximumRange, 0), 'dps' => round($WeaponDPS, 0), 'speed' => $Weapon['speed']];
    }

    public static function getDataForItemInstance($IDs){
        $Query = "SELECT entry, name, MaxDurability, InventoryType FROM item_template WHERE ENTRY IN (%s)";
        $ItemsString = "";

        for($i = 0; $i < count($IDs); $i++){
            if($i == count($IDs) - 1)
                $ItemsString .= $IDs[$i];
            else
                $ItemsString .= $IDs[$i].', ';
        }
        $Query = sprintf($Query, $ItemsString);

        return Database::getMultiRow('World', $Query);
    }

    public static function generateItemInstanceSQL($CharacterGUID, $Items){
        $SQLArray = [];
        $ItemsData = self::getDataForItemInstance($Items);
        foreach($ItemsData as $Item){
            $ItemEntry = $Item['entry'];
            $Durability = $Item['MaxDurability'];
            $SQLArray[] = "INSERT INTO item_instance(guid, itemEntry, owner_guid, creatorGuid, giftCreatorGuid, count, duration, charges, flags, enchantments, randomPropertyId, durability, playedTime, text) VALUES ((SELECT MAX(guid) FROM item_instance ii) +1, '$ItemEntry', '$CharacterGUID', '0', '0', '1', '0', '0 0 0 0 0', '1', '0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0', '0', '$Durability', '0', '')";
        }
        $SQLArray[] = "INSERT INTO item_instance(guid, itemEntry, owner_guid, creatorGuid, giftCreatorGuid, count, duration, charges, flags, enchantments, randomPropertyId, durability, playedTime, text) VALUES ((SELECT MAX(guid) FROM item_instance ii) +1, '6948', '$CharacterGUID', '0', '0', '1', '0', '0 0 0 0 0', '1', '0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0', '0', '0', '0', '')";

        return $SQLArray;
    }

    private static function GetSpellData($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT spellID, durationID, spellname_loc0, tooltip_loc0 FROM freedomcore_spell where spellID = :spellid');
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

    public static function QuestInfo($QuestID)
    {
        global $FCCore;
        $Statement = Items::$WConnection->prepare('
          SELECT
            qt.*,
            ff.name_loc0 as factionname
          FROM
            quest_template qt, '.$FCCore['Database']['database'].'.freedomcore_factions ff
          WHERE
            qt.RewardFactionId1 = ff.factionID
          AND
            id = :questid
        ');
        $Statement->bindParam(':questid', $QuestID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        for($i = 1; $i <= 4; $i++)
        {
            if($Result['RewardItem'.$i] != 0)
            {
                $Result['RewardItem'.$i] = Items::GetItemInfo($Result['RewardItem'.$i]);
            }
        }
        $Result['XPReward'] = Items::QuestXP($Result['QuestLevel'], $Result['RewardXPId']);
        $Result['MoneyReward'] = Text::MoneyToCoins($Result['RewardOrRequiredMoney']);
        return $Result;
    }

    public static function BondTranslation($BondID)
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

    public static function isItemEnchanted($ItemData)
    {
        $SumValue = 0;
        $Explode = explode(" ", $ItemData);
        foreach($Explode as $Value)
            $SumValue = $SumValue + $Value;
        if($SumValue > 0)
            return true;
        else
            return false;
    }

    public static function getEnchantments($String)
    {
        $Enchantments = [];
        $Values = explode(' ', $String);
        for($i = 0; $i < count($Values); $i++)
            if($Values[$i] > 0)
                $Enchantments[] = $Values[$i];
        return $Enchantments;
    }

    public static function LoadGemProperties()
    {
        $File = getcwd().DS.'Core'.DS.'Extensions'.DS.'GemBonuses.json';
        Items::$GemProperties = json_decode(file_get_contents($File), true);
    }

    public static function FindSimilarBonusString($BonusString)
    {
        $SimilarStringKey = 0;
        $PossibleKeys = [];
        foreach(Items::$GemProperties as $Key=>$Bonus){
            similar_text($BonusString, $Bonus, $Percent);
            if($Percent > 70){
                $PossibleKeys[] = $Key;
            }
        }
        if(count($PossibleKeys) == 1)
            return $PossibleKeys[0];
        else
            return $PossibleKeys[0];
    }

    public static function GetValueVariable($String){
        if(strstr($String, " and ")){
            $Explode = explode(" and ", $String);
            preg_match_all('!\d+!', $String, $Values);

            $FirstString = self::FindSimilarBonusString($Explode[0]);
            $SecondString = self::FindSimilarBonusString($Explode[1]);
            $BonusValues = $Values[0];

            $FSBonus = Items::$GemProperties[$FirstString];
            $SSBonus = Items::$GemProperties[$SecondString];

            if(strstr($SSBonus, $FSBonus))
                $SSBonus = str_replace($FSBonus.' and ', '', $SSBonus);

            $FSBonus = trim(str_replace('%i%', '', str_replace('+%i', '', $FSBonus)));
            $SSBonus = trim(str_replace('%i%', '', str_replace('+%i', '', $SSBonus)));

            $FinalArray = [
                'first' => [
                    'value'     =>  self::BonusToValue($FSBonus),
                    'points'    =>  $BonusValues[0]
                ],
                'second' => [
                    'value'     =>  self::BonusToValue($SSBonus),
                    'points'    =>  $BonusValues[1]
                ]
            ];
            return $FinalArray;
        } else {
            $Key = self::FindSimilarBonusString($String);
            $BonusString = Items::$GemProperties[$Key];
            $Formatted = trim(str_replace('%i%', '', str_replace('+%i', '', $BonusString)));
            preg_match_all('!\d+!', $String, $Values);
            $BonusValues = $Values[0];
            $Value = self::BonusToValue($Formatted);
            if($Value == "AllStats"){
                $FinalArray = [
                    'first' => [
                        'value'     =>  self::BonusToValue('Agility'),
                        'points'    =>  $BonusValues[0]
                    ],
                    'second' => [
                        'value'     =>  self::BonusToValue('Strength'),
                        'points'    =>  $BonusValues[0]
                    ],
                    'third' => [
                        'value'     =>  self::BonusToValue('Intellect'),
                        'points'    =>  $BonusValues[0]
                    ],
                    'forth' => [
                        'value'     =>  self::BonusToValue('Stamina'),
                        'points'    =>  $BonusValues[0]
                    ],
                    'fifth' => [
                        'value'     =>  self::BonusToValue('Spirit'),
                        'points'    =>  $BonusValues[0]
                    ]
                ];
                return $FinalArray;
            } else
                return [['value' => self::BonusToValue($Formatted), 'points' => $BonusValues[0]]];

        }
    }

    public static function BonusToValue($Text){
        $Bonuses = [
            'Strength'                  =>  'StrengthValue',
            'Agility'                   =>  'AgilityValue',
            'Stamina'                   =>  'StaminaValue',
            'Intellect'                 =>  'IntellectValue',
            'Spirit'                    =>  'SpiritValue',

            'Spell Power'               =>  'SpellPowerValue',
            'Spell Penetration'         =>  'SpellPenetrationValue',

            'Critical Strike Rating'    =>  'CritValue',
            'Increased Critical Damage' =>  'CritValue',
            'Hit Rating'                =>  'HitValue',
            'All Stats'                 =>  'AllStats',
            'Armor Penetration Rating'  =>  'ArmorPenetrationValue'
        ];

        return $Bonuses[$Text];
    }

    public static function getEnchantmentData($EnchantmentID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT * FROM freedomcore_itemenchantmet WHERE itemenchantmetID = :id');
        $Statement->bindParam(':id', $EnchantmentID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result = $Statement->fetch(PDO::FETCH_ASSOC);
            $GetSpellData = Spells::GetSpellByMisc($Result['itemenchantmetID']);
            if($GetSpellData && $GetSpellData['effect1id'] == 53){
                $Result['enchant_id'] = $GetSpellData['effect1itemtype'];
                $Result['name'] = $GetSpellData['spellname_loc0'];
                if($GetSpellData['effect1itemtype'] != 0)
                    $Result['create_tooltip'] = 1;
                else
                    $Result['create_tooltip'] = 0;
                $Result['is_spell'] = 1;
                $Result['is_socket'] = 0;
            } else {
                $GemData = Items::getGemData($Result['itemenchantmetID']);
                $Result['enchant_id'] = $GemData['entry'];
                $Result['name'] = $GemData['name'];
                $Result['icon'] = $GemData['icon'];
                $Result['is_spell'] = 0;
                $Result['is_socket'] = 1;
            }

            return $Result;
        }
    }

    public static function getGemBonus($GemProperty)
    {
        $Statement = Items::$DBConnection->prepare('SELECT ie.text_loc0 as bonus FROM freedomcore_itemenchantmet ie LEFT JOIN freedomcore_gemproperties gp ON gp.itemenchantmetID = ie.itemenchantmetID WHERE gp.gempropertiesID = :gpid');
        $Statement->bindParam(':gpid', $GemProperty);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetch(PDO::FETCH_ASSOC)['bonus'];
    }

    public static function getGemData($EnchantmentID)
    {
        global $FCCore;
        $Statement = Items::$DBConnection->prepare('SELECT gempropertiesID FROM freedomcore_gemproperties WHERE itemenchantmetID = :enchid');
        $Statement->bindParam(':enchid', $EnchantmentID);
        $Statement->execute();
        $GemProperty = $Statement->fetch(PDO::FETCH_ASSOC)['gempropertiesID'];

        $WorldStatement = Items::$WConnection->prepare('SELECT it.entry, it.name, LOWER(fi.iconname) as icon FROM item_template it LEFT JOIN '.$FCCore['Database']['database'].'.freedomcore_icons fi ON it.displayid = fi.id WHERE GemProperties = :enchid');
        $WorldStatement->bindParam(':enchid', $GemProperty);
        $WorldStatement->execute();
        if(Database::IsEmpty($WorldStatement))
            return false;
        else
            return $WorldStatement->fetch(PDO::FETCH_ASSOC);
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

    private static function LoadSocketBonus()
    {
        $Statement = Items::$DBConnection->prepare('
        SELECT
            gp.itemenchantmetID as id,
            ie.text_loc0 as description
        FROM
            freedomcore_gemproperties gp, freedomcore_itemenchantmet ie
        WHERE
            gp.itemenchantmetID = ie.itemenchantmetID
        ');
        $Statement->execute();
        Items::$SocketBonuses = $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function SocketBonus($BonusID)
    {
        $Bonuses = array(
            2927 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            2868 => '+6 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            2869 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_INTELLECT'),
            2873 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_HIT_MELEE_RATING'),
            2879 => '+3 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            2892 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            2900 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_POWER'),
            3263 => '+4 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_MELEE_RATING'),
            3304 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_DODGE_RATING'),
            3307 => '+9 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            3312 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            3313 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_AGILITY'),
            3302 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_DEFENSE_SKILL_RATING'),
            3305 => '+12 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            3314 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_CRIT_TAKEN_MELEE_RATING'),
            3353 => '+8 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_INTELLECT'),
            3354 => '+12 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            3356 => '+12 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_ATTACK_POWER'),
            3357 => '+6 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STRENGTH'),
            2872 => '+9 Healing',
            3600 => '+6 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_RESILIENCE_RATING'),
            3602 => '+7 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_POWER'),
            3752 => '+5 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_POWER'),
            3753 => '+9 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_SPELL_POWER'),
            3766 => '+12 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_STAMINA'),
            3877 => '+16 '.Items::$TM->GetConfigVars('Item_Stat_ITEM_MOD_ATTACK_POWER'),
            //  => ' '.Items::$TM->GetConfigVars(''),
        );

//        foreach(Items::$SocketBonuses as $Bonus)
//            if(!in_array($Bonus['id'], $Bonuses))
//                $Bonuses[$Bonus['id']] = $Bonus['description'];

        return @$Bonuses[$BonusID];
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

    public static function QuestXP($QuestLevel, $ExpID)
    {
        $File = file(getcwd().DS.'Templates'.DS.'FreedomCore'.DS.'dbc'.DS.'QuestXP.csv');
        $LevelArray = array();
        $Level = 1;
        foreach($File as $Line)
        {
            $DataPerLevel = array();
            $Explode = explode(',', $Line);
            for($i = 0; $i < 2; $i++)
                unset($Explode[$i]);
            $Explode = array_values($Explode);
            for($i = 0; $i < 8; $i++)
                $DataPerLevel[$i+1] = $Explode[$i];
            $LevelArray[$Level] = $DataPerLevel;
            unset($DataPerLevel);
            $Level++;
        }
        return $LevelArray[$QuestLevel][$ExpID];
    }

    public static function ItemSubClass($ClassID, $SubClassID, $Menu = false, $API = false)
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
                '8' => array('subclass' => '8', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_Inscription_Bag')),
//                '9' => array('subclass' => '9', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_FishingBag')),
//                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Item_SubClass_FoodBag'))
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
                '10' => array('subclass' => '10', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Jewelcrafting')),
                '11' => array('subclass' => '11', 'translation' => Items::$TM->GetConfigVars('Character_Professions_Inscription'))
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

        if($Menu == false && $API == false)
        {
            $SubClass = $SubClassesByClasses[$ClassID];
            return $SubClass[$SubClassID];
        }
        elseif($Menu)
        {
            return $SubClassesByClasses[$ClassID];
        }
        elseif($API)
            return $SubClassesByClasses;

    }
}

Class Spells
{
    public static function SpellInfo($SpellID)
    {
        $SpellArray = array();
        $SpellData = Spells::GetSpellByID($SpellID);
        if($SpellData)
        {
            $SpellArray['SpellID'] = $SpellData['spellID'];
            $SpellArray['Name'] = $SpellData['spellname_loc0'];
            $SpellArray['Description'] = Spells::ParseDescription($SpellData, $SpellData['tooltip_loc0']);
            $SpellArray['icon'] = $SpellData['icon'];
            return $SpellArray;
        }
        else
            return false;
    }

    private static function replaceBrackets($String)
    {
        return str_replace('$$', '$', str_replace('{', '', str_replace('}', '', $String)));
    }

    private static function ParseDescription($SpellData, $DescriptionString)
    {
        $DescriptionString = strtr($DescriptionString, array("\r" => '', "\n" => '<br />'));
        $DescriptionString = self::replaceBrackets($DescriptionString);
        $Modifiers = array('+', '-', '/', '*', '^');
        $SubSpells = array();
        $ModifierWasFound = false;
        $DurationChanged = false;
        $UsedModifier = "";
        foreach($Modifiers as $Modifier)
        {
            if(strstr(substr($DescriptionString, strpos($DescriptionString, '$')), $Modifier))
            {
                $Explode = explode($Modifier, substr($DescriptionString, strpos($DescriptionString, '$')));
                $SpellID = $Explode[0];
                $UsedModifier = $Modifier;
                if(strstr($Explode[1], ';')){
                    @$SecondExplode = explode(';', $Explode[1]);
                    $ExplodedWith = ';';
                }
                else{
                    @$SecondExplode = explode(' ', $Explode[1]);
                    $ExplodedWith = ' ';
                }
                $Duration = $SecondExplode[0];
                $SubSpells[] = @array(str_replace('.', '', $SecondExplode[1]));
                $ModifierWasFound = true;
            }
        }
        preg_match_all('!\$\w+\D\d?!', $DescriptionString, $SubSpells);
        if(empty($SubSpells[0]))
            preg_match_all('!\$\D\d?!', $DescriptionString, $SubSpells);

        $SubSpells = call_user_func_array('array_merge', $SubSpells);
        $SubSpellsData = Spells::ParseSubSpells($SubSpells);
        if(isset($Duration)){
            $OldDuration = "";
            if(strstr($Duration, '$')){
                $DurationChanged = true;
                $OldDuration = $Duration;
                $Data = self::ParseSubSpells([$Duration])[0];
                $ParseResult = Spells::ArgumentParser($SpellData, $Data['SpellID'], $Data['Argument'], $Data['ArgumentValue']).' ';
                $Duration = $ParseResult;

                $Unset = Text::Search($SubSpellsData, ['SpellID' => $Data['SpellID'], 'Argument' => $Data['Argument']])[0];
                unset($SubSpellsData[$Unset]);
            }
        }

        foreach($SubSpellsData as $SubSpell)
        {
            if($ModifierWasFound == false)
            {
                $ParseResult = Spells::ArgumentParser($SpellData, $SubSpell['SpellID'], $SubSpell['Argument'], $SubSpell['ArgumentValue']).' ';
                $Replacement = '$'.$SubSpell['SpellID'].$SubSpell['Argument'].$SubSpell['ArgumentValue'];
                $DescriptionString = str_replace($Replacement, $ParseResult, $DescriptionString);
            }
            else
            {
                $ParseResult = Spells::ArgumentParser($SpellData, $SubSpell['SpellID'], $SubSpell['Argument'], $SubSpell['ArgumentValue'], $UsedModifier.$Duration);
                if($DurationChanged)
                    if(strstr($SubSpell['SpellID'], '*') || strstr($SubSpell['Argument'], '*') || strstr($SubSpell['ArgumentValue'], '*'))
                        $Replacement = '$'.$SubSpell['SpellID'].$SubSpell['Argument'].$SubSpell['ArgumentValue'].$OldDuration;
                    else
                        $Replacement = '$'.$SubSpell['SpellID'].$SubSpell['Argument'].$SubSpell['ArgumentValue'];
                else
                    $Replacement = '$'.$UsedModifier.$Duration.$ExplodedWith.$SecondExplode[1];
                $DescriptionString = str_replace($Replacement, $ParseResult, $DescriptionString);
            }
        }

        return $DescriptionString;
    }

    public static function GetGlyphData($GlyphID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT gp.spellid, gp.iconid, LOWER(si.iconname) as icon  FROM freedomcore_glyphproperties gp LEFT JOIN freedomcore_spellicons si ON gp.iconid = si.id WHERE gp.id = :glyphid');
        $Statement->bindParam(':glyphid', $GlyphID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function ParseSubSpells($SubSpells)
    {
        $SpellSplitter = array('r', 'z', 'c', 's', 'o', 't', 'm', 'x', 'q', 'a', 'h', 'f', 'n', 'd', 'i', 'e', 'v', 'u', 'b', 'l', 'g');
        $SubSpellsData = array();
        foreach($SubSpells as $SubSpell){
            $SubSpell = str_replace('.', '', str_replace(',', '', $SubSpell));
            foreach($SpellSplitter as $SplitBy)
                if(strstr($SubSpell, $SplitBy))
                {
                    $Explode = explode($SplitBy, $SubSpell);;
                    $SubSpellsData[] = array('SpellID' => str_replace('$', '', $Explode[0]), 'Argument' => $SplitBy, 'ArgumentValue' => $Explode[1]);
                }
        }
        return $SubSpellsData;
    }

    private static function ArgumentParser($SpellData, $Spell, $Argument, $Value, $MathEquation = false)
    {
        $Modifiers = array('+', '-', '/', '*', '%', '^');
        $Data = '';
        switch($Argument)
        {
            case 's':
                if(is_numeric($Spell))
                    $SpellData = Spells::GetSpellByID($Spell);
                if($MathEquation == false)
                {
                    $BasePoints = $SpellData['effect1BasePoints']+1;
                    $Data = abs($BasePoints).($SpellData['effect1DieSides'] > 1 ? ' - '.abs(($BasePoints+$SpellData['effect1DieSides'])) : '');
                }
                else
                {
                    $Equation = abs($SpellData['effect1BasePoints']).$MathEquation;
                    eval("\$BasePoints = $Equation;");
                    $Data = abs(floor($BasePoints)).($SpellData['effect1DieSides'] > 1 ? ' - '.abs((floor($BasePoints)+$SpellData['effect1DieSides'])) : '');
                }
            break;

            case 'm':
                if(is_numeric($Spell))
                    $SpellData = Spells::GetSpellByID($Spell);
                if($MathEquation){
                    $Equation = abs($SpellData['effect1BasePoints']).$MathEquation;
                    eval("\$BasePoints = $Equation;");
                    $Data = abs($BasePoints);
                }
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

                $BasePoints = Spells::GetSpellRadiusByRadiusID($SpellData['effect'.trim($Value).'radius']);

                $Data = abs($BasePoints);
            break;
        }

        return $Data;
    }

    public static function GetSpellByID($SpellID)
    {
        $Statement = Items::$DBConnection->prepare('SELECT fs.*, LOWER(fsi.iconname) as icon FROM freedomcore_spell fs, freedomcore_spellicons fsi WHERE fs.spellID = :spellid AND fsi.id = fs.spellicon LIMIT 1');
        $Statement->bindParam(':spellid', $SpellID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if ($Statement->rowCount() > 0)
            return $Result;
        else
            return false;
    }

    public static function GetSpellByMisc($MiscValue)
    {
        $Statement = Items::$DBConnection->prepare('SELECT fs.*, LOWER(fsi.iconname) as icon FROM freedomcore_spell fs, freedomcore_spellicons fsi WHERE fs.effect1MiscValue = :mvalue AND fsi.id = fs.spellicon LIMIT 1');
        $Statement->bindParam(':mvalue', $MiscValue);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if (Database::IsEmpty($Statement))
            return false;
        else
            return $Result;
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