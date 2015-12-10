<?php

Class FileStorage {

    public $ContentFolder;
    public $DBClientFiles;
    public $DBCFilesCount;
    public $DBClientIcons;
    public $DBCIconsCount;
    public $GamePatch;
    public $GameBuild;

    public $CanExtractDBC = 0;
    public $CanExtractIcons = 0;

    public $Prefix;
    public $FileData;
    public $WorkingWith;

    private $Reader;

    public function __construct($PatchNumber, $BuildNumber){
        global $FCExtractor;

        $this->Prefix = $FCExtractor['Database']['prefix'];
        $this->GamePatch = $PatchNumber;
        $this->GameBuild = $BuildNumber;
        $this->ContentFolder = getcwd().DS.$FCExtractor['Storage']['DataFiles'].DS.$PatchNumber.DS;
        $this->DBClientFiles = $this->ContentFolder.$FCExtractor['Storage']['DBClient'].DS.$FCExtractor['Locale'].DS;
        $this->DBClientIcons = $this->ContentFolder.$FCExtractor['Storage']['Icons'].DS.$FCExtractor['Locale'].DS;
        $this->DBCFilesCount = $this->getFilesCount($this->DBClientFiles);
        $this->DBCIconsCount = $this->getFilesCount($this->DBClientIcons);
        if($this->DBCFilesCount != 0)
            $this->CanExtractDBC = 1;
        if($this->DBCIconsCount != 0)
            $this->CanExtractIcons = 1;
    }

    public function getFileData($Folder, $FileName){
        $FilePath = $this->getFilePath($Folder, $FileName);
        if(!file_exists($FilePath))
            die("<strong>Critical Error:</strong><br />Unable to find file <strong>".$FileName."</strong>");

        $GenericData = [
            'tableName'         =>  strtok(strtolower(substr($FilePath, strrpos($FilePath, DS) + 1)), '.'),
            'fileName'          =>  substr($FilePath, strrpos($FilePath, DS) + 1),
            'fileExtension'     =>  pathinfo($FilePath, PATHINFO_EXTENSION),
            'fileSize'          =>  filesize($FilePath)
        ];
        $this->WorkingWith = new FileStructure($FileName, $this->GameBuild);
        if($this->WorkingWith->Fields == NULL)
            return false;
        $this->FileData = array_merge($GenericData, $this->WorkingWith->getFileFormatString(pathinfo($FilePath, PATHINFO_EXTENSION)));
        return $this;
    }

    private function getFilePath($Folder, $FileName){
        if(strtolower($Folder) == 'dbc')
            return $this->DBClientFiles.$FileName;
        elseif(strtolower($Folder) == 'icons')
            return $this->DBClientIcons.$FileName;
        else
            return false;
    }

    public function getFilesCount($Directory){
        $FileIterator = new FilesystemIterator($Directory, FilesystemIterator::SKIP_DOTS);
        return iterator_count($FileIterator);
    }

    public function CreateDatabaseStructure(){
        $Query = "CREATE TABLE ".$this->Prefix.$this->FileData['tableName']." (";

        $Index = 0;
        $PrimaryKey = "";

        foreach(str_split($this->WorkingWith->Format) as $Key => $Value){
            switch($Value){
                case 'f':
                    $Query .= '`'.$this->WorkingWith->Fields[$Index].'` FLOAT NOT NULL, ';
                    break;
                case 's':
                    $Query .= '`'.$this->WorkingWith->Fields[$Index].'` TEXT NOT NULL, ';
                    break;
                case 'i':
                case 'n':
                case 'b':
                case 'u':
                    $Query .= '`'.$this->WorkingWith->Fields[$Index].'` BIGINT(20) NOT NULL, ';
                    break;
                default:
                    continue 2;
            }
            if ($Value == 'n')
                $PrimaryKey = $this->WorkingWith->Fields[$Index];
            $Index++;
        }

        if($PrimaryKey)
            $Query .= 'PRIMARY KEY (`'.$PrimaryKey.'`) ';
        else
            $Query = substr($Query, 0, -2);

        $Query .=  ') COLLATE=\'utf8_general_ci\' ENGINE=InnoDB';

        $ReaderName = strtoupper($this->FileData['fileExtension']).'Reader';
        $this->Reader = new $ReaderName($this->DBClientFiles, $this->FileData);

        if(!$this->Reader->isReadable)
            die("This Reader is not yet ready!");

        $DBCArray = $this->Reader->readFile();
        $NewArray = [];
        $Index = 0;
        foreach($DBCArray as $Data){
            foreach($Data as $Key=>$Value){
                $NewArray[$Index][$this->WorkingWith->Fields[$Key]] = $Value;
            }
            $Index++;
        }
        echo "<pre>";
        print_r($NewArray);
        echo "</pre>";

        //Database::Query("DROP TABLE IF EXISTS ".$this->Prefix.$this->FileData['tableName']);
        //Database::Query($Query);

        return $this;
    }

    public function PopulateDatabase(){
        $DataArray = $this->Reader->readFile();
        $StringQuery = "INSERT INTO ".$this->Prefix.$this->FileData['tableName']." ";
        $Fields = $this->WorkingWith->Fields;
        for($i = 0; $i < count($Fields); $i++){
            if(count($Fields) == 1){
                $StringQuery .= ' ('.$Fields[$i].') VALUES ';
            } else {
                if($i == (count($Fields) - 1))
                    $StringQuery .= ' '.$Fields[$i].') VALUES ';
                elseif ($i == 0)
                    $StringQuery .= '('.$Fields[$i].',';
                else
                    $StringQuery .= ' '.$Fields[$i].',';
            }
        }
        $Index = 0;
        foreach($DataArray as $DBEntry){
            for($i = 0; $i < count($DBEntry); $i++){
                if(count($DBEntry) == 1){
                    if($Index  == (count($DataArray) - 1))
                        $StringQuery .= " ('".addslashes($DBEntry[$i])."'); ";
                    else
                        $StringQuery .= " ('".addslashes($DBEntry[$i])."'),";
                } else {
                    if($i == (count($DBEntry) - 1))
                        if($Index  == (count($DataArray) - 1))
                            $StringQuery .= " '".addslashes($DBEntry[$i])."'); ";
                        else
                            $StringQuery .= " '".addslashes($DBEntry[$i])."'),";
                    elseif ($i == 0)
                        $StringQuery .= "\n ('".addslashes($DBEntry[$i])."',";
                    else
                        $StringQuery .= " '".addslashes($DBEntry[$i])."',";
                }
            }
            $Index++;
        }
        $PopulationStatus = Database::Query($StringQuery);
        if($PopulationStatus != true){
            $ErrorMessage = "<strong>Error occurred:</strong> ".$PopulationStatus."<br /><strong>Error Statement:</strong> ".$StringQuery;
            die($ErrorMessage);
        }
    }
}

?>