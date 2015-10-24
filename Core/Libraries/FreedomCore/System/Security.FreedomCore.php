<?php

Class Security
{
    private static $GeneratedFilesList = array();
    public function __construct()
    {
        Security::HideServerIdentity();
        //Security::NotifyByHost(true);
    }

    public static function HideServerIdentity()
    {
        header('X-Powered-By: FreedomCore Management Engine');
        header('Server: FreedomCore HTTP Server IIS 7.5 (Windows Server 2012)');
    }

    private static function NotifyByHost($Enabled = false)
    {
        date_default_timezone_set('Europe/Moscow');
        if($Enabled)
        {
            $WriteToDirectory = getcwd().DS.'Cache'.DS.'Compile'.DS.'Templates'.DS.'FreedomCore'.DS;
            $WriteToFile = md5(uniqid(rand(), true));
            $Files = array_diff(scandir($WriteToDirectory), array('.', '..'));
            foreach($Files as $Key=>$Value)
            {
                $GetExtension = explode('.', $Value);
                if(end($GetExtension) == 'php')
                    unset($Files[$Key]);
                elseif(end($GetExtension) == 'html')
                    unset($Files[$Key]);
                else
                {
                    if(!empty($Files))
                    {
                        $Files[0] = $Value;
                        unset($Files[$Key]);
                    }
                }
            }
            if(empty($Files))
            {
                $Response = Security::RequestServerResponse();
                $RequestTime = file_put_contents($WriteToDirectory.$WriteToFile, time().":".$Response);
                if($Response != 1)
                    die('Cant access FreedomCore Notification Server<br /> Delete file without extension inside <strong>/Cache/Compile/Templates/FreedomCore</strong> and try again');
            }
            else
            {
                $FileData = explode(':', file_get_contents($WriteToDirectory.$Files[0]));
                $TimeInFile = $FileData[0];
                $Response = $FileData[1];
                $NextRequest = $TimeInFile + 24*60*60;
                $TimeNow = time();
                if($TimeNow >= $NextRequest)
                {
                    if(Security::RequestServerResponse() != 1)
                        die('Cant access FreedomCore Notification Server');
                    else
                    {
                        unlink($WriteToDirectory.$Files[0]);
                        $Response = Security::RequestServerResponse();
                        $RequestTime = file_put_contents($WriteToDirectory.$WriteToFile, time().":".$Response);
                    }
                }
                else
                {
                    if($Response == 0)
                        die('Cant access FreedomCore Notification Server');
                }
            }
        }
        else
            die('You must allow access to Notification Server');
    }

    private static function RequestServerResponse()
    {
        $GitHead = getcwd().DS.'.git'.DS.'FETCH_HEAD';
        if(file_exists($GitHead))
        {
            $LocalVersion = file_get_contents(getcwd().DS.'.git'.DS.'FETCH_HEAD');
            list($LocalVersion, $ServiceInfo) = explode('branch', $LocalVersion);
        }
        else
            die('Script can be only used while cloned from GitHub Repo');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"http://project.freedomcore.ru/notify.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query
            (
                array(
                    'project_name' => 'FreedomNet',
                    'server_name' => $_SERVER['SERVER_NAME'],
                    'server_ip' => $_SERVER['SERVER_ADDR'],
                    'version' => $LocalVersion
                )
            )
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $Response = curl_exec ($ch);
        curl_close ($ch);
        return $Response;
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
            if(Text::MASearch($SecurityList, 'file', $NewFileName))
            {
                $FileHash = sha1_file(getcwd().$NewFileName);
                if(!Text::MASearch($SecurityList, 'hash', $FileHash))
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