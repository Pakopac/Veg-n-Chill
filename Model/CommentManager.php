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

        $stmt = $pdo->prepare("INSERT INTO comments (id, post_id, message) VALUES (NULL, :id, :comment)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();
    }
    public function getCommentsByPost($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM comments WHERE post_id = :id");
        $result->execute([':id' => $id]);
        $post = $result->fetchall();

        return $post;
    }
}