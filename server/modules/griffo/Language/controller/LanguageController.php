<?php

class LanguageController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function select($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                global $APP;
                $APP->setDependencyModule(array('Language'));
                $return = array();
                $language = array();
                $language = Language::all();
                $return[] = array('label' => "", 'value' => "");
                foreach ($language as $key => $value) {
                    $return[] = array('label' => $value->label, 'value' => $value->idlanguage);
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
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                //if(isset($_SESSION[CLIENT.LAYER])){

                global $APP;
                $APP->setDependencyModule(array('Language'));
                $return = array();
                $languages = array();
                if ($id > 0) {
                    $return = Language::find($id);
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    $languages = Language::all();
                    foreach ($languages as $key => $value) {
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

    private static function checkLanguage($attributes = array(), $id = 0, $json = false) {

        $url = $attributes['url'];
        $language_url = $attributes['language_url'];

        if (!empty($url)) {

            global $APP;

            $APP->setDependencyModule(array('Language'));

            $languages = Language::all();
            $arrayUrl = explode('/', $url);
            $status = false;
            foreach ($languages as $value) {
                $cont = 0;
                foreach ($arrayUrl as $value2) {
                    $tmp = "";
                    for ($i = 0; $i < count($arrayUrl) - $cont; $i++) {
                        if (!empty($arrayUrl[$i]))
                            $tmp .= $arrayUrl[$i];
                    }

                    if (strcmp($tmp, $value->name) == 0) {
                        $status = true;
                        $url = str_replace('/' . $tmp, '', $url);
                        $language_url = $tmp;
                        break;
                    }
                    $cont++;
                }
                if ($status)
                    break;
            }
        }

        return array('url' => $url, 'languageUrl' => $language_url);
    }

    private static function validateLanguageUrl($attributes = array(), $id = 0, $json = false) {

        global $APP;

        $APP->setDependencyModule(array('Authentication', 'Language'));

        $alias = $attributes['alias'];
        $language_url = $attributes['language_url'];
        $language_layer = $attributes['language_layer'];
        $auth = Authentication::Auth();
        $language = '';
        $statusurl = 200;

        if(LAYER === 'admin'){
            if (empty($language_url)) {
                if ($auth === true) {
                    $language_user = LANGUAGE_USER;
                } else {
                    $language = $language_layer;
                }
            } else {
                if ($auth === true) {
                    $language_user = LANGUAGE_USER;

                    if ($language_user === $language_url) {
                        $language = $language_url;
                    } else {
                        $find = false;
                        $find = Language::find_by_name($language_url);
                        if(!$find){
                            $statusurl = 404;
                        }
                    }
                } else {
                    $return = "";
                    $return = Language::find('all', array('conditions' => "name = '{$language_url}'"));
                    if (!empty($return)) {
                        foreach ($return as $value) {
                            $language = $value->name;
                            break;
                        }
                    } else {
                        $statusurl = 404;
                    }
                }
            }
        }

        return array('language' => $language, 'status_url' => $statusurl);
    }

}

?>
