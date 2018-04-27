<?php

namespace Controller;

use Cool\BaseController;

class AdminController extends BaseController
{
    public function homeAction()
    {
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/home.html.twig',$arr);
    }
    public function generalAction()
    {
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/general.html.twig',$arr);
    }
    public function displayAction()
    {
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/display.html.twig',$arr);
    }
    public function usersAction()
    {
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/users.html.twig',$arr);
    }
}