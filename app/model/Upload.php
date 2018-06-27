<?php

class Upload extends TransfertController {

    public static function uploadFiles() {
        
        $erreur = 0;

        if(isset($_POST['submitForm'])){
            if(!empty($_FILES)){

                $message = array();

                if(isset($_FILES['fichier']['error'])){
                    switch($_FILES['fichier']['error']){
                        case 1:
                            
                            $message['msg'] = "Votre fichier ne doit pas dépasser 10Mo";
                            $message['type'] = 'error';
                            $erreur++;
                            break;
                        case 2:
                            
                            $message['msg'] = "Le fichier téléchargé ne doit pas dépasser 10Mo";
                            $message['type'] = 'error';
                            $erreur++;
                            break;
                        case 3:
                            
                            $message['msg'] = "Une erreur est survenue lors du téléchargement.";
                            $message['type'] = 'error';
                            $erreur++;
                            break;
                        case 4:
                            
                            $message['msg'] = "Aucun fichier n'a été séléctionné.";
                            $message['type'] = 'error';
                            $erreur++;
                            break; 
                    }

                }
                    if($erreur == 0){
                        $expediteur= $_POST['expediteur'];
                        $destinataire= $_POST['destinataire'];
                        print_r($expediteur);
                        print_r($destinataire);
                        if(is_a_mail($expediteur) && is_a_mail($destinataire)){
                            $fichier = $_FILES['fichier'];
                            $ext = substr($fichier['name'], strrpos($fichier['name'], '.') + 1);
                            $unallowed_ext = array("exe", "EXE");
                             
                            if(!in_array($ext, $unallowed_ext)){
                                $db = Database::getInstance();
                                $sql = 
                                "INSERT INTO transfer_table
                                (
                                tra_expediteur, 
                                tra_destinataire, 
                                tra_fichier
                                )
                                VALUES (
                                    :tra_expediteur, 
                                    :tra_destinataire, 
                                    :tra_fichier
                                )";
                                $stmt = $db->prepare($sql);
                                $stmt->bindValue(':tra_expediteur', $expediteur, PDO::PARAM_STR);
                                $stmt->bindValue(':tra_destinataire', $destinataire, PDO::PARAM_STR);
                                $stmt->bindValue(':tra_fichier', $fichier['name'], PDO::PARAM_STR);                            
                                $stmt->execute();
    
                                $message['msg'] = 'Fichier envoyé';
                                $message['type'] = 'success';  
                                
                            }
                        } else {
                            $message['msg'] = 'Email invalide';
                            $message['type'] = 'error';
                        }
                        
                      
                    }
            }
            else{

                $message['msg'] = "L'extension du fichier est incorrecte";
                $message['type'] = 'error';    
            } 
            return $message;
        }
    }
    
   /*  public static function getFiles() {
        
        $db = Database::getInstance();
        $sql = "SELECT *
        FROM transfer_table";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    } */

      /*       
        $message['urlfichier'] = '';
        $message['msg'] = 'Sélectionner une image ou extension incorrect';
        $message['type'] = 'error';    
 */

        /* return $message; */

}
