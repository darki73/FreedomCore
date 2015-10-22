<?php

Class Installer
{
    public static $DBConnection;
    public static $CConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Installer::$DBConnection = $VariablesArray[0]::$Connection;
        Installer::$CConnection = $VariablesArray[0]::$CConnection;
        Installer::$WConnection = $VariablesArray[0]::$WConnection;
        Installer::$TM = $VariablesArray[1];
    }

    public static function Import($Host, $User, $Password, $Database, $Encoding, $File)
    {
        $Connection = new PDO("mysql:host=".$Host.";dbname=".$Database.";charset=".$Encoding, $User, $Password, array(PDO::ATTR_PERSISTENT => false));
        $Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $ImportStatus = false;
        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = $Connection->prepare($Line);
            if($Statement->execute())
                $ImportStatus = true;
            else
                $ImportStatus = false;
        }
        $Connection = null;
        return $ImportStatus;
    }

    public static function ImportCoreTable($Host, $User, $Password, $Database, $Encoding, $File)
    {
        $Connection = new PDO("mysql:host=".$Host.";dbname=".$Database.";charset=".$Encoding, $User, $Password, array(PDO::ATTR_PERSISTENT => false));
        $Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $Lines = file($File, FILE_IGNORE_NEW_LINES);
        foreach($Lines as $Line)
        {
            $Statement = $Connection->prepare($Line);
            if($Statement->execute())
                return true;
            else
                return false;
        }

        $Connection = null;
    }

    public static function InstallerVersion()
    {
        $GitHead = getcwd().DS.'.git'.DS.'FETCH_HEAD';
        if(file_exists($GitHead))
        {
            $LocalVersion = file_get_contents(getcwd().DS.'.git'.DS.'FETCH_HEAD');
            list($LocalVersion, $ServiceInfo) = explode('branch', $LocalVersion);
        } else {
            $LocalVersion = "Undefined";
        }
        return $LocalVersion;
    }
    
    private static function GithubResponse($APIUrl)
    {
        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_USERAGENT, 'FreedomCore Installer v2.0');
        curl_setopt($Curl, CURLOPT_URL, $APIUrl);
        $Result = curl_exec($Curl);
        curl_close($Curl);
        return $Result;
    }

    private static function WriteToFile($Folder, $FileName, $Data = array())
    {
        $FileHandler = fopen($Folder.DS.$FileName, 'w');
        foreach($Data as $Key => $Value){
            fwrite($FileHandler, $Key . " = " .$Value ."\n");
        }
        fclose($FileHandler);
    }

    public static function GithubRepoStatus()
    {
        $ServerFolder = getcwd();
        $InstallationFolder = $ServerFolder.DS.'Install';
        $FileName = md5(uniqid(rand(), true)).".github-data";
        $ReposURL = "https://api.github.com/users/darki73/repos?type=owner";
        if(Installer::GithubStatusFileExists($InstallationFolder)){

        } else {
            $FreedomCoreRepo = [];
            $Repos = json_decode(Installer::GithubResponse($ReposURL), true);
            $ArrayIndex = 0;
            for($i = 0; $i < count($Repos); $i++){
                if($Repos[$i]['name'] != 'FreedomCore')
                    unset($Repos[$i]);
                else {
                    $FreedomCoreRepo['name']            =   $Repos[$i]['name'];
                    $FreedomCoreRepo['stargazers']      =   $Repos[$i]['stargazers_count'];
                    $FreedomCoreRepo['watchers']        =   $Repos[$i]['watchers_count'];
                    $FreedomCoreRepo['language']        =   $Repos[$i]['language'];
                    $FreedomCoreRepo['forks']           =   $Repos[$i]['forks_count'];
                    $FreedomCoreRepo['size']            =   $Repos[$i]['size'];
                    $FreedomCoreRepo['forks_url']       =   $Repos[$i]['forks_url'];
                    $FreedomCoreRepo['stargazers_url']  =   $Repos[$i]['stargazers_url'];
                    $FreedomCoreRepo['url']             =   $Repos[$i]['html_url'];
                    $FreedomCoreRepo['last_update']     =   $Repos[$i]['updated_at'];
                    unset($Repos);
                    break;
                }
            }
            $BranchesURL = "https://api.github.com/repos/darki73/".$FreedomCoreRepo['name']."/branches";
            $Branches = json_decode(Installer::GithubResponse($BranchesURL), true);
            $Commit = "";
            for($i = 0; $i <= count($Branches); $i++){
                if($i == 0){
                    $Commit = $Branches[$i]['commit']['sha'];
                    break;
                }
            }
            $FreedomCoreRepo['commit'] = $Commit;

            Installer::CheckAndWrite($InstallationFolder, $FileName, $FreedomCoreRepo);
        }

        return Installer::GetGithubFileData($InstallationFolder);
    }

    private static function GithubDataFileName($Folder) {
        $Files = array();
        foreach (glob($Folder.DS."*.github-data") as $File)
            $Files[] = $File;
        return $Files[0];
    }

    private static function GithubStatusFileExists($Folder){
        $Files = array();
        foreach (glob($Folder.DS."*.github-data") as $File) {
            $Files[] = $File;
        }
        if(!empty($Files)){
            if(time() > (86400 + filemtime($Files[0])))
                return false;
            else
                return true;
        }
        else
            return false;
    }

    private static function CheckAndWrite($Folder, $Name, $Data)
    {
        $Files = array();
        foreach (glob($Folder.DS."*.github-data") as $File) {
            $Files[] = $File;
        }
        if(!empty($Files)){
            if(time() > (86400 + filemtime($Files[0]))){
                unlink($Files[0]);
                Installer::WriteToFile($Folder, $Name, $Data);
            }
        } else {
            Installer::WriteToFile($Folder, $Name, $Data);
        }
    }

    private static function GetGithubFileData($Folder)
    {
        $Lines = file(Installer::GithubDataFileName($Folder), FILE_IGNORE_NEW_LINES);
        $FreedomCoreRepo = [];
        foreach ($Lines as $Line){
            $Exploded = explode(' = ', $Line);
            $FreedomCoreRepo[$Exploded[0]] = $Exploded[1];
        }

        return $FreedomCoreRepo;
    }

    public static function CheckPHPModules()
    {
        $ModulesArray = array(
            array(
                'name' => 'pdo_mysql',
                'status' => extension_loaded('pdo_mysql')
            ),
            array(
                'name' => 'curl',
                'status' => extension_loaded('curl')
            ),
            array(
                'name' => 'mysqli',
                'status' => extension_loaded('mysqli')
            ),
            array(
                'name' => 'soap',
                'status' => extension_loaded('soap')
            ),
            array(
                'name' => 'gd',
                'status' => extension_loaded('gd')
            ),
            array(
                'name' => 'soap',
                'status' => extension_loaded('soap')
            ),
        );

        return $ModulesArray;
    }

    public static function ServerInfo()
    {
        $ServerData = [];

        $ServerData['Server'] = $_SERVER["SERVER_SOFTWARE"];
        $ServerData['PHP'] = Installer::PHPInfoMagic();
        $ServerData['MySQL'] = Installer::MySQLInfoMagic();
        if(strstr($_SERVER['SERVER_SOFTWARE'], 'Apache')){
            $ServerData['Apache'] = Installer::ApacheInfoMagic();
            $ServerData['TestState'] = "VALIDATION_STATE_TESTED";
        } else
            $ServerData['TestState'] = "VALIDATION_STATE_UNTESTED";
        $ServerData['OS'] = Installer::OSInfoMagic();


        if( $ServerData['PHP']['valid'] == 'VALIDATION_STATE_PASSED' || $ServerData['PHP']['valid'] == 'VALIDATION_STATE_WARNING' &&
            $ServerData['MySQL']['valid'] == 'VALIDATION_STATE_PASSED' || $ServerData['MySQL']['valid'] == 'VALIDATION_STATE_WARNING' &&
            $ServerData['OS']['valid'] == 'VALIDATION_STATE_PASSED' || $ServerData['OS']['valid'] == 'VALIDATION_STATE_WARNING' )
            $ServerData['AllowInstallation'] = "YES";
        else
            $ServerData['AllowInstallation'] = "NO";
        return $ServerData;
    }

    private static function PHPInfoMagic()
    {
        $Required = '5.5.1';
        $Installed = phpversion();
        if(strstr($Installed, '-'))
            $Installed = substr($Installed, 0, strrpos($Installed, '-'));

        if(strlen($Installed) > 6){
            $InstalledT = substr($Installed, 0, -1);
            $InstalledR = str_replace('.', '', $InstalledT);
        } else {
            $InstalledR = str_replace('.', '', $Installed);
        }

        $RequiredR = str_replace('.', '', $Required);

        if($InstalledR >= $RequiredR)
            $Valid = "VALIDATION_STATE_PASSED";
        else
            $Valid = "VALIDATION_STATE_FAILED";

        return ['required' => $Required, 'installed' => $Installed, 'valid' => $Valid];
    }

    private static function MySQLInfoMagic(){
        $Required = '5.0.11';
        $Installed = Database::ClientVersion();

        $RequiredR = str_replace('.', '', substr($Required, 0, 3));
        $InstalledR = str_replace('.', '', substr($Installed, 0, 3));

        if($InstalledR >= $RequiredR)
            $Valid = "VALIDATION_STATE_PASSED";
        else
            $Valid = "VALIDATION_STATE_FAILED";

        return ['required' => $Required, 'installed' => $Installed, 'valid' => $Valid];
    }

    private static function ApacheInfoMagic()
    {
        $Version = apache_get_version();
        $Required = "2.2.29";
        $Installed = str_replace(' ', '', str_replace('Apache/', '', strstr($Version, '(', true)));

        if(strlen($Installed) < strlen($Required))
            for($i = 0; $i < (strlen($Required) - strlen($Installed)); $i++)
                $Installed .= "0";

        $RequiredR = str_replace('.', '', $Required);
        $InstalledR = str_replace('.', '', $Installed);

        if($InstalledR >= $RequiredR)
            $Valid = "VALIDATION_STATE_PASSED";
        else
            if($InstalledR == "000"){
                $Valid = "VALIDATION_STATE_WARNING";
                $Installed = "Hidden";
            }
            else
                $Valid = "VALIDATION_STATE_FAILED";

        return ['required' => $Required, 'installed' => $Installed, 'valid' => $Valid];
    }

    private static function OSInfoMagic()
    {
        $Required = "Windows / Linux";
        $TestedSystems = ['WINNT', 'LINUX'];
        $ShortName = PHP_OS;
        $OSData = [];

        if(strtoupper($ShortName) == 'WINNT'){
            $Build = explode('build ', php_uname('v'))[1];
            $OSData = [
                'name'      =>  substr(php_uname('s'), 0, strrpos(php_uname('s'), ' ')),
                'version'   =>  php_uname('r'),
                'build'     =>  substr($Build, 0, strrpos($Build, ' ')),
                'arch'      =>  php_uname('m')
            ];
        } elseif (strtoupper($ShortName) == "LINUX") {
            $Build = explode('-', php_uname('r'));
            $OSData = [
                'name'      =>  php_uname('s'),
                'version'   =>  $Build[0],
                'build'     =>  $Build[0],
                'arch'      =>  php_uname('m'),
            ];
        } else {
            $OSData = [
                'name'      =>  "Mac OS X",
                'version'   =>  "Undefined",
                'build'     =>  "Undefined",
                'arch'      =>  "Undefined",
            ];
        }
        if(in_array(strtoupper($ShortName), $TestedSystems))
            $Valid = "VALIDATION_STATE_PASSED";
        else
            $Valid = "VALIDATION_STATE_WARNING";

        return ['required' => $Required, 'installed' => $OSData, 'valid' => $Valid];
    }

    public static function ProcessInput($Input)
    {
        $SiteDB = [];
        $AuthDB = [];
        $CharDB = [];
        $WorldDB = [];
        $Soap = [];

        foreach($Input as $Key => $Value){
            if(strstr($Key, 'website_db_')){
                $SiteDB[str_replace('website_db_', '', $Key)] = $Value;
                unset($Input[$Key]);
            }
            if(strstr($Key, 'auth_db_')){
                $AuthDB[str_replace('auth_db_', '', $Key)] = $Value;
                unset($Input[$Key]);
            }
            if(strstr($Key, 'characters_db_')){
                $CharDB[str_replace('characters_db_', '', $Key)] = $Value;
                unset($Input[$Key]);
            }
            if(strstr($Key, 'world_db_')){
                $WorldDB[str_replace('world_db_', '', $Key)] = $Value;
                unset($Input[$Key]);
            }
            if(strstr($Key, 'soap_')){
                $Soap[str_replace('soap_', '', $Key)] = $Value;
                unset($Input[$Key]);
            }
        }

        $Globals = [
            'ApplicationName' => $Input['site_name'],
            'ApplicationDescription' => $Input['site_description'],
            'ApplicationKeywords' => $Input['site_keywords'],
            'Template' => 'FreedomCore',
            'ExpansionTemplate' => $Input['site_tempalte'],
        ];

        $ConfigArray = [
            'Database' => $SiteDB,
            'AuthDB' => $AuthDB,
            'CharDB' => $CharDB,
            'WorldDB' => $WorldDB,
            'soap' => $Soap,
            'Globals' => array_merge($Globals, Installer::GlobalsAddons()),
            'Social' => Installer::GenerateSocialBlock(),
            'GoogleAnalytics' => Installer::GenerateGABlock(),
            'Facebook' => Installer::GenerateFABlock(),
        ];

        return $ConfigArray;
    }

    public static function CreateConfigurationFile($Data, $Global)
    {
        $Configuration = '<?php'.PHP_EOL;
        $Configuration .= 'global '.$Global.';'.PHP_EOL.PHP_EOL;
        foreach($Data as $Key => $Value){
            if($Key == "Database"){
                $Configuration .= PHP_EOL.'// Main Database Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "AuthDB"){
                $Configuration .= PHP_EOL.'// Auth Database Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "CharDB"){
                $Configuration .= PHP_EOL.'// Characters Database Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "WorldDB"){
                $Configuration .= PHP_EOL.'// World Database Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "soap"){
                $Configuration .= PHP_EOL.'// SOAP Client Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "Globals"){
                $Configuration .= PHP_EOL.'// Site Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    if($IVal === false)
                        $Configuration .= ''.$Global.'[\''.$IKey.'\'] = false;'.PHP_EOL;
                    elseif($IVal === true)
                        $Configuration .= ''.$Global.'[\''.$IKey.'\'] = true;'.PHP_EOL;
                    else
                        $Configuration .= ''.$Global.'[\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "Social"){
                $Configuration .= PHP_EOL.'// Social Media Links'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "GoogleAnalytics"){
                $Configuration .= PHP_EOL.'// Google Analytics Configuration'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            } elseif($Key == "Facebook"){
                $Configuration .= PHP_EOL.'// Facebook Settings'.PHP_EOL;
                foreach($Value as $IKey => $IVal){
                    $Configuration .= ''.$Global.'[\''.$Key.'\'][\''.$IKey.'\'] = \''.$IVal.'\';'.PHP_EOL;
                }
            }
        }
        $Configuration .= PHP_EOL."?>";
        $ConfigurationFile = getcwd().DS.'Core'.DS.'Configuration'.DS.'Configuration.php';
        if(File::Exists($ConfigurationFile)){
            unlink($ConfigurationFile);
            file_put_contents($ConfigurationFile, $Configuration);
        } else {
            file_put_contents($ConfigurationFile, $Configuration);
        }
    }

    private static function GenerateSocialBlock($LeftOvers = null)
    {
        $Social = [
            "Facebook"	=>	"FreedomHead",
            "Twitter"	=>	"FreedomHead",
            "Vkontakte"	=>	"FreedomHead",
            "Skype"	=>	"FreedomHead",
            "Youtube"	=>	"FreedomHead",
        ];
        return $Social;
    }

    /**
     * Google Analytics
     *
     * @param null $LeftOvers
     * @return array
     */
    private static function GenerateGABlock($LeftOvers = null)
    {
        $GoogleAnalytics = [
            "Account"	=>	"",
            "Domain"	=>	"",
        ];

        return $GoogleAnalytics;
    }

    /**
     * Facebook Admins
     *
     * @param null $LeftOvers
     * @return array
     */
    private static function GenerateFABlock($LeftOvers = null)
    {
        $Facebook = [
            'admins'    =>  '',
            'pageid'    =>  '',
        ];
        return $Facebook;
    }

    private static function GlobalsAddons()
    {
        $Globals = [
            "TimeZone"			=>	"Europe/Moscow",
            "SmartyCaching"	    =>	false,
            "SmartyDebug"		=>	false,
            "Caching"			=>	false,
            "debug"			    =>	true,
            "email"			    =>	"",
            "registration"		=>	false,
        ];
        return $Globals;
    }
}