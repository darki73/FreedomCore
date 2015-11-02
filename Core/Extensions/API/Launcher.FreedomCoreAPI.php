<?php

Class LauncherAPI extends API
{
    private static $VersionsPath;
    private static $LauncherPath;
    private static $FreedomCoreURL;

    public function __construct()
    {
        LauncherAPI::$VersionsPath = getcwd().DS.'Launcher'.DS.'versions'.DS;
        LauncherAPI::$LauncherPath = getcwd().DS.'Launcher'.DS;
        LauncherAPI::$FreedomCoreURL = "https://project.freedomcore.ru/freedomcore.php?action=%s&current_hash=%s";
    }

    public static function GetVersions($FirstLaunch = false){
        $Directories = array_filter(glob(LauncherAPI::$VersionsPath.'*'), 'is_dir');
        foreach($Directories as $Directory)
            $VersionsArray[] = str_replace('.', '', str_replace(LauncherAPI::$VersionsPath, '', $Directory));
        if(!$FirstLaunch)
            return $VersionsArray;
        else
            return end($VersionsArray);
    }

    public static function CheckForUpdate($AssemblyVersion, $Format = false, $FirstLaunch = false){
        $Versions = LauncherAPI::GetVersions();
        $CurrentVersion = LauncherAPI::VersionToString($AssemblyVersion);
        if(!$FirstLaunch){
            for($i = 0; $i <= Text::ASearch($Versions, $CurrentVersion); $i++)
                unset($Versions[$i]);
        }

        if(empty($Versions))
            return false;
        else
            if($Format)
                return LauncherAPI::StringToVersion(end($Versions));
            else
                return end($Versions);
    }

    public static function StringToVersion($String)
    {
        return implode('.', str_split($String));
    }

    public static function VersionToString($Version)
    {
        return str_replace('.', '', $Version);
    }

    public static function BuildUpdateList($Version)
    {
        $Folders = LauncherAPI::GetFolders($Version);
        $Files = LauncherAPI::GetFiles($Version);

        if(!$Folders || !$Files)
            return false;
        else
            return ['Folders' => $Folders, 'Files' => $Files];
    }

    private static function GetFiles($Version)
    {
        $Files = [];
        $TMP = [];
        $TotalSize = 0;
        $ServerIP = gethostbyname(gethostname());
        $NewVersionPath = LauncherAPI::$VersionsPath.$Version;
        $VersionFolder = 'FreedomNet.'.LauncherAPI::VersionToString($Version);
        if(is_dir($NewVersionPath)){
            $Iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($NewVersionPath));

            foreach($Iterator as $File){
                if($File->isFile()){
                    $FilePath = str_replace($NewVersionPath, '', $File->getRealpath());
                    $FileSize = filesize($File->getRealpath());
                    $TotalSize += $FileSize;
                    $TMP[] = [
                        'local_path'    =>  $VersionFolder.$FilePath,
                        'download_path' =>  'http://'.$ServerIP.'/Launcher/versions/'.$Version.str_replace('\\', '/', $FilePath),
                        'file_size'     =>  $FileSize
                    ];
                }
            }
            $Files['count'] = count($TMP);
            $Files['total_size'] = $TotalSize;
            $Files['files'] = $TMP;
            return $Files;
        } else {
            return false;
        }
    }

    private static function DatabaseVersion()
    {
        $Statement = parent::$DBConnection->prepare('SELECT * FROM db_version');
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function ServerSupported()
    {
        $DBData = LauncherAPI::DatabaseVersion();
        $Response = LauncherAPI::APIRequest('launcher_supported', $DBData['database_version']);
        return $Response;
    }

    public static function GetAgentVersion($AssemblyVersion, $UseHostname = false)
    {
        $DBData = LauncherAPI::DatabaseVersion();
        $Response = LauncherAPI::APIRequest('agent-version', $DBData['database_version']);
        $ServerRelatedData = [
            'server_ip' => $_SERVER['HTTP_HOST'],
            'server_name' => $_SERVER['SERVER_NAME']
        ];
        foreach($ServerRelatedData as $Key => $Value)
            $Response[$Key] = $Value;

        if($UseHostname)
            $DownloadURL = 'http://'.$ServerRelatedData['server_name'].'/Launcher/FreedomNetAgent.exe';
        else
            $DownloadURL = 'http://'.$ServerRelatedData['server_ip'].'/Launcher/FreedomNetAgent.exe';

        if(LauncherAPI::VersionToString($Response['agent_version']) != LauncherAPI::VersionToString($AssemblyVersion))
            $Response['update_needed'] = 1;
        else
            $Response['update_needed'] = 0;

        $Response['download_url'] = $DownloadURL;
        return $Response;
    }

    private static function GetAgentFileData()
    {
        $RootPath = LauncherAPI::$LauncherPath;
        $AgentDataArray = [
            'count'     =>  1,
            'size'      =>  filesize($RootPath.'FreedomNetAgent.exe'),
            'files'     =>  [[
                        'local_path'    =>  'FreedomNetAgent.exe',
                        'download_path' =>  'http://'.$_SERVER['HTTP_HOST'].'/Launcher/FreedomNetAgent.exe',
                        'file_size'     =>  filesize($RootPath.'FreedomNetAgent.exe'),
            ]]
        ];

        return $AgentDataArray;
    }

    private static function APIRequest($Method, $Hash, $AdditionalParameters = [])
    {
        $PList = "";

        $AgentData = Manager::GetUrlData(sprintf(LauncherAPI::$FreedomCoreURL, $Method, $Hash).$PList);
        $JsonResponse = json_decode($AgentData, true);
        return $JsonResponse;
    }

    private static function GetFolders($Version)
    {
        $NewVersionPath = LauncherAPI::$VersionsPath.$Version;
        $VersionFolder = 'FreedomNet.'.LauncherAPI::VersionToString($Version);
        if(is_dir($NewVersionPath)){
            $Iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($NewVersionPath),
                RecursiveIteratorIterator::SELF_FIRST);
            $Folders = [];
            foreach($Iterator as $File)
                if($File->isDir())
                    $Folders[] = $File->getRealpath();

            $Folders = Text::UniqueSingle($Folders);
            if(Text::ASearch($Folders, substr(LauncherAPI::$VersionsPath, 0, -1)) != false)
                unset($Folders[Text::ASearch($Folders, substr(LauncherAPI::$VersionsPath, 0, -1))]);
            if($Folders[0] == $NewVersionPath)
                unset($Folders[0]);

            $FinalList = [];
            foreach($Folders as $Folder){
                $InVersionFolder = str_replace($NewVersionPath, '', $Folder);
                $FinalList[] = $VersionFolder.$InVersionFolder;
            }
            return $FinalList;
        } else {
            return false;
        }
    }
}

new LauncherAPI();

?>