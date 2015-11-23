<?php

Class Classes
{
	public static $DBConnection;
	public static $TM;

	public function __construct($VariablesArray)
	{
		Classes::$DBConnection = $VariablesArray[0]::$Connection;
		Classes::$TM = $VariablesArray[1];
	}

	public static function GetAll()
	{
		$Statement = Classes::$DBConnection->prepare('SELECT * FROM classes');
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
		{
			$Result[$i]['class_full_name'] = Classes::$TM->GetConfigVars('Class_'.str_replace('-', '_', ucfirst($Result[$i]['class_name'])));
			$Result[$i]['class_description_classes'] = Classes::$TM->GetConfigVars($Result[$i]['class_description_classes']);
		}
		return $Result;
	}

	public static function GetClass($ClassName)
	{
		$Statement = Classes::$DBConnection->prepare('SELECT * FROM classes WHERE class_name = :class');
		$Statement->bindParam(':class', $ClassName);
		$Statement->execute();
		$Result = $Statement->fetch(PDO::FETCH_ASSOC);
		$Result['class_full_name'] = Classes::$TM->GetConfigVars('Class_'.str_replace('-', '_', ucfirst($Result['class_name'])));
		$Result['indicator_first_type'] = Classes::$TM->GetConfigVars($Result['indicator_first_type']);
		$Result['indicator_second_type'] = Classes::$TM->GetConfigVars($Result['indicator_second_type']);
		$Result['can_be_picked_by'] = Classes::RaceClassMatch($ClassName);
		$Result['abilities'] = Classes::GetAbilities($ClassName);
		return $Result;
	}

	private static function RaceClassMatch($ClassName)
	{
		$PreparedQuery = 'SELECT rcr.race, r.can_join_alliance, r.can_join_horde FROM  raceclassrelation rcr, races r WHERE rcr.race = r.race AND rcr.`class_'.$ClassName.'` = 1';
		$Statement = Classes::$DBConnection->prepare($PreparedQuery);
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
		{
			if($Result[$i]['race'] == 'blood-elf')
				$Result[$i]['race_full_name'] = Races::$TM->GetConfigVars('Race_Blood_Elf');
			elseif($Result[$i]['race'] == 'night-elf')
				$Result[$i]['race_full_name'] = Races::$TM->GetConfigVars('Race_Night_Elf');
			else
				$Result[$i]['race_full_name'] = Races::$TM->GetConfigVars('Race_'.ucfirst($Result[$i]['race']));
		}
		return $Result;
	}

	private static function GetAbilities($ClassName)
	{
		$Statement = Classes::$DBConnection->prepare('SELECT * FROM classabilities WHERE class_name = :class');
		$Statement->bindParam(':class', $ClassName);
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
		{
			$Result[$i]['ability_name'] = Races::$TM->GetConfigVars($Result[$i]['ability_name']);
			$Result[$i]['ability_description'] = Races::$TM->GetConfigVars($Result[$i]['ability_description']);
		}
		return $Result;
	}

	public static function GetNavigation($ClassID)
	{
		if($ClassID == 1)
			$Statement = Races::$DBConnection->prepare('SELECT id+1 as nextclass, max(id) as previousclass FROM classes WHERE id = :id');
		elseif($ClassID == 11)
			$Statement = Races::$DBConnection->prepare('SELECT (SELECT min(id) FROM classes) as nextclass, id-1 as previousclass FROM classes WHERE id = :id');
		else
			$Statement = Races::$DBConnection->prepare('SELECT id+1 as nextclass, id-1 as previousclass FROM classes WHERE id = :id');
		$Statement->bindParam(':id', $ClassID);
		$Statement->execute();
		$NavigationIDs = $Statement->fetch(PDO::FETCH_NUM);

		$GetRaceNames = Races::$DBConnection->prepare('SELECT class_name FROM classes WHERE id IN (:idfirst, :idlast)');
		$GetRaceNames->bindParam(':idfirst', $NavigationIDs[0]);
		$GetRaceNames->bindParam(':idlast', $NavigationIDs[1]);
		$GetRaceNames->execute();
		$Result = $GetRaceNames->fetchAll(PDO::FETCH_ASSOC);

		$PreviousClass = 'Class_'.ucfirst($Result[0]['class_name']);

		$NextClass = 'Class_'.ucfirst($Result[1]['class_name']);


		return array('previousclass' => $Result[0]['class_name'], 'previousname' => Races::$TM->GetConfigVars($PreviousClass), 'nextclass' => $Result[1]['class_name'], 'nextname' => Races::$TM->GetConfigVars($NextClass));
	}

    public static function getBoostClassData($ClassID)
    {
        return [
            'SetGear'   =>  Classes::getBoostClassSetGear($ClassID),
            'Weapons'   =>  Classes::getBoostClassWeapons($ClassID),
            'Rings'     =>  Classes::getBoostClassRings($ClassID),
            'Trinkets'  =>  Classes::getBoostClassTrinkets($ClassID),
            'NonSet'    =>  Classes::getBoostClassNonSet($ClassID)
        ];
    }

	public static function getBoostClassSetGear($ClassID){

		$Classes = [
			1	=>	[
				'protection'	=>	[45425, 45428, 45424, 45427, 45426],
				'fury'			=>	[45431, 45433, 45429, 45432, 45430],
				'arms'			=>	[45431, 45433, 45429, 45432, 45430]
			],
			2	=>	[
				'holy'			=>	[45370, 45371, 45372, 45373, 45374],
				'retribution'	=>	[45375, 45376, 45377, 45379, 45380],
				'protection'	=>	[45381, 45382, 45383, 45384, 45385]
			],
			3	=>	[
				'beast_mastery'	=>	[46273, 46274, 46275, 46276, 46277],
				'survival'		=>	[46273, 46274, 46275, 46276, 46277],
				'marksmanship'	=>	[46273, 46274, 46275, 46276, 46277]
			],
			4	=>	[
				'assassination'	=>	[45396, 45397, 45398, 45399, 45400],
				'combat'		=>	[45396, 45397, 45398, 45399, 45400],
				'subtlety'		=>	[45396, 45397, 45398, 45399, 45400]
			],
			5	=>	[
				'shadow'		=>	[45391, 45392, 45393, 45394, 45395],
				'holy'			=>	[45386, 45387, 45388, 45389, 45390],
				'discipline'	=>	[45386, 45387, 45388, 45389, 45390],
			],
			6	=>	[
				'blood'			=>	[45335, 45336, 45337, 45338, 45339],
				'frost'			=>	[45340, 45341, 45342, 45343, 45344],
				'unholy'		=>	[45340, 45341, 45342, 45343, 45344]
			],
			7	=>	[
				'elemental'		=>	[45406, 45411, 45408, 45409, 45410],
				'restoration'	=>	[45401, 45402, 45403, 45404, 45405],
				'enhancement'	=>	[45413, 45414, 45415, 45412, 45416]
			],
			8	=>	[
				'fire'			=>	[46131, 45365, 45367, 45369, 45368],
				'frost'			=>	[46131, 45365, 45367, 45369, 45368],
				'arcane'		=>	[46131, 45365, 45367, 45369, 45368]
			],
			9	=>	[
				'affliction'	=>	[46242, 46243, 46244, 46245, 46246],
				'demonology'	=>	[46242, 46243, 46244, 46245, 46246],
				'destruction'	=>	[46242, 46243, 46244, 46245, 46246]
			],
			10	=>	[

			],
			11	=>	[
				'restoration'	=>	[45345, 45346, 45347, 45348, 45349],
				'balance'		=>	[46313, 45351, 45352, 45353, 45354],
				'feral'			=>	[45355, 45356, 45357, 45358, 45359],
				'guardian'		=>	[45355, 45356, 45357, 45358, 45359]
			]
		];
		return $Classes[$ClassID];
	}

    public static function getBoostClassWeapons($ClassID){
        $Classes = [
            1	=>	[
                'protection'	=>	[47500, 49835, 42450],
                'fury'			=>	[46016, 46016, 40190],
                'arms'			=>	[46016, 40190]
            ],
            2	=>	[
                'holy'			=>	[49833, 45314],
                'retribution'	=>	[46016],
                'protection'	=>	[47500, 49835]
            ],
            3	=>	[
                'beast_mastery'	=>	[45695, 46339],
                'survival'		=>	[45695, 46339],
                'marksmanship'	=>	[45695, 46339]
            ],
            4	=>	[
                'assassination'	=>	[46011, 46011],
                'combat'		=>	[46011, 46011],
                'subtlety'		=>	[46011, 46011]
            ],
            5	=>	[
                'shadow'		=>	[49790],
                'holy'			=>	[49790],
                'discipline'	=>	[49790],
            ],
            6	=>	[
                'blood'			=>	[49839],
                'frost'			=>	[45458],
                'unholy'		=>	[45892, 49783]
            ],
            7	=>	[
                'elemental'		=>	[46025],
                'restoration'	=>	[47509, 45314],
                'enhancement'	=>	[45284, 45284]
            ],
            8	=>	[
                'fire'			=>	[46025],
                'frost'			=>	[46025],
                'arcane'		=>	[46025]
            ],
            9	=>	[
                'affliction'	=>	[46025],
                'demonology'	=>	[46025],
                'destruction'	=>	[46025]
            ],
            10	=>	[

            ],
            11	=>	[
                'restoration'	=>	[47509, 45314],
                'balance'		=>	[49790],
                'feral'			=>	[49793],
                'guardian'		=>	[49793]
            ]
        ];
        return $Classes[$ClassID];
    }

    public static function getBoostClassRings($ClassID){
        $Classes = [
            1	=>	[
                'protection'	=>	[45874, 47243],
                'fury'			=>	[45675, 49812],
                'arms'			=>	[45675, 49812]
            ],
            2	=>	[
                'holy'			=>	[45418, 49800],
                'retribution'	=>	[45675, 49812],
                'protection'	=>	[45874, 47243]
            ],
            3	=>	[
                'beast_mastery'	=>	[45303, 49803],
                'survival'		=>	[45303, 49803],
                'marksmanship'	=>	[45303, 49803]
            ],
            4	=>	[
                'assassination'	=>	[45303, 49803],
                'combat'		=>	[45303, 49803],
                'subtlety'		=>	[45303, 49803]
            ],
            5	=>	[
                'shadow'		=>	[47512, 45702],
                'holy'			=>	[45418, 49800],
                'discipline'	=>	[45418, 49800],
            ],
            6	=>	[
                'blood'			=>	[45874, 47243],
                'frost'			=>	[45675, 49812],
                'unholy'		=>	[45675, 49812]
            ],
            7	=>	[
                'elemental'		=>	[47512, 45702],
                'restoration'	=>	[45418, 49800],
                'enhancement'	=>	[45303, 49803]
            ],
            8	=>	[
                'fire'			=>	[47512, 45702],
                'frost'			=>	[47512, 45702],
                'arcane'		=>	[47512, 45702]
            ],
            9	=>	[
                'affliction'	=>	[47512, 45702],
                'demonology'	=>	[47512, 45702],
                'destruction'	=>	[47512, 45702]
            ],
            10	=>	[

            ],
            11	=>	[
                'restoration'	=>	[45418, 49800],
                'balance'		=>	[47512, 45702],
                'feral'			=>	[45303, 49803],
                'guardian'		=>	[45303, 49803]
            ]
        ];
        return $Classes[$ClassID];
    }

    public static function getBoostClassTrinkets($ClassID){
        $Classes = [
            1	=>	[
                'protection'	=>	[46021, 45313],
                'fury'			=>	[45286, 40256],
                'arms'			=>	[45286, 40256]
            ],
            2	=>	[
                'holy'			=>	[45292, 45703],
                'retribution'	=>	[45286, 40256],
                'protection'	=>	[46021, 45313]
            ],
            3	=>	[
                'beast_mastery'	=>	[45286, 40256],
                'survival'		=>	[45286, 40256],
                'marksmanship'	=>	[45286, 40256]
            ],
            4	=>	[
                'assassination'	=>	[45286, 40256],
                'combat'		=>	[45286, 40256],
                'subtlety'		=>	[45286, 40256]
            ],
            5	=>	[
                'shadow'		=>	[45866, 45308],
                'holy'			=>	[45292, 45703],
                'discipline'	=>	[45292, 45703],
            ],
            6	=>	[
                'blood'			=>	[46021, 45313],
                'frost'			=>	[45286, 40256],
                'unholy'		=>	[45286, 40256]
            ],
            7	=>	[
                'elemental'		=>	[45866, 45308],
                'restoration'	=>	[45292, 45703],
                'enhancement'	=>	[45286, 40256]
            ],
            8	=>	[
                'fire'			=>	[45866, 45308],
                'frost'			=>	[45866, 45308],
                'arcane'		=>	[45866, 45308]
            ],
            9	=>	[
                'affliction'	=>	[45866, 45308],
                'demonology'	=>	[45866, 45308],
                'destruction'	=>	[45866, 45308]
            ],
            10	=>	[

            ],
            11	=>	[
                'restoration'	=>	[45292, 45703],
                'balance'		=>	[45866, 45308],
                'feral'			=>	[45286, 40256],
                'guardian'		=>	[46021, 45313]
            ]
        ];
        return $Classes[$ClassID];
    }

    public static function getBoostClassNonSet($ClassID){
        $Classes = [
            1	=>	[
                'protection'	=>	[46343, 45304, 49795, 45283, 49832],
                'fury'			=>	[45285, 49808, 45330, 47565, 45318],
                'arms'			=>	[45285, 49808, 45330, 47565, 45318]
            ],
            2	=>	[
                'holy'			=>	[46015, 47510, 45698, 46345, 45317],
                'retribution'	=>	[45285, 49808, 45330, 47565, 45318],
                'protection'	=>	[46343, 45304, 49795, 45283, 49832]
            ],
            3	=>	[
                'beast_mastery'	=>	[47494, 49810, 46346, 49820, 49792],
                'survival'		=>	[47494, 49810, 46346, 49820, 49792],
                'marksmanship'	=>	[47494, 49810, 46346, 49820, 49792]
            ],
            4	=>	[
                'assassination'	=>	[47494, 49806, 45302, 47496, 49792],
                'combat'		=>	[47494, 49806, 45302, 47496, 49792],
                'subtlety'		=>	[47494, 49806, 45302, 47496, 49792]
            ],
            5	=>	[
                'shadow'		=>	[49799, 45306, 49798, 45291, 45317],
                'holy'			=>	[46015, 45694, 49805, 45423, 45317],
                'discipline'	=>	[46015, 45694, 49805, 45423, 45317],
            ],
            6	=>	[
                'blood'			=>	[46343, 45304, 49795, 45283, 49832],
                'frost'			=>	[45285, 49808, 45330, 47565, 45318],
                'unholy'		=>	[45285, 49808, 45330, 47565, 45318]
            ],
            7	=>	[
                'elemental'		=>	[49799, 45333, 45316, 49798, 45317],
                'restoration'	=>	[46015, 45333, 45316, 49798, 45317],
                'enhancement'	=>	[47494, 49810, 46346, 49820, 49792]
            ],
            8	=>	[
                'fire'			=>	[49799, 45306, 49798, 45291, 45317],
                'frost'			=>	[49799, 45306, 49798, 45291, 45317],
                'arcane'		=>	[49799, 45306, 49798, 45291, 45317]
            ],
            9	=>	[
                'affliction'	=>	[49799, 45306, 49798, 45291, 45317],
                'demonology'	=>	[49799, 45306, 49798, 45291, 45317],
                'destruction'	=>	[49799, 45306, 49798, 45291, 45317]
            ],
            10	=>	[

            ],
            11	=>	[
                'restoration'	=>	[46015, 40566, 47504, 46009, 49823],
                'balance'		=>	[49799, 40566, 47504, 46009, 45317],
                'feral'			=>	[47494, 49806, 45302, 47496, 49792],
                'guardian'		=>	[47494, 45709, 45302, 47496, 49792]
            ]
        ];
        return $Classes[$ClassID];
    }

	public static function getBoostProfessions($CharacterGUID){
		$Professions = Characters::getCharactersProfessions($CharacterGUID);
		for($i = 0; $i < count($Professions); $i++){
			$Professions[$i]['new_value']	= 450;
			$Professions[$i]['new_max']		= 450;
		}
		return $Professions;
	}

	public static function getBoostSpells($ClassID)
	{
		$Classes = [
			1	=>	[750, 2567, 227, 264, 5011],
			2	=>	[750, 198, 199],
			3	=>	[8737, 2567, 227, 264, 5011, 266],
			6	=>	[750, 198, 199],
			7	=>	[8737]
		];

		if(array_key_exists($ClassID, $Classes))
			return $Classes[$ClassID];
	}

	public static function prepareBoostData($Character){
		$ClassItems = Classes::getBoostClassData($Character['class']);
		$CharacterItems = [];
		$DataArray['Main'] = Characters::generateCharacterLevelUP($Character['guid']);

		foreach($ClassItems as $CI)
			foreach($CI as $Key => $Value)
				if($Key == $_REQUEST['specialization'])
					$CharacterItems[] = $Value;
		$CharacterItems = call_user_func_array('array_merge', $CharacterItems);

		if($Character['level'] >= 60){
			$BoostProfessions = Classes::getBoostProfessions($Character['guid']);
			$DataArray['Skills'] = Characters::generateCharacterSkillsSQL($Character['guid'], $BoostProfessions);
		}

		if(self::getBoostSpells($Character['class']))
			$DataArray['Spells'] = Characters::generateCharacterSpellsSQL($Character['guid'], self::getBoostSpells($Character['class']));
		$DataArray['ItemInstance'] = Items::generateItemInstanceSQL($Character['guid'], $CharacterItems);
		$DataArray['CharacterInv'] = Characters::generateCharacterInventorySQL($Character['guid'], $CharacterItems);

		return $DataArray;
	}

	public static function performCharacterBoost($Character)
	{
		$SQLs = self::prepareBoostData($Character);
		foreach($SQLs as $Categories){
			foreach($Categories as $Query)
				Database::plainSQL('Characters', $Query);
		}
	}
}

?>