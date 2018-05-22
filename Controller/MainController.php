<?php
/**
 * MainController
 *
 * All calls for logics associated with main actions
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
use Model\UserManager;
use Model\AdminManager;

/**
 * MainController Class Doc Comment
 * 
 * @category Class
 * @package  MainController
 * @author   Yanis Bendahmane <twttr@yanisbendahmane.fr>
 * @author   Lilian Pacaud <lilian.pacaud@supinternet.fr>
 * @license  http://unlicense.org/ The Unlicense
 * @link     https://localhost/
 * 
 * @since 1.0.0
 */
class MainController extends BaseController
{
    public function homeAction()
    {
        $arr = [
            'user' => $_SESSION
        ];
        return $this->render('home.html.twig', $arr);
    }

    public function registerAction()
    {
        if (!empty($_POST['firstname']) || !empty($_POST['lastname'])
            || !empty($_POST['username']) || !empty($_POST['email'])
            || !empty($_POST['password']) || !empty($_POST['password_repeat'])
        ) {
            $UserManager = new UserManager();
            $login = $UserManager->registerUser(
                htmlentities($_POST['firstname']),
                htmlentities($_POST['lastname']),
                htmlentities($_POST['username']),
                $_POST['password'], $_POST['password_repeat'],
                htmlentities($_POST['email'])
            );
            if ($login === true) {
                $data = [
                    'status' => 'ok',
                    'message' => 'The user has been registred'
                ];
                return json_encode($data);
            } else {
                $data = [$login];
                return json_encode($data);
            }
        } else {
            $data = ['message' => "Input's not filled"];
            return json_encode($data);
        }
    }

    /**
     * Call for logging in a user
     *
     * @return Array $arr Returns datas on JSON for AJAX login
     */
    public function loginAction()
    {
        if (isset($_POST['username']) && isset($_POST['password'])
            || $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {
            $userManager = new UserManager();
            $adminManager = new AdminManager();
            $username = htmlentities($_POST['username']);
            $password = $_POST['password'];
            $getUserData = $userManager->loginUser($username, $password);
            if ($getUserData !== true) {
                $arr = [
                    'status' => 'failed',
                    'message' => 'There was a problem loggin in the user'
                ];
                return json_encode($arr);
            } else {
                $loggedIn = $adminManager->addLoggedUser(
                    $_SESSION['id'],
                    $_SERVER['REMOTE_ADDR']
                );
                $arr = [
                    'status' => 'ok',
                    'message' => 'The user has successfully been logged in'
                ];
                return json_encode($arr);
            }
        }
    }

    public function logoutAction()
    {
        $adminManager = new AdminManager();
        $adminManager->removeLoggedUser($_SESSION['id']);
        session_destroy();
        return $this->redirectToRoute('home');
    }
}
