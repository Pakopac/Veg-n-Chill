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
        if (!empty($_POST)) {
            $posts = $manager->searchPostByName($_POST['searchbox']);
            $arr = [
                'posts' => $posts,
                'user' => $_SESSION
            ];
            return $this->render('article.html.twig', $arr);
        } else {
            $posts = $manager->getAllPosts();
            $data = [
                'posts' => $posts,
                'user' => $_SESSION
            ];
            return $this->render('article.html.twig', $data);
        }
    }
    public function viewArticleAction()
    {
        $postManager = new PostManager();
        $commentManager = new CommentManager();
        $post = $postManager->getPostById(intval($_GET['id']));
        $getComments = $commentManager->getCommentsByPost(intval($_GET['id']));
        $getReply = $commentManager->getReplyByComments();
        $arr = [
            'article' => $post['post'],
            'user' => $_SESSION,
            'reply' => $getReply,
            'comments' => $getComments,
            'type' => $post['type']
        ];
        return $this->render('viewArticle.html.twig', $arr);
    }

    public function addArticleAction()
    {
        if ($_SESSION['rank_id'] == 1 || empty($_SESSION['rank_id'])) {
            $this->redirectToRoute('home');
        } else {
            if (isset($_POST['title']) && isset($_POST['content'])
                && isset($_POST['desc']) && isset($_POST['img'])
                && isset($_POST['articleType'])
            ) {
                $manager = new PostManager();
                $img = $_POST['img']; 
                $title = $_POST['title'];
                $desc = $_POST['desc'];
                $content = $_POST['content'];
                $type = $_POST['articleType'];
                $posts = $manager->addPost(
                    $title, $desc, $img,
                    $content, $_SESSION['id'],
                    $type
                );

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
        if ($_SESSION['rank_id'] == 1 || !isset($_SESSION['rank_id'])) {
            $this->redirectToRoute('article');
        } else {
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
        if ($_SESSION['rank_id'] == 1 || !isset($_SESSION['rank_id'])) {
            $this->redirectToRoute('article');
        }
        $postManager = new PostManager();
        $showPost = $postManager->showArticle(intval($_GET['id']));
        $datas = [
            'datas' => $showPost
        ];
        if (isset($_POST['submitEditArticle']) && isset($_GET['id'])) {
            $postManager->editArticle(
                $_POST['editDesc'],
                $_POST['editArticle'], 
                intval($_GET['id'])
            );
            return $this->redirectToRoute('viewArticle', "id=".$_GET['id']);
        }
        return $this->render('editArticle.html.twig', $datas);
    }

    public function rateArticleAction()
    {
        $ratingManager = new RatingManager();
        $rateArticle = $ratingManager->rateArticle(
            $_POST['rating'],
            intval($_GET['id'])
        );

        return $rateArticle;
    }
}