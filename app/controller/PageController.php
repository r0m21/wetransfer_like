<?php

class PageController extends Controller {
     
    public function display(){
        $message['msg'] = ''; $message['type'] = '';$message['url'] = '';
        
            $template = $this->twig->loadTemplate('/Page/home.html.twig');
            echo $template->render(array(
                'session_message' => $_SESSION['globalMessage'],
                'message' => $message['msg'],
                'type' => $message['type'],
                'url' => $message['url']
            ));

        
    }

    public function pageTransfert(){

        $message['msg'] = ''; $message['type'] = '';$message['url'] = '';
        unset($_SESSION['globalMessage']);

        if(isset($_POST['submitForm'])){  
            $result = Upload::uploadFiles();
            $message = $result;
            $_SESSION['globalMessage'] = $message;

            try
            {    
                if($_SESSION['globalMessage']['type'] == "success"){

                    $email = Upload::newMail();                    
                    $template = $this->twig->loadTemplate('/Page/transfert.html.twig');

                    echo $template->render(array(

                    'message' => $message['msg'],
                    'type' => $message['type'],
                    'url' => $message['url']

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
                echo "catch"; 
                
            }
        }

        else {
            header('Location:/wetransfer_like');
            echo 'non';
            print_r($message);
        }
    }
}
