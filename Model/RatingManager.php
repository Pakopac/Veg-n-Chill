<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class RatingManager
{

    public function updateState($action, $id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        if($action === "likes"){
            $stmt = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = :id");
        } else if($action === "dislikes"){
            $stmt = $pdo->prepare("UPDATE posts SET dislikes = dislikes + 1 WHERE id = :id");
        }
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();

        return $result;
    }

    public function rateArticle($action, $id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $ratings = $_POST['rating'];

        if($ratings === "likes"){
            $this->updateState($ratings, $_GET['id']);

            $status = [
                "status" => "ok",
                "message" => "Thanks for liking !"
            ];
            return json_encode($status);
        } else if ($ratings === "dislikes"){
            $this->updateState($ratings, $_GET['id']);

            $status = [
                "status" => "ok",
                "message" => "Sad :("
            ];
            return json_encode($status);
        } else {
            $status = [
                "status" => "failed",
                "message" => "Whoops, there is something inconveniant that arrived"
            ];
            return json_encode($status);
        }
    }
}