<?php

Class DB2Reader {

    public $isReadable = false;
    public $FileName;
    public $FileSize;
    public $HeaderSize;
    public $HeaderStructure;
    public $MagicBytes;
    public $FileFormat;
    public $FormatString;
    public $FileExtension;
    public $FilePath;
    public $FileHandler;

    private $UnpackFormat = array('x' => 'x/x/x/x', 'X' => 'x', 's' => 'V', 'f' => 'f', 'i' => 'V', 'u' => 'V', 'b' => 'C', 'd' => 'x4', 'n' => 'V');

    public function __construct($DB2Path, $FileData){
        $this->FileName = $FileData['fileName'];
        $this->FileSize = $FileData['fileSize'];
        $this->HeaderSize = $FileData['headerSize'];
        $this->MagicBytes = $FileData['magic'];
        $this->FileFormat = $FileData['dec'];
        $this->FileExtension = $FileData['fileExtension'];
        $this->FormatString = $FileData['formatString'];
        $this->FilePath = $DB2Path.$this->FileName;
        $this->FileHandler = fopen($this->FilePath, "rb");

        $this->isReadable = true;
    }

    public function readFile(){
        $this->VerifyFileSize();
        $this->getFileHeader();
        $this->VerifyMagicBytes();
        $this->VerifyFileStructure();

        $UnpackString = $this->unpackString();
        $Data = fread($this->FileHandler, $this->HeaderStructure["recordCount"] * $this->HeaderStructure["recordSize"]);
        $Strings = fread($this->FileHandler, $this->HeaderStructure["stringSize"]);

        $DB2Array = [];
        $RecordsCount = $this->HeaderStructure['recordCount'];
        $RecordSize = $this->HeaderStructure['recordSize'];

        for($i = 0; $i < $RecordsCount; $i++) {
            $Index = $i;
            $DB2Array[$Index] = [];
            $Record = unpack($UnpackString, substr($Data, $i*$RecordSize, $RecordSize));
            for($j = 0; $j < strlen($this->FormatString); $j++) {
                if (!isset($Record['f'.$j]))
                    continue;
                $Value = '';
                switch ($this->FormatString[$j]) {
                    case 's':
                        $Value = substr($Strings, $Record['f'.$j]);
                        $Value = substr($Value, 0, strpos($Value, "\000"));
                        break;
                    case 'i':
                        if ($Record['f'.$j] & 0x80000000)
                            $Value = $Record['f'.$j] - 0x100000000;
                        else
                            $Value = $Record['f'.$j];
                        break;
                    case 'f':
                        $Value = round($Record['f'.$j], 8);
                        break;
                    case 'n':
                        $Value = $Record['f'.$j];
                        break;
                }

                array_push($DB2Array[$i], $Value);
            }
        }

        return $DB2Array;
    }

    private function getFileHeader(){
        $this->HeaderStructure = unpack('VmagicBytes/VrecordCount/VfieldCount/VrecordSize/VstringSize/VstringTableSize/VbuildNumber/VlastChanged/VminID/VmaxID/Vlocale/VunknownValue', fread($this->FileHandler, 48));
    }

    private function VerifyFileSize(){
        if($this->FileSize < 20)
            die("File " . $this->FileName . " is too small for a DB2 file\n");
    }

    private function VerifyMagicBytes(){
        if($this->HeaderStructure['magicBytes'] != $this->FileFormat)
            die("File " . $this->FileName . " has incorrect magic bytes\n");
    }

    private function VerifyFileStructure(){
        $InfoString =
            '(recordCount='.$this->HeaderStructure['recordCount'].
            ' fieldCount=' .$this->HeaderStructure['fieldCount'] .
            ' recordSize=' .$this->HeaderStructure['recordSize'] .
            ' stringSize=' .$this->HeaderStructure['stringSize'] .')';

        if ($this->HeaderStructure["recordCount"] * $this->HeaderStructure["recordSize"] + $this->HeaderStructure["stringSize"] + $this->HeaderSize != $this->FileSize)
            die("File " . $this->FileName . " has incorrect size" . $InfoString);
        if ($this->HeaderStructure["fieldCount"] != strlen($this->FormatString))
            die("Incorrect format string specified for file " . $this->FileName . $InfoString);
    }

    private function VerifyRecordSize($RecordSize){
        if ($RecordSize != $this->HeaderStructure["recordSize"])
            die("Format string size (".$RecordSize.") for file " . $this->FileName . " does not match actual size (".$this->HeaderStructure["recordSize"].")");
    }

    private function unpackString(){
        $UnpackedString = '';
        $RecordSize = 0;
        for($i = 0; $i < strlen($this->FormatString); $i++) {
            $Character = $this->FormatString[$i];
            if ($Character == 'X' || $Character == 'b') $RecordSize += 1; else $RecordSize += 4;
            if (!isset($this->UnpackFormat[$Character])) die("Unknown format parameter '" . $Character . "' in format string\n");
            $UnpackedString = $UnpackedString . "/" . $this->UnpackFormat[$Character];
            if ($Character != 'X' && $Character != 'x') $UnpackedString = $UnpackedString .'f'.$i;
        }
        $this->VerifyRecordSize($RecordSize);
        $UnpackedString = substr($UnpackedString, 1);
        while (preg_match("/(x\\/)+x/", $UnpackedString, $Read))
            $UnpackedString = substr_replace($UnpackedString, 'x'.((strlen($Read[0])+1)/2), strpos($UnpackedString, $Read[0]), strlen($Read[0]));
        return $UnpackedString;
    }
}

?>