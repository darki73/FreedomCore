<?php

Class Database
{
    public static $Connection;

    public function __construct($Configuration)
    {
        Database::$Connection = new PDO("mysql:host=".$Configuration['host'].";dbname=".$Configuration['database'].";charset=".$Configuration['encoding'], $Configuration['username'], $Configuration['password'], array(PDO::ATTR_PERSISTENT => false));
        Database::$Connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function Query($Query, $Parameters = null){
        try{
            $Statement = Database::$Connection->prepare($Query);
            if($Parameters != null)
                foreach($Parameters as $Parameter)
                    if(count($Parameters) > 2)
                        die("<strong>Received more parameters that expected! Query Aborted!<br />Query:</strong> ".$Query);
                    else
                        $Statement->bindParam($Parameter[0], $Parameter[1]);
            $Statement->execute();
            return true;
        } catch (Exception $e){
            die($e->getMessage());
            return $e->getMessage();
        }
    }
}


$Database = new Database($FCExtractor['Database']);
?>