<?php

Class Updater {

    public static $DBConnection;
    public static $CConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Updater::$DBConnection = $VariablesArray[0]::$Connection;
        Updater::$CConnection = $VariablesArray[0]::$CConnection;
        Updater::$WConnection = $VariablesArray[0]::$WConnection;
        Updater::$TM = $VariablesArray[1];
    }

    public static function GetUpdateData()
    {
        $DBData = Updater::GetDatabaseVersion();

        $UpdateURL = "https://project.freedomcore.ru/freedomcore.php?action=%s&current_hash=%s";
        $UpdateData = Manager::GetUrlData(sprintf($UpdateURL, 'update', $DBData['database_version']));
        $Data = json_decode($UpdateData, true);
        $Data['downloaded'] = Updater::CheckForPull($Data['updating_to']['sha']);
        return $Data;
    }

    public static function GetDatabaseVersion()
    {
        $Statement = Updater::$DBConnection->prepare('SELECT * FROM db_version');
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function GenerateUpdateList()
    {
        $UpdatesFolder = getcwd().DS.'Update'.DS.'sql'.DS;
        $UpdateFiles = File::GetDirectoryContent($UpdatesFolder, 'sql');
        $UpdateData = Updater::GetUpdateData();

        $UpdatesList = [];

        foreach($UpdateFiles as $Update){
            $ExplodedUpdate = explode('_', $Update['FileName']);
            $Version = $ExplodedUpdate[0];
            $Part = str_replace('.sql', '', $ExplodedUpdate[1]);

            if($UpdateData['updating_from']['id'] < $Version)
                $UpdatesList[] = $Update;
        }
        sort($UpdatesList);
        $UpdatesList = array_map("unserialize", array_unique(array_map("serialize", $UpdatesList)));
        return $UpdatesList;
    }

    public static function PushUpdates()
    {
        $Updates = Updater::GenerateUpdateList();
        $UpdateData = Updater::GetUpdateData();

        if(empty($Updates))
            Updater::UpdateDatabaseVersion($UpdateData['updating_to']['sha'], $UpdateData['updating_to']['date']);
        else {
            $Iteration = 0;
            $Length = count($Updates);
            foreach($Updates as $Update){
                if($Iteration == $Length - 1)
                    Updater::ApplyUpdate($Update['FileLink'], true, $UpdateData['updating_to']['sha'], $UpdateData['updating_to']['date']);
                else
                    Updater::ApplyUpdate($Update['FileLink'], false, $UpdateData['updating_to']['sha'], $UpdateData['updating_to']['date']);
                $Iteration++;
            }
        }
    }

    public static function CheckForPull($UpdateTo)
    {
        $GitHead = getcwd().DS.'.git'.DS.'FETCH_HEAD';
        if(file_exists($GitHead))
        {
            $LocalVersion = file_get_contents(getcwd().DS.'.git'.DS.'FETCH_HEAD');
            list($LocalVersion, $ServiceInfo) = explode('branch', $LocalVersion);
        } else {
            $LocalVersion = "UNKNOWN";
        }
        if(trim($LocalVersion) == trim($UpdateTo))
            return true;
        else
            return false;
    }

    public static function ApplyUpdate($File, $Last, $Hash, $Date){

        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = Updater::$DBConnection->prepare($Line);
            $Statement->execute();
        }

        if($Last == true)
            Updater::UpdateDatabaseVersion($Hash, $Date);
    }

    private static function UpdateDatabaseVersion($NewVersion, $UpdateDate){
        $DateTime = new DateTime(date("Y-m-d H:i:s"));
        $Date = $DateTime->format(DateTime::ISO8601);
        $VersionID = '1';

        $Statement = Updater::$DBConnection->prepare('UPDATE db_version SET database_version = :hash, update_date = :update_date, install_date = :current_date WHERE id= :version_id');
        $Statement->bindParam(':hash', $NewVersion);
        $Statement->bindParam(':update_date', $UpdateDate);
        $Statement->bindParam(':current_date', $Date);
        $Statement->bindParam(':version_id', $VersionID);
        $Statement->execute();
    }
}
