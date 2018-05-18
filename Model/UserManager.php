<?php

namespace Model;
use Cool\BaseController;
use Cool\DBManager;

class UserManager
{
    /**
     * Register a user
     *
     * @param mixed $firstname      Firstname entered by the user on the form
     * @param mixed $lastname       Lastname entered by the user on the form
     * @param mixed $username       Username entered by the user on the form
     * @param mixed $password       Password entered by the user on the form
     * @param mixed $repeatPassword Password entered by the user on the form
     *                              check if it's the same as $password
     * @param mixed $email          Email entered by the user on the form
     *
     * @return Array 
     * => $errors | If errors are found, then the process fail and send AJAX datas
     * => true | If no errors found, then the user is registred into the database
     */
    public function registerUser(
        $firstname, $lastname, $username, 
        $password, $repeatPassword, $email
    ) {
        $errors = [];
        $usernameExists = $this->usernameExists($username);
        $emailExists = $this->emailExists($email);
        if (strlen($firstname) < 2) {
            $errors = [
                "status" => "failed",
                "message" => 'Firstname too short'
            ];
        }
        if (strlen($lastname) < 2) {
            $errors = [
                "status" => "failed",
                "message" => "Lastname too short"
            ];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors = [
                "status" => "failed",
                "message" => "Invalid Email"
            ];
        }
        if (strlen($username) < 4) {
            $errors = [
                "status" => "failed",
                "message" => "Username too short"
            ];
        }
        if (strlen($password) < 4) {
            $errors = [
                "status" => "failed",
                "message" => "Password too short"
            ];
        }
        if ($password !== $repeatPassword) {
            $errors = [
                "status" => "failed",
                "message" => "Password must be identicals"
            ];
        }
        if ($usernameExists) {
            $errors = [
                "status" => "failed",
                "message" => "Username already exists"
            ];
        }
        if ($emailExists) {
            $errors = [
                "status" => "failed",
                "message" => "Email already used"
            ];
        }
        if (empty($errors)) {
            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $hashedPwd = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare(
                "INSERT INTO `users` 
                (`id`, `pseudo`, `password`, `firstname`, 
                    `lastname`, `email`, `rank_id`)
                VALUES (NULL, :username, :password, 
                    :firstname, :lastname, :email, 1)"
            );
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPwd);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $errors = true;
        }
        return $errors;
    }

    /**
     * Login a user
     *
     * @param mixed $user     Username entered by the user
     * @param mixed $password Password entered the user
     *
     * @return Array Returns an array of datas
     * => Errors | if datas not found or false
     * => SESSION | if true
     */
    public function loginUser($user, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT *
            FROM users
            WHERE pseudo = :username"
        );
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!password_verify($password, $result['password'])) {
            $errors = 'Invalid username or password';
            return $errors;
        } else {
            $_SESSION['username'] = $result['username'];
            $_SESSION['at_username'] = $result['at_username'];
            $_SESSION['id'] = $result['id'];
            return true;
        }
    }

    /**
     * Check if this username is already existing
     *
     * @param string $username Username entered on the form
     *
     * @return boolean $data Returns true if a username has been found, false if not
     */
    public function usernameExists(string $username)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `Users` 
            WHERE username = :username"
        );
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }
    /**
     * Checks if this emails is already existing
     *
     * @param string $email Email entered on the form
     *
     * @return boolean $data Returns an array for AJAX requests
     */
    public function emailExists(string $email)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT * 
            FROM `Users` 
            WHERE email = :email"
        );
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_BOUND);
        return $data;
    }
}