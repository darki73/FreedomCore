<?php

Class Security
{
    private static $GeneratedFilesList = array();
    public function __construct()
    {
        Security::HideServerIdentity();
    }

    public static function HideServerIdentity()
    {
        header('X-Powered-By: FreedomCore Management Engine');
        header('Server: FreedomCore HTTP Server IIS 7.5 (Windows Server 2012)');
    }

    private static function GenerateFileList($ScanDirectory = null)
    {
        $FilesArray = array();
        if($ScanDirectory == null)
            $ScanDirectory = getcwd();
        $RootDirectory = array_diff(scandir($ScanDirectory), array('.', '..', '.idea', '.git', '.gitattributes', '.gitignore'));
        foreach($RootDirectory as $Directory)
        {
           if($Directory != 'images' && $Directory != 'Tools' && $Directory != 'Cache' && $Directory != 'Installation' && $Directory != 'Uploads')
           {
               if(is_dir($ScanDirectory.DS.$Directory))
                   Security::GenerateFileList($ScanDirectory.DS.$Directory);
               else
               {
                   $FilesArray[] = array(
                       "file" => str_replace(getcwd(), '', $ScanDirectory.DS.$Directory),
                       "hash" => sha1_file($ScanDirectory.DS.$Directory),
                       "changed" => filemtime($ScanDirectory.DS.$Directory)
                   );
               }
           }
        }
        Security::$GeneratedFilesList[] = $FilesArray;
        unset($FilesArray);
    }

    private static function CreateFileList()
    {
        Security::GenerateFileList();
        $RemoveEmptyArrays = array_filter(Security::$GeneratedFilesList);
        $ConvertTDToOD = call_user_func_array('array_merge', $RemoveEmptyArrays);
        return $ConvertTDToOD;
    }

    public static function WriteToFile($ForceScan)
    {
        $SecurityList = Security::CreateFileList();
        $SecurityFile = getcwd().DS.'Cache'.DS.'securitychecklist.php';
        if($ForceScan)
        {
            $SecurityFileCreationDate = filemtime($SecurityFile);
            unlink($SecurityFile);
            Security::ArrayToFile($SecurityFile, $SecurityList);
        }
        else
        {
            if(file_exists($SecurityFile))
            {
                $SecurityFileCreationDate = filemtime($SecurityFile);
                $CurrentDate = strtotime('-1 day', time());
                if($CurrentDate >= $SecurityFileCreationDate) {
                    unlink($SecurityFile);
                    Security::ArrayToFile($SecurityFile, $SecurityList);
                }
            }
            else
            {
                Security::ArrayToFile($SecurityFile, $SecurityList);
            }
        }
    }

    private static function ArrayToFile($File, $DataArray)
    {
        $SecurityList = fopen($File, "w");
        fwrite($SecurityList, '<?php'."\n".'$SecurityList = array(');
        $TotalFiles = count($DataArray);
        $Index = 0;
        foreach($DataArray as $String) {
            if($Index == ($TotalFiles - 1))
            {
                fwrite($SecurityList, "
                    array(
                        'file' => '".$String["file"]."',
                        'hash' => '".$String["hash"]."',
                        'changed' => '".$String["changed"]."'
                    )
                ");
            }
            else
            {
                fwrite($SecurityList, "
                    array(
                        'file' => '".$String["file"]."',
                        'hash' => '".$String["hash"]."',
                        'changed' => '".$String["changed"]."'
                    ),
                ");
            }
            $Index++;
        }
        fwrite($SecurityList, ');'."\n".'?>');
        fclose($SecurityList);
    }

    public static function VerifyFileList($ForceScan = false)
    {
        Security::WriteToFile($ForceScan);
        Security::$GeneratedFilesList = array();
        $SecurityFile = getcwd().DS.'Cache'.DS.'securitychecklist.php';
        require_once($SecurityFile);
        $NewFilesArray = Security::CreateFileList();
        $NewArrayIndex = 0;

        $ScannedFiles = count($SecurityList);

        $PassedFiles = 0;
        $FailedFiles = 0;
        $AddedFiles = 0;

        $AddedFilesArray = array();
        $FailedFilesArray = array();

        foreach($NewFilesArray as $NewFile)
        {
            $NewFileName = $NewFile['file'];
            if(String::MASearch($SecurityList, 'file', $NewFileName))
            {
                $FileHash = sha1_file(getcwd().$NewFileName);
                if(!String::MASearch($SecurityList, 'hash', $FileHash))
                {
                    $FailedFiles++;
                    $FileName = explode(DS, $NewFileName);
                    $FileExtension = explode('.', $NewFileName);
                    $FailedFilesArray[] = array(
                        "name" => end($FileName),
                        "file" => $NewFileName,
                        "extension" => Security::FileTypeByExtension(end($FileExtension)),
                        "hash" => sha1_file(getcwd().$NewFileName),
                        "changed" => filemtime(getcwd().$NewFileName)
                    );
                }
                else
                    $PassedFiles++;
            }
            else
            {
                $AddedFiles++;
                $FileName = explode(DS, $NewFileName);
                $FileExtension = explode('.', $NewFileName);
                $AddedFilesArray[] = array(
                    "name" => end($FileName),
                    "file" => $NewFileName,
                    "extension" => Security::FileTypeByExtension(end($FileExtension)),
                    "hash" => sha1_file(getcwd().$NewFileName),
                    "changed" => filemtime(getcwd().$NewFileName)
                );
            }
        }
        $ScanResult = array(
            'scanned' => $ScannedFiles,
            'failed' => $FailedFilesArray,
            'new' => $AddedFilesArray,
            'lastupdate' => filemtime($SecurityFile)
        );
        return $ScanResult;
    }

    private static function FileTypeByExtension($Extension)
    {
        $Extensions = array(
            'php' => 'Code',
            'js' => 'JavaScript',
            'tpl' => 'Template',
            'css' => 'Stylesheet',
            'jpg' => 'Image',
            'jpeg' => 'Image',
            'png' => 'Image',
            'svg' => 'Image',
            'less' => 'Stylesheet',
            'woff' => 'Font',
            'language' => 'Language',
            'in' => 'Include'
        );

        return $Extensions[$Extension];
    }
}

$System_Extension_Security = new Security();

?>