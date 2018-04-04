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

            $stmt = $pdo->prepare("INSERT INTO `users` (`id`, `pseudo`, `password`, `firstname`, `lastname`, `email`) VALUES (NULL, :pseudo, :password, :firstname, :lastname, :email)");
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':password', $hashedPwd);
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);

            $stmt->execute();
        }
        return $errors;
    }
}