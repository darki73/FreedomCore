<?php

Class API
{
    public static $DBConnection;
    public static $CharConnection;
    public static $WConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        API::$DBConnection = $VariablesArray[0]::$Connection;
        API::$CharConnection = $VariablesArray[0]::$CConnection;
        API::$WConnection = $VariablesArray[0]::$WConnection;
        API::$TM = $VariablesArray[1];
        header('Content-Type: application/json; charset=utf-8');
    }


    public static function EnableAPIExtension($ExtensionName)
    {
        if(file_exists(FREEDOMCORE_EXTENSIONS_DIR.'API'.DS.$ExtensionName.'.FreedomCoreAPI.php'))
        {
            $ClassName = $ExtensionName.'API';
            require_once(FREEDOMCORE_EXTENSIONS_DIR.'API'.DS.$ExtensionName.'.FreedomCoreAPI.php');
            if(!class_exists($ClassName)) {
                header('Content-Type: text/html; charset=utf-8');
                die("<strong>Loaded API Extension: </strong>".$ExtensionName."<br />Unable to locate Class named <strong>".$ClassName."</strong><br /> Class name should look like <strong>".$ClassName."</strong>");
            }
        }
        else
        {
            header('Content-Type: text/html; charset=utf-8');
            die("<strong>Unable to Load API Extension: </strong>".$ExtensionName."<br />Check if this Extension actually exists");
        }
    }

    public static function Encode($Array, $Parent = null)
    {
        if($Parent != null)
            echo json_encode([''.$Parent.'' => $Array], JSON_UNESCAPED_UNICODE);
        else
            echo json_encode($Array, JSON_UNESCAPED_UNICODE);
    }

    public static function VerifyIPKey($APIKey)
    {
        $Statement = API::$DBConnection->prepare('SELECT api_key FROM api_keys WHERE api_key = :apikey');
        $Statement->bindParam(':apikey', $APIKey);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($Statement->rowCount() > 0)
        {
            if($APIKey == $Result['api_key'])
                return true;
            else
                return false;
        }
    }

    public static function GenerateResponse($ResponseCode, $DisplayDetail = false, $Detail = null)
    {
        $PlainResponse = [];
        $PlainResponse['code'] = $ResponseCode;
        $CodeData = API::ReponseCodeTranslator($ResponseCode);
        $PlainResponse['type'] = $CodeData['type'];
        if($DisplayDetail)
            $PlainResponse['detail'] = $CodeData['detail'];
        else
            $PlainResponse['detail'] = 'Unhandled Error Occurred';
        echo json_encode($PlainResponse);
        if(in_array($ResponseCode, [400,401,403,404,405,409,429,500,501,502,596]))
            die();
    }

    public static function VerifyRequestEligibility($SecondsPerRequest)
    {
        if(!isset($_SESSION['last_request_time']))
        {
            $_SESSION['last_request_time'] = $SecondsPerRequest;
            Session::UpdateSession($_SESSION);
        }
        if ($_SESSION['last_request_time'] && time() - $_SESSION['last_request_time'] > $SecondsPerRequest)
        {
            $_SESSION['last_request_time'] = time();
            Session::UpdateSession($_SESSION);
        }
        else
            API::GenerateResponse(429, true);
    }

    private static function ReponseCodeTranslator($CodeID)
    {
        $Codes = [
            200 => ['type' => 'OK', 'detail' => 'The request has succeeded'],
            201 => ['type' => 'Created', 'detail' => 'The request has been fulfilled and resulted in a new resource being created'],
            202 => ['type' => 'Accepted', 'detail' => 'The request has been accepted for processing, but the processing has not been completed'],
            204 => ['type' => 'No Content', 'detail' => 'The server successfully processed the request, but is not returning any content'],
            400 => ['type' => 'Bad Request', 'detail' => 'The request cannot be fulfilled due to bad syntax'],
            401 => ['type' => 'Unauthorized', 'detail' => 'The request requires user authentication'],
            403 => ['type' => 'Forbidden', 'detail' => 'Account Inactive'],
            404 => ['type' => 'Not Found', 'detail' => 'The requested resource could not be found but may be available again in the future'],
            405 => ['type' => 'Method Not Allowed', 'detail' => 'The method specified in the Request-Line is not allowed for the resource identified by the Request-URI'],
            409 => ['type' => 'Conflict', 'detail' => 'The request could not be completed due to a conflict with the current state of the resource'],
            429 => ['type' => 'Too Many Requests', 'detail' => 'The user has sent too many requests in a given amount of time'],
            500 => ['type' => 'Internal Server Error', 'detail' => 'The server encountered an unexpected condition which prevented it from fulfilling the request'],
            501 => ['type' => 'Not Implemented', 'detail' => 'The server does not support the functionality required to fulfill the request'],
            502 => ['type' => 'Bad Gateway', 'detail' => 'The server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request'],
            596 => ['type' => 'Service Not Found', 'detail' => 'The requested service could not be found']
        ];
        return $Codes[$CodeID];
    }
}

?>
