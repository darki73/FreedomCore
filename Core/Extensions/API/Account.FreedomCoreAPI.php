<?php

Class AccountAPI extends API
{
    public static function Authorize($Username, $Password, $JSONP, $VerifyCredentials = false)
    {
        $AuthorizationStatus = false;
        $StringToHash = $Username.':'.$Password;
        $HashedPassword = Account::HashPassword('sha1', $StringToHash);
        $Statement = parent::$DBConnection->prepare('SELECT username FROM users WHERE username = :username AND password = :password');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $HashedPassword);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
        {
            $Result = ['code' => 403, 'response' => 'Incorrect Login Details'];
            $AuthorizationStatus = false;
        }
        else
        {
            $Result = ['code' => 200, 'response' => 'Successful Login'];
            $AuthorizationStatus = true;
        }

        if($VerifyCredentials)
            if($AuthorizationStatus)
                return true;
            else
                return false;
        else
            return parent::Encode($Result, $JSONP);
    }

    public static function Android($Username, $Password, $JSONP)
    {
        $AuthorizationStatus = AccountAPI::Authorize($Username, $Password, $JSONP, true);
        if($AuthorizationStatus)
            return parent::Encode(AccountAPI::AddAndroidArmoryKey($Username, $Password), $JSONP);
        else
            return parent::Encode(['code' => 403, 'response' => 'Incorrect Login Details'], $JSONP);
    }

    private static function AddAndroidArmoryKey($Username, $Password)
    {
        $ArmoryKey = AccountAPI::VerifyAndroidArmoryKeyExistance($Username, $Password);
        if($ArmoryKey == false)
        {
            $NewKey = substr("abcdefghijklmnopqrstuvwxyz", mt_rand(0 ,25),1).substr(md5(time()),1);
            $StringToHash = $Username.':'.$Password;
            $HashedPassword = Account::HashPassword('sha1', $StringToHash);
            $Statement = parent::$DBConnection->prepare('INSERT INTO api_android_armory (username, password, armory_key) VALUES (:username, :password, :akey)');
            $Statement->bindParam(':username', $Username);
            $Statement->bindParam(':password', $HashedPassword);
            $Statement->bindParam(':akey', $NewKey);
            $Statement->execute();
            $Result = ['code' => 200, 'download_link' => 'http://'.$_SERVER['HTTP_HOST'].'/data/armory/android?username='.$Username.'&password='.$Password.'&downloadkey='.$NewKey];
        }
        else
            $Result = ['code' => 200, 'download_link' => 'http://'.$_SERVER['HTTP_HOST'].'/data/armory/android?username='.$Username.'&password='.$Password.'&downloadkey='.$ArmoryKey];

        return $Result;
    }

    private static function VerifyAndroidArmoryKeyExistance($Username, $Password)
    {
        $StringToHash = $Username.':'.$Password;
        $HashedPassword = Account::HashPassword('sha1', $StringToHash);
        $Statement = parent::$DBConnection->prepare('SELECT armory_key FROM api_android_armory WHERE username = :username AND password = :password');
        $Statement->bindParam(':username', $Username);
        $Statement->bindParam(':password', $HashedPassword);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Result['armory_key'];
    }

    public static function Deauthorize($Username, $Password)
    {

    }
}

?>