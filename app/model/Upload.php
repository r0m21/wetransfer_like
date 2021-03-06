<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Upload extends Model {

    public function __construct($i_idMod){
        parent::__construct($i_idMod);
    }

    public function Load(){
        $sql = "select * from transfer_table
                where tra_id = " .$this->getID();
        if($row = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC)){
            $this->setFields($row);
        } else {
            $this->_id = -1;
            $this->setFields(array());
        }
    }

    public function Delete(){
        if(!$this->IsDeletable()){
            return false;
        }

        $sql = "delete from transfer_table
                where tra_id = " .$this->getID();
        $this->db->exec($sql);

        return true;
    }

    public function IsDeletable(){
        return true;
    }

    public static function uploadFiles() {

        unset($_SESSION['globalMessage']);

        $erreur = 0;

        
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
                        $expediteur= htmlentities($_POST['expediteur']);
                        $destinataire= htmlentities($_POST['destinataire']);
                        
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
                                
                                
                                $mail = new PHPMailer(true);  
                                $mail->CharSet = 'UTF-8';                            // Passing `true` enables exceptions
                                try {
                                    //Server settings
                                    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
                                    $mail->isSMTP();                                      // Set mailer to use SMTP
                                    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                                    $mail->SMTPAuth = true;     // Enable SMTP authentication
                                    $mail->Mailer = "smtp";                               
                                    $mail->Username = 'youpload.master@gmail.com';                 // SMTP username
                                    $mail->Password = 'azerty987123';                           // SMTP password
                                    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                                    $mail->Port = 25;                                    // TCP port to connect to
                                
                                    //Recipients
                                    $mail->setFrom('youpload.master@gmail.com', 'Youpload');
                                    $mail->addAddress($destinataire, '');     // Add a recipient
                                
                                    //Content
                                    $mail->isHTML(true);                                  // Set email format to HTML
                                    $mail->Subject = 'Vous avez reçu un fichier sur Youpload';
                                    $mail->Body    =
                                    '<div style="background-color: rgb(255, 240, 225); height: 100px; width: 100%; 
                                    text-align: center;">
                                        <p style="font-size: 1.625em; text-align: center; margin: auto;">' . $expediteur . '</p>
                                    </div>

                                    <div style="background-color: rgb(255, 225, 205); height: 110px; padding: 20px;">
                                        <p style="margin:0; font-size: 1.625em; text-align: center; color: #444;">Vous a envoyé le fichier</p>
                                        <p style="font-size: 1.625em; text-align: center;">'. $file_name . '</p>
                                    </div>


                                    <div style="background-color: rgb(240, 215, 200); height: 270px; padding: 20px;">
                                        <p style="margin:0; font-size: 1.625em; text-align: center; font-style: italic; color: #444;">Recuperez le <br> sur Youpload :</p>
                                        <div style="width: 100%;">
                                            <div style="background-color: rgb(232, 87, 67); height: 55px; width: 320px;margin: auto;">
                                                <p style="text-align:center;color: #fff;">LIEN DU FICHIER :
                                                    <a href="http://localhost/wetransfer_like/download/'. $id . '" style="background-color:#fff; color:#212121;">https://valenting.promo-18.codeur.online/wetransfer_like/download/' . $id . '</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>';
                                                                                                 
                                    $mail->AltBody = "Change ton navigateur please.";
                                
                                    $mail->send();
                                } 
                        
                                catch (Exception $e) {
                                    echo "Une erreur est survenue lors de l'envoi du mail : " .  $mail->ErrorInfo;
                                }

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
            
            $_SESSION['globalMessage'] = $message['msg'];

            return $message;
            
        
    }


    public static function getFiles($id) {

        
            $message = array();

            $db = Database::getInstance();

            $sql = "SELECT * FROM transfer_table
                    WHERE tra_id = :tra_id";
                
            $stmt = $db->prepare($sql); 
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindValue(':tra_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();  

            $message['url'] = $id;
    }
}
