<?php

Class Media
{
    public static $DBConnection;
    public static $WConnection;
    public static $CConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        Media::$DBConnection = $VariablesArray[0]::$Connection;
        Media::$WConnection = $VariablesArray[0]::$WConnection;
        Media::$CConnection = $VariablesArray[0]::$CConnection;
        Media::$TM = $VariablesArray[1];
    }

    public static function getAll()
    {
        return [
            'videos' => Media::getMedia(1),
            'screenshots' => Media::getMedia(2),
            'music' => Media::getMedia(3),
            'wallpapers' => Media::getMedia(4)
        ];
    }

    public static function getMediaTypeByID($TypeID)
    {
        $MediaTypes = [
            1   =>  ['name' => 'Videos', 'link' => '/media/videos', 'folder' => '/Uploads/Media/Videos/'],
            2   =>  ['name' => 'Screenshots', 'link' => '/media/screenshots', 'folder' => '/Uploads/Media/Screenshots/'],
            3   =>  ['name' => 'Music', 'link' => '/media/music', 'folder' => '/Uploads/Media/Music/'],
            4   =>  ['name' => 'Wallpapers', 'link' => '/media/wallpapers', 'folder' => '/Uploads/Media/Wallpapers/'],
        ];

        return $MediaTypes[$TypeID];
    }

    public static function getMediaRecord($MediaSlug, $MediaType)
    {
        $Statement = Media::$DBConnection->prepare("SELECT * FROM media WHERE type = :typeid AND slug = :slug");
        $Statement->bindParam(':typeid', $MediaType);
        $Statement->bindParam(':slug', $MediaSlug);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result = $Statement->fetch(PDO::FETCH_ASSOC);
            $Result['data'] = Media::getMediaTypeByID($MediaType);
            return $Result;
        }
    }

    public static function getMediaTypeByName($TypeName)
    {
        $MediaTypes = [
            'videos'        => 1,
            'screenshots'   => 2,
            'music'         => 3,
            'wallpapers'    => 4
        ];

        return $MediaTypes[strtolower($TypeName)];
    }

    public static function getMedia($TypeID)
    {
        $Statement = Media::$DBConnection->prepare('SELECT * FROM media WHERE type = :typeid');
        $Statement->bindParam(':typeid', $TypeID);
        $Statement->execute();
        if(Database::IsEmpty($Statement))
            return false;
        else {
            $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
            return ['count' => count($Result), 'items' => $Result, 'data' => Media::getMediaTypeByID($TypeID)];
        }
    }
}