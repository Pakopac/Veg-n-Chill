<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class RatingManager
{
    public function getTotal($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT total_votes FROM posts");
        $result = $stmt->fetch();
        return $result;
    }

    public function getLastAvg($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT ratings FROM posts WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result;
    }

    public function updateTotal($id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE posts SET total_votes = total_votes + 1 WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $result = $stmt->execute();
        
        return $result;
    }

    public function rateArticle($action, $id)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $total = $this->getTotal($id);

        if($total['total_votes'] == 0){
            $stmt = $pdo->prepare("UPDATE posts SET ratings = :action WHERE id = :id");
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $this->updateTotal($total, $id);
            
            $results = [
                'status' => 'Ok',
                'message' => 'Everything is okay !'
            ];
            return json_encode($results);
        } else {
            $total = $this->getTotal($id);
            $lastAvg = $this->getLastAvg($id);
            
            $newRate = ((intval($lastAvg) * intval($total)) + intval($action)) / (intval($total) + 1);

            $stmt = $pdo->prepare('UPDATE posts SET ratings = :newRate WHERE id = :id');
            $stmt->bindParam(':newRate', $newRate);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $data = $this->updateTotal($id);

            $results = [
                'status' => 'Ok',
                'message' => 'Everything is okay !'
            ];
            return json_encode($results);
        }
    }
}