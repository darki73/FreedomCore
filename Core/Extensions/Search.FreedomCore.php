<?php

Class Search
{
    private static $DBConnection;
    private static $AConnection;
    private static $CConnection;
    private static $WConnection;
    private static $TM;

    public function __construct($VariablesArray)
    {
        Search::$DBConnection = $VariablesArray[0]::$Connection;
        Search::$AConnection = $VariablesArray[0]::$AConnection;
        Search::$CConnection = $VariablesArray[0]::$CConnection;
        Search::$WConnection = $VariablesArray[0]::$WConnection;
        Search::$TM = $VariablesArray[1];
    }

    public static function PerformSearch($Query)
    {
        $FoundTotal = 0;
        $GuildsFound = 0;
        $CharactersFound = 0;
        $ArticlesFound = 0;
        $ItemsFound = 0;

        $GuildsArray = array();
        $CharactersArray = array();
        $ArticlesArray = array();
        $ItemsArray = array();

        $SearchForGuild = Search::SearchForGuild($Query);
        $SearchForCharacter = Search::SearchForCharacter($Query);
        $SearchForArticles = Search::SearchForArticles($Query);
        $SearchForItems = Search::SearchForItems($Query);
        if(!empty($SearchForGuild))
        {
            $FoundTotal = $FoundTotal + count($SearchForGuild);
            $GuildsFound = count($SearchForGuild);
            foreach($SearchForGuild as $Guild)
            {
                $GuildData = Characters::GetGuildData($Guild['name']);
                $GuildsArray[] = array(
                    'guid' => $GuildData['guildid'],
                    'name' => $GuildData['name'],
                    'side' => $GuildData['side']['name'],
                    'side_translation' => $GuildData['side']['translation'],
                    'BackgroundColor' => $GuildData['BackgroundColor'],
                    'BorderStyle' => $GuildData['BorderStyle'],
                    'BorderColor' => $GuildData['BorderColor'],
                    'EmblemStyle' => $GuildData['EmblemStyle'],
                    'EmblemColor' => $GuildData['EmblemColor']
                );
            }
        }
        if(!empty($SearchForCharacter))
        {
            $FoundTotal = $FoundTotal + count($SearchForCharacter);
            $CharactersFound = count($SearchForCharacter);
            foreach($SearchForCharacter as $Character)
            {
                $CharacterData = Characters::GetCharacterData($Character['name']);
                $CharactersArray[] = array(
                    'name' => $CharacterData['name'],
                    'class' => $CharacterData['class'],
                    'race' => $CharacterData['race'],
                    'gender' => $CharacterData['gender'],
                    'class_name' => $CharacterData['class_data']['translation'],
                    'race_name' => $CharacterData['race_data']['translation'],
                    'level' => $CharacterData['level'],
                    'online' => $CharacterData['online'],
                    'guild' => $CharacterData['guild_name'],
                    'side' => $CharacterData['side_id'],
                    'sidetranslation' => $CharacterData['side_translation']
                );
            }
        }

        if(!empty($SearchForArticles))
        {
            $FoundTotal = $FoundTotal + count($SearchForArticles);
            $ArticlesFound = count($SearchForArticles);
            foreach($SearchForArticles as $Article)
            {
                $ArticleData = News::GetArticle($Article['id']);
                $ArticlesArray[] = array(
                    'id' => $ArticleData['id'],
                    'miniature' => $ArticleData['post_miniature'],
                    'title' => $ArticleData['title'],
                    'comments' => $ArticleData['comments_count'],
                );
            }
        }

        if(!empty($SearchForItems))
        {
            $FoundTotal = $FoundTotal + count($SearchForItems);
            $ItemsFound = count($SearchForItems);
            foreach($SearchForItems as $Item)
            {
                $ItemsArray[] = array(
                    'id' => $Item['entry'],
                    'name' => $Item['name'],
                    'Quality' => $Item['Quality'],
                    'ItemLevel' => $Item['ItemLevel'],
                    'RequiredLevel' => $Item['RequiredLevel'],
                    'Class' => $Item['class']['translation'],
                    'Subclass' => $Item['subclass']['translation'],
                    'icon' => $Item['icon']
                );

            }
        }

        $SearchResult = array(
            'foundtotal' => $FoundTotal,
            'guildsfound' => $GuildsFound,
            'charactersfound' => $CharactersFound,
            'articlesfound' => $ArticlesFound,
            'itemsfound' => $ItemsFound,
            'guilds' => $GuildsArray,
            'characters' => $CharactersArray,
            'articles' => $ArticlesArray,
            'items' => $ItemsArray
        );
        return $SearchResult;
    }

    private static function SearchForGuild($GuildName)
    {
        $Statement = Search::$CConnection->prepare("SELECT * FROM guild WHERE name LIKE ?");
        $Statement->execute(array('%'.$GuildName.'%'));
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
            return $Result;
    }

    private static function SearchForCharacter($CharacterName)
    {
        $Statement = Search::$CConnection->prepare("SELECT name FROM characters WHERE name LIKE ?");
        $Statement->execute(array('%'.$CharacterName.'%'));
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
            return $Result;
    }

    private static function SearchForArticles($ArticleTitle)
    {
        $Statement = Search::$DBConnection->prepare("SELECT id FROM news WHERE title LIKE ? OR full_description LIKE ?");
        $Statement->execute(array('%'.$ArticleTitle.'%', '%'.$ArticleTitle.'%'));
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
            return $Result;
    }

    private static function SearchForItems($ItemName)
    {
        global $FCCore;
        $Statement = Search::$WConnection->prepare('
          SELECT
          *,
          LOWER(fi.iconname) as icon
            FROM
              item_template it
            LEFT JOIN '.$FCCore['Database']['database']	.'.freedomcore_icons fi ON
                it.displayid = fi.id
            WHERE name LIKE ?
            ORDER BY it.ItemLevel DESC');
        $Statement->execute(array('%'.$ItemName.'%'));
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        if(empty($Result))
            return false;
        else
        {
            $Index = 0;
            foreach($Result as $Item)
            {
                $Result[$Index]['subclass'] = Items::ItemSubClass($Item['class'], $Item['subclass']);
                $Result[$Index]['class'] = Items::ItemClass($Item['class']);
                $Index++;
            }
            return $Result;
        }
    }
}

?>