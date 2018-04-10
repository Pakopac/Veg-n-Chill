<?php

namespace Controller;

use Cool\BaseController;
use Model\PostManager;
use Model\CommentManager;

class ArticleController extends BaseController
{
    public function articleAction()
    {
        $manager = new PostManager();
        $posts = $manager->getAllPosts();

        $data = [
            'posts' => $posts,
            'user' => $_SESSION
        ];

        return $this->render('article.html.twig', $data);
    }
    public function viewArticleAction()
    {
        $postManager = new PostManager();
        if(isset($_POST['comment'])) {
            $post = $postManager->getPostById(intval($_GET['id']));
            $commentsManager = new CommentManager();
            $commentsManager->addComment(intval($_GET['id']), $_POST['comment']);
            $getComments = $commentsManager->getCommentsByPost(intval($_GET['id']));
            $data = [
                'article' => $post,
                'user' => $_SESSION,
                'comments' => $getComments
            ];
            return $this->render('viewArticle.html.twig', $data);
        }
        return $this->render('viewArticle.html.twig');
    }

    public function addArticleAction()
    {
        if($_SESSION['rank_id'] == 1 || !isset($_SESSION['rank_id'])){
            $this->redirectToRoute('home');
        }
        else {
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $manager = new PostManager();
                $posts = $manager->addPost($title, $content);

                $this->redirectToRoute('article');
            }
            $arr = [
                'user' => $_SESSION
            ];
            return $this->render('addArticle.html.twig', $arr);
        }
    }

    public function deleteArticleAction()
    {
        if($_SESSION['rank_id'] == 1 || !isset($_SESSION['rank_id'])){
            $this->redirectToRoute('article');
        }
        else {
            $postManager = new PostManager();
            $post = $postManager->deletePost(intval($_GET['id']));
            $data = [
                'article' => $post,
                'user' => $_SESSION
            ];
            $this->redirectToRoute("article");

            return $this->render('deleteArticle.html.twig', $data);
        }
    }
}