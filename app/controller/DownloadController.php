<?php

class DownloadController extends Controller {

    public function selectFiles(){
        $id_fichier = $this->route["params"]["id"];
        $result = Upload::getFiles();
        $template = $this->twig->loadTemplate('/Page/download.html.twig');
        echo $template->render(array(
            'result' => $result
        ));
        


    }
}