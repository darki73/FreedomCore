<?php

Class Achievements
{
    private static $DBConnection;
    private static $CharConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Achievements::$DBConnection = $VariablesArray[0]::$Connection;
        Achievements::$CharConnection = $VariablesArray[0]::$CConnection;
        Achievements::$TM = $VariablesArray[1];
    }

    public static function GetCategories()
    {
        $Categories = Achievements::GetRootCategories();
        $Index = 0;
        foreach($Categories as $Category)
        {
            $Categories[$Index]['achievements_in_category'] = 0;
            $Categories[$Index]['points_for_category'] = 0;

            $SubCategories = Achievements::GetSubCategories($Category['id']);
            $CategoryData = Achievements::GetDataForCategory($Category['id']);
            $Categories[$Index]['achievements_in_category'] = $Categories[$Index]['achievements_in_category'] + $CategoryData['amount'];
            $Categories[$Index]['points_for_category'] = $Categories[$Index]['points_for_category'] + $CategoryData['maxscore'];
            if(!empty($SubCategories))
            {
                $Categories[$Index]['subcategories'] = $SubCategories;
                $InnerIndex = 0;
                foreach($SubCategories as $SubCategory)
                {
                    $SubSubCategories = Achievements::GetSubCategories($SubCategory['id']);
                    $SubCategoryData = Achievements::GetDataForCategory($SubCategory['id']);
                    $Categories[$Index]['achievements_in_category'] = $Categories[$Index]['achievements_in_category'] + $SubCategoryData['amount'];
                    $Categories[$Index]['points_for_category'] = $Categories[$Index]['points_for_category'] + $SubCategoryData['maxscore'];
                    if(!empty($LastCategories))
                        $Categories[$Index]['subcategories'][$InnerIndex]['lastcategory'] = $LastCategories;
                    $InnerIndex++;
                }
            }
            $Index++;
        }
        //unset($Categories[0]); // we dont need stats.... yet....
        return $Categories;
    }

    public static function GetAchievementsInCategory($CategoryID)
    {
        $Statement = Achievements::$DBConnection->prepare('SELECT
            fa.id,
            name_loc0 as name,
            description_loc0 as description,
            category,
            points,
            LOWER(si.iconname) as iconname
        FROM
            freedomcore_achievement fa
        LEFT JOIN freedomcore_spellicons si ON
            fa.icon = si.id
        WHERE
            category = :category');
        $Statement->bindParam(':category', $CategoryID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function GetAchievement($ID)
    {
        $Statement = Achievements::$DBConnection->prepare('
        SELECT
            fa.id,
            name_loc0 as name,
            description_loc0 as description,
            category,
            points,
            LOWER(si.iconname) as iconname
        FROM
            freedomcore_achievement fa
        LEFT JOIN freedomcore_spellicons si ON
            fa.icon = si.id
        WHERE
            fa.id = :id
        ');
        $Statement->bindParam(':id', $ID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function GetRootCategories()
    {
        $Order = [92, 96, 97, 95, 168, 169, 201, 155, 81, 1];
        $Statement = Achievements::$DBConnection->prepare('SELECT id, name_loc0 as name FROM freedomcore_achievementcategory WHERE parentAchievement = "-1" ORDER BY id ASC');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $OrderedArray = [];
        foreach($Order as $Position)
            foreach($Result as $Category)
                if($Position == $Category['id'])
                    $OrderedArray[] = $Category;

        return $OrderedArray;
    }

    public static function GetAchievementsStats()
    {
        $Statement = Achievements::$DBConnection->prepare('SELECT sum(points) points_maximum, count(id) as achievements_amount FROM freedomcore_achievement');
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

    private static function GetDataForCategory($CategoryID)
    {
        $Statement = Achievements::$DBConnection->prepare('SELECT count(id) as amount, sum(points) as maxscore FROM freedomcore_achievement WHERE category = :categoryid');
        $Statement->bindParam(':categoryid', $CategoryID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result;
    }

    private static function GetSubCategories($RootCategoryID)
    {
        $Statement = Achievements::$DBConnection->prepare('SELECT id, name_loc0 as name FROM freedomcore_achievementcategory WHERE parentAchievement = :parent ORDER BY id ASC');
        $Statement->bindParam(':parent', $RootCategoryID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
            return $Result;
    }
}

?>