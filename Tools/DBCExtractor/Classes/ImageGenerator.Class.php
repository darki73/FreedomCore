<?php
require_once('Classes/DBC2Array.Class.php');
require_once('Classes/BLP2Image.Class.php');
Class ImageGenerator
{
	private static $DataDir;
	private static $DBCDirectory;
	private static $Locale;
	private static $ImagesDir;
	private static $DirArray;
    private static $Patch;

	public function __construct($Data, $DBCDir, $Loc, $IDir, $Patch)
	{
		ImageGenerator::$DataDir = $Data;
		ImageGenerator::$DBCDirectory = $DBCDir;
		ImageGenerator::$Locale = $Loc;
		ImageGenerator::$ImagesDir = $IDir;
        ImageGenerator::$Patch = $Patch;
		ImageGenerator::UpdateFolderStructure();
	}

	private static function UpdateFolderStructure()
	{
		$LargeDir  = ImageGenerator::$ImagesDir . "large/";
		$MediumDir = ImageGenerator::$ImagesDir . "medium/";
		$SmallDir  = ImageGenerator::$ImagesDir . "small/";
		$TinyDir   = ImageGenerator::$ImagesDir . "tiny/";
		ImageGenerator::$DirArray = array('large' => $LargeDir, 'medium' => $MediumDir, 'small' => $SmallDir, 'tiny' => $TinyDir);

		@mkdir(ImageGenerator::$ImagesDir);
		@mkdir($LargeDir);
		@mkdir($MediumDir);
		@mkdir($SmallDir);
		@mkdir($TinyDir);
	}

	private static function Unpack($Filename, $Format)
	{
		if (@stat(ImageGenerator::$DBCDirectory . $Filename) == NULL) 
			$Filename = strtolower($Filename);
    	return DBC2Array::Convert(ImageGenerator::$DBCDirectory . $Filename, $Format);
	}

	private static function Status($Message)
	{
		echo $Message;
	    @ob_flush();
	    flush();
	    @ob_end_flush();
	}

	private static function ReSave($OutFileName, $Image, $Width, $Height)
	{
		$NewImage = imagecreatetruecolor($Width, $Height);
		imagecopyresampled($NewImage, $Image, 0,0, 0,0, $Width,$Height, imagesx($Image),imagesy($Image));
		if (substr($OutFileName, -4) == ".jpg")
			imagejpeg($NewImage, $OutFileName);
		else if (substr($OutFileName, -4) == ".gif")
			imagegif($NewImage, $OutFileName);
		else die("Unsupported file fromat: " . substr($OutFileName, -4));
		imagedestroy($NewImage);
	}

	public static function Extract($Filename, $Format)
	{
		ImageGenerator::Status("Reading icons list from $Filename...\n");
		ImageGenerator::Status("Looking for icons in ".ImageGenerator::$DataDir."...\n");
		$UnpackedDBC = ImageGenerator::Unpack($Filename, $Format);
		$Count = count($UnpackedDBC);
		ImageGenerator::Status($Count." icons found\n");
		$CurrentIcon = 0;
		$Status = array();
		$LastFile = array();

		foreach($UnpackedDBC as $Row)
		{
			$SourceFileName = strtolower(str_replace("\\", "/", $Row[1]));
			if (strpos($SourceFileName, "/") === FALSE)
				$SourceFileName = ImageGenerator::$Patch."/interface/icons/" . $SourceFileName;
			$Source = ImageGenerator::$DataDir . $SourceFileName . ".blp";
			$SourceStat = @stat($Source);
			if ($Row[1] == "")
				echo " ";
			else if ($SourceStat == NULL || $SourceStat['size'] == 0)
			{
				$Message = "Not Found";
				$Status[$Message] = isset($Status[$Message]) ? $Status[$Message]+1 : 1;
				$LastFile[$Message][] = $Source;
				echo "-";
			}
			else
			{
				$DestFileName = strtolower(substr(strrchr($SourceFileName,"/"),1));
				$DestStat = @stat(ImageGenerator::$DirArray['large'] . $DestFileName . ".jpg");
				if($DestStat != NULL && $DestStat['mtime'] >= $SourceStat['mtime'])
				{
					$Message = "Already up-to-date";
					$Status[$Message] = isset($Status[$Message]) ? $Status[$Message]+1 : 1;
					$LastFile[$Message][0] = "...";
					$LastFile[$Message][1] = $Source;
					echo ".";
				}
				else
				{
					$Image = BLP2Image::Create($Source);
					ImageGenerator::ReSave(ImageGenerator::$DirArray['large'] . $DestFileName . ".jpg", $Image, 56, 56);
					ImageGenerator::ReSave(ImageGenerator::$DirArray['medium'] . $DestFileName . ".jpg", $Image, 36, 36);
					ImageGenerator::ReSave(ImageGenerator::$DirArray['small'] . $DestFileName . ".jpg", $Image, 18, 18);
					ImageGenerator::ReSave(ImageGenerator::$DirArray['tiny'] . $DestFileName . ".gif", $Image, 15, 15);
					echo "+";
				}
			}

			$CurrentIcon++;
			if($CurrentIcon % 60 == 0)
				ImageGenerator::Status(" " . $CurrentIcon . "/" . $Count . " (" . round(100*$CurrentIcon/$Count) . "%)\n");
		}

		if ($CurrentIcon % 60 != 0)
			ImageGenerator::Status(" " . $CurrentIcon . "/" . $Count . " (100%)\n");

		echo "Done\n";
		if(count($Status) > 0)
		{
			echo "Status:\n";
			foreach($Status as $S => $Row)
			{
				echo "  " . $S . ": " . $Row . "\n";
			}
		}



		echo "\n\n\n";
	}
}

?>