<?php
    class ThemeController{

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
                    $APP->setDependencyModule(array('Theme','Layer'));
                    $return = array();
                    $theme = array();
                    $layer = Layer::find_by_name(LAYER);

                    $themes = Theme::find_by_sql("SELECT t.idtheme,t.name,t.path,t.fkidlayer FROM ".DB_PREFIX."theme t INNER JOIN ".DB_PREFIX."layer l on t.fkidlayer = l.idlayer WHERE l.status = 1 AND l.idlayer = {$layer->idlayer}");
                    $return[] = array('label' => "",'value' => "");
                    foreach($themes as $key => $value){
                        $return[] = array('label' => $value->name,'value' => $value->idtheme);
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

        private static function update_attributes($attributes = array(),$id = 0,$json = false){

            if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                try
                {
                    global $APP;
                    $APP->CON->transaction();
                    $APP->setDependencyModule(array('Theme','Authentication'));
                    $array = array();
                    $update_attributes = array();
                    $msg = "";
                    $token = "";
                    $idtheme = 0;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'theme','update')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach($attributes as $key => $value){
                        if(isset($value->name)){
                            if ($value->name === 'idtheme') {
                                $idtheme = $value->value;
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

                    $find = Theme::find('all',array('conditions' => "name = '{$array['name']}' and idtheme <> {$idtheme}"));

                    if($find){
                        throw new Exception("MODULE.THEME.ERROR.EXISTS.NAME");
                    }

                    if(!empty($array)){
                        $update_attributes = $array;
                    }else{
                        $update_attributes = $attributes;
                    }

                    $find = Theme::find($id);
                    $name = $find->name;
                    $find->update_attributes($update_attributes);
                    $APP->CON->commit();
                    $APP->activityLog("Update Theme {$name}");

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

        private static function get($attributes = array(),$id = 0,$json = false){
            try{

                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    global $APP;
                    $APP->setDependencyModule(array('Theme','Layer'));

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'theme','select')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $return = array();
                    $themes = array();
                    $layer = Layer::find_by_name(LAYER);
                    if($id > 0){
                        $return = Theme::find($id);
                        return $APP->response($return->to_array(),'success','',$json);
                    }else{

                        $themes = Theme::find_by_sql("SELECT t.idtheme,t.name,t.path,t.fkidlayer FROM ".DB_PREFIX."theme t INNER JOIN ".DB_PREFIX."layer l on t.fkidlayer = l.idlayer WHERE l.status = 1 AND l.idlayer = {$layer->idlayer}");
                        foreach($themes as $key => $value){
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

        private static function insert($attributes = array(),$id = 0,$json = false){
            try{
                global $APP;
                $APP->CON->transaction();
                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                    if(!empty($attributes)){

                        $APP->setDependencyModule(array('Theme','Authentication'));
                        $array = array();
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        if(!Authentication::check_access_control(CUR_LAYER,'theme','insert')){
                            throw new Exception("ERROR.RECUSED.RESOURCE");
                        }

                        ////////////////////////////////////////////////////////////

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

                        $find = Theme::find('all',array('conditions' => "name = '{$array['name']}'"));

                        if($find){
                            throw new Exception("MODULE.THEME.ERROR.EXISTS.NAME");
                        }

                        Theme::create($array);
                        $APP->CON->commit();
                        $APP->activityLog("Register Theme {$array['name']}");
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
        }

        private static function delete($attributes = array(),$id = 0,$json = false){
            if(is_numeric($id) && $id > 0){
                try{
                    global $APP;
                    $APP->CON->transaction();
                    $id = (int) $id;
                    $APP->setDependencyModule(array('Theme'));
                    $theme = 0;
                    $return = false;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'theme','delete')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $theme = Theme::find($id);
                    $name = $theme->name;
                    $theme->delete();
                    $APP->CON->commit();
                    $APP->activityLog("Delete Theme {$name}");
                    return $APP->response(true,'success','SUCCESS.DELETE.COMPLETED',$json);
                }catch(\Exception $e){
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
        }

    }
?>
