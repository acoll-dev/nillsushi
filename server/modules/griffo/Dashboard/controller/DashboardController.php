<?php

    class DashboardController{

        public static function controller($function = "", $attributes = array(),$json = false){
            if(!empty($function)){
                return self::$function($attributes,$json);
            }else{
                return false;
            }
        }
    }
?>
