<?php

namespace Controller;

use Cool\BaseController;
use Model\AdminManager;

class AdminController extends BaseController
{
    public function homeAction()
    {
        if($_SESSION['rank_id'] != 3){
            $this->redirectToRoute('home');
        }
        $adminManager = new AdminManager();
        $totalUsers = $adminManager->getTotalOfUsers();
        $arr = [
            'user' => $_SESSION,
            'totalUsers' => $totalUsers
        ];
        return $this->render('admin/home.html.twig', $arr);
    }
    public function generalAction()
    {
        if($_SESSION['rank_id'] != 3){
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/general.html.twig', $arr);
    }
    public function displayAction()
    {
        if($_SESSION['rank_id'] != 3){
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/display.html.twig', $arr);
    }
    public function usersAction()
    {
        if($_SESSION['rank_id'] != 3){
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/users.html.twig', $arr);
    }
}