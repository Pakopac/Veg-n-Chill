<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class CommentManager
{
    public function addComment($id, $comment){
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        if(isset($_GET['idComment'])    ) {
            $stmt = $pdo->prepare("INSERT INTO comments (id, post_id, message, `type`) VALUES (NULL, :id, :comment, 'reply')");
        }
        else{
            $stmt = $pdo->prepare("INSERT INTO comments (id, post_id, message, `type`) VALUES (NULL, :id, :comment, 'comment')");
        }
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();
    }
    public function getCommentsByPost($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM comments WHERE post_id = :id AND `type` = 'comment'");
        $result->execute([':id' => $id]);
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
}