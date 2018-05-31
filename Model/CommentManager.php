<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class CommentManager
{
    public function addComment($contentComment, $idNews){
        var_dump($_SESSION['username']);
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("INSERT INTO comments (id, post_id, message, `type`, `user`, `date`) VALUES (NULL, :idNews, :content, 'comment', :user, :date)");
        $stmt->bindParam(':content', $contentComment);
        $stmt->bindParam(':idNews', $idNews);
        $stmt->bindParam(':user', $_SESSION['username']);
        $stmt->bindParam(':date', date('y-m-d'));

        $stmt->execute();
    }
    public function getCommentsByPost()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM comments WHERE post_id = 1 AND `type` = 'comment'");
        $result -> execute();
        $comment = $result->fetchall();

        return $comment;
    }
    public function getReplyByComments()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM comments WHERE `type` = 'reply'");
        $result -> execute();
        $reply = $result->fetchAll();

        return $reply;
    }

    public function showComment($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;
    }

    public function editComment($content, $id){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE comments SET message = :content WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":content", $content);
        $result = $stmt->execute();

        return $result;
    }

    public function deleteComment($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id ");
        $stmt->execute([':id' => $id]);
    }
}