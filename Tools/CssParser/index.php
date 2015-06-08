<?php
set_time_limit(300);
define("DS", DIRECTORY_SEPARATOR);
$FileName = 'profession.css';
$Lines = file($FileName);
$RootFolder = str_replace("\Tools\CssParser", "", str_replace("/Tools/CssParser", "", getcwd()));
$TemplatesFolder = $RootFolder.DS."Templates".DS."FreedomCore";

$RemoteFileArray = array();
$LocalFileArray = array();


$BattleNetStaticContent = array(
	"0" => "http://eu.battle.net/wow/static/local-common",
	"1" => "http://eu.battle.net/wow/static",
	"2" => "http://eu.battle.net/login/static"
);
$Found = false;
for($i = 0; $i < count($Lines); $i++)
{
	if(strpos($Lines[$i], $BattleNetStaticContent[0]) !== false)
	{
		$StringBeginOn = strpos($Lines[$i], '(')+1;
		$StringEndsOn = strpos($Lines[$i], ')') - $StringBeginOn;
		$Lines[$i] = substr($Lines[$i], $StringBeginOn, $StringEndsOn);
		$RemoteFileArray[] = $Lines[$i];
		$LocalFileArray[] = str_replace("\\", DS, str_replace('/', DS, str_replace($BattleNetStaticContent[0], $TemplatesFolder, $Lines[$i])));
	}
	else if(strpos($Lines[$i], $BattleNetStaticContent[1]) !== false)
	{
		$StringBeginOn = strpos($Lines[$i], '(')+1;
		$StringEndsOn = strpos($Lines[$i], ')') - $StringBeginOn;
		$Lines[$i] = substr($Lines[$i], $StringBeginOn, $StringEndsOn);
		$RemoteFileArray[] = $Lines[$i];
		$LocalFileArray[] = str_replace("\\", DS, str_replace('/', DS, str_replace($BattleNetStaticContent[1], $TemplatesFolder, $Lines[$i])));
	}
	else if(strpos($Lines[$i], $BattleNetStaticContent[2]) !== false)
	{
		$StringBeginOn = strpos($Lines[$i], '(')+1;
		$StringEndsOn = strpos($Lines[$i], ')') - $StringBeginOn;
		$Lines[$i] = substr($Lines[$i], $StringBeginOn, $StringEndsOn);
		if (substr($Lines[$i], 0, 4) === 'http') 
		{
			$RemoteFileArray[] = $Lines[$i];
			$LocalFileArray[] = str_replace("\\", DS, str_replace('/', DS, str_replace($BattleNetStaticContent[2], $TemplatesFolder, $Lines[$i])));
		}
	}
}
$Downloaded = 0;
$Exists = 0;
for($f = 0; $f < count($RemoteFileArray); $f++)
{
	if (!file_exists($LocalFileArray[$f]))  
	{
		$FileDir = substr($LocalFileArray[$f], 0, strrpos( $LocalFileArray[$f], '\\'));
		if (!is_dir($FileDir)) 
		{
			mkdir($FileDir, 0755, true);        
		}

		ImageSaver($RemoteFileArray[$f], $LocalFileArray[$f]);
		$Downloaded++;
	}
	else
		$Exists++;
}



echo "<strong>Всего файлов: </strong> ".count($RemoteFileArray)." <br />";
echo "<strong>Загружено:</strong> ".$Downloaded." файл(а/ов)!<br />";
echo "<strong>Существующих:</strong> ".$Exists." файл(а/ов)!<br />";

//echo "<strong></strong> <br />";



function ImageSaver($DownloadFrom, $SaveTo)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL , $DownloadFrom);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$response= curl_exec ($ch);
	curl_close($ch);
	$file = fopen($SaveTo , 'w') or die("<strong> Unable to save image: </strong> $DownloadFrom <br />");
	fwrite($file, $response);
	fclose($file);
}

?>