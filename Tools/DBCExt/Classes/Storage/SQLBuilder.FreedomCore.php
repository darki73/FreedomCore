<?php

Class SQLBuilder{

    public $Patch, $Build, $Prefix, $WorkingWith = [], $MergeBy, $InsertAfter;
    /**
     * This query will be used before creating new database
     * @var
     */
    public $DropQuery;
    /**
     * This query will be executed to create initial database structure
     * @var
     */
    public $CreateQuery;
    /**
     * This query will be used to create insert string
     * @var
     */
    public $InsertQuery;
    /**
     * This First level of this array should contain new table name based on given DBC/DB2 Files
     * Second Level of this array should contain DBC/DB2 files and their table names
     * @var array
     */
    public $Tables = [];
    /**
     * This array is used to merge data from DBC/DB2 Files based on the keys given
     * @var array
     */
    public $Relations = [];
    /**
     * The First Level of this array should contain Storage Data (count of storages, amount of inserts, and their order)
     * The Second Level of this array should contain name of storages
     * The Third Level of this array should contain values
     * @var array
     */
    public $SQLStorage = [];

    private $TMPData = [];

    public function __construct($Patch, $Build, $TableName){
        $this->Patch = $Patch;
        $this->Build = $Build;
        $this->Tables = ['Table' => $TableName, 'Structure' => '', 'Format' => '', 'Data' => []];
        $this->SQLStorage = ['Count' => 0, 'Inserts' => 0, 'Storage' => []];
    }

    public function addDataSource($FileName){
        $Storage = new FileStorage($this->Patch, $this->Build);
        $TMPDataName = strtolower(str_replace('.dbc', '', str_replace('.db2', '', $FileName)));
        $Data = $Storage->getFileData('DBC', $FileName);
        $this->Prefix = $Data->Prefix;

        $ReaderName = strtoupper($Storage->FileData['fileExtension']).'Reader';
        $StorageReader = new $ReaderName($Storage->DBClientFiles, $Storage->FileData);


        $this->Tables['Data'][] = [
            'File'      => $FileName,
            'Table'     => $Data->Prefix.$Data->FileData['tableName'],
            'Format'    =>  $Data->FileData['formatString'],
            'Structure' =>  $Data->WorkingWith->Fields,
            'Data'      =>  $StorageReader->readFile()
        ];
        unset($Storage);
    }
    public function deleteDataSource($FileName, $TableName){
        $TablesArray = $this->Tables['Data'];

        foreach($TablesArray as $Key=>$Value){
            if($Value['File'] == $FileName && $Value['Table'] == $TableName)
                unset($this->Tables['Data'][$Key]);
        }
        $this->Tables['Data'] = array_values($this->Tables['Data']);
    }

    public function addRelation($MainTable, $SecondaryTable, $MainTableIndex, $SecondTableIndex, $InsertValuesAfter = null){
        $this->Relations[] = ['Master' => $MainTable, 'Slave' => $SecondaryTable, 'Where' => $SecondTableIndex, 'Equal' => $MainTableIndex, 'After' => $InsertValuesAfter];
    }
    public function deleteRelation(){

    }

    public function createStorage(){
        $Tables = $this->Tables['Data'];

        foreach($Tables as $Table)
            $this->WorkingWith[] = ['Name' => $Table['Table'], 'Key' => $this->extendedSearch($this->Tables['Data'], 'Table', $Table['Table'])];

        foreach($this->WorkingWith as $Table){
            $TKey = $Table['Key'];
            $TName = $Table['Name'];
            $Elements = $Tables[$TKey]['Data'];

            foreach($Elements as $Element){
                $ElementStructure = [];
                $TableColumns = $this->Tables['Data'][$TKey]['Structure'];
                foreach($Element as $Key=>$Value)
                    $ElementStructure[$TableColumns[$Key]] = $Value;
                $this->SQLStorage['Storage'][$TName][] = $ElementStructure;
            }
            unset($this->Tables['Data'][$TKey]['Data']);
        }
        return $this;
    }
    public function optimizeStorage(){
        $SQLStorages = $this->SQLStorage['Storage'];

        $TMPArray = [];
        $SlaveArray = [];
        $FinalTable = [];
        foreach($this->Relations as $Relation){
            $Master = $Relation['Master'];
            $Slave  = $Relation['Slave'];
            $Where  = $Relation['Where'];
            $Equal  = $Relation['Equal'];

            $MasterTable = $this->remapMaster($SQLStorages[$Master]);
            $SlaveTableStructure = $this->getSlaveTableStructure(str_replace($this->Prefix, '', $Slave));
            $ExampleArray = [];
            $NeedToAdd = -1;
            foreach($MasterTable as $MasterData){
                $SlaveKeys = $this->findAll($SQLStorages[$Slave], [$Where => $MasterData[$Equal]]);
                $SlaveKeysCount = count($SlaveKeys);
                $SlaveIndex = 1;
                foreach($SlaveKeys as $SlaveKey){
                    foreach($SlaveTableStructure as $Key=>$Value){
                        if($NeedToAdd == -1)
                            $NeedToAdd = $Value[1];
                        break;
                    }
                    $SlaveColumns = $SQLStorages[$Slave][$SlaveKey];
                    unset($SlaveColumns['id']);
                    unset($SlaveColumns[$Where]);

                    if(empty($ExampleArray))
                        $ExampleArray = $this->generateExampleArray($SlaveColumns);

                    $TMPArray[$MasterData[$Equal]][] = $SlaveColumns;
                    if($SlaveIndex == $SlaveKeysCount){
                        for($i = $SlaveKeysCount; $i < $NeedToAdd; $i++)
                            $TMPArray[$MasterData[$Equal]][$i] = $ExampleArray;
                    }
                    $SlaveIndex++;
                }
            }

            foreach($SlaveTableStructure as $Key=>$Value){
                foreach($TMPArray as $OKey=>$OValue){
                    $Index = 1;
                    foreach($OValue as $TKey=>$TValue){
                        foreach($TValue as $LKey=>$LValue){
                            if($Key == $LKey){
                                $TMPArray[$OKey][$TKey][$Value[0].$Index] = $LValue;
                                unset($TMPArray[$OKey][$TKey][$LKey]);
                            }
                        }
                        $Index++;
                    }
                }
            }

            foreach($TMPArray as $Key=>$Value){
                $SlaveArray[$Key] = [];
                foreach($Value as $Data)
                    $SlaveArray[$Key] = array_merge($SlaveArray[$Key], $Data);
                krsort($SlaveArray[$Key]);
                $Parts = array_filter(array_chunk($SlaveArray[$Key], $NeedToAdd, true));

                foreach($Parts as $Key=>$Value)
                    ksort($Parts[$Key]);
                $SlaveArray[$Key] = call_user_func_array('array_merge', $Parts);
            }

            foreach($MasterTable as $MKey=>$MValue){
                if(array_key_exists($MKey, $SlaveArray)){
                    $SlaveData = $SlaveArray[$MKey];
                    $MasterTable[$MKey] = $this->arrayInsert($MasterTable[$MKey], $SlaveData, $this->InsertAfter);
                }
            }
            $FinalTable = $MasterTable;
        }
        $this->SQLStorage['Storage'] = [];
        $SplitBy = 200;
        $Chunks = array_filter(array_chunk($FinalTable, $SplitBy));

        $FinalStructure = $this->Tables['Structure'];

        for($i = 0; $i < count($Chunks); $i++){
            $this->SQLStorage['Count'] = $this->SQLStorage['Count'] + 1;
            $this->SQLStorage['Inserts'] = $this->SQLStorage['Inserts'] + count($Chunks[$i]);
            $InternalIndex = 0;
            foreach($Chunks[$i] as $Chunk){
                $Statement = "";
                foreach($FinalStructure as $Key=>$Value){
                    if(isset($Chunk[$Value])){
                        if($Key == 0){
                            $Statement .= "('".addslashes($Chunk[$Value])."', ";
                        } elseif($Key == (count($FinalStructure) - 1)){
                            $Statement .= "'".addslashes($Chunk[$Value])."')";
                        } else {
                            $Statement .= "'".addslashes($Chunk[$Value])."', ";
                        }
                    } else {
                        //echo "Unable to process SetID: ".$Chunk['id'].' with name: '.$Chunk['name']."<br />";
                    }
                }

                if($InternalIndex == (count($Chunks[$i]) - 1))
                    $this->SQLStorage['Storage'][$this->Tables['Table'].$i][] = $Statement.";";
                else
                    $this->SQLStorage['Storage'][$this->Tables['Table'].$i][] = $Statement.',';
                $InternalIndex++;
            }
        }
    }

    public function populateDatabase(){
        Database::Query($this->DropQuery);
        Database::Query($this->CreateQuery);
        $Storages = $this->SQLStorage['Storage'];

        foreach($Storages as $Storage){
            $Query = $this->InsertQuery;
            foreach($Storage as $Value)
                $Query .= $Value;
            Database::Query($Query);
        }
    }


    public function generateNewStructure(){
        $ImportedIndexes = [];

        $MasterTableName = $this->Relations[0]['Master'];
        $MasterTable = $this->Tables['Data'][$this->extendedSearch($this->Tables['Data'], 'Table', $this->Relations[0]['Master'])];
        $MasterTableStructure = $MasterTable['Structure'];
        $TempStructure = $MasterTableStructure;
        $TempFormat = str_split(str_replace('x', '', $MasterTable['Format']));
        $this->TMPData['MasterFormat'] = $TempFormat;

        foreach($this->Relations as $Key=>$Value){
            if($MasterTableName == $Value['Master']){
                $ImportedIndexes[] = $Key;

                $SlaveData = $this->Tables['Data'][$this->extendedSearch($this->Tables['Data'], 'Table', $Value['Slave'])];
                $SlaveStructure = $SlaveData['Structure'];
                $this->TMPData['Slave'][$Value['Slave']] = str_split(str_replace('x', '', $SlaveData['Format']));

                $FormattedSlave = $this->unsetMergingValue($this->prepareSlaveArray($SlaveStructure, $Value['Slave']), $Value['Where'], $Value['Slave']);
                $ReformattedSlave = $this->regenerateSlaveStructure($FormattedSlave, $SlaveData['File']);

                $NewSlaveStructure = $ReformattedSlave['Structure'];
                $NewSlaveFormat = $ReformattedSlave['Format'];

                $InsertAtPosition = $this->simpleSearch($TempStructure, $Value['After']) + 1;
                $this->InsertAfter = $InsertAtPosition;

                array_splice($TempStructure, $InsertAtPosition, 0, $NewSlaveStructure);
                array_splice($TempFormat, $InsertAtPosition, 0, $NewSlaveFormat);
            }
        }

        $this->DropQuery = 'DROP TABLE IF EXISTS '.$this->Prefix.$this->Tables['Table'].';';
        $this->InsertQuery = 'INSERT INTO '.$this->Prefix.$this->Tables['Table']." (".implode(', ', $TempStructure).") VALUES ";
        $this->Tables['Structure'] = $TempStructure;
        $this->Tables['Format'] = implode('', $TempFormat);
    }

    public function generateCreationQuery(){
        $Query = "CREATE TABLE ".$this->Prefix.$this->Tables['Table']." (";

        $Index = 0;
        $PrimaryKey = "";

        foreach(str_split($this->Tables['Format']) as $Key => $Value){
            switch($Value){
                case 'f':
                    $Query .= '`'.$this->Tables['Structure'][$Index].'` FLOAT NOT NULL, ';
                    break;
                case 's':
                    $Query .= '`'.$this->Tables['Structure'][$Index].'` TEXT NOT NULL, ';
                    break;
                case 'i':
                case 'n':
                case 'b':
                case 'u':
                    $Query .= '`'.$this->Tables['Structure'][$Index].'` BIGINT(20) NOT NULL, ';
                    break;
                default:
                    continue 2;
            }
            if ($Value == 'n')
                $PrimaryKey = $this->Tables['Structure'][$Index];
            $Index++;
        }

        if($PrimaryKey)
            $Query .= 'PRIMARY KEY (`'.$PrimaryKey.'`) ';
        else
            $Query = substr($Query, 0, -2);

        $Query .=  ') COLLATE=\'utf8_general_ci\' ENGINE=InnoDB';
        $this->CreateQuery = $Query;
        $this->createStorage()->optimizeStorage();
    }

    private function remapMaster($MasterTable){
        $NewArray = [];
        foreach($MasterTable as $Key => $Value)
            $NewArray[$Value['id']] = $Value;
        return $NewArray;
    }

    private function getStorageNameByID($StorageName){
        $PossibleNames = [
            'One'       =>  1,
            'Two'       =>  2,
            'Three'     =>  3,
            'Four'      =>  4,
            'Five'      =>  5,
            'Six'       =>  6,
            'Seven'     =>  7,
            'Eight'     =>  8,
            'Nine'      =>  9,
            'Ten'       =>  10,
            'Eleven'    =>  11,
            'Twelve'    =>  12,
            'Thirteen'  =>  13,
            'Fourteen'  =>  14,
            'Fifteen'   =>  15,
            'Sixteen'   =>  16,
            'Seventeen' =>  17,
            'Eighteen'  =>  18,
            'Nineteen'  =>  19,
            'Twenty'    =>  20
        ];
        return $PossibleNames[$StorageName];
    }

    private function extendedSearch($array, $field, $value)
    {
        foreach($array as $key => $item)
        {
            if ( $item[$field] === $value )
                return $key;
        }
        return false;
    }

    private function simpleSearch($Array, $Item){
        foreach($Array as $Key => $Value)
            if(trim($Value) == trim($Item))
                return $Key;
        return false;
    }

    private static function findAll($array, $search)
    {
        $result = array();
        foreach ($array as $key => $value)
        {
            foreach ($search as $k => $v){
                if (!isset($value[$k]) || $value[$k] != $v)
                    continue 2;
            }
            $result[] = $key;
        }
        return $result;
    }

    private function prepareSlaveArray($Structure, $SlaveTable){
        unset($this->TMPData['Slave'][$SlaveTable][0]);
        unset($Structure[0]);
        $this->TMPData['Slave'][$SlaveTable] = array_values($this->TMPData['Slave'][$SlaveTable]);
        return array_values($Structure);
    }

    private function unsetMergingValue($Array, $Value, $SlaveTable){
        $KeyID = $this->simpleSearch($Array, $Value);
        $this->MergeBy = $KeyID + 1;
        unset($this->TMPData['Slave'][$SlaveTable][$KeyID]);
        $this->TMPData['Slave'][$SlaveTable] = array_values($this->TMPData['Slave'][$SlaveTable]);
        unset($Array[$KeyID]);
        $Array = array_values($Array);
        return $Array;
    }

    private function regenerateSlaveStructure($Array, $FileName){
        $TableName = strtolower(str_replace('.dbc', '', str_replace('.db2', '', $FileName)));
        $RegenerationData = $this->getSlaveTableStructure($TableName);

        $NewStructure = [];
        $NewFormat = [];

        foreach($Array as $Key=>$Value){
            if(array_key_exists($Value, $RegenerationData))
                $Regenerate = $RegenerationData[$Value];
            else
                $Regenerate = false;

            if($Regenerate != false){
                for($i = 1; $i <= $Regenerate[1]; $i++){
                    $NewStructure[] = $Regenerate[0].$i;
                    $NewFormat[] = $this->TMPData['Slave']['freedomcore_'.$TableName][$Key];
                }
                unset($Array[$Key]);
            }
        }
        $NewStructure = array_merge($NewStructure, $Array);
        foreach($Array as $Key=>$Value){
            $NewFormat[] = $this->TMPData['Slave']['freedomcore_'.$TableName][$Key];
        }
        return [
            'Structure' =>  $NewStructure,
            'Format'    =>  $NewFormat
        ];
    }

    private function getSlaveTableStructure($TableName){
        $Tables = [
            'itemsetspell'  => [
                'spell_id'  =>  ['spell', 8],
                'threshold' =>  ['bonus', 8]
            ]
        ];
        return $Tables[$TableName];
    }

    private function generateExampleArray($Array){
        $NewArray = [];
        foreach($Array as $Key => $Value)
            $NewArray[$Key] = 0;
        return $NewArray;
    }

    private function arrayInsert($Array, $Values, $Offset) {
        return array_slice($Array, 0, $Offset, true) + $Values + array_slice($Array, $Offset, NULL, true);
    }
}

?>