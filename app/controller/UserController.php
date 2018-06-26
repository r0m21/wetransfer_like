<?php

class UserController extends Controller {

    /**
     * Fonction d'affichage et de traitement du login
     */
    public function login(){
        $message = ''; $type = '';

        if(isset($_POST['submitLogin']) && $_POST['submitLogin'] == 'Connexion'){
            $login = $_POST['login']; $password = $_POST['password'];
            $is_log = User::login($login, $password);

            $message = $is_log['message'];
            $type = $is_log['type'];

            header('Location:/mycine');
        }

        $template = $this->twig->loadTemplate('/User/login.html.twig');
        echo $template->render(array(
                'message'   => $message,
                'type'      => $type
            )
        );
    }

    /**
     * Fonction d'affichage et de traitement d'inscription
     */
    public function signup(){
        $message = '';

        if(isset($_POST['submitSignup']) && $_POST['submitSignup'] == 'Inscription'){
            $signup = User::signup($_POST);

        }

        $template = $this->twig->loadTemplate('/User/signup.html.twig');
        echo $template->render(array(
                'message'   => $message
            )
        );
    }

    public function logout(){
        $_SESSION = array();
        header('Location:/mycine');
    }
}