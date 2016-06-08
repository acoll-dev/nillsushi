<?php
    class TemplateController{

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
                    $APP->setDependencyModule(array('Template','Layer'));
                    $return = array();
                    $template = array();
                    $layer = Layer::find_by_name(CUR_LAYER);
                    //$template = Template::find_by_sql("SELECT t.idtemplate,t.name,t.path FROM ".DB_PREFIX."template t INNER JOIN ".DB_PREFIX."layer l on t.idtemplate = l.fkidtemplate WHERE l.idlayer = {$layer->idlayer} AND l.status = 1");
                    $template = Template::all();
                    $return[] = array('label' => "",'value' => "");
                    foreach($template as $key => $value){
                        $return[] = array('label' => $value->name,'value' => $value->idtemplate);
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
                    $APP->setDependencyModule(array('Template','Authentication'));
                    $array = array();
                    $update_attributes = array();
                    $msg = "";
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'template','update')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach($attributes as $key => $value){
                        if(isset($value->name)){
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            if ($value->name === 'idtemplate') {
                                $idtemplate = $value->value;
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

                    $find = Template::find('all',array('conditions' => "name = '{$array['name']}' and idtemplate <> {$idtemplate}"));

                    if($find){
                        throw new Exception("MODULE.TEMPLATE.ERROR.EXISTS.NAME");
                    }

                    if(!empty($array)){
                        $update_attributes = $array;
                    }else{
                        $update_attributes = $attributes;
                    }

                    $find = Template::find($id);
                    $name = $find->name;
                    $find->update_attributes($update_attributes);
                    $APP->CON->commit();
                    $APP->activityLog("Update Template {$name}");

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
                    $APP->setDependencyModule(array('Template','Layer'));

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'template','select')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $return = array();
                    $templates = array();
                    if($id > 0){
                        $return = Template::find($id);
                        return $APP->response($return->to_array(),'success','',$json);
                    }else{
                        //$layer = Layer::find_by_name(CUR_LAYER);
                        //$templates = Template::find_by_sql("SELECT t.idtemplate,t.name,t.path FROM ".DB_PREFIX."template t INNER JOIN ".DB_PREFIX."layer l on t.idtemplate = l.fkidtemplate WHERE l.idlayer = {$layer->idlayer} AND l.status = 1");
                        $templates = Template::all();
                        foreach($templates as $key => $value){
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

                        $APP->setDependencyModule(array('Template','Authentication'));
                        $array = array();
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        if(!Authentication::check_access_control(CUR_LAYER,'template','insert')){
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

                        $find = Template::find('all',array('conditions' => "name = '{$array['name']}'"));

                        if($find){
                            throw new Exception("MODULE.TEMPLATE.ERROR.EXISTS.NAME");
                        }

                        Template::create($array);
                        $APP->CON->commit();
                        $APP->activityLog("Register Template {$array['name']}");
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
                    $APP->setDependencyModule(array('Template'));

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'template','delete')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    $template = 0;
                    $return = false;
                    $template = Template::find($id);
                    $name = $template->name;
                    $template->delete();
                    $APP->CON->commit();
                    $APP->activityLog("Delete Template {$name}");
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
