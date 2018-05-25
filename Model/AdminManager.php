<?php
/**
 * AdminManager
 *
 * All logic associated for all admins actions
 *
 * PHP Version 7.2
 *
 * @category Recipe
 * @package  Recipe
 * @author   Yanis Bendahmane <vegnchill@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 */

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

/**
 * AdminManager Class Doc Comment
 * 
 * @category Class
 * @package  AdminManager
 * @author   Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 * 
 * @since 1.0.0
 */
class AdminManager
{
    /**
     * Add user as logged in
     *
     * @param mixed $sessionId ID of the Session created by the user
     * @param mixed $sessionIP IP of the user
     *
     * @return Void
     */
    public function addLoggedUser($sessionId, $sessionIP): void
    {
        date_default_timezone_set('Europe/Paris');
        $logDate = date('Y-m-d H:i:s');
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "INSERT INTO `logged_users`
            (`id`, `session_id`,
                `log_date`, `session_ip`)
            VALUES (NULL, :session,
                :log_date, :session_ip)"
        );
        $stmt->bindParam(':session', $sessionId);
        $stmt->bindParam(':log_date', $logDate);
        $stmt->bindParam(':session_ip', $sessionIP);
        $result = $stmt->execute();
    }

    /**
     * Remove the user as logged in
     *
     * @param mixed $sessionId ID of the Session created by the user
     *
     * @return Void
     */
    public function removeLoggedUser($sessionId): void
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "DELETE
            FROM `logged_users`
            WHERE `session_id` = :session_id"
        );
        $stmt->bindParam(":session_id", $sessionId);
        $result = $stmt->execute();
    }

    /**
     * Count all sessions contained into the website
     *
     * @return string Substract of the numbers of IP's and the logged users
     */
    public function countNonLoggedUsers()
    {
        $loggedUsers = $this->countAll('totalUsers', 'users');
        return $loggedUsers[0] - count($_SERVER['REMOTE_ADDR']);
    }

    /**
     * Get datas from logged users
     *
     * @return string $result
     */
    public function getLoggedUserDatas()
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT logged_users.*,
                users.pseudo
            FROM logged_users
            LEFT JOIN users
                ON logged_users.session_id = users.id"
        );
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    /**
     * Count all
     * 
     * @param string $name  Refers to the name of the count
     * @param string $table Refers to the associated table
     *
     * @return string $result
     */
    public function countAll(string $name, string $table)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*)
                AS $name
            FROM $table"
        );
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    /**
     * Get user page
     * 
     * @return string $result
     */
    public function getPage()
    {
        $page = $_SERVER['QUERY_STRING'];
        $result = str_replace('action=', '', $page);
        return $result;
    }

    /**
     * Converts date format to another one
     * 
     * @param mixed $data The first date format
     * @param mixed $date The dates we want to format
     * 
     * @return string Converted date format
     */
    public function convertDate($data, $date)
    {
        foreach ($data as $i) {
            $timestamp = strtotime($i[$date]);
            $converted = date("F j, Y, g:i a", $timestamp);
            return $converted;
        }
    }
}