<?php

Class Patches
{
	public static $DBConnection;
	public static $TM;

	public function __construct($VariablesArray)
	{
		Patches::$DBConnection = $VariablesArray[0]::$Connection;
		Patches::$TM = $VariablesArray[1];
	}

	public static function GetMenu()
	{
		$Statement = Patches::$DBConnection->prepare('SELECT * FROM patch_notes ORDER BY id DESC');
		$Statement->execute();
		$Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
		for($i = 0; $i < count($Result); $i++)
			$Result[$i]['patch_name'] = $Result[$i]['patch_name_'.Utilities::GetLanguage(true)];
		return $Result;
	}
}

?>