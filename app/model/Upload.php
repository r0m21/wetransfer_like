<?php

class Upload extends TransfertController {

    public static function uploadFiles() {
        
        $db = Database::getInstance();
        $sql = "INSERT INTO transfer_table 
        (tra_expediteur, tra_destinataire, tra_fichier)
        VALUES (:tra_expediteur, :tra_destinataire, :tra_fichier)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':tra_expediteur', $expediteur, PDO::PARAM_STR);
        $stmt->bindValue(':tra_destinataire', $destinataire, PDO::PARAM_STR);
        $stmt->bindValue(':tra_fichier', $fichier, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public static function getFiles() {
        
        $db = Database::getInstance();
        $sql = "SELECT *
        FROM transfer_table";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

      /*       
        $message['urlImg'] = '';
        $message['msg'] = 'Sélectionner une image ou extension incorrect';
        $message['type'] = 'error';    
 */

        /* return $message; */

}
