<?php

Class ItemAPI extends API
{
    public static function GetSingleItem($ItemID)
    {
        $NewArray = [];
        $Item = Items::GetItemInfo($ItemID);
        if($Item != false)
        {
            $NewArray['id'] = $Item['entry'];
            foreach(array('description', 'name', 'icon', 'stackable') as $Field)
                $NewArray[$Field] = $Item[$Field];
            $NewArray['allowableClasses'] = 'NYI';
            $NewArray['itemBind'] = $Item['bonding'];
            for($i = 1; $i <= 10; $i++)
                if($Item['stat_type'.$i] != 0)
                    $NewArray['bonusStats'][] = ['stat' => $Item['stat_type'.$i], 'amount' => $Item['stat_value'.$i]];
            for($i = 1; $i <= 5; $i++)
                if($Item['spellid_'.$i] != -1 && $Item['spellid_'.$i] != 0)
                    $NewArray['itemSpells'][] = ['spellId' => $Item['spell_data'.$i]['SpellID'], 'spell' => ['id' => $Item['spell_data'.$i]['SpellID'], 'name' => $Item['spell_data'.$i]['Name'], 'icon' => $Item['spell_data'.$i]['icon'], 'description' => $Item['spell_data'.$i]['Description']]];
            if(isset($Item['BuyPrice']['gold']))
                $FinalBuyPrice = $Item['BuyPrice']['gold'];
            if(isset($Item['BuyPrice']['silver']))
                $FinalBuyPrice = $Item['BuyPrice']['silver'];
            if(isset($Item['BuyPrice']['copper']))
                $FinalBuyPrice = $Item['BuyPrice']['copper'];

            $NewArray['buyPrice'] = $FinalBuyPrice;
            $NewArray['itemClass'] = $Item['class']['class'];
            $NewArray['itemSubClass'] = $Item['subclass']['subclass'];
            $NewArray['containerSlots'] = $Item['ContainerSlots'];
            if($Item['class']['class'] == 2)
            {
                if ($Item['dmg_min1'] != 0)
                {
                    $DPS = ($Item['dmg_min1'] + $Item['dmg_max1'])/2/($Item['delay']/1000);
                    $DamageMin = $Item['dmg_min1'];
                    $DamageMax = $Item['dmg_max1'];
                }
                elseif($Item['dmg_min2'] != 0)
                {
                    $DPS = ($Item['dmg_min2'] + $Item['dmg_max2'])/2/($Item['delay']/1000);
                    $DamageMin = $Item['dmg_min2'];
                    $DamageMax = $Item['dmg_max2'];
                }
                $NewArray['weaponInfo'] = [
                    'damage' => [
                        'min' => $DamageMin,
                        'max' => $DamageMax,
                        'exactMin' => number_format((float)$DamageMin, 1, '.', ''),
                        'exactMax' => number_format((float)$DamageMax, 1, '.', '')
                    ],
                    'weaponSpeed' => $Item['delay']/1000,
                    'dps' => $DPS
                ];
            }
            $NewArray['inventoryType'] = $Item['InventoryType'];
            $NewArray['itemLevel'] = $Item['ItemLevel'];
            $NewArray['maxCount'] = $Item['maxcount'];
            $NewArray['maxDurability'] = $Item['MaxDurability'];
            $NewArray['minFactionId'] = $Item['RequiredReputationFaction'];
            $NewArray['minReputation'] = $Item['RequiredReputationRank'];
            $NewArray['quality'] = $Item['Quality'];
            if(isset($Item['SellPrice']['gold']))
                $FinalSellPrice = $Item['SellPrice']['gold'];
            if(isset($Item['SellPrice']['silver']))
                $FinalSellPrice = $Item['SellPrice']['silver'];
            if(isset($Item['SellPrice']['copper']))
                $FinalSellPrice = $Item['SellPrice']['copper'];
            $NewArray['sellPrice'] = $FinalSellPrice;
            $NewArray['requiredSkill'] = $Item['RequiredSkill'];
            $NewArray['requiredLevel'] = $Item['RequiredLevel'];
            $NewArray['requiredSkillRank'] = $Item['RequiredSkillRank'];
            $SocketsCount = 0;
            for($i = 1; $i <= 3; $i++)
                if($Item['socketColor_'.$i] != 0)
                {
                    $NewArray['socketInfo']['sockets'][] = ['type' => Items::SocketDescription($Item['socketColor_'.$i])['type']];
                    $SocketsCount++;
                }
            if($SocketsCount > 0)
                $NewArray['socketInfo']['socketBonus'] = $Item['socketBonusDescription'];
            $NewArray['baseArmor'] = $Item['armor'];
            if($SocketsCount > 0)
                $NewArray['hasSockets'] = true;
            else
                $NewArray['hasSockets'] = false;
            $NewArray['displayInfoId'] = $Item['displayid'];

            return parent::Encode($NewArray);
        }
        else
            return parent::GenerateResponse(404, true);
    }

    public static function GetItemSet($SetID)
    {
        $Statement = parent::$DBConnection->prepare('SELECT * FROM freedomcore_itemset WHERE itemsetID = :setid');
        $Statement->bindParam(':setid', $SetID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if ($Statement->rowCount() > 0)
        {
            $FinalArray = [];

            $FinalArray['id'] = $Result['itemsetID'];
            $FinalArray['name'] = $Result['name_loc0'];
            $i = 8;
            while ($i > 0) {
                if ($Result['bonus' . $i] != 0)
                    $FinalArray['setBonuses'][] = ['description' => Spells::SpellInfo($Result['spell' . $i])['Description'], 'threshold' => $Result['bonus' . $i]];
                $i--;
            }
            $SetItems = [];
            for ($i = 1; $i <= 10; $i++)
                if ($Result['item' . $i] != 0)
                    $SetItems[] = $Result['item' . $i];

            $FinalArray['items'] = $SetItems;
            parent::Encode($FinalArray);
        }
        else
            parent::GenerateResponse(404, true);
    }
}

?>