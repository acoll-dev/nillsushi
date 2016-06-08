<?php
    class UserauthController{
        public static function controller($function = "", $attributes = array(),$id = 0,$json = false){
            if(!empty($function)){
                return self::$function($attributes,$id,$json);
            }else{
                return false;
            }
        }

        
    }
?>
