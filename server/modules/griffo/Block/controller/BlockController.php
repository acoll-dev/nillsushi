<?php

class blockController {

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

                    $APP->setDependencyModule(array('Block', 'Authentication'));
                    $array = array();
                    $token = "";

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
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

                    $find = Block::find('all', array('conditions' => "name = {$array['name']} and fkidpage = {$array['fkidpage']}"));

                    if ($find) {
                        throw new Exception("ERROR.EXISTS.NAME");
                    }

                    Block::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Block");
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("There are no values for registering!");
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
                $APP->setDependencyModule(array('Block', 'Module','Layer','Page'));
                $return = array();
                $blocks = array();

                if ($id > 0) {
                    $return = Block::find($id);
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {

                    $conditions = "";

                    foreach ($attributes as $key => $value) {
                        if (!is_numeric($value)) {
                            $conditions .= "{$key} = '{$value}'";
                        } else {
                            $conditions .= "{$key} = {$value}";
                        }
                    }

                    if(!empty($conditions)){
                        $blocks = Block::find('all',array('conditions'));
                    }else{
                        $blocks = Block::all();
                    }

                    foreach ($blocks as $key => $value) {
                        $return = $value->to_array();
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
                $APP->setDependencyModule(array('Block'));
                $block = 0;
                $return = false;

                $block = Block::find($id);
                $block->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Block {$block->idblock}");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
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
                $APP->setDependencyModule(array('Block', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idblock = 0;

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'idblock') {
                            $idblock = $value->value;
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

                $find = Block::find('all', array('conditions' => "name = '{$array['name']}' and idblock <> {$idblock}"));

                    if ($find) {
                        throw new Exception("ERROR.EXISTS.NAME");
                    }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Block::find($id);
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Block {$find->idblock}");

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
