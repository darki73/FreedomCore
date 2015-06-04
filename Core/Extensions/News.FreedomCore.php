<?php

Class News
{
    public static $DBConnection;
    public static $TM;

    public function __construct($VariablesArray)
    {
        News::$DBConnection = $VariablesArray[0]::$Connection;
        News::$TM = $VariablesArray[1];
    }

    public static function GetAllNews()
    {
        $Statement = News::$DBConnection->prepare('SELECT * FROM news ORDER BY post_date DESC');
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        $Index = 0;
        foreach($Result as $Article)
        {
            $CountStatement = News::$DBConnection->prepare('SELECT count(id) as count FROM comments WHERE article_id = :aid');
            $CountStatement->bindParam(':aid', $Article['id']);
            $CountStatement->execute();
            $CountResult = $CountStatement->fetch(PDO::FETCH_ASSOC);
            $Result[$Index]['comments_count'] = $CountResult['count'];
            $Index++;
        }
        return $Result;
    }

    public static function GetArticle($ArticleID)
    {
        $Statement = News::$DBConnection->prepare('SELECT *, (SELECT count(id) FROM comments WHERE article_id = :articleid) as comments_count FROM news WHERE id = :articleid');
        $Statement->bindParam(':articleid', $ArticleID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        if(String::IsNull($Result['id']))
            return null;
        else
            return $Result;
    }

    public static function GetComments($ArticleID)
    {
        $Statement = News::$DBConnection->prepare('SELECT * FROM comments WHERE article_id = :articleid ORDER BY id DESC');
        $Statement->bindParam(':articleid', $ArticleID);
        $Statement->execute();
        $Result = $Statement->fetchAll(PDO::FETCH_ASSOC);
        return $Result;
    }

    public static function AddComment($ArticleID, $PostedBy, $CommentText)
    {
        $DateAndTime = date('Y-m-d H:i:s');
        $Statement = News::$DBConnection->prepare('INSERT INTO comments (article_id, comment_by, comment_text, comment_date) VALUES (:articleid, :poster, :comment, :dt)');
        $Statement->bindParam(':articleid', $ArticleID);
        $Statement->bindParam(':poster', $PostedBy);
        $Statement->bindParam(':comment', $CommentText);
        $Statement->bindParam(':dt', $DateAndTime);
        $Statement->execute();
    }

    public static function GetLastCommentID($ArticleID)
    {
        $Statement = News::$DBConnection->prepare('SELECT max(id) as maxcomment FROM comments WHERE article_id = :articleid');
        $Statement->bindParam(':articleid', $ArticleID);
        $Statement->execute();
        $Result = $Statement->fetch(PDO::FETCH_ASSOC);
        return $Result['maxcomment'];
    }
}

?>