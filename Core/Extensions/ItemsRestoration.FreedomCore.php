<?php

Class ItemsRestoration extends Items
{
    public static function GetCharactersDeletedItems($CharacterGUID)
    {
        $Statement = parent::$CConnection->prepare('SELECT * FROM item_restoration WHERE character_guid = :guid');
        $Statement->bindParam(':guid', $CharacterGUID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Index = 0;
            $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
            foreach($Result as $Item)
            {
                $Result[$Index]['price'] = Text::MoneyToCoins($Item['item_price']);
                $Result[$Index]['data'] = parent::GetItemInfo($Item['item_id']);
                $Index++;
            }

            return $Result;
        }
    }
}

?>