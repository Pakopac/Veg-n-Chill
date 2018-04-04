<?php

namespace Controller;

use Cool\BaseController;
use Model\PostManager;

class ArticleController extends BaseController
{
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