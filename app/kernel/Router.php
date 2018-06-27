<?php

class Router {

    public static function analyse($request){
        $result = array(
            'controller'    => 'Error',
            'action'        => 'error404',
            'params'        => array()
        );

        if($request === '' || $request === '/'){ // Route vers la page d'accueil
            $result['controller']   = 'Page';
            $result['action']       = 'display';
        } else {
            $parts = explode('/', $request);

            if($parts[0] == 'transfert' && (count($parts) == 1 || $parts[1] == '')){ 
                $result['controller']       = 'Page';
                $result['action']           = 'pageTransfert';
            }elseif($parts[0] == 'success' && (count($parts) == 1 || $parts[1] == '')){
                $result['controller']       = 'Success';
                $result['action']           = 'display';
            } 
            elseif($parts[0] == "download" && count($parts) == 2){ 
                $result['controller']       = 'Download';
                $result['action']           = 'selectFiles';
                $result["params"]["id"] = $parts[1];  
            } 
        }
        return $result;
    }
}