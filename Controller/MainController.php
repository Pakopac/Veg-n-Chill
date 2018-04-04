<?php

require_once('Cool/BaseController.php');
require_once('Model/UserManager.php');
require_once('Model/PostManager.php');

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
    public function articleAction()
    {
        $manager = new PostManager();
        $posts = $manager->getAllPosts();

        $data = [
            'posts' => $posts
        ];

        return $this->render('article.html.twig', $data);
    }
    public function viewArticleAction()
    {
        $postManager = new PostManager();
        $post = $postManager->getPostById(intval($_GET['id']));
        $data = [
            'article'   => $post,
        ];

        return $this->render('viewArticle.html.twig', $data);

    }
    public function addArticleAction()
    {
        if(isset($_POST['title']) && isset($_POST['content'])){
            $title = $_POST['title'];
            $content = $_POST['content'];
            $manager = new PostManager();
            $posts = $manager->addPost($title, $content);

            $this->redirectToRoute('article');
        }

        return $this->render('addArticle.html.twig');
    }
}
