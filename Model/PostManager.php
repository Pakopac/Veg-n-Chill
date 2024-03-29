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

    public function addPost($title, $desc, $img, $content, $authorID, $type): void
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            "INSERT INTO posts 
            (id, title, description,
            img, content, author_id,
            type, likes)
            VALUES 
            (NULL, :title, :desc,
            :img, :content, :author,
            :type, 0)"
        );
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':desc', $desc);
        $stmt->bindParam(':img', $img);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':author', $authorID);
        $stmt->bindParam(':type', $type);

        $stmt->execute();
    }

    public function getPostById($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $result = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $result->execute([':id' => $id]);
        $post = $result->fetch();

        $type = $this->getArticleType($id);
        $datas = [
            "post" => $post,
            "type" => $type
        ];

        return $datas;
    }

    public function deletePost($id): void
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

    public function editArticle($desc, $content, $id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            "UPDATE posts 
            SET content = :content, 
            description = :desc 
            WHERE id = :id"
        );
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":desc", $desc);
        $stmt->bindParam(":content", $content);
        $result = $stmt->execute();

        return $result;
    }

    public function searchPostByName($query)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            "SELECT *
            FROM posts
            WHERE title LIKE ?"
        );
        $params = array("%$query%");
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        return $result;
    }
    
    public static function getArticleType($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            "SELECT type
            FROM posts
            WHERE id = :id"
        );
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;
    }
}