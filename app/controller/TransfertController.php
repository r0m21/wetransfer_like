<?php

class TransfertController extends Controller {

    public function display(){

        

        $template = $this->twig->loadTemplate('/Page/transfert.html.twig');
        echo $template->render(array());
    }
}