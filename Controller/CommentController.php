<?php

namespace Controller;

use Cool\BaseController;
use Model\CommentManager;
use Model\RatingManager;

class CommentController extends BaseController
{
    public function addCommentAction()
    {
        if(isset($_POST['contentComment']) && isset($_POST['idNews'])) {
            $commentsManager = new CommentManager();
            $commentsManager->addComment(($_GET['contentComment']), $_GET['idNews']);
            return $this;
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
    public function getCommentsAction()
    {
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentsByPost();
        return json_encode($comment);
    }
}