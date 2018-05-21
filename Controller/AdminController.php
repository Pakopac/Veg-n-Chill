<?php
/**
 * AdminController
 *
 * All logic calls for admin related actions
 *
 * PHP Version 7.2
 *
 * @category Recipe
 * @package  Recipe
 * @author   Yanis Bendahmane <vegnchill@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 */

namespace Controller;

use Cool\BaseController;
use Model\AdminManager;

/**
 * AdminController Class Doc Comment
 * 
 * @category Class
 * @package  AdminController
 * @author   Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 * 
 * @since 1.0.0
 */
class AdminController extends BaseController
{
    public function homeAction()
    {
        if ($_SESSION['rank_id'] != 3) {
            $this->redirectToRoute('home');
        }
        $adminManager = new AdminManager();
        $userPage =         $adminManager->getPage();
        $loggedIn =         $adminManager->countAll('totalUsers', 'users');
        $countAllComments = $adminManager->countAll('totalComment', 'comments');
        $countAllUsersReg = $adminManager->countAll('totalUsers', 'users');
        $countAllArticles = $adminManager->countAll('totalArticles', 'posts');
        $nonLogged =        $adminManager->countNonLoggedUsers();
        $allUsersDatas =    $adminManager->getLoggedUserDatas();
        $allUsers =         $this->countAllUsersOnSite();
        $arr = [
            'user'              => $_SESSION,
            'totalUsers'        => $allUsers,
            'loggedIn'          => $loggedIn,
            'nonLogged'         => $nonLogged,
            'page'              => $userPage,
            'userdata'          => $allUsersDatas,
            'totalComments'     => $countAllComments,
            'countEveryone'     => $countAllUsersReg,
            'countEveryArticle' => $countAllArticles
        ];
        return $this->render('admin/home.html.twig', $arr);
    }
    public function generalAction()
    {
        if ($_SESSION['rank_id'] != 3) {
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/general.html.twig', $arr);
    }
    public function displayAction()
    {
        if ($_SESSION['rank_id'] != 3) {
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/display.html.twig', $arr);
    }
    public function usersAction()
    {
        if ($_SESSION['rank_id'] != 3) {
            $this->redirectToRoute('home');
        }
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('admin/users.html.twig', $arr);
    }
}
