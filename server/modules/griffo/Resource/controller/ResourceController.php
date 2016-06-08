<?php
    class ResourceController{
        public static function controller($function = "", $attributes = array(),$id = 0,$json = false){
            if(!empty($function)){
                return self::$function($attributes,$id,$json);
            }else{
                return false;
            }
        }

        private static function get($attributes = array(),$id = 0,$json = false){
            try{
                if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){
                    global $APP;
                    $APP->setDependencyModule(array('Resource'));
                    $return = array();
                    $resources = array();
                    if($id > 0){
                        $return = Resource::find($id);
                        return $APP->response($return->to_array(),'success','',$json);
                    }else{
                        $resources = Resource::all();
                        foreach($resources as $key => $value){
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
    }
?>
