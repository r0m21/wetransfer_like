<?php

class PageController extends Controller {
     
    public function display(){
        unset($_SESSION['globalMessage']);

        $message['msg'] = ''; $message['type'] = '';$message['url'] = '';
        
            $template = $this->twig->loadTemplate('/Page/home.html.twig');
            echo $template->render(array(
                'message' => $message['msg'],
                'type' => $message['type'],
                'url' => $message['url']
            ));

        
    }

    public function pageTransfert(){

        $message['msg'] = ''; $message['type'] = '';$message['url'] = '';

        if(isset($_POST['submitForm'])){  
            $result = Upload::uploadFiles();
            $message = $result;

            try
            {    
                if($result['type'] == "success"){

                    $id = $message['url'];
                    $email = Upload::newMail();  
                    $infos = Upload::getFiles($id);
                    
                    $template = $this->twig->loadTemplate('/Page/transfert.html.twig');                   
                    echo $template->render(array(
                    'expediteur' => $infos['TRA_EXPEDITEUR'],
                    'destinataire' => $infos['TRA_DESTINATAIRE'],
                    'fichier' => $infos['TRA_FICHIER']
                    ));
                }
                else{
                    header('Location:/wetransfer_like/');                   
                }          
            } 

            catch(\Exception $e) // Global exception again here
            {
                echo $e->getMessage();
                die();                         
            }
        }

        else {
            header('Location:/wetransfer_like');
        }
    }
    public function pageSuccess(){
        $message['msg'] = ''; $message['type'] = '';$message['url'] = '';
        
            $id = $message['url'];
            $infos = Upload::getFiles($id);

            $template = $this->twig->loadTemplate('/Page/success.html.twig');
            echo $template->render(array(

                'destinataire' => $infos['TRA_DESTINATAIRE'],
                'fichier' => $infos['TRA_FICHIER'],
                'id' => $infos['TRA_ID']
            ));
    }

}
