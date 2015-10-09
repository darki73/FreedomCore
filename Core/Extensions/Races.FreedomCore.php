<?php

Class Races
{
	public static $DBConnection;
	public static $TM;

	public function __construct($VariablesArray)
	{
		Races::$DBConnection = $VariablesArray[0]::$Connection;
		Races::$TM = $VariablesArray[1];
	}

	public static function GetHorde()
	{
		$Statement = Races::$DBConnection->prepare('SELECT * FROM factions WHERE side = 0');
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
		{
			$Result[$i]['race_name'] = Races::$TM->GetConfigVars($Result[$i]['race_name']);
			$Result[$i]['race_description'] = Races::$TM->GetConfigVars($Result[$i]['race_description']);
		}
		return $Result;
	}

	public static function GetAlliance()
	{
		$Statement = Races::$DBConnection->prepare('SELECT * FROM factions WHERE side = 1');
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
		{
			$Result[$i]['race_name'] = Races::$TM->GetConfigVars($Result[$i]['race_name']);
			$Result[$i]['race_description'] = Races::$TM->GetConfigVars($Result[$i]['race_description']);
		}
		return $Result;
	}

	public static function GetRace($RaceName)
	{
		$Statement = Races::$DBConnection->prepare('SELECT * FROM races WHERE race = :race');
		$Statement->bindParam(':race', $RaceName);
		$Statement->execute();
		$Result = $Statement->fetch(PDO::FETCH_ASSOC);
		if($Result['race'] == 'blood-elf')
			$Result['race_full_name'] = Races::$TM->GetConfigVars('Race_Blood_Elf');
		elseif($Result['race'] == 'night-elf')
			$Result['race_full_name'] = Races::$TM->GetConfigVars('Race_Night_Elf');
		else
			$Result['race_full_name'] = Races::$TM->GetConfigVars('Race_'.ucfirst($Result['race']));
		$Result['classes'] = Races::GetRaceClassRelation($RaceName);
		return $Result;
	}

	private static function GetRaceClassRelation($RaceName)
	{
		$ClassArray = array();
		$Statement = Races::$DBConnection->prepare('SELECT * FROM raceclassrelation WHERE race = :race');
		$Statement->bindParam(':race', $RaceName);
		$Statement->execute();
		$Result = $Statement->fetch(PDO::FETCH_ASSOC);
		unset($Result['id']);
		unset($Result['race']);
		foreach($Result as $Key=>$Value)
		{
			$ClassArray[$Key] = array(
				'can_be' => $Value, 
				'class_link' => str_replace('class_', '', $Key),
				'class_name' => Races::$TM->GetConfigVars('Class_'.ucfirst(str_replace('class_', '', str_replace('-', '_', $Key))))
			);
		}

		return $ClassArray;
	}

	public static function GetNavigation($RaceID)
	{
		if($RaceID == 1)
			$Statement = Races::$DBConnection->prepare('SELECT id+1 as nextrace, max(id) as previousrace FROM races WHERE id = :id');
		elseif($RaceID == 13)
			$Statement = Races::$DBConnection->prepare('SELECT (SELECT min(id) FROM races) as nextrace, id-1 as previousrace FROM races WHERE id = :id');
		else
			$Statement = Races::$DBConnection->prepare('SELECT id+1 as nextrace, id-1 as previousrace FROM races WHERE id = :id');
		$Statement->bindParam(':id', $RaceID);
		$Statement->execute();
		$NavigationIDs = $Statement->fetch(PDO::FETCH_NUM);

		$GetRaceNames = Races::$DBConnection->prepare('SELECT race FROM races WHERE id IN (:idfirst, :idlast)');
		$GetRaceNames->bindParam(':idfirst', $NavigationIDs[0]);
		$GetRaceNames->bindParam(':idlast', $NavigationIDs[1]);
		$GetRaceNames->execute();
		$Result = $GetRaceNames->fetchAll(PDO::FETCH_ASSOC);

		if($Result[0]['race'] == 'blood-elf')
			$PreviousRace = "Race_Blood_Elf";
		else if($Result[0]['race'] == 'night-elf')
			$PreviousRace = "Race_Night_Elf";
		else
			$PreviousRace = 'Race_'.ucfirst($Result[0]['race']);

		if($Result[1]['race'] == 'blood-elf')
			$NextRace = "Race_Blood_Elf";
		else if($Result[1]['race'] == 'night-elf')
			$NextRace = "Race_Night_Elf";
		else
			$NextRace = 'Race_'.ucfirst($Result[1]['race']);


		return array('previousrace' => $Result[0]['race'], 'previousname' => Races::$TM->GetConfigVars($PreviousRace), 'nextrace' => $Result[1]['race'], 'nextname' => Races::$TM->GetConfigVars($NextRace));
	}
}

?>