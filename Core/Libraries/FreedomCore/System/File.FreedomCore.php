<?php

Class File
{
	public static function Download($URL, $SaveTo)
	{
        $Handle = curl_init($URL);
        curl_setopt($Handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $Response = curl_exec($Handle);
        $HTTPCode = curl_getinfo($Handle, CURLINFO_HTTP_CODE);
        if($HTTPCode != 404) {
            $SaveFile = fopen($SaveTo, 'w');
            fwrite($SaveFile, $Response);
            fclose($SaveFile);
        }
        curl_close($Handle);
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
            return false;
    }

	private static function FileDir($File)
	{
		$FullPath = explode(DS, $File);
		$FileName = end($FullPath);
		$Path = str_replace($FileName, '', $File);
		return $Path;
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