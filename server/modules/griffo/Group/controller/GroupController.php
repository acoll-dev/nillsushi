<?php

class GroupController {

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

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Group', 'Groupfraction','Authentication','Module'));
                    $array = array();
                    $token = "";
                    $fractions = array();
                    
                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'fraction','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            if($value->name === 'default'){
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }
                            if($value->name === 'fraction'){
                                $fractions = Tools::objectToArray($value->value);
                                unset($attributes[$key]);
                                continue;
                            }
                            if ($value->name === 'token') {
                                $token = $value->value;
                                unset($attributes[$key]);
                                continue;
                            }
                            $array = array_merge($array, array($value->name => $value->value));
                        }
                    }
                    
                    if(!empty($token)){
                        if (Authentication::check_token($token) === false) {
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }
                    }
                    
                    $find = false;
                    $find = Group::find('all', array('conditions' => "name = '{$array['name']}'"));

                    if ($find) {
                        throw new Exception("MODULE.GROUP.ERROR.EXISTS.NAME");
                    }
                    
                    Group::create($array);
                    
                    $group = false;
                    $group = Group::last();
                    
                    if(!empty($fractions)){
                        foreach($fractions as $key => $value){
                            Groupfraction::create(array("fkidgroup" => $group->idgroup,"fkidfraction" => $value['idfraction']));
                        }
                    }
                    
                    $APP->CON->commit();
                    $APP->activityLog("Register Group");
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
            $APP->setDependencyModule(array('Group','Fraction'));
            $return = array();
            $groups = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    
                    $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE g.idgroup = {$id} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");
                    
                    $cont = 0;
                    
                    foreach($groups as $key2 => $value2){
                        
                        $return[$cont] = $value2->to_array();
                        
                        $findfraction = false;
                        $findfraction = Fraction::find_by_sql("select distinct f.* from ".DB_PREFIX."fraction f INNER JOIN ".DB_PREFIX."groupfraction gf on f.idfraction = gf.fkidfraction where gf.fkidgroup = {$id}");
                        
                        if($findfraction){
                            foreach($findfraction as $value){
                                $return[$cont]['fraction'][] = $value->to_array();
                            }
                        }
                        $cont++;
                    }

                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(!is_numeric($value)){
                                $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE f.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach($groups as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    
                                    $cont++;
                                }
                            }else{
                                $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE f.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach($groups as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        $cont = 0;
                        foreach($groups as $key2 => $value2){
                            
                            $return[$cont] = $value2->to_array();
                            
                            $cont++;
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }else{
                if ($id > 0) {
                    $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE o.idgroup = {$id} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                    $cont = 0;
                    foreach($groups as $key2 => $value2){
                        
                        $return[$cont] = $value2->to_array();
                                    
                        $cont++;
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        
                        foreach($attributes as $key => $value){
                            
                            if(!is_numeric($value)){
                                $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE f.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach($groups as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    
                                    $cont++;
                                }
                            }else{
                                
                                $groups = Group::find_by_sql("SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE f.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach($groups as $key2 => $value2){
                                    
                                    $return[$cont] = $value2->to_array();
                                    
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        
                        $groups = Group::find_by_sql("SELECT DISTINCT SELECT DISTINCT g.idgroup,g.name,g.default FROM ".DB_PREFIX."group g INNER JOIN ".DB_PREFIX."groupfraction gf ON g.idgroup = gf.fkidgroup INNER JOIN ".DB_PREFIX."fraction f ON gf.fkidfraction = f.idfraction INNER JOIN ".DB_PREFIX."module m on f.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        $cont = 0;
                        foreach($groups as $key2 => $value2){
                            
                            $return[$cont] = $value2->to_array();
                                    
                            $cont++;
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
                $APP->setDependencyModule(array('Group'));
                $group = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'fraction','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                $APP->CON->query("DELETE FROM ".DB_PREFIX."groupfraction WHERE fkidgroup = {$id}");
                
                $group = Group::find($id);
                $group->delete();
                //$APP->CON->commit();
                $APP->activityLog("Delete Group");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                //$APP->CON->rollback();
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
                $APP->setDependencyModule(array('Group','Groupfraction','Authentication'));
                $array = array();
                $update_attributes = array();
                $fractions = array();
                $token = "";

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'fraction','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if(empty($value->value)){
                            $value->value = null;
                        }
                        if($value->name === 'default'){
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if($value->name === 'fraction'){
                            $fractions = Tools::objectToArray($value->value);
                            unset($attributes[$key]);
                            continue;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                            continue;
                        }
                        
                        $array = array_merge($array, array($value->name => $value->value));
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }
                
                $find = false;
                $find = Groupfraction::find('all',array('conditions' => "fkidgroup = {$id}"));
                
                if($find){
                    foreach($find as $value){
                        $value->delete();
                    }
                }
                //$APP->CON->query("DELETE FROM ".DB_PREFIX."groupfraction WHERE fkidgroup = {$id}");
                
                if(!empty($fractions)){
                    foreach($fractions as $key => $value){
                        Groupfraction::create(array("fkidgroup" => $id,"fkidfraction" => $value['idfraction']));
                    }
                }
                
                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Group::find($id);
                $find->update_attributes($update_attributes);
                
                $APP->CON->commit();
                $APP->activityLog("Update Group");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }
    
}

?>
