<?php
    class ProfileController{
        
        public static function controller($function = "", $attributes = array(),$id = 0,$json = false){
            if(!empty($function)){
                return self::$function($attributes,$id,$json);
            }else{
                return false;
            }
        }

        private static function select($attributes = array(),$id = 0,$json = false){
            try{
                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    global $APP;
                    $APP->setDependencyModule(array('Profile','Layer'));
                    $return = array();
                    $profile = array();
                    $layer = Layer::find_by_name(CUR_LAYER);
                    $profile = Profile::find_by_sql("SELECT p.idprofile,p.name,p.label FROM ".DB_PREFIX."profile p INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile INNER JOIN ".DB_PREFIX."layer l on pl.idlayer = l.idlayer WHERE pl.idlayer = {$layer->idlayer} AND p.idprofile <> ".ID_PROFILE_MASTER." AND pl.status = 1 AND l.status = 1");
                    $return[] = array('label' => "",'value' => "");
                    foreach($profile as $key => $value){
                        $return[] = array('label' => $value->label,'value' => $value->idprofile);
                    }
                    return $APP->response($return,'success','',$json);

                }else{
                    return $APP->response(false,'danger','ERROR.NOTFOUND.SESSION',$json);
                }
            }catch(\Exception $e){
                $APP->writeLog($e);
                return $APP->response(false,'danger',$e->getMessage(),$json);
            }
        }

        private static function insert($attributes = array(),$id = 0,$json = false){
            try{
                global $APP;
                $APP->CON->transaction();
                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    if(!empty($attributes)){

                        $APP->setDependencyModule(array('Profile','Profilelayer','Layer','Authentication','Module','Profileresource','Profilemenu','Moduleprofile'));
                        $array = array();
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        if(!Authentication::check_access_control(CUR_LAYER,'profile','insert')){
                            throw new Exception("ERROR.RECUSED.RESOURCE");
                        }

                        ////////////////////////////////////////////////////////////

                        foreach($attributes as $key => $value){
                            if(isset($value->name)){
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

                        $find = Profile::find('all',array('conditions' => "name = '{$array['name']}' OR label = '{$array['label']}'"));

                        if($find){
                            throw new Exception("There is already a profile registered under that name or label!");
                        }

                        Profile::create($array);

                        $lastprofile = Profile::last();
                        $layer = Layer::find_by_name(CUR_LAYER);

                        $find = false;
                        $find = Profilelayer::find('all',array('conditions' => "idprofile = {$lastprofile->idprofile} and idlayer = {$layer->idlayer}"));

                        if(empty($find)){
                            Profilelayer::create(array('idprofile' => $lastprofile->idprofile,'idlayer' => $layer->idlayer,'status' => 1));
                            
                            $layer = Layer::find_by_name(LAYER);
                            Profilelayer::create(array('idprofile' => $lastprofile->idprofile,'idlayer' => $layer->idlayer,'status' => 1));
                        }
                        
                        //Cadastrando todos os Recursos e módulos cadastrados
                        
                        $sql = "SELECT distinct r.* FROM ".DB_PREFIX."moduleprofile mp inner join ".DB_PREFIX."profile p on mp.idprofile = p.idprofile inner join ".DB_PREFIX."profileresource pr on pr.idprofile = p.idprofile inner join ".DB_PREFIX."resource r on pr.idresource = r.idresource inner join ".DB_PREFIX."module m on r.fkidmodule = m.idmodule inner join ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule inner join ".DB_PREFIX."layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and m.status = 1";

                        $find = false;
                        $find = Module::find_by_sql($sql);
                        
                        if($find){
                           foreach($find as $value){
                               
                               Profileresource::create(array('idprofile' => $lastprofile->idprofile,'idresource' => $value->idresource,'status' => 1));
                           } 
                        }
                        
                        //Cadastrando todos os Menus do profilemenu
                        
                        $sql = "SELECT distinct m.idmenu FROM ".DB_PREFIX."moduleprofile mp inner join ".DB_PREFIX."profile p on mp.idprofile = p.idprofile inner join ".DB_PREFIX."profilemenu pm on pm.idprofile = p.idprofile inner join ".DB_PREFIX."menu m on pm.idmenu = m.idmenu inner join ".DB_PREFIX."module mo on m.fkidmodule = mo.idmodule inner join ".DB_PREFIX."layermodule lm on mo.idmodule = lm.idmodule inner join ".DB_PREFIX."layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and mo.status = 1 and m.status = 1";

                        $find = false;
                        $find = Module::find_by_sql($sql);
                        
                        if($find){
                           foreach($find as $value){
                               
                               Profilemenu::create(array('idprofile' => $lastprofile->idprofile,'idmenu' => $value->idmenu,'status' => 1));
                               
                           } 
                        }
                        
                        //Cadastrando os moduleprofile
                        
                        $find = false;
                        $find = Module::all();
                        
                        foreach($find as $value){
                             Moduleprofile::create(array('idmodule' => $value->idmodule,'idprofile' => $lastprofile->idprofile,'visible' => 1,'status' => 1));
                        }
                        
                        $APP->CON->commit();
                        $APP->activityLog("Register Profile {$array['name']}");
                        return $APP->response(true,'success','SUCCESS.CREATE.COMPLETED',$json);
                    }else{
                        throw new Exception("There are no values for registering!");
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

        private static function get($attributes = array(),$id = 0,$json = false){
            try{

                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    global $APP;
                    $APP->setDependencyModule(array('Profile','Layer'));

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'profile','select')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $return = array();
                    $profiles = array();
                    if($id > 0){
                        if(ID_PROFILE_MASTER != $id){
                            $return = Profile::find($id);
                            return $APP->response($return->to_array(),'success','',$json);
                        }else{
                            return $APP->response($return,'success','',$json);
                        }
                    }else{
                        $layer = Layer::find_by_name(CUR_LAYER);
                        $profiles = Profile::find_by_sql("SELECT p.idprofile,p.name,p.label FROM ".DB_PREFIX."profile p INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile INNER JOIN ".DB_PREFIX."layer l on pl.idlayer = l.idlayer WHERE pl.idlayer = {$layer->idlayer} AND p.idprofile <> ".ID_PROFILE_MASTER." AND pl.status = 1 AND l.status = 1");
                        foreach($profiles as $key => $value){
                            $return[] = $value->to_array();
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

        private static function delete($attributes = array(),$id = 0,$json = false){
            if(is_numeric($id) && $id > 0){
                try{
                    global $APP;
                    $APP->CON->transaction();
                    $id = (int) $id;
                    $APP->setDependencyModule(array('Profile','Profilelayer','Layer'));
                    $profile = 0;
                    $return = false;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'profile','delete')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    if($id != ID_PROFILE_MASTER){

                        $layer = Layer::find_by_name(CUR_LAYER);
                        $find = false;
                        $find = Profilelayer::find('all',array('conditions' => "idprofile = {$id} and idlayer = {$layer->idlayer} and status = 1"));

                        if($find){
                            foreach($find as $value){
                                $value->delete();
                            }
                        }

                        $profile = Profile::find($id);
                        $name = $profile->name;
                        $profile->delete();
                        $APP->CON->commit();
                        $APP->activityLog("Delete Profile {$name}");
                        return $APP->response(true,'success','SUCCESS.DELETE.COMPLETED',$json);
                    }else{
                        return $APP->response(true,'danger','MODULE.PROFILE.ERROR.RECUSED.DELETE',$json);
                    }

                }catch(\Exception $e){
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
        }

        private static function update_attributes($attributes = array(),$id = 0,$json = false){

            if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                try
                {
                    global $APP;
                    $APP->CON->transaction();
                    $APP->setDependencyModule(array('Profile','Authentication'));
                    $array = array();
                    $update_attributes = array();
                    $token = "";
                    $idprofile = 0;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'profile','update')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach($attributes as $key => $value){
                        if(isset($value->name)){
                            if ($value->name === 'idprofile') {
                                $idprofile = $value->value;
                                unset($attributes[$key]);
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

                    $find = Profile::find('all',array('conditions' => "(name = '{$array['name']}' OR label = '{$array['label']}') and idprofile <> {$idprofile}"));

                    if($find){
                        throw new Exception("There is already a profile registered under that name or label!");
                    }

                    if(!empty($array)){
                        $update_attributes = $array;
                    }else{
                        $update_attributes = $attributes;
                    }

                    $find = Profile::find($id);
                    $name = $find->name;
                    $find->update_attributes($update_attributes);
                    $APP->CON->commit();
                    $APP->activityLog("Update Profile {$name}");

                    return $APP->response(true,'success','SUCCESS.UPDATE.COMPLETED',$json);

                } catch (\Exception $e)
                {
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
            return $APP->response(false,'danger','ERROR.EMPTY.VALUE',$json);
        }
    }
?>
