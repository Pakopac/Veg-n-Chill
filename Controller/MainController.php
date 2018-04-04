<?php

namespace Controller;
use Cool\BaseController;

class MainController extends BaseController
{
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }
    
    public function registerAction()
    {
        if (isset($_SESSION['pseudo'])){
            $this->redirectToRoute('home');
        }
        else {
            if (!empty($_POST['firstname']) && !empty($_POST['lastname'])
                && !empty($_POST['pseudo']) && !empty($_POST['email'])
                && !empty($_POST['password']) && !empty($_POST['repeatPassword'])) {
                $firstname = htmlentities($_POST['firstname']);
                $lastname = htmlentities($_POST['lastname']);
                $pseudo = htmlentities($_POST['pseudo']);
                $email = htmlentities($_POST['email']);
                $password = $_POST['password'];
                $repeatPassword = $_POST['repeatPassword'];

                $UserManager = new UserManager();
                $errors = $UserManager->registerUser($firstname, $lastname, $pseudo, $email, $password, $repeatPassword);
                if ($errors === []) {
                    $data = ['user' => $_SESSION];
                    $this->redirectToRoute('home');
                    return $this->render('layout.html.twig', $data);
                } else {
                    $data = ['errors' => $errors];
                    return $this->render('register.html.twig', $data);
                }
            }
            return $this->render('register.html.twig');
        }
    }
}
