<?php
require_once('header.php');
switch($_REQUEST['action']){
    case 'try_database':
        $Statement = Database::$AConnection->prepare('SELECT id FROM realmlist');
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(Database::IsEmpty($Statement))
            echo 0;
        else
            echo 1;
    break;

    case 'import_database':
        $ImportFolder = getcwd().DS.'Install'.DS.'sql'.DS.'imported'.DS;
        $SQLFolder = getcwd().DS.'Install'.DS.'sql'.DS;
        global $FCCore;

        $NewFiles = File::GetDirectoryContent($SQLFolder, 'sql');
        $OldFiles = File::GetDirectoryContent($ImportFolder, 'sql');

        $ArrayIndex = 0;
        foreach($NewFiles as $File){
            if(strstr($File['FileLink'], $ImportFolder))
                unset($NewFiles[$ArrayIndex]);
            if(Installer::Import($FCCore['Database']['host'], $FCCore['Database']['username'], $FCCore['Database']['password'], $FCCore['Database']['database'], $FCCore['Database']['encoding'], $File['FileLink']))
                rename($File['FileLink'], $ImportFolder.$File['FileName']);
            $ArrayIndex++;
        }

        echo "1";
    break;

    case 'import_status':

        $ImportFolder = getcwd().DS.'Install'.DS.'sql'.DS.'imported'.DS;
        $SQLFolder = getcwd().DS.'Install'.DS.'sql'.DS;

        $NewFiles = File::GetDirectoryContent($SQLFolder, 'sql');
        $OldFiles = File::GetDirectoryContent($ImportFolder, 'sql');

        $ArrayIndex = 0;
        foreach($NewFiles as $File) {
            if (strstr($File['FileLink'], $ImportFolder))
                unset($NewFiles[$ArrayIndex]);
            $ArrayIndex++;
        }

        $CountIF = count($NewFiles);
        $CountOF = count($OldFiles);

        $Total = $CountIF + $CountOF;

        $DataArray = [
            'Total'     =>  $Total,
            'New'       =>  $CountIF,
            'Old'       =>  $CountOF,
        ];

        echo json_encode($DataArray);
    break;
}

?>