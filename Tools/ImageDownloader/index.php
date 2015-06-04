<?php
set_time_limit(300);
define("DS", DIRECTORY_SEPARATOR);
$BaseURL = "http://eu.battle.net/wow/static/images/guild/tabards/";
$RootFolder = str_replace("\Tools\ImageDownloader", "", str_replace("/Tools/ImageDownloader", "", getcwd()));
$SaveTo = str_replace('http://eu.battle.net/wow/static', '/Templates/FreedomCore', $BaseURL);
$StartFrom = 195;
$TryAccessFor = 300;

for($i = $StartFrom; $i <= $TryAccessFor; $i++)
{
	$FileName = 'emblem_'.$i.'.png';
	$FormLink = $BaseURL.$FileName;
	if(TryOpenURL($FormLink))
	{
		echo "<img src='".$FormLink."'><br />".$FormLink."<br />";
		$DownloadInto = str_replace('/', DS, $RootFolder).str_replace('/', DS, $SaveTo).str_replace('/', DS, $FileName);
		ImageSaver($FormLink, $DownloadInto);
	}
}

function TryOpenURL($url)
{
	$handle = curl_init($url);
	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
	$response = curl_exec($handle);
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if($httpCode == 404)
		return false;
	else
		return true;
	curl_close($handle);
}

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

