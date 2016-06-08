<?php

class CategoryController {

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

                    $APP->setDependencyModule(array('Category', 'Authentication'));
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'category','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if ($value->name === 'url') {
                                if (!empty($value->value)){
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = Tools::formatUrl($value->value . '/');
                                    }else{
                                        $value->value = Tools::formatUrl($value->value);
                                    }
                                }
                            }
                            if($value->name === 'status'){
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }
                            if($value->name === 'sort'){
                                if(!empty($value->value)){
                                    $value->value = (int) $value->value;
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

                    $find = Category::find('all', array('conditions' => "(name = '{$array['name']}' or url = '{$array['url']}') and fkidmodule = {$array['fkidmodule']}"));

                    if ($find) {

                        throw new Exception("ERROR.EXISTS.NAMEORURLCATEGORY!");
                    }

                    //Verifica se existe a chave sort, se não existir é buscado o autoincrement da tabela a ser cadastrada
                    if(!isset($array['sort']) || empty($array['sort'])){
                        $id = false;
                        $id = $APP->CON->query_and_fetch_one("SELECT `AUTO_INCREMENT` as id FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_DATABASE."'AND   TABLE_NAME   = '".DB_PREFIX.strtolower(str_replace('Controller','',__CLASS__))."' ");
                        $array['sort'] = (int) $id;
                    }
                    
                    Category::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Category {$array['name']}");
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

    private static function select($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Category'));
            $return = array();
            $categories = array();

            if ($id > 0) {
                $return[] = array('label' => "", 'value' => "");
                $categories = Category::find_by_sql("SELECT c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from ".DB_PREFIX."category c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.name = '".CUR_LAYER."' AND c.idcategory <> {$id} ORDER BY c.name asc");
            } else {
                $return[] = array('label' => "", 'value' => "");
                $categories = Category::find_by_sql("SELECT c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from ".DB_PREFIX."category c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.name = '".CUR_LAYER."' ORDER BY c.name asc");
            }

            ///////////////////////

            $categories = Tools::makeSelect($categories);

            ///////////////////////

            foreach ($categories as $key => $value) {

                ///////////////////////

                $ifen = '';
                for ($x = 1; $x <= $value['level']; $x++) {
                    $ifen .= '- ';
                }

                $return[] = array('label' => $ifen . $value['name'], 'value' => $value['idcategory']);

                ///////////////////////
                //$return[] = array('label' => $value['name'],'value' => $value['idcategory']);
            }
            return $APP->response($return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Category'));
            $return = array();
            $categories = false;
            $layer = $attributes['layer'];
            unset($attributes['layer']);
            $layer = Layer::find_by_name($layer);
            
            $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule,m.name as namemodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer}";
            
            if (is_numeric($id) && $id > 0) {
                $sql .= " and (c.idcategory = {$id} or c.idcategoryparent = {$id})"; 
            }
            
            if(LAYER != 'admin'){
                $sql = " and c.status = 1";
            }
            
            if (count($attributes) > 0) {

                if(isset($attributes['param'])){
                    foreach ($attributes['param'] as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and c.{$key} = '{$value}'";
                        } else {
                            $sql .= " and c.{$key} = {$value}";
                        }
                    }
                }else{
                    foreach ($attributes as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and c.{$key} = '{$value}'";
                        } else {
                            $sql .= " and c.{$key} = {$value}";
                        }
                    }
                }
            }
            
            $sql .= " order by c.sort asc";
            
            $categories = Category::find_by_sql($sql);
            
            if($categories){
                $cont = 0;
                foreach($categories as $value){
                    $return[$cont] = $value->to_array();
                    if ($value->idcategoryparent > 0) {
                        $find = Category::find($value->idcategoryparent);
                        $return[$cont]['parent'] = $find->to_array();
                    }
                    $return[$cont]['child'] = self::recursive_category($value->idcategory);
                    $cont++;
                }
            }
            
            return $APP->response((ONELEVEL) ? Tools::array_child_onelevel($return,'idcategory','child','sort') : $return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function child($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Category'));
            $return = array();
            $categories = false;
            $layer = $attributes['layer'];
            unset($attributes['layer']);
            $layer = Layer::find_by_name($layer);
            
            $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule,m.name as namemodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and (c.idcategoryparent IS NULL or c.idcategoryparent = 0)";
            
            if (is_numeric($id) && $id > 0) {
                $sql .= " and c.idcategory = {$id}"; 
            }
            
            if (count($attributes) > 0) {

                if(isset($attributes['param'])){
                    foreach ($attributes['param'] as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and c.{$key} = '{$value}'";
                        } else {
                            $sql .= " and c.{$key} = {$value}";
                        }
                    }
                }else{
                    foreach ($attributes as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and c.{$key} = '{$value}'";
                        } else {
                            $sql .= " and c.{$key} = {$value}";
                        }
                    }
                }
            }
            
            $sql .= " order by c.sort asc";
            
            $return = self::recursive_category($sql);
            
            return $APP->response((ONELEVEL) ? Tools::array_child_onelevel($return,'idcategory','child','sort') : $return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function module($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Category'));
            $return = array();
            $categories = false;
            $layer = $attributes['layer'];
            unset($attributes['layer']);
            $layer = Layer::find_by_name($layer);
            
            $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule,m.name as namemodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and (c.idcategoryparent IS NULL or c.idcategoryparent = 0)";
            
            if (is_numeric($id) && $id > 0) {
                $sql .= " and m.idmodule = {$id}"; 
            }
            
            if (count($attributes) > 0) {

                if(isset($attributes['param'])){
                    foreach ($attributes['param'] as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and m.{$key} = '{$value}'";
                        } else {
                            $sql .= " and m.{$key} = {$value}";
                        }
                    }
                }else{
                    foreach ($attributes as $key => $value) {
                        if (!is_numeric($value)) {
                            $sql .= " and m.{$key} = '{$value}'";
                        } else {
                            $sql .= " and m.{$key} = {$value}";
                        }
                    }
                }
            }
            
            $sql .= " order by c.sort asc";
            
            $categories = Category::find_by_sql($sql);
            
            if($categories){
                $cont = 0;
                foreach($categories as $value){
                    $return[$cont] = $value->to_array();
                    
                    if ($value->idcategoryparent > 0) {
                        $find = Category::find($value->idcategoryparent);
                        $return[$cont]['parent'] = $find->to_array();
                    }
                    $return[$cont]['child'] = self::recursive_category($value->idcategory);
                    $cont++;
                }
            }
            
            return $APP->response((ONELEVEL) ? Tools::array_child_onelevel($return,'idcategory','child','sort') : $return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function deleteCategoryParent($idcategoryparent,$module){

        $findparent = false;
        $findparent = Category::find('all',array('conditions' => "idcategoryparent = {$idcategoryparent}"));

        if($findparent){
            foreach($findparent as $value2){
                self::deleteCategoryParent($value2->idcategory,$module);
                
                $categories = false;
                $categories = $module::find_by_fkidcategory($value2->idcategory);
                
                if($categories){
                    $categories->fkidcategory = null;
                    $categories->save();
                }
            }
        }
        
    }	

    private static function delete($attributes = array(), $id = 0, $json = false) {
        if (is_numeric($id) && $id > 0) {
            try {
                global $APP;
                //$APP->CON->transaction();
                $id = (int) $id;
                $APP->setDependencyModule(array('Category'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'category','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                $APP->setDependencyModule(array('Specialist'));
                
                if(class_exists('Specialist')){
                    
                    $APP->DB->set_model_directory(DIR_MODULE . 'complementary' . DS . 'Specialist' . DS . "model");
                    $find = false;
                    $find = $APP->CON->query("SELECT idspecialist FROM ".DB_PREFIX."specialistcategory WHERE idcategory = {$id}");
                    if ($find) {
                        foreach($find as $value){
                            $APP->CON->query("DELETE FROM ".DB_PREFIX."specialistcategory WHERE idspecialist = {$value['idspecialist']} and idcategory = {$id}");
                        }
                    }
                }

                $APP->setDependencyModule(array('Banner'));
                
                if(class_exists('Banner')){
                    
                    $find = false;
                    $find = Banner::find('all',array('conditions' => "fkidcategory = {$id}"));
                    
                    if ($find) {

                        foreach($find as $value){

                            self::deleteCategoryParent($value->fkidcategory,'Banner');
                        }
                    }
                }
                
                $APP->setDependencyModule(array('Product'));
                
                if(class_exists('Product')){
                    
                    $find = false;
                    $find = Product::find('all',array('conditions' => "fkidcategory = {$id}"));
                    
                    if ($find) {

                        foreach($find as $value){

                            self::deleteCategoryParent($value->fkidcategory,'Product');
                        }
                    }
                }

                $APP->setDependencyModule(array('Service'));
                
                if(class_exists('Service')){
                    
                    $find = false;
                    $find = Service::find('all',array('conditions' => "fkidcategory = {$id}"));
                    
                    if ($find) {

                        foreach($find as $value){

                            self::deleteCategoryParent($value->fkidcategory,'Service');
                        }
                    }
                }
                
                $APP->setDependencyModule(array('News'));
                
                if(class_exists('News')){
                    
                    $find = false;
                    $find = News::find('all',array('conditions' => "fkidcategory = {$id}"));
                    
                    if ($find) {

                        foreach($find as $value){

                            self::deleteCategoryParent($value->fkidcategory,'News');
                        }
                    }
                }
                
                $APP->setDependencyModule(array('Video'));
                
                if(class_exists('Video')){
                    
                    $find = false;
                    $find = Video::find('all',array('conditions' => "fkidcategory = {$id}"));
                    
                    if ($find) {

                        foreach($find as $value){

                            self::deleteCategoryParent($value->fkidcategory,'Video');
                        }
                    }
                }

                $find = false;
                $find = Category::find('all',array('conditions' => "idcategoryparent = {$id}"));
                if ($find) {
                    foreach($find as $value){
                        $value->idcategoryparent = null;
                        $value->save();
                    }
                }

                $category = 0;
                $return = false;
                $category = Category::find($id);
                $name = $category->name;
                $url = $category->url;
                $category->delete();
                //$APP->CON->commit();
                $APP->activityLog("Delete Category {$name}");
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
                $APP->setDependencyModule(array('Category', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idcategory = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'category','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if (empty($value->value)) {
                            $value->value = null;
                        }
                        if($value->name === 'status'){
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if($value->name === 'sort'){
                            if(!empty($value->value)){
                                $value->value = (int) $value->value;
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
                        if ($value->name === 'idcategory') {
                            $idcategory = $value->value;
                            unset($attributes[$key]);
                        }
                        if ($value->name === 'namecategoryparent') {
                            unset($attributes[$key]);
                            continue;
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

                $find = Category::find('all', array('conditions' => "(name = '{$array['name']}' or url = '{$array['url']}') and fkidmodule = {$array['fkidmodule']} and idcategory <> {$idcategory}"));

                if ($find) {
                    throw new Exception("ERROR.EXISTS.NAMEORURLCATEGORY!");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Category::find($id);
                
                //Verifica se existe a chave sort, se não existir é atribuído o id do produto
                if(!isset($update_attributes['sort']) || empty($update_attributes['sort'])){
                    $update_attributes['sort'] = (int) $find->{"id".strtolower(str_replace('Controller','',__CLASS__))};
                }
                
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Category {$name}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    private static function sitemap($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {

            $APP->setDependencyModule(array('Layer', 'Module', 'Layermodule','Category'));
            $return = array();
            $find = false;
            $urls = array();
            $columns = '';
            $layer = Layer::find_by_name(CUR_LAYER);
            $checkcategory = true;
            $file = "sitemap.category.xml";
            $page = Module::find_by_name('page');
            $page = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$page->idmodule} and status = 1"));

            if($page){
                foreach($page as $value){
                    $page = $value->to_array();
                }
            }

            $aux = explode('/',$page['filtermodel']);

            $find = false;
            $thismodule = strtolower(str_replace('Controller','',__CLASS__));
            $module = Module::find_by_name($thismodule);
            $nick = substr($thismodule,0,1);
            $thismoduleup = ucfirst($thismodule);

            $APP->setDependencyModule(array($thismoduleup));

            $sql = "SELECT DISTINCT m.idmodule,lm.filtermodel,lm.url as urlmodule,lm.urlcategory,pg.idpage,pg.url as pageparent FROM ".DB_PREFIX."page pg INNER JOIN ".DB_PREFIX."module m ON pg.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE lm.idlayer = {$layer->idlayer} and  m.status = 1 and lm.status = 1 and pg.status = 1 and pg.fkidmodule <> {$page['idmodule']}";

            $find = $thismoduleup::find_by_sql($sql);

            if($find){

                foreach($find as $value){

                    if($value->idmodule != $page['idmodule']){
                        $tempfilter = explode('/',$page['filtermodel']);
                        $tempfilter = '/'.$tempfilter[1].$value->filtermodel;
                    }else{
                        $tempfilter = $value->filtermodel;
                    }

                    $find = false;
                    $find = Category::find('all',array('conditions' => "fkidmodule = {$value->idmodule} and status = 1 and (idcategoryparent IS NULL or idcategoryparent = 0)"));

                    if($find){

                        foreach($find as $value2){

                            $temparray = self::recursive_category_id($value2->idcategory);

                            if(!empty($temparray)){
                                foreach($temparray as $value3){
                                    $find = false;
                                    $find = Category::find($value3);

                                    $tempurl = LayermoduleController::controller('generateUrl',array('module' => $module->name,'layer' => $layer->name,'filtermodel' => $tempfilter,'urlmodule' => $value->urlmodule,'url' => $value->urlcategory,'category' => $find->url,'pageparent' => $value2->url));
                                    if(Tools::check_url($tempurl) === true){
                                        $urls[] = $tempurl;
                                    }
                                }
                            }else{
                                $tempurl = LayermoduleController::controller('generateUrl',array('module' => $module->name,'layer' => $layer->name,'filtermodel' => $tempfilter,'urlmodule' => $value->urlmodule,'url' => '','category' => '','pageparent' => $value->pageparent));

                                if(Tools::check_url($tempurl) === true){
                                    $urls[] = $tempurl;
                                }
                            }
                        }
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
                $return = BASE_URL . DIR_SITEMAP_PATH . '/' . $file;
            }


            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    public static function recursive_category_id($id) {

        global $APP;
        $APP->setDependencyModule(array('Category'));
        $return = array();
        $arrayParent = array();
        $categories = false;

        $sql = "select DISTINCT c.idcategory,c.idcategoryparent from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where c.idcategoryparent = {$id} and c.status = 1";

        $categories = Category::find_by_sql($sql);

        if ($categories) {
            foreach ($categories as $key => $value) {

                $return[] = $value->idcategory;

                $arrayParent = self::recursive_category_id($value->idcategory);

                if(!empty($arrayParent)){
                    $return = array_merge($return,$arrayParent);
                }
            }
        }

        return $return;
    }

    public static function recursive_category($id, $sqlAux = "") {

        global $APP;
        $APP->setDependencyModule(array('Category'));
        $return = array();
        $arrayParent = array();
        $categories = false;
        $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
        
        if(empty($sqlAux)){
            
            $sql = "select DISTINCT c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule,m.name as namemodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.name = '{$layer}'";
        }else{
            $sql = $sqlAux;
        }
        
        if(LAYER != 'admin'){
            $sql .= " and c.status = 1";
        }

        if(is_int($id)){
             $sql .= " and c.idcategoryparent = {$id}";
        }
        
        $categories = Category::find_by_sql($sql);

        if ($categories) {
            $cont = 0;
            foreach ($categories as $key => $value) {

                $arrayParent = self::recursive_category($value->idcategory);

                $return[$cont] = $value->to_array();
                if ($value->idcategoryparent > 0) {
                    $find = Category::find($value->idcategoryparent);
                    $return[$cont]['parent'] = $find->to_array();
                }
                $return[$cont]['child'] = $arrayParent;
                
                $cont++;
            }
        }

        return $return;
    }

}

?>
