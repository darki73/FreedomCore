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

    private static function GetUserBasicData($Username, $Password)
    {
        $AuthorizationStatus = AccountAPI::Authorize($Username, $Password, null, true);
        if($AuthorizationStatus)
        {
            $UUP = strtoupper($Username);
            $Statement = parent::$AConnection->prepare('SELECT id, username FROM account WHERE username = :usernamelower OR username = :usernameupper');
            $Statement->bindParam(':usernamelower', $Username);
            $Statement->bindParam(':usernameupper', $UUP);
            $Statement->execute();
            return $Statement->fetch(PDO::FETCH_ASSOC);
        }
        else
        {
            $Result = ['code' => 403, 'response' => 'Incorrect Login Details'];
            return $Result;
        }
    }

    public static function Deauthorize($Username, $Password)
    {

    }

    public static function GetCharacters($Username, $Password, $JSONP)
    {
        $GameData = AccountAPI::GetUserBasicData($Username, $Password);
        if(isset($GameData['code']))
        {
            return parent::Encode($GameData, $JSONP);
        }
        else
        {
            $Statement = parent::$CharConnection->prepare('SELECT name, race, class, gender, level, money FROM characters WHERE account = :accountid');
            $Statement->bindParam(":accountid", $GameData['id']);
            $Statement->execute();
            $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
            $ArrayIndex = 0;
            foreach($Result as $Character)
            {
                $Result[$ArrayIndex]['race'] = Characters::GetRaceByID($Character['race']);
                $Result[$ArrayIndex]['class'] = Characters::GetClassByID($Character['class']);
                $Result[$ArrayIndex]['class'] = Characters::GetClassByID($Character['class']);
                $Result[$ArrayIndex]['money'] = Text::MoneyToCoins($Character['money']);
                $ArrayIndex++;
            }

            return parent::Encode($Result, $JSONP, "characters");
        }
    }
}

?>