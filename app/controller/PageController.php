<?php

class PageController extends Controller {
     
    public function display(){
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
            echo "submit";

            try
            {    
                echo "try";
                print_r($message);
                if($message['type'] == "success"){
                    echo "if success";
                    print_r($message);
                    $email = Upload::newMail();
                    
                    $template = $this->twig->loadTemplate('/Page/transfert.html.twig');
                    echo $template->render(array(

                    'message' => $message['msg'],
                    'type' => $message['type'],
                    'url' => $message['url']

                    ));
                }
                else{
                    
                    print_r($message);
                    echo 'non';
                    return false;
                   
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
