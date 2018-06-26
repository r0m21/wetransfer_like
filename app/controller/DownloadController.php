<?php

class DownloadController extends Controller {

    public function display(){
        $template = $this->twig->loadTemplate('/Page/download.html.twig');
        echo $template->render(array());
    }
}