<?php

class ShopController {

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
            $APP->setDependencyModule(array('Shop'));
            $return = array();
            $shop = array();
            $shop = Shop::all();
            $return[] = array('label' => "", 'value' => "");
            foreach ($shop as $key => $value) {
                $return[] = array('label' => $value->name, 'value' => $value->idshop);
            }
            return $APP->response($return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->CON->transaction();

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Shop', 'Authentication','Module'));
                    $array = array();
                    $token = "";
                    $products = array();

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'shop','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {

                            if(empty($value->value)){
                                $value->value = null;
                            }
                            if($value->name === 'status'){
                                $status = true;
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
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


                    if(!empty($token)){
                        if (Authentication::check_token($token) === false) {
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }
                    }


                    $find = Module::find_by_name('shop');

                    $array['fkidmodule'] = $find->idmodule;


                    Shop::create($array);

                    $APP->CON->commit();
                    $APP->activityLog("Register Shop");
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("There are no values for registering!");
                }
        } catch (\Exception $e) {
            $APP->CON->rollback();
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Shop'));
            $return = array();
            $shops = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    $return = Shop::find($id);

                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(is_string($value)){
                                $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                foreach($shops as $key2 => $value2){
                                    $return[] = $value2->to_array();
                                }
                            }else{
                                $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                foreach($shops as $key2 => $value2){
                                    $return[] = $value2->to_array();
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        foreach($shops as $key2 => $value2){
                            $return[] = $value2->to_array();
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }else{
                if ($id > 0) {
                    $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.idshop = {$id} AND s.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                    foreach($shops as $key2 => $value2){
                        $return[] = $value2->to_array();
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(is_string($value)){
                                $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.{$key} = '{$value}' AND s.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                foreach($shops as $key2 => $value2){
                                    $return[] = $value2->to_array();
                                }
                            }else{
                                $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.{$key} = {$value} AND s.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                foreach($shops as $key2 => $value2){
                                    $return[] = $value2->to_array();
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $shops = Shop::find_by_sql("SELECT DISTINCT s.idshop,s.name,s.address,s.number,s.complement,s.district,s.city,s.state,s.phone1,s.phone2,s.phone3,s.status FROM ".DB_PREFIX."shop s INNER JOIN ".DB_PREFIX."module m on s.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE s.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        foreach($shops as $key2 => $value2){
                            $return[] = $value2->to_array();
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
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
                //$APP->CON->transaction();
                $id = (int) $id;
                $APP->setDependencyModule(array('Shop','Order'));
                $shop = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'shop','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////


                //Busca os pedidos que são da loja a ser deletada, se existir excluí

                $find = false;
                $find = Order::find_by_sql('all',array('conditions' => "fkidshop = {$id}"));

                if($find){
                    $APP->CON->query("DELETE FROM ".DB_PREFIX."orderproduct WHERE fkidorder = {$value->idorder}");
                    foreach($find as $value){
                        $value->delete();
                    }
                }

                $shop = Shop::find($id);
                $shop->delete();
                //$APP->CON->commit();
                $APP->activityLog("Delete Shop");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                //$APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function update_attributes($attributes = array(), $id = 0, $json = false) {

        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Shop','Authentication'));
                $array = array();
                $update_attributes = array();
                $products = array();
                $token = "";
                $idshop = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'shop','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
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
                        if ($value->name === 'idshop') {
                            $idshop = $value->value;
                            unset($attributes[$key]);
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

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Shop::find($id);
                $find->update_attributes($update_attributes);

                $APP->CON->commit();
                $APP->activityLog("Update Shop");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function config($attributes = array(), $id = 0, $json = false) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Authentication','Layermodule','Layer','Module'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'shop','config')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $array = array();

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if (empty($value->value)) {
                            $value->value = null;
                        }
                        if ($value->name === 'url') {
                            if(!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }else{
                                    $value->value = Tools::formatUrl($value->value);
                                }
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

                $module = Module::find_by_name(MODULE);
                $layer = Layer::find_by_name(CUR_LAYER);
                $find = false;

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                $find = Layermodule::find('all', array('conditions' => "url = '{$array['url']}' and idlayer = {$layer->idlayer} and idmodule <> {$module->idmodule}"));

                if ($find) {
                    throw new Exception("ERROR.EXISTS.URL");
                }

                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$module->idmodule}"));

                if($layermodule){
                    $layermodule = false;
                    $layermodule = $APP->CON->query("UPDATE ".DB_PREFIX."layermodule SET url = '{$array['url']}',filtermodel = '{$array['filtermodel']}',custom = '{$array['custom']}' WHERE idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule}");

                    if($layermodule){
                        $APP->CON->commit();
                        $APP->activityLog("Update Config Shop");
                        return $APP->response(true, 'success', 'MODULE.BUDGET.SUCCESS.CONFIG', $json);
                    }
                }
                return $APP->response(true, 'success', 'MODULE.SHOP.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Shop', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'shop', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('shop');
            $layermodule = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule} AND status = 1"));

            if($layermodule){
                foreach($layermodule as $value){
                    $return = $value->to_array();
                }
            }

            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

}

?>
