<?php
    class FiltermodelController{
        public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
            if (!empty($function)) {

                return self::$function($attributes, $id, $json);
            } else {
                return false;
            }
        }

        private static function select($attributes = array(), $id = 0, $json = false) {
            try {
                global $APP;
                if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                    $return = array();
                    $filtermodel = array();
                    $filtermodel = array();
                    $filters = Filtermodel::all();
                    $return[] = array('label' => "", 'value' => "");
                    foreach ($filters as $key => $value) {
                        $filter = Filtermodel::find($value->idfiltermodel);
                        $return[] = array('filter' => $filter->filter, 'value' => $filter->idfiltermodel);
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
                }
            } catch (\Exception $e) {
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }
?>
