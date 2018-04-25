<?php

namespace Controller;

use Cool\BaseController;
use Model\PostManager;
use Model\CommentManager;
use Model\RatingManager;

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
        $commentManager = new CommentManager();
        $post = $postManager->getPostById(intval($_GET['id']));
        $getComments = $commentManager->getCommentsByPost(intval($_GET['id']));
        $getReply = $commentManager->getReplyByComments();
            $arr = [
                'article' => $post,
                'user' => $_SESSION,
                'reply' => $getReply,
                'comments' => $getComments
            ];
        return $this->render('viewArticle.html.twig', $arr);
    }

    public function addArticleAction()
    {
        if($_SESSION['rank_id'] == 1 || empty($_SESSION['rank_id'])){
            $this->redirectToRoute('home');
        }
        else {
            if (isset($_POST['title']) && isset($_POST['content'])) {
                $title = $_POST['title'];
                $content = $_POST['content'];
                $manager = new PostManager();
                $posts = $manager->addPost($title, $content, $_SESSION['author_id']);

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

    public function editArticleAction()
    {
        $postManager = new PostManager();
        $showPost = $postManager->showArticle(intval($_GET['id']));
        $datas = [
            'datas' => $showPost
        ];
        if(isset($_POST['submitEditArticle']) && isset($_GET['id'])){
            $postManager->editArticle($_POST['editArticle'], intval($_GET['id']));
            return $this->redirectToRoute('viewArticle', "id=".$_GET['id']);
        }
        return $this->render('editArticle.html.twig', $datas);
    }

    public function rateArticleAction()
    {
        $ratingManager = new RatingManager();
        $rateArticle = $ratingManager->rateArticle($_POST['rating'], intval($_GET['id']));

        return $rateArticle;
    }
}