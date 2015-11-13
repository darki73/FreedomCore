<?php

Class FriendsAPI extends API
{
    public static function getCharacterFriends($AccountName)
    {
        $AccountID = FriendsAPI::getAccountID($AccountName);
        if($AccountID){
            $AccountCharacters = FriendsAPI::getAccountCharacters($AccountID);
            if($AccountCharacters){
                    $FriendsArray = [];
                    foreach($AccountCharacters as $Character)
                        $FriendsArray[] = FriendsAPI::getFriendsRelationForCharacter($Character['guid']);
                    $FriendsArray = FriendsAPI::flattenFriendsArray($FriendsArray);

                    $CharOwners = [];
                    foreach($FriendsArray as $Character)
                        $CharOwners[] = FriendsAPI::getAccountByCharacterID($Character);

                    $CharOwners = array_unique($CharOwners);
                    $OwnersData = [];
                    $OwnerCharacters = [];
                    foreach($CharOwners as $Owner){
                        $OwnersData[$Owner] = [
                            'game' => FriendsAPI::getGameAccountData($Owner),
                            'site' => FriendsAPI::getSiteAccountData($Owner),
                        ];
                        $OwnerCharacters[$Owner] = FriendsAPI::getAccountCharacters($Owner);
                    }
                foreach($OwnerCharacters as $Key=>$Value)
                    for($j = 0; $j < count($Value); $j++)
                        if($OwnerCharacters[$Key][$j]['online'] == 1)
                            $OwnersData[$OwnerCharacters[$Key][$j]['owner']]['game']['online_on'] = $OwnerCharacters[$Key][$j];
                $ArrayIndex = 0;
                $FinalArray = [];
                foreach($OwnersData as $Friend)
                    $FinalArray[] = $Friend;
                foreach($FinalArray as $Owner){
                    if(!isset($Owner['game']['online_on']))
                        $FinalArray[$ArrayIndex]['game']['online_on'] = ['guid' => "", 'name' => "", 'level' => "", 'map' => "", 'zone' => "", 'online' => "", 'owner' => "", 'location' => ""];
                    $ArrayIndex++;
                }
                if(empty($FinalArray))
                    echo Text::SimpleJson(1404, "status", "User has no friends");
                else
                    return Text::toJson(['code' => '1200', 'status' => 'Successful request', 'friends' => $FinalArray], ['JSON_UNESCAPED_UNICODE']);
            } else {
                return Text::SimpleJson(1404, 'status', sprintf("Account with id %i has no characters", $AccountID));
            }
        } else {
            return Text::SimpleJson(1404, 'status', sprintf("Account %s could not be found!", $AccountName));
        }
    }

    private static function getAccountID($AccountName)
    {
        $Username = strtoupper($AccountName);
        $Statement = parent::$AConnection->prepare("SELECT id FROM account WHERE username = :username");
        $Statement->bindParam(':username', $Username);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetch(PDO::FETCH_ASSOC)['id'];
    }

    private static function getGameAccountData($AccountID)
    {
        $Statement = parent::$AConnection->prepare('SELECT username, email, last_login, online FROM account WHERE id = :aid');
        $Statement->bindParam(':aid', $AccountID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function getSiteAccountData($AccountID)
    {
        $Statement = parent::$DBConnection->prepare('SELECT username, email, freedomtag_name, freedomtag_id FROM users WHERE id = :aid');
        $Statement->bindParam(':aid', $AccountID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetch(PDO::FETCH_ASSOC);
    }

    private static function getAccountCharacters($AccountID)
    {
        $Statement = parent::$CharConnection->prepare('SELECT guid, name, level, map, zone, online FROM characters WHERE account = :aid');
        $Statement->bindParam(':aid', $AccountID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
            $ArrayIndex = 0;
            foreach($Result as $Character) {
                $Result[$ArrayIndex]['owner'] = $AccountID;
                $Result[$ArrayIndex]['location'] = FriendsAPI::getCharacterLocation($Character);
                $ArrayIndex++;
            }
            return $Result;
        }
    }

    private static function getCharacterLocation($Character)
    {
        $Statement = parent::$DBConnection->prepare('SELECT name_loc0 FROM freedomcore_zones WHERE mapID = :map AND areatableID = :zone');
        $Statement->bindParam(':map', $Character['map']);
        $Statement->bindParam(':zone', $Character['zone']);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return "Unknown";
        else
            return $Statement->fetch(PDO::FETCH_ASSOC)['name_loc0'];
    }

    private static function getFriendsRelationForCharacter($CharacterID)
    {
        $Statement = parent::$CharConnection->prepare('SELECT friend FROM character_social WHERE guid = :charguid');
        $Statement->bindParam(':charguid', $CharacterID);
        $Statement->execute();
        return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function getAccountByCharacterID($CharacterID)
    {
        $Statement = parent::$CharConnection->prepare('SELECT account FROM characters WHERE guid = :charid');
        $Statement->bindParam(':charid', $CharacterID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else
            return $Statement->fetch(PDO::FETCH_ASSOC)['account'];
    }

    private static function flattenFriendsArray($FriendsArray)
    {
        $FinalArray = [];
        $FriendsArray = call_user_func_array('array_merge', $FriendsArray);
        foreach($FriendsArray as $Friend)
            if(!in_array($Friend['friend'], $FinalArray))
                $FinalArray[] = $Friend['friend'];

        return $FinalArray;
    }
}

?>