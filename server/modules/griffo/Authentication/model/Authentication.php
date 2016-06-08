<?php

class Authentication {

    public static function OAuth(){

        global $APP;
        
        if($APP->REQUIRE_AUTHENTICATION === true){

            if(!isset($_SESSION[CLIENT.LAYER])){

                $APP->setDependencyModule(array('Layer'));

                if(!empty($APP->LAYER->urllogin)){
                    if($APP->FILTER != '/'.$APP->LAYER->urllogin){
                        $APP->URL_REDIRECTION = CUR_ALIAS.'/'.$APP->LAYER->urllogin;
                    }
                }else{
                    require_once ('client/404.php');
                    exit(0);
                }

            }else{
                
                $APP->setDependencyModule(array('User','Usersession','Menu','Layer'));

                $accesstoken = $_SESSION[CLIENT.LAYER];

                $find = false;
                $find = Usersession::find_by_accesstoken(Tools::$salt_Pre . $accesstoken . Tools::$salt_End);
                
                if($find){
                    $currentdate = date('Y-m-d H:i');
                    $expiry = $find->expiry;

                    $obj1 = new DateTime($currentdate);
                    $obj2 = new DateTime($expiry);
                    
                    if($obj1 > $obj2){
                        
                        AuthenticationController::logout();
                        $APP->URL_REDIRECTION = CUR_ALIAS.'/'.$APP->LAYER->urllogin;
                    }else{

                        $auxHour = (int) date('H') + 1;
                        $minutes = (int) date('i');
                        $expiry = date('Y-m-d') . ' ' . $auxHour . ':' . $minutes;
                        
                        $find->update_attributes(array('expiry' => $expiry));
                        if(LAYER === 'admin'){
                            MenuController::controller('load_menu_admin', array('array_filter' => $APP->ARRAY_FILTER, 'cur_layer' => CUR_LAYER));
                        }else{
                            MenuController::controller('loadMenu', array('array_filter' => $APP->ARRAY_FILTER, 'cur_layer' => CUR_LAYER));
                        }
                        UserController::update_session();
                    }

                    $find = false;
                    $find = Layer::find_by_name(LAYER);

                    if($APP->FILTER === '/'.$find->urllogin){
                        $APP->URL_REDIRECTION = CUR_ALIAS;
                    }
                }else{
                    AuthenticationController::logout();
                    $APP->URL_REDIRECTION = CUR_ALIAS.'/'.$APP->LAYER->urllogin;
                }
            }
        }
    }

    public static function Auth(){
        return self::check_session();
    }

    public static function check_token($token = "") {
        if (!empty($token)) {
            if (isset($_SESSION[CLIENT.LAYER])) {
                $tokenSession = $_SESSION[CLIENT.LAYER];
                return (strcmp($tokenSession, $token) == 0) ? true : false;
            }
        }
        return false;
    }

    private static function check_session(){
        
        if (isset($_SESSION[CLIENT.LAYER])) {
            
            try {
                global $APP;
                $APP->setDependencyModule(array('Usersession'));

                $token = $_SESSION[CLIENT.LAYER];
                $usersession = false;
                $usersession = Usersession::find_by_accesstoken(Tools::$salt_Pre . $token . Tools::$salt_End);                      
                if($usersession){

                    return ($usersession->accesstoken === Tools::$salt_Pre . $token . Tools::$salt_End) ? true : false;
                }
            } catch (\Exception $e) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                //session_destroy();
                AuthenticationController::logout();
            }
        }

        return false;
    }

    /*private static function check($idusersession, $token) {
        if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
            if (is_int($idusersession)) {
                try {
                    global $APP;
                    $APP->setDependencyModule(array('Usersession'));

                    $usersession = Usersession::find($idusersession);

                    if ($usersession) {
                        return (Tools::$salt_Pre . $usersession->accesstoken . Tools::$salt_End === $token) ? true : false;
                    }
                } catch (\Exception $e) {
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    //session_destroy();
                    AuthenticationController::logout();
                }
            }
        }

        return false;
    }*/

    public static function access_module($idmodule, $idprofile) {
        global $APP;
        try {

            $return = false;

            if (is_numeric($idmodule) && is_numeric($idprofile)) {

                $APP->setDependencyModule(array('Moduleprofile'));
                $find = false;
                $find = Moduleprofile::find('all', array('conditions' => "idmodule = {$idmodule} AND idprofile = {$idprofile} AND status = 1"));

                if ($find) {
                    $return = true;
                }
            }

            return $return;
        } catch (\Exception $e) {

            $APP->writeLog($e);
            return false;
        }
    }

    public static function check_access_control($layer = "", $module = "", $resource = "",$idprofile = 0) {

        global $APP;
        try {
            if (!empty($layer) && !empty($module) && !empty($resource)) {

                if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                    $APP->setDependencyModule(array('Module', 'Resource', 'Profileresource', 'Layer'));

                    if($idprofile == 0){

                        /*$APP->setDependencyModule(array('Usersession'));

                        $token = Tools::$salt_Pre.$_SESSION[CLIENT.LAYER].Tools::$salt_End;
                        $find = false;
                        $sql = "SELECT p.idprofile FROM ".DB_PREFIX."usersession us INNER JOIN ".DB_PREFIX."userauth au ON us.fkiduserauth = au.iduserauth INNER JOIN ".DB_PREFIX."user u ON au.iduserauth = u.fkiduserauth INNER JOIN ".DB_PREFIX."profile p ON u.fkidprofile = p.idprofile WHERE us.accesstoken = '{$token}' AND u.status = 1";

                        $find = Usersession::find_by_sql($sql);

                        if($find){
                            foreach($find as $value){
                                $idprofile = $value->idprofile;
                                break;
                            }
                        }else{
                            throw new Exception("Invalid token!");
                        }*/
                        $idprofile = $APP->PROFILE['idprofile'];
                    }

                    $module = Module::find_by_name($module);
                    $layer = Layer::find_by_name($layer);
                    $find = false;
                    $find = Resource::find_by_sql("SELECT r.idresource,r.resource,r.idresource,r.fkidmodule FROM " . DB_PREFIX . "resource r INNER JOIN " . DB_PREFIX . "profileresource pr on r.idresource = pr.idresource INNER JOIN " . DB_PREFIX . "profile p on pr.idprofile = p.idprofile INNER JOIN " . DB_PREFIX . "profilelayer pl on p.idprofile = pl.idprofile WHERE r.fkidmodule = {$module->idmodule} AND r.resource = '{$resource}' AND pl.idlayer = {$layer->idlayer}");

                    if ($find) {
                        foreach ($find as $value) {
                            $resource = $value;
                            break;
                        }

                        $find = false;
                        $find = Profileresource::find('all', array('conditions' => "idprofile = {$idprofile} AND idresource = {$resource->idresource}"));

                        if ($find) {
                            foreach ($find as $value) {
                                if ($value->status == 0) {
                                    throw new Exception("The current user does not issue permission for the requested resource!");
                                }
                            }

                            return true;
                        } else {
                            throw new Exception("The current user does not issue permission for the requested resource!");
                        }
                    }
                } else {
                    throw new Exception("The system session variable does not exist!");
                }
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return false;
        }
    }

    public static function check_user_master(){

        global $APP;
        $return = false;

        if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){
            $idprofile = $APP->PROFILE['idprofile'];

            if($idprofile === ID_PROFILE_MASTER){
                $return = true;
            }
        }

        return $return;
    }
}

?>
