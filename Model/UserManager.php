<?php

namespace Model;
use Cool\BaseController;
use Cool\DBManager;

class UserManager
{
    public function registerUser($firstname, $lastname, $pseudo, $email,$password, $repeatPassword)
    {
        $regexPassword = "\"^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$\"";
        $regexEmail =  " /^[^\W][a-zA-Z0-9]+(.[a-zA-Z0-9]+)@[a-zA-Z0-9]+(.[a-zA-Z0-9]+)*.[a-zA-Z]{2,4}$/ ";
        $errors = [];

        if (!preg_match($regexEmail,$email)){
            $errors[] = 'Invalid Email';
        }
        if((strlen($pseudo) < 4) || (strlen($pseudo) > 20)){
            $errors[] = 'Pseudo too short or too long';
        }
        if(!preg_match($regexPassword,$password)){
            $errors[] = 'Password must have at least 6 characters with 1 letter uppercase and 1 number';
        }
        if($password !== $repeatPassword){
            $errors[] = 'Password must be identical to the verification';
        }
        if($errors === []) {
            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $hashedPwd = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare("INSERT INTO `users` (`id`, `pseudo`, `password`, `firstname`, `lastname`, `email`, `rank_id`) VALUES (NULL, :pseudo, :password, :firstname, :lastname, :email, 1)");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':password', $hashedPwd);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);

            $stmt->execute();
        }
        return $errors;
    }

    public function loginUser($user, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();

        $stmt = $pdo->prepare("SELECT * FROM users 
        WHERE pseudo = :username");
        $stmt->bindParam(':username', $user);

        $stmt->execute();
        $result = $stmt->fetch();

        if(!password_verify($password, $result['password'])){
            $errors = 'Invalid username or password';
            return $errors;
        } else {
            $_SESSION['username'] = $user;
            $_SESSION['rank_id'] = $result['rank_id'];
            $_SESSION['author_id'] = $result['id'];
            return $_SESSION;
        }
    }
}