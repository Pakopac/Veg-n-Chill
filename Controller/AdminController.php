<?php

namespace Controller;

use Cool\BaseController;

class AdminController extends BaseController
{
    public function homeAction()
    {
        return $this->render('admin/home.html.twig');
    }
    public function generalAction()
    {
        return $this->render('admin/general.html.twig');
    }
    public function displayAction()
    {
        return $this->render('admin/display.html.twig');
    }
    public function usersAction()
    {
        return $this->render('admin/users.html.twig');
    }
}