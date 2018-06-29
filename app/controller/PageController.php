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
                    $infos = Upload::getFiles($id);

                    
                    $template = $this->twig->loadTemplate('/Page/transfert.html.twig');                   
                    echo $template->render(array(
                    'expediteur' => $infos['TRA_EXPEDITEUR'],
                    'destinataire' => $infos['TRA_DESTINATAIRE'],
                    'fichier' => $infos['TRA_FICHIER'],
                    'id' => $infos['TRA_ID']
                    ));
                }
                else{
                    header('Location:/wetransfer_like/');                   
                }          
            } 

            catch(\Exception $e) 
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
        if(isset($_POST['submitTransfert'])){

            $id_fichier = $this->route["params"]["id"];
            $o_Upload = new Upload($id_fichier);

            if($o_Upload->Exist()){
                $result = Upload::getFiles($id_fichier);

                $uploadInfos = $o_Upload->getFields();
                
                $template = $this->twig->loadTemplate('/Page/success.html.twig');
                echo $template->render(array(
                    'uploadInfos' => $uploadInfos
                ));
            }   
        }
        else{
            header('Location:/wetransfer_like');
        }
        
    }
}
