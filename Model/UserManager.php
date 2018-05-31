<?php
/**
 * UserManager
 *
 * All logic associated for all user actions
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
use Cool\BaseController;
use Cool\DBManager;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * UserManager Class Doc Comment
 * 
 * @category Class
 * @package  UserManager
 * @author   Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 * 
 * @since 1.0.0
 */
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
            $_SESSION['username'] = $result['pseudo'];
            $_SESSION['rank_id'] = $result['rank_id'];
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

    public function generateRandomStr($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function newPassword($email)
    {
        $randomStr = $this->generateRandomStr(10);
        $newPass = password_hash($randomStr);
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "UPDATE users 
            SET password = :newPass
            WHERE email = :email"
        );
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':newPass', $newPass);
        $stmt->execute();
        return $randomStr;
    }

    public function forgotPassword($email)
    {
        $emailExists = $this->emailExists($email);
        $newPassword = $this->newPassword($email);
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        if ($emailExists) {
            $mail->isHTML();
            $mail->Username = "contact@vegnchill.com";
            $mail->Password = "<&ur8RdeY";
            $mail->setFrom('contact@vegnchill.com');
            $mail->Subject = "Your new password";
            $mail->Body = $newPassword;
            $mail->addAddress($email);
            $result = $mail->send();
            if ($result == true) {
                $arr = [
                    'success' => "ok",
                    'message' => "Sent new password at your mail"
                ];
                return $arr;
            } else {
                $arr = [
                    'success' => "failed",
                    'message' => "The mail was not sent"
                ];
                return $arr;
            }
        } else {
            $arr = [
                'success' => "failed",
                'message' => "Mail not found"
            ];
            return $arr;
        }
    }

    public function countAllCommentsOfUser($userID)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*)
                AS totalComments
            FROM comments
            WHERE post_id = :userID"
        );
        $stmt->bindParam(":userID", $userID);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function getCommentDatas($userID)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $stmt = $pdo->prepare(
            "SELECT *
            FROM comments
            WHERE post_id = :userID"
        );
        $stmt->bindParam(":userID", $userID);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
}
