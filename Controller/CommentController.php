<?php

namespace Controller;

use Cool\BaseController;
use Model\CommentManager;

class CommentController extends BaseController
{
    public function addCommentAction()
    {
        if(isset($_POST['btn-comment']) && isset($_POST['comment'])) {
            $commentsManager = new CommentManager();
            $commentsManager->addComment(intval($_GET['id']), $_POST['comment']);
            return $this->redirectToRoute('viewArticle', 'id='.intval($_GET['id']));
        }
    }
}