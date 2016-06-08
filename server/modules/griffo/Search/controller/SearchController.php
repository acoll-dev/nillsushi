<?php

class SearchController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        try {
                global $APP;
                $return = false;

                if(isset($attributes['query'])){

                    if(LAYER === 'admin'){
                        $APP->setDependencyModule(array('Menu'));

                        //A busca da layer admin é feita pelos menus, a busca verifica tanto os menus pai como os filhos
                        $attributes['query'] = urlencode($attributes['query']);
                        $array_search = array('search' => $attributes['query'],'array_filter' => array(),'cur_layer' => $attributes['layer']);
                        MenuController::load_menu_admin($array_search);

                        $return = $APP->MENU;
                    }else{
                        $APP->setDependencyModule(array('Module', 'Page', 'Layer', 'Block'));
                        $sql = false;
                        $layer = false;
                        $checkcategory = false;
                        $urlcategory = false;
                        $modules = array();
                        $layer = Layer::find_by_name(LAYER);

                        //Busca todos os módulos da layer atual onde todos devem estar habilitados, não estrutural e pesquisáveis
                        $sql = Module::find_by_sql("SELECT m.idmodule,m.name,lm.url,lm.filtermodel,lm.urlcategory from ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule WHERE m.status = 1 AND lm.status = 1 AND lm.idlayer = {$layer->idlayer} AND m.struct = 0 AND m.searchable = 1");
                        if($sql){
                            foreach($sql as $key => $value){

                                $columns = "";
                                $modulelowercase = $value->name;
                                $nick = substr($modulelowercase,0,1);
                                $module = ucfirst($value->name);
                                if(!class_exists($module)){
                                    $APP->setDependencyModule(array($module));
                                }

                                $aux = new $module();

                                if(isset($aux->url) || isset($aux->link)){
                                    if(isset($aux->name)){
                                        $columns .= $nick.'.name,';
                                    }
                                    if(isset($aux->title)){
                                        $columns .= $nick.'.title,';
                                    }
                                    if(isset($aux->shortdescription)){
                                        $columns .= $nick.'.shortdescription,';
                                    }
                                    if(isset($aux->description)){
                                        $columns .= $nick.'.description,';
                                    }

                                    $find = false;
                                    $urls = array();

                                    $joins = "INNER JOIN ".DB_PREFIX."module m on {$nick}.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule";
                                    $methodsearch = "MATCH({$columns}keywords) AGAINST ('{$attributes['query']}')";
                                    $conditions = "m.status = 1 and lm.status = 1 and lm.idlayer = {$layer->idlayer} and m.name = '{$modulelowercase}' and {$methodsearch}";

                                    $find = $module::find_by_sql("SELECT {$nick}.url FROM ".DB_PREFIX."{$modulelowercase} {$nick} {$joins} WHERE {$conditions}");
                                    if(!$find){
                                        $methodsearch = "keywords LIKE '%{$attributes['query']}%'";
                                        $find = false;

                                        $find = $module::find_by_sql("SELECT {$nick}.url FROM ".DB_PREFIX."{$modulelowercase} {$nick} {$joins} WHERE {$conditions}");

                                        if(!$find){

                                            $find = false;
                                            $checkcategory = true;
                                            $find = $module::find_by_sql("SELECT c.url FROM ".DB_PREFIX."category c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE m.status = 1 and lm.status = 1 and lm.idlayer = {$layer->idlayer} and m.name = '{$modulelowercase}' and MATCH(c.name) AGAINST ('{$attributes['query']}')");
                                        }
                                    }

                                    if($find){

                                        /*if($checkcategory === true){
                                            $lmcategory = false;
                                            $category = Module::find_by_name('category');

                                            $lmcategory = Layermodule::find('all',array('conditions' => "idmodule = {$category->idmodule} and idlayer = {$layer->idlayer} and status = 1"));

                                            if($lmcategory){
                                                foreach($lmcategory as $value2){
                                                    $urlcategory = $value2->urlcategory;
                                                }
                                            }
                                        }*/
                                        foreach($find as $value2){
                                            if(isset($value2->link)){
                                                $urls[] = $value2->link;
                                            }
                                            if(isset($value2->url)){
                                                $urls[] = LayermoduleController::controller('generateUrl',array('module' => $modulelowercase,'layer' => $layer->name,'filtermodel' => $value->filtermodel,'urlmodule' => $value->url,'url' => ($checkcategory === true) ? $value->urlcategory : $value2->url,'urlcategory' => $value->urlcategory,'category' => ($checkcategory === true) ? $value2->url : '' ));
                                            }
                                        }
                                        $return = $urls;
                                        break;
                                    }
                                }
                            }
                        }else{
                            $urls = array();
                            $sql = false;
                            $module = Module::find_by_name("page");

                            $find = false;
                            $find = Page::find_by_sql("SELECT DISTINCT p.url FROM ".DB_PREFIX."page p where MATCH(p.title,p.description,p.keywords) AGAINST('{$attributes['query']}')");
                            if($find){
                                foreach($find as $value){
                                    $urls[] = BASE_URL . $value->url;
                                }
                                $return = $urls;
                            }else{
                                $find = false;
                                $find = Block::find_by_sql("SELECT p.url FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."block b ON p.idpage = b.fkidpage WHERE MATCH(b.content) AGAINST('{$attributes['query']}')");
                                if($find){
                                    foreach($find as $value){
                                        $urls[] = BASE_URL . $value->url;
                                    }
                                    $return = $urls;
                                }
                            }
                        }

                    }
                }

                return $APP->response($return, 'success', '', $json);
            } catch (\Exception $e) {
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function config($attributes = array(), $id = 0, $json = false) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Authentication','Layermodule','Layer','Module'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'search','config')){
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
                                }
                                //Tools::saveUrl($value->value);
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
                        $APP->activityLog("Update Config Search");
                        return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
                    }
                }
                return $APP->response(true, 'success', 'MODULE.SEARCH.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Search', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'search', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('search');
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
