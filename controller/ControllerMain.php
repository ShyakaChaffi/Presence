<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMain extends Controller {

    public function index() {
        if ($this->user_logged()) {
            $user = $this->get_user_or_redirect();
            if ($user->role === "teacher") {
                $this->redirect("user", "home");
            } else if ($user->role === "admin") {
                $this->redirect("admin", "home");
            }
        } else {
            (new View("index"))->show();
        }
    }

    public function login() {
        $pseudo = '';
        $password = '';
        $errors = [];
        if (isset($_POST['pseudo']) && isset($_POST['password'])) {
            $pseudo = $_POST['pseudo'];
            $password = $_POST['password'];
            $errors = User::validate_login($pseudo, $password);
            if (empty($errors)) {
                $this->log_user(User::get_user_by_pseudo($pseudo));
            }
        }
        (new View("login"))->show(array("pseudo" => $pseudo, "password" => $password, "errors" => $errors));
    }

    public function get_admin_or_redirect() {
        $user = $this->get_user_or_false();
        if ($user === FALSE || $user->role != "admin") {
            $this->redirect();
        } else {
            return $user;
        }
    }

    public function get_teacher_or_redirect() {
        $user = $this->get_user_or_false();
        if ($user === FALSE || $user->role != "teacher") {
            $this->redirect();
        } else {
            return $user;
        }
    }

}
