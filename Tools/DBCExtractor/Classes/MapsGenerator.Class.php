<?php
require_once('Classes/DBC2Array.Class.php');
require_once('Classes/BLP2Image.Class.php');
Class MapsGenerator
{
	private static $DataDir;
	private static $DBCDirectory;
	private static $Locale;
	private static $ImagesDir;
	private static $DirArray;
	private static $BLPMapWidth = 1002;
	private static $BLPMapHeight = 668;

	public function __construct($Data, $DBCDir, $Loc)
	{
		MapsGenerator::$DataDir = $Data;
		MapsGenerator::$DBCDirectory = $DBCDir;
		MapsGenerator::$Locale = $Loc;
		MapsGenerator::UpdateFolderStructure();
	}

	private static function UpdateFolderStructure()
	{
		$WorldMapDir = MapsGenerator::$DataDir . "interface/worldmap/";
  		$NormalDir = MapsGenerator::$ImagesDir . "enus/normal/";
  		$ZoomDir   = MapsGenerator::$ImagesDir . "enus/zoom/";

  		MapsGenerator::$DirArray = array('WorldMap' => $WorldMapDir, 'NormalMap' => $NormalDir, 'ZoomMap' => $ZoomDir);

  		@mkdir($NormalDir, 0777, true);
  		@mkdir($ZoomDir, 0777, true);
	}

	private static function Unpack($Filename, $Format)
	{
		if (@stat(MapsGenerator::$DBCDirectory . $Filename) == NULL) 
			$Filename = strtolower($Filename);
    	return DBC2Array::Convert(MapsGenerator::$DBCDirectory . $Filename, $Format);
	}

	private static function Status($Message)
	{
		echo $Message;
	    @ob_flush();
	    flush();
	    @ob_end_flush();
	}

	public static function GenerateDungeons()
	{
		
	}

	public static function GenerateWorld()
	{
		MapsGenerator::Status("Reading subzones list...\n");
		$UnpackedDBC = MapsGenerator::Unpack("WorldMapOverlay.dbc", "nixxxxxxsiiiixxxx");
		$WMO = array();
		foreach ($UnpackedDBC as $Row)
		{
			if($Row[2])
				$WMO[$Row[1]][] = array (
					"name"   => strtolower($Row[2]),
					"width"  => $Row[3],
					"height" => $Row[4],
					"left"   => $Row[5],
					"top"    => $Row[6]
				);
		}
		MapsGenerator::Status(count($UnpackedDBC) . "\n");
		MapsGenerator::Status("Reading zones list...\n");

		$UnpackedDBC = MapsGenerator::Unpack("WorldMapArea.dbc", "nxisxxxxxxx");
		MapsGenerator::Status(count($UnpackedDBC) . "\n");

		$Count = 0;
		foreach ($UnpackedDBC as $Row)
		{
			$Count++;
			if ($Row[1])
			{
				$zid = $Row[0];
				$MapID = $Row[1];
				$MapName = $Row[2];
				MapsGenerator::Status($MapName . "[" . $MapID . "]");
				$MapName = strtolower($MapName);

				$Map = imagecreatetruecolor(1024, 768);

				$MapFG = imagecreatetruecolor(1024, 768);
				imagesavealpha($MapFG, true);
				imagealphablending($MapFG, false);
				$bgcolor = imagecolorallocatealpha($MapFG, 0, 0, 0, 127);
				imagefilledrectangle($MapFG, 0, 0, 1023, 767, $bgcolor);
				imagecolordeallocate($MapFG, $bgcolor);
				imagealphablending($MapFG, true);
				echo ".";

				$prefix = MapsGenerator::$DirArray['WorldMap'] . $MapName . "/" . $MapName;
				if (@stat($prefix . "1.blp") == NULL)
					$prefix = $prefix . "1_";
				if (@stat($prefix . "1.blp") == NULL)
				{
					MapsGenerator::Status(" not found.\n");
					continue;
				}
				for ($i = 0; $i < 12; $i++)
				{
					$Image = BLP2Image::Create($prefix . ($i+1) . ".blp");
					imagecopyresampled($Map, $Image, 256*($i%4), 256*intval($i/4), 0, 0, 256, 256, imagesx($Image), imagesy($Image));
					imagedestroy($Image);
				}
				echo ".";

				if (isset($wmo[$zid]))
				{
					foreach ($wmo[$zid] as $Row)
					{
						$i = 1; $y = 0;
						while($y < $Row["height"])
						{
							$x = 0;
							while($x < $Row["width"])
							{
								$Image = BLP2Image::Create(MapsGenerator::$DirArray['WorldMap'] . $MapName . "/" . $Row["name"] . $i . ".blp");
								imagecopy($MapFG, $Image, $Row["left"]+$x, $Row["top"]+$y, 0, 0, imagesx($Image), imagesy($Image));
								imagedestroy($Image);
								$x += 256;
								$i++;
							}
							$y += 256;
						}
					}
					echo ".";

					if (isset($outtmpdir))
					{
						$tmp = imagecreate(1000,1000);
						$cbg = imagecolorallocate($tmp, 255,255,255);
						$cfg = imagecolorallocate($tmp, 0,0,0);
						for ($y = 0; $y < 1000; $y++)
							for ($x = 0; $x < 1000; $x++)
							{
								$a = imagecolorat($MapFG, ($x*MapsGenerator::$BLPMapWidth)/1000, ($y*MapsGenerator::$BLPMapHeight)/1000)>>24;
								imagesetpixel($tmp, $x, $y, $a < 30 ? $cfg : $cbg);
							}
						imagepng($tmp, $outtmpdir . $MapID . ".png");
						imagecolordeallocate($tmp, $cbg);
						imagecolordeallocate($tmp, $cfg);
						imagedestroy($tmp);
						echo ".";
					}
				}

				for ($y = 0; $y < imagesy($MapFG); $y++)
					for ($x = 0; $x < imagesx($MapFG); $x++)
					{
						$c = imagecolorat($MapFG, $x, $y);
						if (($c>>24) < 127 && ($c>>24) > 0)
						{
							$c &= 0xFFFFFF;
							imagesetpixel($MapFG, $x, $y, $c);
						}
					}
				imagecopy($Map, $MapFG, 0, 0, 0, 0, imagesx($MapFG), imagesy($MapFG));
				imagedestroy($MapFG);

				$NewImage = imagecreatetruecolor(488,325);
				imagecopyresampled($NewImage, $Map, 0,0, 0,0, 488,325, MapsGenerator::$BLPMapWidth,MapsGenerator::$BLPMapHeight);
				imagejpeg($NewImage, MapsGenerator::$DirArray['NormalMap'] . $MapID . ".jpg");
				imagedestroy($NewImage);
				$NewImage = imagecreatetruecolor(772,515);
				imagecopyresampled($NewImage, $Map, 0,0, 0,0, 772,515, MapsGenerator::$BLPMapWidth,MapsGenerator::$BLPMapHeight);
				imagejpeg($NewImage, MapsGenerator::$DirArray['ZoomMap'] . $MapID . ".jpg");
				imagedestroy($NewImage);

				imagedestroy($Map);

				MapsGenerator::Status("done (" . intval($Count*100/count($UnpackedDBC)) . "%)\n");
			}
		}
	}
}

?>