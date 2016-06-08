<?php

class LayermoduleController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {

            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->CON->transaction();
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Layermodulemodule', 'Authentication', 'Module'));
                    $array = array();
                    $token = "";

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if ($value->name === 'url') {
                                if (!empty($value->value)) {
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = Tools::formatUrl($value->value . '/');
                                    }else{
                                        $value->value = Tools::formatUrl($value->value);
                                    }
                                }
                            }
                            if ($value->name === 'urlcategory') {
                                if (!empty($value->value)) {
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = Tools::formatUrl($value->value . '/');
                                    }else{
                                        $value->value = Tools::formatUrl($value->value);
                                    }
                                }
                            }
                            if ($value->name === 'status') {
                                if ($value->value == true) {
                                    $value->value = 1;
                                } else {
                                    $value->value = 0;
                                }
                            }
                            if ($value->name === 'token') {
                                $token = $value->value;
                                unset($attributes[$key]);
                            } else {
                                $array = array_merge($array, array($value->name => $value->value));
                            }
                        }
                    }

                    if (Authentication::check_token($token) === false) {
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    $find = Layermodule::find('all', array('conditions' => "(idlayer = {$array['idlayer']} and idmodule = {$array['idmodule']}) or url = '{$array['url']}'"));

                    if ($find) {
                        throw new Exception("MODULE.LAYER.ERROR.EXISTS.IDLAYERANDIDMODULE");
                    }

                    Layermodule::create($array);
                    $last = Layermodule::last();
                    $module = Module::find($last->idmodule);
                    $APP->CON->commit();
                    $APP->activityLog("Register Layermodule {$module->name}");
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("There are no values​for registering!");
                }
            } else {
                throw new Exception("ERROR.NOTFOUND.SESSION");
            }
        } catch (\Exception $e) {
            $APP->CON->rollback();
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                global $APP;
                $APP->setDependencyModule(array('Layermodule'));
                $return = array();
                $layermodules = array();
                $idlayer = $APP->LAYER->idlayer;
                if ($id > 0) {
                    $array = Layermodule::find('all', array('conditions' => "idmodule = {$id} and idlayer = {$idlayer}"));

                    foreach ($array as $value) {
                        $return[] = $value->to_array();
                    }

                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    $layermodules = Layermodule::all();
                    foreach ($layermodules as $key => $value) {
                        $return[] = $value->to_array();
                    }
                    return $APP->response($return, 'success', '', $json);
                }
            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function delete($attributes = array(), $id = 0, $json = false) {
        if (is_numeric($id) && $id > 0) {
            try {
                global $APP;
                $APP->CON->transaction();

                foreach ($attributes as $key => $value) {
                    if ($key === 'idmodule') {
                        $idmodule = $value;
                    }
                    if ($key === 'idlayer') {
                        $idlayer = $value;
                    }
                }

                $APP->setDependencyModule(array('Layermodule', 'Module'));
                $layermodule = false;
                $return = false;
                $module = Module::find($idmodule);
                $name = $module->name;
                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$idlayer} and idmodule = {$idmodule}"));
                if ($layermodule) {
                    foreach ($layermodule as $value) {
                        $value->delete();
                    }
                }
                $APP->CON->commit();
                $APP->activityLog("Delete Layermodule {$name}");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function update_status($attributes = array(), $id = 0, $json = false) {
        global $APP;
        if (!empty($attributes)) {
            try {

                $APP->CON->transaction();
                $APP->setDependencyModule(array('Layermodule', 'Authentication', 'Layer', 'Module'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'config')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $array = array();
                $update_attributes = array();
                $token = "";
                $idlayermodule = 0;
                $idmodule = 0;
                $layer = array();
                $param = array();

                if (isset($attributes['param'])) {
                    $param = $attributes['param'];
                    if (isset($attributes['param']['layer'])) {
                        $layer = Layer::find_by_name($attributes['param']['layer']);
                    }
                    unset($attributes['param']);
                }

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {

                        if ($value->name === 'status') {
                            if ($value->value == true) {
                                $value->value = 1;
                            } else {
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'url') {
                            if (!empty($value->value)) {
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }
                            }
                        }
                        if ($value->name === 'urlcategory') {
                            if (!empty($value->value)) {
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }
                            }
                        }
                        if ($value->name === 'idmodule') {
                            $idmodule = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        } else {
                            $array = array_merge($array, array($value->name => $value->value));
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                $layermodule = false;
                $module = false;
                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$layer->idlayer} AND idmodule = {$idmodule}"));
                $module = Module::find($idmodule);

                foreach ($layermodule as $value) {
                    $idmodule = $value->idmodule;
                    $value->update_attributes(array('status' => $array['status']));
                    break;
                }

                $APP->CON->commit();
                $APP->activityLog("Update Layermodule layer {$layer->name} and module {$module->name}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function generateUrl($attributes = array(), $id = 0, $json = false) {
        global $APP;

        if (!empty($attributes)) {
            try {
                $APP->setDependencyModule(array('Layermodule', 'Filtermodel'));

                $module = (isset($attributes['module'])) ? $attributes['module'] : '';
                $layer = (isset($attributes['layer'])) ? $attributes['layer'] : '';
                $filtermodel = (isset($attributes['filtermodel'])) ? $attributes['filtermodel'] : '';
                $url = (isset($attributes['url'])) ? $attributes['url'] : '';
                $urlmodule = (isset($attributes['urlmodule'])) ? $attributes['urlmodule'] : '';
                $urlcategory = (isset($attributes['urlcategory'])) ? $attributes['urlcategory'] : '';
                $category = (isset($attributes['category'])) ? $attributes['category'] : '';
                $id = (isset($attributes['id'])) ? $attributes['id'] : '';
                $pageparent = (isset($attributes['pageparent'])) ? $attributes['pageparent'] : '';
                $pagechild = (isset($attributes['pagechild'])) ? $attributes['pagechild'] : '';

                $filtermodel = array_filter((strstr($filtermodel, '/')) ? explode('/', $filtermodel) : $filtermodel);

                $filters = Filtermodel::all();
                $pattern = '/(\%(.*)\%)/';
                $findlayer = Layer::find_by_name($layer);
                $array = array();
                $return = BASE_URL;

                foreach ($filtermodel as $value) {
                    foreach ($filters as $value2) {
                        preg_match($pattern, $value2->filter, $matches);

                        if ($matches[1] === $value) {

                            //grava somente os textos do filtro
                            $array[] = $matches[2];
                            break;
                        }
                    }
                }

                foreach ($array as $value) {

                    switch ($value) {

                        case 'view': {
                            if(!empty($urlmodule)){
                                if (substr($urlmodule, -1) != '/') {
                                    $return .= $urlmodule . '/';
                                }else{
                                    $return .= $urlmodule;
                                }
                            }

                            break;
                            }
                        case 'id': {

                            if(!empty($id)){
                                if (substr($id, -1) != '/') {
                                    $return .= $id . '/';
                                }else{
                                    $return .= $id;
                                }
                            }

                            break;
                            }
                        case 'url': {

                            if(!empty($url)){
                                if (substr($url, -1) != '/') {
                                    $return .= $url . '/';
                                }else{
                                    $return .= $url;
                                }
                            }

                            break;
                            }
                        case 'urlcategory': {

                            if(!empty($urlcategory)){
                                if (substr($urlcategory, -1) != '/') {
                                    $return .= $urlcategory . '/';
                                }else{
                                    $return .= $urlcategory;
                                }
                            }

                            break;
                            }
                        case 'category': {

                            if(!empty($category)){
                                if (substr($category, -1) != '/') {
                                    $return .= $category . '/';
                                }else{
                                    $return .= $category;
                                }
                            }

                            break;
                        }
                        case 'pageparent': {

                            if(!empty($pageparent)){
                                if (substr($pageparent, -1) != '/') {
                                    $return .= $pageparent . '/';
                                }else{
                                    $return .= $pageparent;
                                }
                            }

                            break;
                        }
                        case 'pagechild': {

                            if(!empty($pagechild)){
                                if (substr($pagechild, -1) != '/') {
                                    $return .= $pagechild . '/';
                                }else{
                                    $return .= $pagechild;
                                }
                            }

                            break;
                        }
                    }
                }

                return $return;
            } catch (\Exception $e) {
                $APP->writeLog($e);
                return false;
            }
        }
        return false;
    }

}

?>
