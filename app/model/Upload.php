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
                            $message['url'] = '';  
                            $erreur++;
                            break;
                        case 2:
                            
                            $message['msg'] = "Le fichier téléchargé ne doit pas dépasser 10Mo";
                            $message['type'] = 'error';
                            $message['url'] = '';  
                            $erreur++;
                            break;
                        case 3:
                            
                            $message['msg'] = "Une erreur est survenue lors du téléchargement.";
                            $message['type'] = 'error';
                            $message['url'] = '';  
                            $erreur++;
                            break;
                        case 4:
                            
                            $message['msg'] = "Aucun fichier n'a été séléctionné.";
                            $message['type'] = 'error';
                            $message['url'] = '';  
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
                                 //Cripte le fichier.
                                $file_name = 'fichier_'.substr(md5($fichier['name']), 0, 5).'_'.time().'.'.$ext;

                                //Récupère le chemin temporaire + la direction où on veux l'envoyer.
                                $tmp_name = $_FILES["fichier"]["tmp_name"];
                                move_uploaded_file($tmp_name, "upload/".$file_name);

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
                                $stmt->bindValue(':tra_fichier', $file_name, PDO::PARAM_STR);  
                                $stmt->execute();
                                $id = $db->lastInsertId();   

                                $message['msg'] = 'Fichier envoyé';
                                $message['type'] = 'success';  
                                $message['url'] = $id; 
                                
                            }
                        } else {
                            $message['msg'] = 'Email invalide';
                            $message['type'] = 'error';
                            $message['url'] = '';
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

    public static function getFiles() {
        
        if(isset($id))
        {
            $db = Database::getInstance();
            $sql = "SELECT *
            FROM transfer_table
            WHERE tra_id = :tra_id";
            $stmt = $db->prepare($sql); 
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindValue(':tra_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
            
        }
        else{
            
        }
    }
}
