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
    public function rateCommentAction()
    {
        $ratingManager = new RatingManager();
        $rateComment = $ratingManager->rateComment($_POST['rating'], $_POST['id']);
        return $rateComment;
    }
}