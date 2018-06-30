<?php

class DownloadController extends Controller {

    public function selectFiles(){
            $id_fichier = $this->route["params"]["id"];
            $o_Upload = new Upload($id_fichier);

            if($o_Upload->Exist()){
                $result = Upload::getFiles($id_fichier);

                $uploadInfos = $o_Upload->getFields();
                
                $template = $this->twig->loadTemplate('/Page/download.html.twig');
                echo $template->render(array(
                    'uploadInfos' => $uploadInfos
                ));
            }
            else{
                $template = $this->twig->loadTemplate('/Error/404.html.twig');
                echo $template->render(array(
        
                ));
            }
        
    }
}