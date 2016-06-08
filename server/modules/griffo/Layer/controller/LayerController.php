<?php

class LayerController {

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

                    $APP->setDependencyModule(array('Layer', 'Authentication'));
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'insert')) {
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if ($value->name === 'path') {
                                if (!empty($value->value)){
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = $value->value . '/';
                                    }
                                }
                            }
                            if ($value->name === 'url') {
                                if (!empty($value->value)){
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = Tools::formatUrl($value->value . '/');
                                    }else{
                                        $value->value = Tools::formatUrl($value->value);
                                    }
                                }
                            }
                            if ($value->name === 'urllogin') {
                                if (!empty($value->value)){
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

                    $find = Layer::find('all', array('conditions' => "name = '{$array['name']}' OR label = '{$array['label']}'"));

                    if ($find) {
                        throw new Exception("MODULE.LAYER.ERROR.EXISTS.NAMEORLABEL");
                    }

                    Layer::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Layer {$array['name']}");
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

    private static function select($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                $idprofile = $APP->PROFILE['idprofile'];
                
                $APP->setDependencyModule(array('Layer', 'ProfileLayer'));
                $return = array();
                $layer = array();
                $layers = array();
                $layers = ProfileLayer::find('all', array('conditions' => "idprofile = {$idprofile}"));
                $return[] = array('label' => "", 'value' => "");
                foreach ($layers as $key => $value) {
                    $layer = Layer::find($value->idlayer);
                    $return[] = array('label' => $layer->label,'name' => $layer->name, 'value' => $layer->idlayer);
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

    private static function get($attributes = array(), $id = 0, $json = false) {
        
        global $APP;
        
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                $APP->setDependencyModule(array('Layer', 'Module','Template'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'select')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $return = array();
                $layers = array();

                if ($id > 0) {
                    $return = Layer::find($id);
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    $layers = Layer::all();
                    $cont = 0;
                    foreach ($layers as $key => $value) {
                        $template = Template::find($value->fkidtemplate);
                        $return[$cont] = $value->to_array();
                        $return[$cont]['template'] = $template->name;
                        $cont++;
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
                $id = (int) $id;
                $APP->setDependencyModule(array('Layer'));
                $layer = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'delete')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $layer = Layer::find($id);
                $name = $layer->name;
                $url = $layer->url;
                $layer->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Layer {$name}");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function update_attributes($attributes = array(), $id = 0, $json = false) {
        global $APP;
        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {

                $APP->CON->transaction();
                $APP->setDependencyModule(array('Layer', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idlayer = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'update')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'path') {
                            if (!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = $value->value . '/';
                                }
                            }
                        }
                        if ($value->name === 'url') {
                            if (!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }else{
                                    $value->value = Tools::formatUrl($value->value);
                                }
                            }
                        }
                        if ($value->name === 'urllogin') {
                            if (!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }else{
                                    $value->value = Tools::formatUrl($value->value);
                                }
                            }
                        }
                        if ($value->name === 'idlayer') {
                            $idlayer = $value->value;
                            unset($attributes[$key]);
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

                $find = Layer::find('all', array('conditions' => "(name = '{$array['name']}' OR label = '{$array['label']}') and idlayer <> {$idlayer}"));

                if ($find) {
                    throw new Exception("MODULE.LAYER.ERROR.EXISTS.NAMEORLABEL");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Layer::find($id);
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Layer {$name}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function checkFilter($attributes = array(), $id = 0, $json = false) {
        
        global $APP;

        if (empty($attributes)) {

            return $APP->response(false, 'danger', 'ERROR.NOTFOUND.PARAMETERS', $json);
        }
        try {
            $array = array();
            $arrayLayer = array();
            $return = array();
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Filtermodel','Template'));
            
            $layer = Layer::find_by_name($attributes['layer']);//obtem informações da layer pelo nome
            
            $filterModel = $layer->filtermodel;//pega o modelo de filtro da layer
            
            $filterModel = array_filter((strstr($filterModel, '/')) ? explode('/', $filterModel) : $filterModel);
            //transforma em array sendo cada posição do modelo de filtro da layer o valor entre barra(/)
            
            $url = array_filter((strstr($attributes['url'], '/')) ? explode('/', $attributes['url']) : (array) $attributes['url']);//transforma em array sendo cada posição da url repassada o valor entre barra(/)            
            
            //se existe igual em alguma posição da url repassa, é excluída a posição
            foreach ($url as $key => $value) {
                if (strpos($value, '=')) {
                    unset($url[$key]);
                }
            }

            $filters = Filtermodel::all();//obtem todos os modelos de filtros
            $pattern = '/(\%(.*)\%)/';//define a regex para validar cada posição dos arrays dos filtros
            
            //Verificando se contém algum filtro da url igual ao do modelo da layer, se tiver armazena em um array
            foreach ($filterModel as $value) {
                foreach ($filters as $value2) {
                    preg_match($pattern, $value2->filter, $matches);
                    if ($matches[1] === $value) {
                        $array[] = $matches[2];
                        break;
                    }
                }
            }
            
            //Verificando se existe o filtro login
            
            if($key = array_search('login', $array) !== false){
                if(LAYER === 'admin'){
                    $APP->REQUIRE_AUTHENTICATION = true;
                }
                unset($array[$key-1]);
            }
            
            if (count($array) > 0 && count($url) > 0) {

                $cont = 0;
                $aux = array();
                $idUrl = "";
                foreach ($url as $value) {
                    $aux[$cont] = $value;
                    $cont++;
                }
                $filter = $aux;
                $pathModule = "";
                
                rsort($array);
                
                foreach ($array as $key => $value) {
                    foreach ($filter as $key2 => $value2) {

                        $value2 = $APP->PURIFIER->purify($value2);
                        $regexFilter = Filtermodel::find('all', array('conditions' => "filter = '%{$value}%'"));
                        foreach ($regexFilter as $value3) {
                            $regexCurFilter = '/' . $value3->regex . '/';
                            break;
                        }

                        preg_match($regexCurFilter, $value2, $matches);

                        if ($matches[0] === $value2) {
                            if (isset($array[$key2])) {
                                
                                switch ($array[$key2]) {

                                    case 'layer': {
                                            
                                            $find = false;
                                            $find = Layer::find_by_name($value2);

                                            if ($find) {
                                                $return[$array[$key2]] = $value2;
                                            }/*else{
                                                require_once (DIR_CURTEMPLATE . '404.php');
                                                exit(0);
                                            }*/
                                            break;
                                        }
                                    case 'language': {
                                            $APP->useModule('Language');
                                            $find = false;
                                            $find = Language::find_by_name($value2);
                                            if ($find) {
                                                $return[$array[$key2]] = $value2;
                                            }/*else{
                                                require_once (DIR_CURTEMPLATE . '404.php');
                                                exit(0);
                                            }*/
                                            break;
                                        }
                                    case 'login': {
                                        
                                        $find = false;
                                        $find = Layer::find_by_name($value2);
                                        if ($find) {
                                            $return[$array[$key2]] = $value2;
                                            $APP->URL_REDIRECION = $find->urllogin;
                                            $APP->REQUIRE_AUTHENTICATION = true;
                                        }
                                        
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    break;
                }
            }

            if (!empty($return)) {
                //Captura somente no REST
                if (array_key_exists('layer', $return)) {
                    define('CUR_LAYER', $return['layer']);
                } else {
                    //define('CUR_LAYER', $attributes['layer']);
                }
            } else {
                //define('CUR_LAYER', $attributes['layer']);
            }

            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Thumb', 'Layermodule'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            //$thumb = false;
            $layermodule = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            //$thumb = Thumb::find_by_fkidlayer($layer->idlayer);
            //$template = false;
            //$template = TemplateController::controller('select');
            //$template = $template['response'];
            //$layermodule = Layermodule::find_by_sql("SELECT * FROM ".DB_PREFIX."layermodule lm WHERE lm.idlayer = {$layer->idlayer} AND lm.default = 1");

            /*if ($layermodule) {
                foreach ($layermodule as $value) {
                    $layermodule = $value;
                    break;
                }
            }*/

            $return = $layer->to_array();

            /*if($thumb && $layer && $layermodule){
                $return = array('layer_idlayer' => $layer->idlayer, 'layer_filtermodel' => $layer->filtermodel, 'layer_default' => $layermodule->idmodule, 'layer_url' => $layermodule->url, 'layer_custom' => $layermodule->custom, 'layer_fkidtemplate' => $template, 'thumb_label' => $thumb->label, 'thumb_width' => $thumb->width, 'thumb_height' => $thumb->height);

            }else if($layer && $layermodule){
                $return = array('layer_idlayer' => $layer->idlayer, 'layer_filtermodel' => $layer->filtermodel, 'layer_default' => $layermodule->idmodule, 'layer_url' => $layermodule->url, 'layer_custom' => $layermodule->custom, 'layer_fkidtemplate' => $template, 'thumb_label' => '', 'thumb_width' => '', 'thumb_height' => '');
            }*/

            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        if (!empty($attributes)) {
            try {

                $APP->CON->transaction();
                $APP->setDependencyModule(array('Layer', 'Layermodule', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idlayer = 0;
                $modules = array();

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'layer', 'update')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

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
                        }elseif ($value->name === 'idlayer') {
                            $idlayer = $value->value;
                            unset($attributes[$key]);
                        }elseif ($value->name === 'modules') {
                            $modules = $value->value;
                            unset($attributes[$key]);
                        }elseif ($value->name === 'token') {
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

                if(!empty($modules)){
                    foreach($modules as $value){

                        $find = false;
                        $find = Layermodule::find_all_by_idlayer_and_idmodule($idlayer, $value->idmodule);

                        if($find){
                            foreach($find as $value2){
                                $value2->update_attributes(array('default' => (int)$value->default,'status' => (int)$value->status));
                            }
                        }
                    }
                }

                $find = false;

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Layer::find($idlayer);
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Config layer {$find->name}");

                return $APP->response(true, 'success', 'MODULE.LAYER.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);

    }
    
    private static function generate_session($attributes = array(), $id = 0, $json = false) {
        
        global $APP;
        
        if (!empty($attributes['accesstoken']) && !empty($attributes['iduserauth']) && !empty($attributes['application'])) {
            
            if($attributes['application'] === 'admin'){
                
                $APP->setDependencyModule(array('Profile','Usersession','Theme','Language','User'));
                
                $lastSession = Usersession::find_by_accesstoken(Tools::$salt_Pre . $attributes['accesstoken'] . Tools::$salt_End);
                
                $user = User::find_by_fkiduserauth($attributes['iduserauth']);
                
                $profile = Profile::find($user->fkidprofile);
                
                $language = Language::find($user->fkidlanguage);
                
                $theme = Theme::find($user->fkidtheme);
                
                $accessControl = AuthenticationController::access_control(array('idprofile' => $profile->idprofile));
                $APP->RESOURCES = $accessControl['response'];
                
                $arrayProfile = array('idprofile' => $profile->idprofile, 'name' => $profile->name,'label' => $profile->label,'accessControl' => $accessControl['response']);

                $arraySession = array('iduser' => $user->iduser, 'name' => $user->name, 'nickname' => $user->nickname, 'profile' => $arrayProfile, 'picture' => $user->picture, 'email' => $user->email, 'language' => $language->name, 'menuorder' => $user->menuorder, 'token' => $attributes['accesstoken'], 'theme' => $theme->name, 'idusersession' => $lastSession->idusersession, 'fkidlayer' => $user->fkidlayer);
                
                $_SESSION[CLIENT.LAYER] = $attributes['accesstoken'];
                
            }else{
                $_SESSION[CLIENT.LAYER] = $attributes['accesstoken'];
            }
            
            session_write_close();
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);

    }

}

?>
