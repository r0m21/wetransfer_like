<?php

class User extends Model {

    public function __construct($i_idMod){
        parent::__construct($i_idMod);		
    }

	public function Load(){
		$sql = "select * from USERS
                where ID_USER = " . $this->getID();
	    if ($row = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC)){
            $this->setFields($row);
        } else {
            $this->_id = -1;
            $this->setFields(array ());
        }	
	}

	public function Delete(){
        if(!$this->IsDeletable()){
            return false;
        }

        $sql = "delete from USERS
                where ID_USER = " . $this->getID();
        $this->db->exec($sql);

        return true;
    }

    public function IsDeletable(){
        return true;
    }

    public static function login($login, $password){
        $db = Database::getInstance();
        $return = array();
        $error = 0;

        $sql = "select * from USERS
                where USE_LOGIN = " .$db->quote($login)."
                and USE_PASSWORD = " .$db->quote(md5($password));
              
        if($row = $db->query($sql)->fetch(PDO::FETCH_ASSOC)){
            $return[] = array(
                'message'   => 'Connexion établie.',
                'type'      => 'success',
            );

            $_SESSION['User'] = md5($row['USE_EMAIL']);
        } else {
            $return[] = array(
                'message'   => 'Erreur dans l\'ensemble login/mot de passe.',
                'type'      => 'error'
            );
        }

        return $return;
    }

    /**
     * Enregistre un nouvel utilisateur
     * @param array $a_Values
     * @return array $return
     */
    public static function signup($a_Values){
        $db = Database::getInstance();
        $return = array();
        $error = 0;

        if($a_Values['login'] == ''){
            $return[] = array(
                'message'   => 'Veuillez saisir un login',
                'type'      => 'error'
            );
            $error++;
        }
        if($a_Values['email'] == ''){
            $return[] = array(
                'message'   => 'Veuillez saisir un email',
                'type'      => 'error'
            );
            $error++;
        }

        if($error == 0){
            $sql = "select * from USERS 
                    where USE_LOGIN = " .$db->quote($a_Values['login']);
            if($row = $db->query($sql)->fetch(PDO::FETCH_ASSOC)){
                $return[] = array(
                    'message'   => 'Ce login est déjà utilisé.',
                    'type'      => 'error'
                );
                $error++;
            }
            
            $sql = "select * from USERS
                    where USE_EMAIL = " .$db->quote($a_Values['email']);
            if($row = $db->query($sql)->fetch(PDO::FETCH_ASSOC)){
                $return[] = array(
                    'message'   => 'Cet email est déjà utilisé.',
                    'type'      => 'error'
                );
                $error++;
            } else {
                if(!is_a_mail($a_Values['email'])){
                    $return[] = array(
                        'message'   => 'Cet email n\'est pas valide.',
                        'type'      => 'error'
                    );
                }
            }
        }

        if($error == 0){
            $stmt = $db->prepare("insert into USERS (
                            USE_LOGIN,
                            USE_PASSWORD,
                            USE_EMAIL) values (
                            :USE_LOGIN,
                            :USE_PASSWORD,
                            :USE_EMAIL)"
            );

            $stmt->bindValue(':USE_LOGIN', $a_Values['login'], PDO::PARAM_STR);
            $stmt->bindValue(':USE_PASSWORD', md5($a_Values['password']), PDO::PARAM_STR);
            $stmt->bindValue(':USE_EMAIL', $a_Values['email'], PDO::PARAM_STR);
            $stmt->execute();

            $i_idMod = $db->lastInsertID();
            $return[] = array(
                'message'   => 'Votre compte a bien été enregistré.',
                'type'      => 'success',
                'ID_USER'   => $i_idMod
            );
        }

        return $return;
    }
}