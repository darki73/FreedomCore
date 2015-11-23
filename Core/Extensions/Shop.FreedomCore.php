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
        $Sidebar['items'] = Shop::GetItems();

        return $Sidebar;
    }

    public static function GetAllItemsForAdministrator()
    {
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.short_code = p.short_code');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Iteration = 0;
        foreach($Result as $Item){
            $Result[$Iteration]['category_name'] = str_replace(':', '', Shop::GetCategoryByID($Item['item_type']))['type'];
            $Iteration++;
        }

        return $Result;
    }

    public static function GetItem($ItemID){
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.id = p.id WHERE si.id = :itemid');
        $Statement->bindParam(':itemid', $ItemID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result['category'] = Shop::GetCategoryByID($Result['item_type']);
            $Result['backgrounds'] = Shop::AvailableBackgrounds();
            return $Result;
        }
    }

    public static function UpdateItem($Request){
        $Statement = Shop::$DBConnection->prepare('UPDATE shop_items si, prices p SET si.item_name = :name, si.item_background = :background, si.item_background_color = :color, p.price = :price WHERE si.short_code = p.short_code AND si.id = :id');
        $Statement->bindParam(':name', $Request['item_name']);
        $Statement->bindParam(':background', $Request['background_template']);
        $Statement->bindParam(':color', $Request['color_template']);
        $Statement->bindParam(':price', $Request['item_price']);
        $Statement->bindParam(':id', $Request['item_id']);
        $Statement->execute();
    }

    public static function DeleteItem($ItemID){
        $ShopStatement = Shop::$DBConnection->prepare('DELETE FROM shop_items WHERE short_code = :itemid');
        $ShopStatement->bindParam(':itemid', $ItemID);
        $ShopStatement->execute();
        $PriceStatement = Shop::$DBConnection->prepare('DELETE FROM prices WHERE short_code = :itemid');
        $PriceStatement->bindParam(':itemid', $ItemID);
        $PriceStatement->execute();
    }

    private static function AvailableBackgrounds()
    {
        $Backgrounds = [
            'images_folder' => '/Templates/FreedomCore/images/shop/items/',
            'sizes'         => [
                1024,
                1280,
                1920
            ],
            'types'         => [
                'classic' => [
                    'name'  => 'Classic',
                    'color' => '#39100d',
                    'image' => 'item_before_lk'
                ],
                'tbc' => [
                    'name'  => 'The Burning Crusade',
                    'color' => '#39100d',
                    'image' => 'item_before_lk'
                ],
                'lich' => [
                    'name'  => 'Wrath of the Lich King',
                    'color' => '#050933',
                    'image' => 'item_lk'
                ],
                'cata' => [
                    'name'  => 'Cataclysm',
                    'color' => '#240a08',
                    'image' => 'item_after_lk'
                ],
                'mop' => [
                    'name'  => 'Mists of Pandaria',
                    'color' => '#240a08',
                    'image' => 'item_after_lk'
                ],
                'wod' => [
                    'name'  => 'Warlords of Draenor',
                    'color' => '#240a08',
                    'image' => 'item_after_lk'
                ]
            ]
        ];

        return $Backgrounds;
    }

    public static function AddItem($Data){
        $ItemID = $Data['item_id'];
        $ItemC = $Data['item_class'];
        $ItemSC = $Data['item_subclass'];
        $ItemP = $Data['item_price'];
        $ItemName = $Data['item_name_in'];

        $ShortCode = Shop::GenerateShortCode($ItemName);
        $Category = "";
        $PageSettings = Shop::ItemPageSettings($ItemID);

        if($ItemC == '15'){
            if($ItemSC == '2')
                $Category = 5;
            elseif($ItemSC == '5')
                $Category = 3;
        } else if($ItemC == '0' || $ItemC == '1' || $ItemC == '2' || $ItemC == '4' || $ItemC == '9')
            $Category = 2;

        $ShopStatement = Shop::$DBConnection->prepare('INSERT INTO shop_items (short_code, item_id, item_name, item_type, item_shop_icon, item_background, item_background_color) VALUES (:one, :two, :three, :four, :five, :six, :seven)');
        $ShopStatement->bindParam(':one', $ShortCode);
        $ShopStatement->bindParam(':two', $ItemID);
        $ShopStatement->bindParam(':three', $ItemName);
        $ShopStatement->bindParam(':four', $Category);
        $ShopStatement->bindParam(':five', $ShortCode);
        $ShopStatement->bindParam(':six', $PageSettings[0]);
        $ShopStatement->bindParam(':seven', $PageSettings[1]);
        $ShopStatement->execute();

        $PricesStatement = Shop::$DBConnection->prepare('INSERT INTO prices (type, short_code, price) VALUES (:one, :two, :three)');
        $PricesStatement->bindParam(':one', $Category);
        $PricesStatement->bindParam(':two', $ShortCode);
        $PricesStatement->bindParam(':three', $ItemP);
        $PricesStatement->execute();
    }

    private static function GenerateShortCode($ItemName)
    {
        $Code = str_replace("'", '', $ItemName);
        $Code = str_replace(" ", '-', $Code);
        $Code = str_replace(",", '', $Code);
        return strtolower($Code);
    }

    private static function ItemPageSettings($ItemID) {
        // Well it is so hacky, that i dont even want to present it as a feature.
        // Unless ill be able to somehow distinguish items by expansions, this
        // is a super hacky way of doing "MATH" behind expansion = item id
        $LKItemImage = "item_lk";
        $LKItemColor = "#050933";
        $LKItem = [$LKItemImage, $LKItemColor];

        $CataItemImage = "item_after_lk";
        $CataItemColor = "#240a08";
        $CataItem = [$CataItemImage, $CataItemColor];

        if($ItemID <= 59000)
            return $LKItem;
        else
            return $CataItem;
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

    private static function GetItems()
    {
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.short_code = p.short_code  WHERE item_type = 2');
        $Statement->execute();
        return $Statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function GetCategoryByID($CategoryID)
    {
        $Categories = array(
            1 => array('name' => 'services', 'type' => 'World of Warcraft® In-Game Service: '),
            2 => array('name' => 'items', 'type' => 'World of Warcraft® In-Game Item: '),
            3 => array('name' => 'mounts', 'type' => 'World of Warcraft® In-Game Mount: '),
            4 => array('name' => 'wallet', 'type' => 'World of Warcraft® Wallet: '),
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

    public static function CodeActivated($Account, $Code)
    {
        $Statement = Shop::$DBConnection->prepare('SELECT * FROM shop_codes WHERE purchased_for_account = :account AND purchase_code = :code');
        $Statement->bindParam(':account', $Account);
        $Statement->bindParam(':code', $Code);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if ($Statement->rowCount() > 0)
            return $Result;
        else
            return false;
    }

    public static function ChangeActivationState($Account, $Code)
    {
        $Statement = Shop::$DBConnection->prepare('UPDATE shop_codes SET code_activated = 1 WHERE purchased_for_account = :account AND purchase_code = :code');
        $Statement->bindParam(':account', $Account);
        $Statement->bindParam(':code', $Code);
        $Statement->execute();
    }

    public static function GetAdministratorShopData()
    {
        $ShopData = ['count' => 0, 'total' => 0, 'recentorder' => '', 'items' => []];
        $Statement = Shop::$DBConnection->prepare('SELECT si.*, p.price FROM shop_items si LEFT JOIN prices p ON si.short_code = p.short_code');
        $Statement->execute();
        $Result = $Statement->fetchAll();
        $TotalAmount = 0;
        foreach($Result as $Item)
        {
            $ShopData['items'][] = $Item;
            $TotalAmount = $TotalAmount + $Item['price'];
        }
        $ShopData['count'] = count($Result);
        $ShopData['total'] = $TotalAmount;
        return $ShopData;
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