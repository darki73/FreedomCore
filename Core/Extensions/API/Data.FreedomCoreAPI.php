<?php

Class DataAPI extends API
{
    public static function CharacterRaces()
    {
        $Statement = parent::$DBConnection->prepare('SELECT race_id as id, race as name, can_join_alliance, can_join_horde FROM races');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Result[] = ['id' => '25', 'race' => 'pandaren', 'can_join_alliance' => 1, 'can_join_horde' => 0];
        $Result[] = ['id' => '26', 'race' => 'pandaren', 'can_join_alliance' => 0, 'can_join_horde' => 1];
        $ArrayIndex = 0;
        foreach($Result as $Race)
        {
            $Result[$ArrayIndex]['mask'] = DataAPI::RacialBitmastConverter($Race['id']);
            if($Race['can_join_alliance'] && !$Race['can_join_horde'])
                $Result[$ArrayIndex]['side'] = 'alliance';
            elseif(!$Race['can_join_alliance'] && $Race['can_join_horde'])
                $Result[$ArrayIndex]['side'] = 'horde';
            else
                $Result[$ArrayIndex]['side'] = 'neutral';
            unset($Result[$ArrayIndex]['can_join_alliance']);
            unset($Result[$ArrayIndex]['can_join_horde']);
            $ArrayIndex++;
        }
        parent::Encode($Result, 'races');
    }

    public static function CharacterClasses()
    {
        $Statement = parent::$DBConnection->prepare('SELECT class_id as id, class_name as name, LOWER(REPLACE(indicator_second_type, "Class_Indicator_", "")) as powerType FROM classes');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Class)
        {
            if($Class['powerType'] == 'druid')
                $Result[$ArrayIndex]['powerType'] = 'mana';
            elseif($Class['powerType'] == 'monk')
                $Result[$ArrayIndex]['powerType'] = 'energy';
            if(strstr($Class['name'], '-') !== false)
            {
                $Explode = explode('-', $Class['name']);
                $Result[$ArrayIndex]['name'] = ucfirst($Explode[0]).' '.ucfirst($Explode[1]);
            }
            else
                $Result[$ArrayIndex]['name'] = ucfirst($Class['name']);
            $Result[$ArrayIndex]['mask'] = DataAPI::ClassBitmastConverter($Class['id']);
            $ArrayIndex++;
        }
        parent::Encode($Result, 'classes');
    }

    public static function ItemClasses()
    {
        $Result = Items::ItemSubClass(null, null, false, true);
        $JSONArray = [];
        foreach($Result as $Key => $Value)
        {
            $ClassArray = [];
            $ClassArray['class'] = $Key;
            $ClassArray['name'] = Items::ItemClass($Key)['translation'];
            $ClassArray['subclasses'] = [];
            foreach($Value as $SKey => $SValue)
            {
                $ClassArray['subclasses'][] = [
                    'subclass' => $SValue['subclass'],
                    "name" => $SValue['translation']
                ];
            }
            $JSONArray[] = $ClassArray;
        }
        parent::Encode($JSONArray, 'classes');
    }

    private static function RacialBitmastConverter($RaceID)
    {
        $RaceMask = [
            1 => 1,
            2 => 2,
            3 => 4,
            4 => 8,
            5 => 16,
            6 => 32,
            7 => 64,
            8 => 128,
            9 => 256,
            10 => 512,
            11 => 1024,
            22 => 2097152,
            24 => 8388608,
            25 => 16777216,
            26 => 33554432
        ];
        return $RaceMask[$RaceID];
    }

    private static function ClassBitmastConverter($ClassID)
    {
        $ClassMask = [
            1 => 1,
            2 => 2,
            3 => 4,
            4 => 8,
            5 => 16,
            6 => 32,
            7 => 64,
            8 => 128,
            9 => 256,
            10 => 512,
            11 => 1024
        ];
        return $ClassMask[$ClassID];
    }
}

?>