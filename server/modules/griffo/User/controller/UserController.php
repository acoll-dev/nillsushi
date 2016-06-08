<?php
    class UserController{

        public static function controller($function = "", $attributes = array(),$id = 0,$json = false){
            if(!empty($function)){
                return self::$function($attributes,$id,$json);
            }else{
                return false;
            }
        }

        private static function update_attributes($attributes = array(),$id = 0,$json = false){
            global $APP;
            if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                try
                {
                    $APP->CON->transaction();
                    $APP->setDependencyModule(array('User','Authentication'));
                    $array = array();
                    $update_attributes = array();
                    $msg = "";
                    $token = "";
                    $iduser = 0;
                    $editprofile = false;
                    $idprofile = 0;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'user','update')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach($attributes as $key => $value){
                        if(isset($value->name)){
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            if ($value->name === 'iduser') {
                                $iduser = $value->value;
                                unset($attributes[$key]);
                                continue;
                            }
                            if($value->name === 'status'){
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }
                            if($value->name === 'token'){
                                $token = $value->value;
                                unset($attributes[$key]);
                                continue;
                            }
                            if(is_int(strpos($value->name,'re-'))){
                                unset($attributes[$key]);
                                continue;
                            }else{
                                if($value->name === 'fkidprofile'){
                                    $idprofile = $value->value;
                                    if(self::checkEditProfile($iduser,$idprofile)){
                                        $editprofile = true;
                                    }
                                }
                                if($value->name === 'menuorder'){
                                    if($editprofile === true){
                                        $value->value = self::returnModuleProfile($idprofile);
                                    }else{
                                        $value->value = serialize($value->value);
                                    }
                                }
                                if($value->name === 'theme'){
                                    $APP->setDependencyModule(array('Theme'));
                                    $theme = Theme::find_by_name($value->value);
                                    $value->name = 'fkidtheme';
                                    $value->value = $theme->idtheme;

                                }
                                if($value->name === 'password'){
                                    $value->value = Tools::myMD5($value->value);
                                 }
                            }
                            $array = array_merge($array,array($value->name => $value->value));
                        }
                    }

                    if(Authentication::check_token($token) === false){
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    if(count($attributes) > 1){
                        if(isset($array['login']) && isset($array['name']) && $iduser){
                            $find = User::find('all',array('conditions' => "(name = '{$array['name']}' OR login = '{$array['login']}') and iduser <> {$iduser}"));


                            if($find){
                                throw new Exception("MODULE.USER.ERROR.EXISTS.NAMEORLOGIN");
                            }
                        }
                    }

                    if(!empty($array)){
                        $update_attributes = $array;
                    }else{
                        $update_attributes = $attributes;
                    }

                    if(count($update_attributes) === 1){
                        foreach($update_attributes as $key => $value){
                            $msg = ucfirst(strtolower($key))." changed successfully!";
                            break;
                        }
                    }else{
                        $msg = "MODULE.PROFILE.SUCCESS.UPDATE";
                    }

                    $find = User::find($id);
                    $name = $find->name;
                    $find->update_attributes($update_attributes);
                    $APP->CON->commit();
                    $APP->activityLog("Update User {$name}");
                    self::update_session();

                    return $APP->response(true,'success',$msg,$json);

                } catch (\Exception $e)
                {
                    $APP->writeLog($e);
                    $APP->CON->rollback();
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
            return $APP->response(false,'danger','ERROR.EMPTY.VALUE',$json);
        }

        private static function change_password($attributes = array(),$id = 0,$json = false){
            if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                    try
                    {
                        global $APP;
                        $APP->setDependencyModule(array('User','Authentication','Userauth'));
                        $array = array();
                        $update_attributes = array();
                        $oldPassword = "";
                        $password = "";
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        if(!Authentication::check_access_control(CUR_LAYER,'user','update')){
                            throw new Exception("ERROR.RECUSED.RESOURCE");
                        }

                        ////////////////////////////////////////////////////////////

                        foreach($attributes as $key => $value){
                            if(isset($value->name)){
                                if($value->name === 'old-password'){
                                    $oldPassword = Tools::myMD5($value->value);
                                }
                                if($value->name === 'password'){
                                   $password = Tools::myMD5($value->value);
                                }
                                if($value->name === 'token'){
                                   $token = $value->value;
                                }
                            }
                        }

                        if(Authentication::check_token($token) === false){
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }

                        if(!empty($oldPassword)){
                            $APP->CON->transaction();
                            $user = User::find($id);
                            $name = $user->name;
                            if($user->password === $oldPassword){
                                $userauth = false;
                                $userauth = Userauth::find($user->fkiduserauth);
                                
                                if($userauth){
                                    $user->update_attributes(array('password' => $password));
                                    $userauth->update_attributes(array('password' => $password));
                                    
                                    $APP->CON->commit();
                                    $APP->activityLog("Update password User {$name}");
                                    return $APP->response(true,'success','MODULE.USER.SUCCESS.PASSWORD.UPDATE',$json);
                                }else{
                                    $APP->CON->rollback();
                                return $APP->response(false,'warning','MODULE.USER.ERROR.USERAUTH.EMPTY',$json);
                                }
                            }else{
                                $APP->CON->rollback();
                                return $APP->response(false,'warning','MODULE.USER.WARNING.PASSWORD.EQUAL',$json);
                            }
                        }else{
                            $APP->CON->rollback();
                            return $APP->response(false,'danger','MODULE.USER.ERROR.PASSWORD.EMPTY',$json);
                        }
                    } catch (\Exception $e)
                    {
                        $APP->CON->rollback();
                        $APP->writeLog($e);
                        return $APP->response(false,'danger',$e->getMessage(),$json);
                    }
                }
                return $APP->response(false,'danger','ERROR.EMPTY.VALUE',$json);
            }
            return $APP->response(false,'danger','ERROR.NOTFOUND.SESSION',$json);
        }

        private static function get($attributes = array(),$id = 0,$json = false){
            try{

                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    global $APP;
                    $APP->setDependencyModule(array('User','Layer'));

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'user','select')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $return = array();
                    $users = array();
                    $idUserSession = IDUSER;
                    if($id > 0){
                        $return = User::find($id);
                        return $APP->response($return->to_array(),'success','',$json);
                    }else{
                        $layer = Layer::find_by_name(CUR_LAYER);

                        $idprofile = $APP->PROFILE['idprofile'];
                        
                        $users = User::find_by_sql("SELECT u.iduser,u.name,u.nickname,u.login,u.password,u.datecad,u.email,u.picture,u.menuorder,u.status,u.fkidprofile,u.fkidlanguage,u.fkidlayer,u.fkidtheme,u.fkiduserauth FROM ".DB_PREFIX."user u INNER JOIN ".DB_PREFIX."profile p on u.fkidprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile INNER JOIN ".DB_PREFIX."layer l on pl.idlayer = l.idlayer WHERE pl.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        foreach($users as $key => $value){
                            if($idUserSession === ID_PROFILE_MASTER){
                                $return[] = $value->to_array();
                            }else{
                                if($value->iduser != ID_PROFILE_MASTER){
                                    $return[] = $value->to_array();
                                }
                            }
                        }

                        return $APP->response($return,'success','',$json);
                        
                    }
                }else{
                    return $APP->response(false,'danger','ERROR.NOTFOUND.SESSION',$json);
                }
            }catch(\Exception $e){
                $APP->writeLog($e);
                return $APP->response(false,'danger',$e->getMessage(),$json);
            }
        }
        
        private static function checkEditProfile($iduser = 0,$idprofile = 0){
            global $APP;
            $return = false;
            if($iduser > 0 && $idprofile > 0){
                $APP->setDependencyModule(array('User'));
                $user = User::find($iduser);
                if($user->fkidprofile != $idprofile){
                    $return = true;
                }
            }

            return $return;
        }

        private static function returnModuleProfile($idprofile = 0){

            global $APP;
            $return = array();

            $APP->setDependencyModule(array('Moduleprofile','Module','Layer'));
            $layer = Layer::find_by_name(CUR_LAYER);

            if($idprofile === 0){
                if(isset($_SESSION[CLIENT.LAYER])  && LAYER === 'admin'){

                    $sessionidprofile = $APP->PROFILE['idprofile'];

                    $find = Moduleprofile::find_by_sql("SELECT mp.idmodule,mp.idprofile,mp.status FROM ".DB_PREFIX."moduleprofile mp INNER JOIN ".DB_PREFIX."profile p on mp.idprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile WHERE mp.idprofile = {$sessionidprofile} AND mp.status = 1 AND pl.status = 1 AND pl.idlayer = {$layer->idlayer}");

                    $modules = array();

                    foreach($find as $value){
                        $module = Module::find($value->idmodule);
                        if($module->name != 'dashboard'){
                            $modules[] = $value->idmodule;
                        }else{
                            $dashboard = $module->idmodule;
                        }
                    }
                    array_unshift($modules,$dashboard);
                    $return = serialize($modules);
                }
            }else{

                $find = Moduleprofile::find_by_sql("SELECT mp.idmodule,mp.idprofile,mp.status FROM ".DB_PREFIX."moduleprofile mp INNER JOIN ".DB_PREFIX."profile p on mp.idprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile WHERE mp.idprofile = {$idprofile} AND mp.status = 1 AND pl.status = 1 AND pl.idlayer = {$layer->idlayer}");

                $modules = array();

                foreach($find as $value){
                    $module = Module::find($value->idmodule);
                    if($module->name != 'dashboard'){
                        $modules[] = $value->idmodule;
                    }else{
                        $dashboard = $module->idmodule;
                    }
                }
                array_unshift($modules,$dashboard);
                $return = serialize($modules);
            }

            return $return;
        }

        private static function insert($attributes = array(),$id = 0,$json = false){
            try{
                global $APP;
                $APP->CON->transaction();
                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    if(!empty($attributes)){

                        $APP->setDependencyModule(array('User','Authentication','Userauth','Userauthapplication','Applicationauth'));
                        $array = array();
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        if(!Authentication::check_access_control(CUR_LAYER,'user','insert')){
                            throw new Exception("ERROR.RECUSED.RESOURCE");
                        }

                        ////////////////////////////////////////////////////////////

                        foreach($attributes as $key => $value){
                            if(isset($value->name)){
                                if(empty($value->value)){
                                    $value->value = null;
                                }
                                if($value->name === 'status'){
                                    if($value->value == true){
                                        $value->value = 1;
                                    }else{
                                        $value->value = 0;
                                    }
                                }
                                if($value->name === 'password'){
                                   $value->value = Tools::myMD5($value->value);
                                }
                                if($value->name === 'token'){
                                    $token = $value->value;
                                    unset($attributes[$key]);
                                }else{
                                    $array = array_merge($array,array($value->name => $value->value));
                                }
                            }
                        }

                        if(Authentication::check_token($token) === false){
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }

                        $find = User::find('all',array('conditions' => "name = '{$array['name']}' OR login = '{$array['login']}'"));

                        if($find){
                            throw new Exception("MODULE.USER.ERROR.EXISTS.NAMEORLOGIN");
                        }

                        if(!isset($attributes['menuorder']) || empty($attributes['menuorder'])){
                            /*if(isset($_SESSION[CLIENT.LAYER]['fkidprofile'])){
                                $idprofile = $_SESSION[CLIENT.LAYER]['fkidprofile'];
                            }
                            $find = Moduleprofile::find('all',array('conditions' => "idprofile = {$idprofile}"));

                            $modules = array();
                            foreach($find as $value){
                                $module = Module::find($value->idmodule);
                                if($module->name != 'dashboard'){
                                    $modules[] = $value->idmodule;
                                }else{
                                    $dashboard = $module->idmodule;
                                }
                            }
                            array_unshift($modules,$dashboard);
                            $array['menuorder'] = serialize($modules);*/
                            $array['menuorder'] = self::returnModuleProfile();
                        }
                        Userauth::create(array('user' => $array['login'],'password' => $array['password']));
                        $userauth = Userauth::last();
                        $array['fkiduserauth'] = $userauth->iduserauth;
                        
                        $application = Applicationauth::find_by_name(LAYER);
                        
                        Userauthapplication::create(array('iduserauth' => $userauth->iduserauth,'idapplicationauth' => $application->idapplicationauth,'status' => 1));
                        
                        User::create($array);
                        
                        $APP->CON->commit();
                        $APP->activityLog("Register User {$array['name']}");
                        return $APP->response(true,'success','SUCCESS.CREATE.COMPLETED',$json);
                    }else{
                        throw new Exception("There are no values​for registering!");
                    }
                }else{
                    throw new Exception("ERROR.NOTFOUND.SESSION");
                }
            }catch(\Exception $e){
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false,'danger',$e->getMessage(),$json);
            }
        }

        private static function delete($attributes = array(),$id = 0,$json = false){
            if(is_numeric($id) && $id > 0){
                try{
                    global $APP;
                    $APP->CON->transaction();
                    $id = (int) $id;
                    $APP->setDependencyModule(array('User'));
                    $user = 0;
                    $return = false;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'user','delete')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $user = User::find($id);
                    $name = $user->name;
                    if($user->iduser != 1){
                        $user->delete();
                        $APP->CON->commit();
                        $APP->activityLog("Delete User {$name}");
                        return $APP->response(true,'success','SUCCESS.DELETE.COMPLETED',$json);
                    }else{
                        throw new Exception('MODULE.USER.ERROR.ADMIN.NOTDELETE');
                    }
                }catch(\Exception $e){
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
        }

        /*private static function access_control($attributes = array(),$id = 0,$json = false){
            try{
                global $APP;
                $APP->CON->transaction();
                if(isset($_SESSION[CLIENT.LAYER])){

                    if(!empty($attributes)){

                        $APP->setDependencyModule(array('User','Authetication'));
                        $array = array();
                        $token = "";

                        foreach($attributes as $key => $value){
                            if(isset($value->name)){
                                if(empty($value->value)){
                                    $value->value = null;
                                }
                                if($value->name === 'token'){
                                    $token = $value->value;
                                    unset($attributes[$key]);
                                }else{
                                    $array = array_merge($array,array($value->name => $value->value));
                                }
                            }
                        }

                        if(Authentication::check_token($token) === false){
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }

                        $find = User::find('all',array('conditions' => "name = '{$array['name']}' OR login = '{$array['login']}'"));

                        if($find){
                            throw new Exception("MODULE.USER.ERROR.EXISTS.NAMEORLOGIN");
                        }

                        User::create($array);
                        $APP->CON->commit();
                        $APP->activityLog("Register User {$array['name']}");
                        return $APP->response(true,'success','SUCCESS.CREATE.COMPLETED',$json);
                    }else{
                        throw new Exception("ERROR.NOTFOUND.VALUE");
                    }
                }else{
                    throw new Exception("ERROR.NOTFOUND.SESSION");
                }
            }catch(\Exception $e){
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false,'danger',$e->getMessage(),$json);
            }
        }*/

        public static function update_menu_user($iduser = 0, $idmodule = 0,$action = 'add', $newmenu = array()) {
            global $APP;
            try {

                $APP->setDependencyModule(array('User', 'Module'));
                $return = false;
                $menuorder = array();

                if($iduser > 0){

                    $user = User::find($iduser);

                    switch($action){
                        case 'add': {

                            if(!empty($newmenu) && is_array($newmenu)){

                                $user->menuorder = serialize($newmenu);
                                $user->save();
                                $return = true;
                            }else if($idmodule > 0){

                                if(!empty($user->menuorder)){
                                    $menuorder = unserialize($user->menuorder);
                                    if(array_search($idmodule,$menuorder) === false){
                                        $menuorder[] = $idmodule;
                                    }
                                    $menuorder = serialize($menuorder);
                                }else{
                                    $menuorder = serialize(array($idmodule));
                                }

                                $user->menuorder = $menuorder;
                                $user->save();
                                $return = true;
                            }

                            break;
                        }
                        case 'remove': {

                            if($idmodule > 0){

                                if(!empty($user->menuorder)){
                                    $menuorder = unserialize($user->menuorder);
                                    $key = array_search($idmodule,$menuorder);
                                    if($key !== false){
                                        unset($menuorder[$key]);
                                    }

                                    $menuorder = serialize($menuorder);
                                    $user->menuorder = $menuorder;
                                    $user->save();
                                }

                                $return = true;
                            }

                            break;
                        }
                    }
                }
                return $return;

            } catch (\Exception $e) {
                //echo $e->getMessage();
                $APP->writeLog($e);
                return false;
            }
        }
        
        public static function update_session(){
        if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
            global $APP;
            try {
                $APP->CON->transaction();
                $APP->setDependencyModule(array('User', 'Profile','Theme'));
                $token = $_SESSION[CLIENT.LAYER];
                $iduser = IDUSER;
                $user = User::find($iduser);
                $profile = Profile::find($user->fkidprofile);
                $theme = Theme::find($user->fkidtheme);
                $APP->THEME = $theme->name;
                $accessControl = AuthenticationController::access_control(array('idprofile' => $profile->idprofile));
                $APP->RESOURCES = $accessControl['response'];
                $APP->PROFILE = array('idprofile' => $profile->idprofile, 'name' => $profile->name, 'label' => $profile->label, 'accessControl' => $accessControl['response']);

                $APP->USER = array('id' => $user->iduser, 'name' => $user->name,'nickname' => $user->nickname,'profile' => $APP->PROFILE,'picture' => $user->picture,'email' => $user->email,'theme' => $APP->THEME,'menuorder' => $user->menuorder,'token' => $token,'fkidlayer' => $user->fkidlayer);
                
                $APP->CON->commit();
                return true;
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return false;
            }
        } else {
            return false;
        }
    }
    }
?>
