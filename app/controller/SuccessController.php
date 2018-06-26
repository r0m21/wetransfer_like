<?php

class SuccessController extends Controller {

    public function display(){
        $template = $this->twig->loadTemplate('/Page/success.html.twig');
        echo $template->render(array());
    }
}