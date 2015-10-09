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
}

?>