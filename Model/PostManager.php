<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class PostManager
{
    public function getAllPosts($order = 'DESC')
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->query("SELECT * FROM posts ORDER BY id ".$order);
        $posts = $result->fetchAll();

        return $posts;
    }

    public function addPost($title, $content)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO posts (id, title, content) VALUES (NULL, :title, :content)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        $stmt->execute();
    }

    public function getPostById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $result->execute([':id' => $id]);
        $post = $result->fetch();

        return $post;
    }

    public function deletePost($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id ");
        $stmt->execute([':id' => $id]);
    }

    public function showArticle($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;
    }

    public function editArticle($content, $id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE posts SET content = :content WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":content", $content);
        $result = $stmt->execute();

        return $result;
    }
}