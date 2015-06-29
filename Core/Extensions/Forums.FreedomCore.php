<?php

Class Forums
{
    private static $DBConnection;
    private static $CharConnection;
    private static $WConnection;
    private static $TM;


    public function __construct($VariablesArray)
    {
        Forums::$DBConnection = $VariablesArray[0]::$Connection;
        Forums::$CharConnection = $VariablesArray[0]::$CConnection;
        Forums::$WConnection = $VariablesArray[0]::$WConnection;
        Forums::$TM = $VariablesArray[1];
    }

    public static function GetForums()
    {
        $Statement = Forums::$DBConnection->prepare('SELECT * FROM forums');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        $Forums = array();
        foreach($Result as $ForumCategory)
        {
            $Result[$ArrayIndex]['forum_name'] = Forums::$TM->GetConfigVars($ForumCategory['forum_name']);
            $Result[$ArrayIndex]['forum_description'] = Forums::$TM->GetConfigVars($ForumCategory['forum_description']);
            $Result[$ArrayIndex]['forum_type_name'] = Forums::GetForumTypeTranslated($ForumCategory['forum_type']);
            $ArrayIndex++;
        }
        foreach($Result as $ForumCategory)
            $Forums[$ForumCategory['forum_type']][] = $ForumCategory;
        return $Forums;
    }

    public static function CheckForumExistance($ForumID)
    {
        $Statement = Forums::$DBConnection->prepare('SELECT id FROM forums WHERE id = :forumid');
        $Statement->bindParam(':forumid', $ForumID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if($ForumID == $Result['id'])
            return true;
        else
            return false;
    }

    public static function GetTopics($ForumID)
    {
        global $FCCore;
        $Statement = Forums::$CharConnection->prepare('
            SELECT
                ft.*,
                c.name as postername,
                count(fc.id)-1 as replies,
                fc.post_message as message
            FROM
                '.$FCCore['Database']['database'].'.forum_topics ft, '.$FCCore['Database']['database'].'.users u, '.$FCCore['Database']['database'].'.forum_comments fc, characters c
            WHERE
                    u.username = ft.posted_by
                AND
                    c.guid = u.pinned_character
                AND
                    ft.forum_id = fc.forum_id
                AND
                    ft.id = fc.topic_id
                AND
                    ft.forum_id = :forumid
        ');
        $Statement->bindParam(':forumid', $ForumID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ForumData = Forums::GetForumData($ForumID);

        $ArrayIndex = 0;
        foreach($Result as $Topic)
        {
            $Result[$ArrayIndex]['last_reply_data'] = Forums::GetLastReplyData($Topic['forum_id'], $Topic['id']);
            $ArrayIndex++;
        }


        $ForumData['topics'] = $Result;
        return $ForumData;
    }

    public static function UpdateTopicViews($ForumID, $TopicID)
    {
        $Statement = Forums::$DBConnection->prepare('UPDATE forum_topics SET views = views+1 WHERE id = :topicid AND forum_id = :forumid');
        $Statement->bindParam(':forumid', $ForumID);
        $Statement->bindParam(':topicid', $TopicID);
        $Statement->execute();
        return true;
    }

    public static function GetTopicData($TopicID)
    {
        $Statement = Forums::$DBConnection->prepare('SELECT fc.*, f.forum_name, f.forum_type, ft.topic FROM forum_comments fc, forums f, forum_topics ft WHERE ft.forum_id = fc.forum_id AND fc.forum_id = f.forum_id AND topic_id = :topicid');
        $Statement->bindParam(':topicid', $TopicID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $ArrayIndex = 0;
        foreach($Result as $Topic)
        {
            $Result[$ArrayIndex]['forum_type_name'] = Forums::GetForumTypeTranslated($Topic['forum_id']);
            $Result[$ArrayIndex]['forum_name'] = Forums::$TM->GetConfigVars($Topic['forum_name']);
            $ArrayIndex++;
        }
        $TopicData = array(
            'type' => array(
                'id' => $Result[0]['forum_type'],
                'name' => $Result[0]['forum_type_name'],
            ),
            'category' => array(
                'id' => $Result[0]['forum_id'],
                'name' => $Result[0]['forum_name']
            ),
            'topic' => array(
                'id' => $Result[0]['topic_id'],
                'name' => $Result[0]['topic']
            ),
            'replies' => array()
        );
        $TopicData['replies'] = String::MassUnset($Result, array('forum_type', 'forum_type_name', 'forum_id', 'forum_name', 'topic', 'topic_id'));

        return $TopicData;
    }

    private static function GetLastReplyData($ForumID, $TopicID)
    {
        global $FCCore;
        $Statement = Forums::$CharConnection->prepare('
            SELECT
                post_time,
                c.name as postername
            FROM
                '.$FCCore['Database']['database'].'.forum_comments fc, '.$FCCore['Database']['database'].'.users u, characters c
            WHERE
                    u.username = fc.posted_by
                AND
                    c.guid = u.pinned_character
                AND
                    fc.forum_id = :forumid
                AND
                    fc.topic_id = :topicid
            ORDER BY fc.id DESC
            LIMIT 1
        ');
        $Statement->bindParam(':forumid', $ForumID);
        $Statement->bindParam(':topicid', $TopicID);
        $Statement->execute();
        return $Statement->fetch(PDO::FETCH_ASSOC);
    }
    private static function GetForumData($ForumID)
    {
        $Statement = Forums::$DBConnection->prepare('SELECT forum_type, forum_name FROM forums WHERE id = :forumid');
        $Statement->bindParam(':forumid', $ForumID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return array(
            'forum_id' => $ForumID,
            'forum_type' => $Result['forum_type'],
            'forum_type_name' => Forums::GetForumTypeTranslated($Result['forum_type']),
            'forum_name' => Forums::$TM->GetConfigVars($Result['forum_name']),
            'topics' => array()
        );
    }

    private static function GetForumTypeTranslated($ForumType)
    {
        $ForumTypes = array(
            1 => Forums::$TM->GetConfigVars('Forum_Category_Support_Name'),
            2 => Forums::$TM->GetConfigVars('Forum_Category_Community_Name'),
            3 => Forums::$TM->GetConfigVars('Forum_Category_GameProcess_Name'),
            4 => Forums::$TM->GetConfigVars('Forum_Category_PvP_Name'),
            5 => Forums::$TM->GetConfigVars('Forum_Category_Classes_Name'),
        );

        return $ForumTypes[$ForumType];
    }
}

?>