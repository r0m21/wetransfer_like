<?php

class Router {

    public static function analyse($request){
        $result = array(
            'controller'    => 'Error',
            'action'        => 'error404',
            'params'        => array()
        );

        if($request === '' || $request === '/'){ // Route vers la page d'accueil
            $result['controller']   = 'Home';
            $result['action']       = 'display';
        } else {
            $parts = explode('/', $request);

            if($parts[0] == 'transfert' && (count($parts) == 1 || $parts[1] == '')){ // Route vers la page d'inscription
                $result['controller']       = 'Transfert';
                $result['action']           = 'display';
            }elseif($parts[0] == 'success' && (count($parts) == 1 || $parts[1] == '')){ // Deconnexion de l'utilisateur
                $result['controller']       = 'Success';
                $result['action']           = 'display';
            } 
            elseif($parts[0] == 'download' && (count($parts) == 1 || $parts[1] == '')){ // Deconnexion de l'utilisateur
                $result['controller']       = 'Download';
                $result['action']           = 'display';
            }

        }

        return $result;
    }
    
}