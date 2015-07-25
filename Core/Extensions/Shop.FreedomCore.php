<?php

Class Shop
{
    private static $DBConnection;
    private static $AConnection;
    private static $CConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Shop::$DBConnection = $VariablesArray[0]::$Connection;
        Shop::$AConnection = $VariablesArray[0]::$AConnection;
        Shop::$CConnection = $VariablesArray[0]::$CConnection;
        Shop::$TM = $VariablesArray[1];
    }

    public static function GetSidebar()
    {
        $Sidebar  = array();

        $Sidebar['mounts'] = Shop::GetMounts();
        $Sidebar['pets'] = Shop::GetPets();

        return $Sidebar;
    }

    private static function GetMounts()
    {
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.short_code = p.short_code  WHERE item_type = 3');
        $Statement->execute();
        return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function GetPets()
    {

    }

    private static function GetCategoryByID($CategoryID)
    {
        $Categories = array(
            1 => array('name' => '', 'type' => 'World of Warcraft® In-Game: '),
            2 => array('name' => '', 'type' => 'World of Warcraft® In-Game: '),
            3 => array('name' => 'mounts', 'type' => 'World of Warcraft® In-Game Mount: '),
            4 => array('name' => '', 'type' => 'World of Warcraft® In-Game: '),
            5 => array('name' => 'pets', 'type' => 'World of Warcraft® In-Game Pet: '),
        );
        return $Categories[$CategoryID];
    }

    public static function GetItemData($ItemName)
    {
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.short_code = p.short_code  WHERE si.short_code = :itemname');
        $Statement->bindParam(':itemname', $ItemName);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        $CategoryData = Shop::GetCategoryByID($Result['item_type']);
        $Result['category'] = $CategoryData['name'];
        $Result['category_desc'] = $CategoryData['type'];
        return $Result;
    }

    public static function InsertPurchaseData($Item, $Account, $Code)
    {
        $Date = time();
        $Statement = Shop::$DBConnection->prepare('INSERT INTO shop_codes (purchased_item, purchase_code, purchase_date, purchased_for_account) VALUES (:item, :code, :pdate, :account)');
        $Statement->bindParam(':item', $Item);
        $Statement->bindParam(':code', $Code);
        $Statement->bindParam(':pdate', $Date);
        $Statement->bindParam(':account', $Account);
        $Statement->execute();
        return true;
    }

    public static function SendCodeEmail($Email, $HTMLCode)
    {
        $Subject = 'Store Purchase';
        $Headers = 'From: noreply@'.$_SERVER['HTTP_HOST']."\r\n";
        $Headers .= 'X-Mailer: FreedomCore Notification Service';
        $Headers .= 'MIME-Version: 1.0'."\r\n";
        $Headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
        mail($Email, $Subject, $HTMLCode, $Headers);
    }

    public static function GenerateItemCode()
    {
        $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $segment_chars = 7;
        $num_segments = 8;
        $key_string = '';

        for ($i = 0; $i < $num_segments; $i++)
        {
            $segment = '';
            for ($j = 0; $j < $segment_chars; $j++)
                $segment .= $tokens[rand(0, 35)];
            $key_string .= $segment;
            if ($i < ($num_segments - 1))
                $key_string .= '-';
        }

        return $key_string;
    }
}