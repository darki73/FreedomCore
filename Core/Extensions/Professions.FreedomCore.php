<?php

Class Professions
{
    private static $DBConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Professions::$DBConnection = $VariablesArray[0]::$Connection;
        Professions::$TM = $VariablesArray[1];
    }

    public static function GetProfessionsList()
    {
        $Statement = Professions::$DBConnection->prepare('SELECT * FROM professions');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach ($Result as $Profession)
        {
            $Result[$Index]['profession_translation'] = Professions::$TM->GetConfigVars($Profession['profession_translation']);
            $Result[$Index]['profession_description'] = Professions::$TM->GetConfigVars($Profession['profession_description']);
            $Index++;
        }
        return $Result;
    }

    public static function GetProfession($ProfessionName)
    {
        $Statement = Classes::$DBConnection->prepare('SELECT * FROM professions WHERE profession_name = :profession');
        $Statement->bindParam(':profession', $ProfessionName);
        $Statement->execute();
        $Profession = $Statement->fetch(PDO::FETCH_ASSOC);
        $Profession['profession_translation'] = Professions::$TM->GetConfigVars($Profession['profession_translation']);
        $Profession['profession_description'] = Professions::$TM->GetConfigVars($Profession['profession_description']);
        $Profession['profession_long_description'] = Professions::$TM->GetConfigVars($Profession['profession_long_description']);
        return $Profession;
    }

    public static function GetNavigation($ProfessionID)
    {

    }

}

?>