<?php

Class ErrorHandler
{
    private static $TM;

    public function __construct($TemplateManager)
    {
        ErrorHandler::$TM = $TemplateManager;
    }

    public static function ListenForError($ResponseCode)
    {
        return ErrorHandler::GetErrorByCode($ResponseCode);
    }

    private static function GetErrorByCode($ErrorCode)
    {
        $ErrorCodes = array(
            400 => array('code' => '400', 'error_type' => 'Bad Request', 'error_description' => 'The request cannot be fulfilled due to bad syntax.'),
            403 => array('code' => '403', 'error_type' => 'Forbidden', 'error_description' => 'The server has refused to fulfil your request.'),
            404 => array('code' => '404', 'error_type' => 'Not Found', 'error_description' => 'The page you requested was not found on this server.'),
            405 => array('code' => '405', 'error_type' => 'Method Not Allowed', 'error_description' => 'The method specified in the request is not allowed for the specified resource.'),
            408 => array('code' => '408', 'error_type' => 'Request Timeout', 'error_description' => 'Your browser failed to send a request in the time allowed by the server.'),
            500 => array('code' => '500', 'error_type' => 'Internal Server Error', 'error_description' => 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
            502 => array('code' => '502', 'error_type' => 'Bad Gateway', 'error_description' => 'The server received an invalid response while trying to carry out the request.'),
            504 => array('code' => '504', 'error_type' => 'Gateway Timeout', 'error_description' => 'The upstream server failed to send a request in the time allowed by the server.'),
        );

        return $ErrorCodes[$ErrorCode];

    }
}

?>