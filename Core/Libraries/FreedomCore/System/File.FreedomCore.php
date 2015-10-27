<?php

Class File
{
	public static function Download($URL, $SaveTo)
	{
        $Handle = curl_init($URL);
        curl_setopt($Handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Handle, CURLOPT_HEADER, false);
        curl_setopt($Handle, CURLOPT_USERAGENT, 'FreedomCore CMS (Manager Class Download Function)');
        curl_setopt($Handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Handle, CURLOPT_SSL_VERIFYHOST, false);
        $Response = curl_exec($Handle);
        $HTTPCode = curl_getinfo($Handle, CURLINFO_HTTP_CODE);
        if($HTTPCode != 404) {
            $SaveFile = fopen($SaveTo, 'w');
            fwrite($SaveFile, $Response);
            fclose($SaveFile);
            curl_close($Handle);
            return true;
        }
        else
        {
            curl_close($Handle);
            return false;
        }
	}

	public static function Unzip($File)
	{
		$Path = File::FileDir($File);

		$ZIP = new ZipArchive;
		$Resource = $ZIP->open($File);
		if ($Resource === TRUE) {
		  $ZIP->extractTo($Path);
		  $ZIP->close();
		}
	}

    public static function Exists($File)
    {
        if (file_exists($File))
            return true;
        else
        {
            if(!file_exists(substr($File, 0, strrpos($File, DS))))
                mkdir(substr($File, 0, strrpos($File, DS)), 0777, true);
            return false;
        }
    }

	private static function FileDir($File)
	{
		$FullPath = explode(DS, $File);
		$FileName = end($FullPath);
		$Path = str_replace($FileName, '', $File);
		return $Path;
	}

    public static function DirectoryContent($Directory, $UserLanguage)
    {
        $ReadDirectory = $Directory.str_replace('.language', '', $UserLanguage);
        $Iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ReadDirectory));
        $FilesArray = array();
        while($Iterator->valid())
        {
            if (!$Iterator->isDot())
            {
                $FilesArray[] = array(
                    'FileLink' => $Iterator->key(),
                    'FileName' => $Iterator->getSubPathName(),
                    'SmallFileName' => strtolower(str_replace('.language', '', $Iterator->getSubPathName())),
                    'LinesCount' => File::CountLines($Iterator->key())
                );
            }
            $Iterator->next();
        }
        return $FilesArray;
    }

    public static function GetDirectoryContent($Directory, $SearchForFormat = null)
    {
        $Iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Directory));
        $FilesArray = array();
        while($Iterator->valid())
        {
            if (!$Iterator->isDot())
            {
                if($SearchForFormat != null)
                {
                    $Exploded = explode('.', $Iterator->getSubPathName());

                    if($Exploded[1] == $SearchForFormat)
                        $FilesArray[] = array(
                            'FileLink' => $Iterator->key(),
                            'FileName' => $Iterator->getSubPathName()
                        );
                }
                else
                    $FilesArray[] = array(
                        'FileLink' => $Iterator->key(),
                        'FileName' => $Iterator->getSubPathName()
                    );
            }
            $Iterator->next();
        }
        return $FilesArray;
    }

    public static function ReadFileToArray($FileName, $ExplodeBy)
    {
        $File = file($FileName, FILE_IGNORE_NEW_LINES);
        $FileData = array();
        foreach($File as $Line)
        {
            $FileData[] = explode($ExplodeBy, $Line);
        }
        return $FileData;
    }

    public static function ArrayChunk($Array, $Offet)
    {
        return array_chunk($Array, $Offet);
    }

    public static function RemapArray($Array, $KeyOne, $KeyTwo)
    {
        $NewArray = array();
        foreach($Array as $Item)
        {
            if(isset($Item[$KeyTwo]))
                $NewArray[$Item[$KeyOne]] = $Item[$KeyTwo];
        }
        return $NewArray;
    }

    private static function CountLines($File)
    {
        $LineCount = 0;
        $Handle = fopen($File, "r");
        while(!feof($Handle)){
            $Line = fgets($Handle, 4096);
            $LineCount = $LineCount + substr_count($Line, PHP_EOL);
        }
        fclose($Handle);
        return $LineCount;
    }

    public static function GetSubDirectories($Directory)
    {
        $DirectoryList = array();
        foreach(glob($Directory.'*', GLOB_ONLYDIR) as $dir) {
            $DirectoryName = str_replace($Directory, '', $dir);
            $FileIterator = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
            $DirectoryList[] = array(
                'LanguageName' => $DirectoryName,
                'LanguageLink' => strtolower($DirectoryName),
                'FilesInside' => iterator_count($FileIterator)
            );
        }
        return $DirectoryList;
    }

    public static function DirectorySize($Directory)
    {
        $Iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($Directory));
        $TotalSize = 0;
        foreach($Iterator as $File) {
            $TotalSize += $File->getSize();
        }
        return File::FormatBytes($TotalSize);
    }

    private static function FormatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function Untar($File)
	{
		$Path = File::FileDir($File);
		$TarGzFile = new PharData($File);
		$TarGzFile->decompress();
		preg_match('/(.*)\.([^.]*)$/', $File, $matches);
		$TarPath = str_replace('.'.$matches[2], '', $File);
		$TarFile = new PharData($TarPath);
		$TarFile->extractTo($Path);
	}

	public static function CopyRecursive($Source, $Destination)
	{
		$Directory = opendir($Source);
		if(!file_exists($Destination))
	    	@mkdir($Destination); 
	    while(false !== ( $File = readdir($Directory)) ) { 
	        if (( $File != '.' ) && ( $File != '..' )) { 
	            if ( is_dir($Source . '/' . $File) ) { 
	                File::CopyRecursive($Source . '/' . $File,$Destination . '/' . $File); 
	            } 
	            else { 
	                copy($Source . '/' . $File,$Destination . '/' . $File); 
	            } 
	        } 
	    } 
	    closedir($Directory);
	}

	public static function RRDirectory($Directory) 
	{ 
		if (is_dir($Directory)) 
		{
			$Files = scandir($Directory); 
	     	foreach ($Files as $File)
	     	{ 
				if ($File != "." && $File != "..")
				{ 
					if (filetype($Directory."/".$File) == "dir") File::RRDirectory($Directory."/".$File); else unlink($Directory."/".$File); 
				}
			} 
			reset($Files); 
			rmdir($Directory);
		} 
	}
}

?>