<?php

class PageController {

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
            $APP->setDependencyModule(array('Page'));
            $return = array();
            $pages = array();

            if ($id > 0) {
                $return[] = array('label' => "", 'value' => "");
                $pages = Page::find_by_sql("SELECT p.idpage,p.title,p.description,p.metatag,p.url,p.idpageparent,p.status,p.fkidmodule from ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.name = '".CUR_LAYER."' AND p.idpage <> {$id} ORDER BY p.title asc");
            } else {
                $return[] = array('label' => "", 'value' => "");
                $pages = Page::find_by_sql("SELECT p.idpage,p.title,p.description,p.metatag,p.url,p.idpageparent,p.status,p.fkidmodule from ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.name = '".CUR_LAYER."' ORDER BY p.title asc");
            }

            $pages = Tools::makeSelect($pages,'idpage','idpageparent');

            foreach ($pages as $key => $value) {

                $ifen = '';
                for ($x = 1; $x <= $value['level']; $x++) {
                    $ifen .= '- ';
                }

                $return[] = array('label' => $ifen . $value['title'], 'value' => $value['idpage']);
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
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Page', 'Authentication','Block'));
                    $array = array();
                    $arrayblocks = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'page','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if ($value->name === 'blocks') {
                                $arrayblocks = $value->value;
                                continue;
                            }
                            if ($value->name === 'metatags') {
                                $value->name = 'metatag';
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
                            if ($value->name === 'authenticate') {
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

                    $find = Page::find('all', array('conditions' => "url = '{$array['url']}'"));

                    if ($find) {
                        throw new Exception("MODULE.PAGE.ERROR.EXISTS.URL");
                    }

                    Page::create($array);
                    $lastpage = Page::last();
                    if(!empty($arrayblocks)){

                        $cont = 0;

                        foreach ($arrayblocks as $key => $value) {

                            Block::create(array('name' => $value->name,'content' => $value->content,'type' => $value->type,'fkidpage' => $lastpage->idpage));
                        }
                    }

                    $APP->CON->commit();
                    $APP->activityLog("Register Page {$array['title']}");
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
        global $APP;
        try {

            //if (isset($_SESSION[CLIENT.LAYER])) {

                $APP->setDependencyModule(array('Page', 'Layer', 'Module', 'Block'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                /*if(!Authentication::check_access_control(CUR_LAYER,'page','select')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }*/

                ////////////////////////////////////////////////////////////

                $array = array();
                $return = array();
                $pages = array();
                if(isset($attributes['layer'])){
                    unset($attributes['layer']);
                }
                if ($id > 0) {

                    $layer = Layer::find_by_name(CUR_LAYER);
                    $pages = Page::find_by_sql("SELECT p.idpage,p.title,p.description,p.metatag as metatags,p.url,p.idpageparent,p.filecss, p.filejs,p.fileview,p.picture,p.authenticate,p.status,p.fkidmodule FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.idlayer = {$layer->idlayer} and p.idpage = {$id}");

                    foreach ($pages as $key => $value) {

                        if(!empty($value->metatags)){
                            $metatags = preg_split("/(?<!\\\\);/",$value->metatags);

                            foreach($metatags as $value2){

                                if(strpos($value2,'\;') !== false){
                                    $value2 = str_replace('\;',';',$value2);
                                }

                                if(strpos($value2,'\,') !== false){
                                    $value2 = str_replace('\,',',',$value2);
                                }

                                $aux = preg_split("/(?<!\\\\),/",$value2);
                                $array[] = array('name' => $aux[0], 'content'=> $aux[1]);
                            }
                        }

                        $return = $value->to_array();
                        $return['authenticate'] = (boolean) $return['authenticate'];
                        
                        $return['metatags'] = $array;

                        $blocks = false;
                        $blocks = Block::find('all',array('conditions' => "fkidpage = {$value->idpage}"));

                        if($blocks){
                            foreach($blocks as $value2){
                                $return['blocks'][] = $value2->to_array();
                            }
                        }else{
                            $return['blocks'] = array();
                        }

                        $arraychild = array();
                        $arraychild = self::recursive_page_child($value->idpage);

                        if(!empty($arraychild)){
                            $return['child'] = $arraychild;
                        }else{
                            $return['child'] = array();
                        }

                    }
                    return $APP->response($return, 'success', '', $json);
                }else if(!empty($attributes)){

                    $pages = false;
                    foreach ($attributes as $key => $value) {
                        if ($key === 'url' && !empty($value)) {
                            if (substr($value, -1) != '/') {
                                $value = $value . '/';
                                $pages = Page::find('all', array('conditions' => "{$key} = '{$value}' and status = 1"));
                            }
                        }
                        if (is_string($value)) {
                            $pages = Page::find('all', array('conditions' => "{$key} = '{$value}' and status = 1"));
                        } else {
                            $pages = Page::find('all', array('conditions' => "{$key} = {$value} and status = 1"));
                        }
                        $cont = 0;
                        foreach ($pages as $key2 => $value2) {

                            $return[$cont] = $value2->to_array();
                            $arraychild = array();
                            $arraychild = self::recursive_page_child($value2->idpage);

                            if(!empty($arraychild)){
                                $return[$cont]['child'] = $arraychild;
                            }else{
                                $return[$cont]['child'] = array();
                            }
                            $cont++;
                        }
                    }

                    return $APP->response($return, 'success', '', $json);
                }else {

                        $auxlayer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
                        $layer = Layer::find_by_name($auxlayer);

                        $pages = Page::find_by_sql("SELECT p.idpage,p.title,p.description,p.metatag,p.url,p.idpageparent,p.filecss, p.filejs,p.fileview,p.picture,p.authenticate,p.status,p.fkidmodule FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.idlayer = {$layer->idlayer}");
                        $cont = 0;

                        foreach ($pages as $key => $value) {

                            if(!empty($value->metatag)){
                                $metatags = preg_split("/(?<!\\\\);/",$value->metatag);

                                foreach($metatags as $value2){

                                    if(strpos($value2,'\;') !== false){
                                        $value2 = str_replace('\;',';',$value2);
                                    }

                                    if(strpos($value2,'\,') !== false){
                                        $value2 = str_replace('\,',',',$value2);
                                    }

                                    $aux = preg_split("/(?<!\\\\),/",$value2);
                                    $array = array($aux[0] => $aux[1]);
                                }
                            }
                            
                            $return[$cont] = $value->to_array();
                            
                            $return[$cont]['authenticate'] = (boolean) $return[$cont]['authenticate'];

                            $return[$cont]['metatags'] = $array;

                            if(!empty($value->idpageparent) && $value->idpageparent > 0){
                                $parent = Page::find($value->idpageparent);
                                $return[$cont]['pageparent'] = $parent->title;
                            }else{
                                $return[$cont]['pageparent'] = '';
                            }

                            $blocks = false;
                            $blocks = Block::find('all',array('conditions' => "fkidpage = {$value->idpage}"));

                            if($blocks){
                                foreach($blocks as $value2){
                                    $return[$cont]['blocks'][] = $value2->to_array();
                                }
                            }else{
                                $return[$cont]['blocks'] = array();
                            }

                            $arraychild = array();
                            $arraychild = self::recursive_page_child($value->idpage);

                            if(!empty($arraychild)){
                                $return[$cont]['child'] = $arraychild;
                            }else{
                                $return[$cont]['child'] = array();
                            }
                            $cont++;
                        }
                        return $APP->response($return, 'success', '', $json);
                    }

            //} else {
                //return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            //}
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
                $APP->setDependencyModule(array('Page','Block'));
                $page = 0;
                $return = false;
                $findblock = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'page','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $find = Page::find('all',array('conditions' => "idpageparent = {$id}"));
                if ($find) {
                    foreach($find as $value){
                        $value->idpageparent = null;
                        $value->save();
                    }
                }



                $page = Page::find($id);
                $findblock = Block::find('all',array('conditions' => "fkidpage = {$page->idpage}"));

                if($findblock){
                    foreach($findblock as $value){
                        $value->delete();
                    }
                }

                $title = $page->title;
                $url = $page->url;
                $page->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Page {$title}");
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
                $APP->setDependencyModule(array('Page', 'Authentication','Block'));
                $array = array();
                $arrayblocks = array();
                $update_attributes = array();
                $token = "";

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'page','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'blocks') {
                            $arrayblocks = $value->value;
                            continue;
                        }
                        if ($value->name === 'metatags') {
                            $value->name = 'metatag';
                        }
                        if ($value->name === 'idpage') {
                            $idpage = $value->value;
                            unset($attributes[$key]);
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
                        if ($value->name === 'authenticate') {
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
                //var_dump($array);exit;
                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                $find = Page::find('all', array('conditions' => "url = '{$array['url']}' and idpage <> {$idpage}"));

                if ($find) {
                    throw new Exception("MODULE.PAGE.ERROR.EXISTS.URL");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Page::find($id);
                $title = $find->title;
                $find->update_attributes($update_attributes);
                
                if(!empty($arrayblocks)){

                    $cont = 0;

                    //Exclui todos os blocos existentes da pagina

                    foreach($arrayblocks as $key => $value){

                        $findblock = false;
                        $findblock = Block::find('all',array('conditions' => "fkidpage = {$id}"));
                        if($findblock){
                            foreach($findblock as $value2){
                                $value2->delete();
                            }
                        }
                    }
                    

                    //Cadastra os blocos enviados

                    foreach ($arrayblocks as $key => $value) {
                        
                        Block::create(array('name' => $value->name,'content' => $value->content,'type' => $value->type,'fkidpage' => $id));
                    }
                }

                $APP->CON->commit();
                $APP->activityLog("Update Page {$title}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        try {

            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                global $APP;
                $APP->setDependencyModule(array('Layer', 'Layermodule'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                /*if(!Authentication::check_access_control(CUR_LAYER,'page','config')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }*/

                ////////////////////////////////////////////////////////////

                $return = array();
                $layermodule = false;
                $module = false;
                $layer = false;
                $custom = "";
                $layer = Layer::find_by_name(CUR_LAYER);
                $module = Module::find_by_name('page');
                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule}"));

                if ($layermodule) {
                    foreach ($layermodule as $value) {
                        if (!empty($value->custom)) {
                            $custom = unserialize($value->custom);
                        }
                        $return = array('url' => $value->url, 'filtermodel' => $value->filtermodel, 'custom' => $custom['default_page']);
                        break;
                    }
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

    private static function select_config($attributes = array(), $id = 0, $json = false) {
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;

                $APP->setDependencyModule(array('Layer','Page'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                /*if(!Authentication::check_access_control(CUR_LAYER,'page','config')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }*/

                ////////////////////////////////////////////////////////////

                $return = array();
                $layer = false;
                $pages = false;
                $layer = Layer::find_by_name(CUR_LAYER);
                $pages = Page::find_by_sql("SELECT p.title,p.idpage from ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m ON p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE lm.idlayer = {$layer->idlayer} AND lm.status = 1 AND p.status = 1");
                $return[] = array('label' => "",'value' => "");
                foreach($pages as $key => $value){
                    $return[] = array('label' => $value->title,'value' => $value->idpage);
                }

                return $APP->response($return, 'success', '', $json);

            }else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function update_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                $APP->CON->transaction();
                $APP->setDependencyModule(array('Layer', 'Layermodule'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'page','config')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $array = array();

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {

                        if($value->name === 'custom'){
                         if(!empty($value->value)){
                             $value->value = serialize(array('default_page' => $value->value));
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

                $layer = false;
                $module = false;
                $layermodule = false;
                $layer = Layer::find_by_name(CUR_LAYER);
                $module = Module::find_by_name('page');
                $layermodule = Layermodule::find_by_sql("SELECT DISTINCT lm.idlayer,lm.idmodule,lm.filtermodel,lm.url,lm.custom,lm.urlcategory,lm.default,lm.status FROM ".DB_PREFIX."layermodule lm WHERE lm.idlayer = {$layer->idlayer} AND lm.idmodule = {$module->idmodule} AND lm.status = 1");

                if($layermodule){
                    $layermodule = false;
                    $layermodule = $APP->CON->query("UPDATE ".DB_PREFIX."layermodule SET url = '{$array['url']}',filtermodel = '{$array['filtermodel']}',custom = '{$array['custom']}' WHERE idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule}");

                    if($layermodule){
                        $APP->CON->commit();
                        $APP->activityLog("Update Config Page");
                        return $APP->response(true, 'success', 'MODULE.PAGE.SUCCESS.CONFIG', $json);
                    }
                }

            } else {
                return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
            }
        } catch (\Exception $e) {
            $APP->CON->rollback();
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    public static function recursive_page_child($id) {

        global $APP;
        $APP->setDependencyModule(array('Page'));
        $return = array();

        $find = false;
        $find = Page::find('all',array('conditions' => "idpageparent = {$id}"));

        if ($find) {
            $cont = 0;
            foreach ($find as $key => $value) {

                $arrayParent = array();
                $arrayParent = self::recursive_page_child($value->idpage);

                $return[$cont] = $value->to_array();

                if(!empty($arrayParent)){
                    $return[$cont]['child'] = $arrayParent;
                }else{
                    $return[$cont]['child'] = array();
                }
                $cont++;
            }
        }

        return $return;
    }

    public static function recursive_page_id($id) {

        global $APP;
        $APP->setDependencyModule(array('Page'));
        $return = array();
        $arrayParent = array();
        $pages = false;

        $sql = "select DISTINCT p.idpage,p.idpageparent from " . DB_PREFIX . "page p inner join " . DB_PREFIX . "module m on p.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where p.idpageparent = {$id} and p.status = 1";

        $pages = Page::find_by_sql($sql);

        if ($pages) {
            foreach ($pages as $key => $value) {

                $return[] = $value->idpage;

                $arrayParent = self::recursive_page_id($value->idpage);

                if(!empty($arrayParent)){
                    $return = array_merge($return,$arrayParent);
                }
            }
        }

        return $return;
    }

    private static function sitemap($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {

            $thismodule = strtolower(str_replace('Controller','',__CLASS__));

            $return = array();
            $find = false;
            $urls = array();
            $columns = '';
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name($thismodule);
            $file = "sitemap.{$thismodule}.xml";
            $nick = substr($thismodule,0,1);
            $thismoduleup = ucfirst($thismodule);

            $APP->setDependencyModule(array('Layer', $thismoduleup, 'Module'));

            $page = Module::find_by_name('page');

            $page = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$page->idmodule} and status = 1"));

            if($page){
                foreach($page as $value){
                    $page = $value->to_array();
                }
            }

            $aux = new $thismoduleup();

            $id = 'id'.$thismodule;

            if(isset($id)){
                $columns .= $nick.'.'.$id.',';
            }
            if(isset($aux->url)){
                $columns .= $nick.'.url,';
            }

            $sql = "SELECT DISTINCT {$columns}lm.filtermodel,lm.url as urlmodule,lm.urlcategory,{$nick}.url as pageparent,{$nick}.idpageparent FROM ".DB_PREFIX."{$thismodule} {$nick} INNER JOIN ".DB_PREFIX."module m ON m.idmodule = {$nick}.fkidmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE lm.idmodule = {$module->idmodule} and lm.idlayer = {$layer->idlayer} and m.name = '{$thismodule}' and m.status = 1 and lm.status = 1 and {$nick}.status = 1 and ({$nick}.idpageparent IS NULL OR {$nick}.idpageparent = 0) GROUP BY ({$id})";
            
            $find = $thismoduleup::find_by_sql($sql);

            if($find){

                foreach($find as $value){

                    $array = array();
                    $array = self::recursive_page_id($value->$id);

                    if(!empty($array)){

                        array_unshift($array, $value->$id);

                        foreach($array as $value2){
                            $data = $thismoduleup::find($value2);

                            $tempurl = LayermoduleController::controller('generateUrl',array('module' => $module->name,'layer' => $layer->name,'filtermodel' => $value->filtermodel,'urlmodule' => $value->urlmodule,'url' => '','urlcategory' => '','category' => '','pageparent' => $value->pageparent,'pagechild' => ($value->$id != $data->$id) ? $data->url : ''));

                            if(Tools::check_url($tempurl) === true){
                                $urls[] = $tempurl;
                            }
                        }
                    }else{

                        $tempurl = LayermoduleController::controller('generateUrl',array('module' => $module->name,'layer' => $layer->name,'filtermodel' => $value->filtermodel,'urlmodule' => $value->urlmodule,'url' => '','urlcategory' => '','category' => '','pageparent' => $value->pageparent,'pagechild' => ''));

                        if(Tools::check_url($tempurl) === true){
                            $urls[] = $tempurl;
                        }
                    }
                }
                if(!empty($urls)){
                    if(file_exists(DIR_SITEMAP.DS.$file)){
                        unlink(DIR_SITEMAP.DS.$file);
                    }

                    $urls = array_unique($urls);

                    $sitemap = new NilPortugues\Sitemap\Sitemap(DIR_SITEMAP,$file);

                    foreach($urls as $value){
                        $item = new NilPortugues\Sitemap\Item\Url\UrlItem($value);
                        $item->setPriority('1.0');
                        $item->setChangeFreq('weekly');
                        $item->setLastMod(date(DATE_ATOM, mktime(date("h"), date("i"), date("s"), date("m"), date("d"), date("Y"))));
                        $sitemap->add($item);
                    }
                    $sitemap->build();
                }
                $return = BASE_URL . DIR_SITEMAP_PATH . '/' . $file;
            }

            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

}

?>
