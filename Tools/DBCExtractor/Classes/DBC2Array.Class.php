<?php

Class DBC2Array	
{
	public static function Convert($Filename, $Format)
	{
		$File = fopen($Filename, "rb") or die("Cannot open file " . $Filename . "\n");
		$Filesize = filesize($Filename);

		DBC2Array::VerifyFileSize($Filename);
		DBC2Array::VerifyMagicBytes($File, $Filename);

		$Header = unpack("VrecordCount/VfieldCount/VrecordSize/VstringSize", fread($File, 16));
		DBC2Array::VerifyFile($Header, $Filename, $Format);

		$Unpack_Fromat = array('x'=>"x/x/x/x", 'X'=>"x", 's'=>"V", 'f'=>"f", 'i'=>"V", 'b'=>"C", 'd'=>"x4", 'n'=>"V");
		$Unpacked_String = "";

		$RecordSize = 0;
		for($i = 0; $i < strlen($Format); $i++)
		{
			$Character = $Format[$i];
			if ($Character == 'X' || $Character == 'b') $RecordSize += 1; else $RecordSize += 4;
			if (!isset($Unpack_Fromat[$Character])) die("Unknown format parameter '" . $Character . "' in format string\n");
			$Unpacked_String = $Unpacked_String . "/" . $Unpack_Fromat[$Character];
			if ($Character != 'X' && $Character != 'x') $Unpacked_String = $Unpacked_String .'f'.$i;
		}
		$Unpacked_String = substr($Unpacked_String, 1);

		while (preg_match("/(x\\/)+x/", $Unpacked_String, $Read))
      		$Unpacked_String = substr_replace($Unpacked_String, 'x'.((strlen($Read[0])+1)/2), strpos($Unpacked_String, $Read[0]), strlen($Read[0]));
		
		if ($RecordSize != $Header["recordSize"])
      		die("Format string size (".$RecordSize.") for file " . $Filename . " does not match actual size (".$Header["recordSize"].")");
	
      	$Data = fread($File, $Header["recordCount"] * $Header["recordSize"]);
      	$Strings = fread($File, $Header["stringSize"]);
      	fclose($File);

      	$FinalDBCArray = array();
      	$Cache = array();
      	$RecordsCount = $Header["recordCount"];
    	$RecordsSize = $Header["recordSize"];
    	$FormatCount = strlen($Format);
    	for($i = 0; $i < $RecordsCount; $i++)
    	{
    		$FinalDBCArray[$i] = array();
    		$Record = unpack($Unpacked_String, substr($Data, $i*$RecordsSize, $RecordsSize));
    		for($j = 0; $j < $FormatCount; $j++)
    		{
    			if(isset($Record['f'.$j]))
    			{
    				$Value = $Record['f'.$j];
    				if($Format[$j] == 's')
    				{
    					if(isset($Cache[$Value]))
    						$Value = $Cache[$Value];
    					else
    					{
    						$String = substr($Strings, $Value);
    						$String = substr($String, 0, strpos($String, "\000"));
    						$Cache[$Value] = $String;
    						$Value = $String;
    					}
    				}
    				else if($Format[$j] == 'f')
    					$Value = round($Value, 2);
    				array_push($FinalDBCArray[$i], $Value);
    			}
    		}
    	}
    	return $FinalDBCArray;
	}

	private static function VerifyFileSize($Filename)
	{
		if(filesize($Filename) < 20)
			die("File " . $Filename . " is too small for a DBC file\n");
	}

	private static function VerifyMagicBytes($File, $Filename)
	{
		if(fread($File, 4) != "WDBC")
			die("File " . $Filename . " has incorrect magic bytes\n");
	}

	private static function VerifyFile($Header, $Filename, $Format)
	{
		$InfoString = 
				"\n(recordCount=" . $Header["recordCount"] . " " .
                "fieldCount=" . $Header["fieldCount"] . " " .
                "recordSize=" . $Header["recordSize"] . " " .
                "stringSize=" . $Header["stringSize"] . ")\n";
		if ($Header["recordCount"] * $Header["recordSize"] + $Header["stringSize"] + 20 != filesize($Filename))
      		die("File " . $Filename . " has incorrect size" . $InfoString);
    	if ($Header["fieldCount"] != strlen($Format))
      		die("Incorrect format string specified for file " . $Filename . $InfoString);
	}
}

?>