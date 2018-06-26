<?php

class HomeController extends Controller {

    public function display(){
        $template = $this->twig->loadTemplate('/Page/home.html.twig');
        echo $template->render(array());
    }
}