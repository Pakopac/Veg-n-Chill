<?php

namespace Controller;

use Cool\BaseController;
use Model\CommentManager;
use Model\RatingManager;

class CommentController extends BaseController
{
    public function addCommentAction()
    {
        if(isset($_POST['btn-comment']) && isset($_POST['comment'])) {
            $commentsManager = new CommentManager();
            $commentsManager->addComment(intval($_GET['id']), $_POST['comment']);
            return $this->redirectToRoute('viewArticle', 'id='.intval($_GET['id']));
        }
        else if(isset($_POST['btn-subComment']) && isset($_POST['subComment'])) {
            $commentsManager = new CommentManager();
            $commentsManager->addComment(intval($_GET['idComment']), $_POST['subComment']);
            return $this->redirectToRoute('viewArticle', 'id='.intval($_GET['id']));
        }
    }

    public function editCommentAction()
    {
        $postManager = new CommentManager();
        $showComment = $postManager->showComment(intval($_GET['id']));
        $datas = [
            'datas' => $showComment
        ];
        if(isset($_POST['submit-edit-comment']) && isset($_GET['id'])){
            $postManager->editComment($_POST['edit-comment'], intval($_GET['id']));
            return $this->redirectToRoute('viewArticle', "id=".$showComment['post_id']);
        }
        return $this->render('editComment.html.twig', $datas);
    }

    public function deleteCommentAction()
    {
        $commentManager = new CommentManager();
        $comment = $commentManager->deleteComment(intval($_GET['id']));
        $data = [
            'article' => $post,
            'user' => $_SESSION
        ];
        $this->redirectToRoute("article");

        return $this->render('deleteArticle.html.twig', $data);
    }

    public function rateCommentAction()
    {
        $ratingManager = new RatingManager();
        $rateComment = $ratingManager->rateComment($_POST['rating'], $_POST['id']);
        return $rateComment;
    }
}