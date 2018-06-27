<?php

class PageController extends Controller {

        
    public function display(){
        $message['msg'] = ''; $message['type'] = '';
        if(isset($_POST['submitForm'])){  
            $result = Upload::uploadFiles();
            $message = $result;
        }
        $template = $this->twig->loadTemplate('/Page/home.html.twig');
        echo $template->render(array(
            'message' => $message['msg'],
            'type' => $message['type']
        ));
    }
/*     public function pageTransfert(){
        $template = $this->twig->loadTemplate('/Page/transfert.html.twig');
        echo $template->render(array()); 
    } */
}