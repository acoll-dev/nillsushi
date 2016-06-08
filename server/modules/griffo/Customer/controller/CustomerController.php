<?php

class CustomerController {

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

                    $APP->setDependencyModule(array('Customer','Authentication'));
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    //if (!Authentication::check_access_control(CUR_LAYER, 'customer', 'insert')) {
                    //    throw new Exception("ERROR.RECUSED.RESOURCE");
                    //}

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
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

                    Customer::create($array);
                    
                    $APP->CON->commit();
                    $APP->activityLog("Register Customer");
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
        
        global $APP;
        
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                $APP->setDependencyModule(array('Customer','Authentication'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                //if (!Authentication::check_access_control(CUR_LAYER, 'customer', 'select')) {
                //    throw new Exception("ERROR.RECUSED.RESOURCE");
                //}

                ////////////////////////////////////////////////////////////

                $return = array();
                $customer = array();

                if ($id > 0) {
                    $return = Customer::find($id);
                    if(!empty($return->social)){
                        $return->social = unserialize($return->social);
                    }
                    if(!empty($return->phone)){
                        $return->phone = unserialize($return->phone);
                    }
                    if(!empty($return->email)){
                        $return->email = unserialize($return->email);
                    }
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    $customer = Customer::all();
                    $cont = 0;
                    foreach ($customer as $key => $value) {
                        $return[$cont] = $value->to_array();
                        if(!empty($return[$cont]['social'])){
                            $return[$cont]['social'] = unserialize($return[$cont]['social']);
                        }
                        if(!empty($return[$cont]['email'])){
                            $return[$cont]['email'] = unserialize($return[$cont]['email']);
                        }
                        if(!empty($return[$cont]['phone'])){
                            $return[$cont]['phone'] = unserialize($return[$cont]['phone']);
                        }
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
                $APP->setDependencyModule(array('Customer'));
                $customer = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                //if (!Authentication::check_access_control(CUR_LAYER, 'customer', 'delete')) {
                //    throw new Exception("ERROR.RECUSED.RESOURCE");
                //}

                ////////////////////////////////////////////////////////////

                $customer = Customer::find($id);
                $name = $customer->name;
                $customer->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Customer {$name}");
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
        if (!empty($attributes)) {
            try {

                $APP->CON->transaction();
                $APP->setDependencyModule(array('Customer', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idcustomer = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                //if (!Authentication::check_access_control(CUR_LAYER, 'customer', 'update')) {
                //    throw new Exception("ERROR.RECUSED.RESOURCE");
                //}

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        
                        if ($value->name === 'idcustomer') {
                            $idcustomer = $value->value;
                            unset($attributes[$key]);
                            continue;
                        }
                        
                        if($value->name === 'social' || $value->name === 'email' || $value->name === 'phone'){
                            if(!empty($value->value)){
                                $value->value = serialize($value->value);
                            }
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

                if(!empty($idcustomer)){
                    $find = Customer::find('all', array('conditions' => "name = '{$array['name']}' and idcustomer <> {$idcustomer}"));

                    if ($find) {
                        throw new Exception("MODULE.CUSTOMER.ERROR.EXISTS.NAME");
                    }
                }
                
                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                if(!empty($idcustomer)){
                    $find = Customer::find($idcustomer);
                }else{
                    $find = Customer::find_by_name($array['name']);    
                }
                
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Customer {$name}");

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
