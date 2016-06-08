<?php

class BannerController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function category($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Banner', 'Module', 'Layer'));
            $return = array();
            $arraychild = array();
            $banner = array();
            $banners = false;
            $banner = Module::find_by_name('Banner');
            $layer = $attributes['layer'];
            unset($attributes['layer']);
            $layer = Layer::find_by_name($layer);
            
            $sql = "select b.idbanner,b.link,b.title,b.description,b.picture,b.sort,c.idcategory from " . DB_PREFIX . "banner b inner join " . DB_PREFIX . "category c on b.fkidcategory = c.idcategory inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $banner->idmodule and b.status = 1";
            
            if (is_numeric($id) && $id > 0) {
                $sql .= " and (c.idcategory = {$id} or c.idcategoryparent = {$id})";
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
            
            $sql .= " order by b.sort asc";

            $banners = Banner::find_by_sql($sql);

            if($banners){
                $cont = 0;
                foreach ($banners as $key => $value) {
                    
                    $arraychild = self::recursive_category_banner($value->idcategory);
                    //unset($banners->$key->idcategory);
                    $return[$cont] = $value->to_array();
                    $return[$cont]['child'] = $arraychild;
                    
                    $cont++;
                }
            }

            return $APP->response((ONELEVEL) ? Tools::array_child_onelevel($return,'idbanner','child','sort') : $return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function recursive_category_banner($id) {

        global $APP;
        $APP->setDependencyModule(array('Banner'));
        $return = array();
        $arrayChild = array();
        $banners = false;

        $sql = "select b.idbanner,b.link,b.title,b.description,b.picture,b.sort,c.idcategory from " . DB_PREFIX . "banner b inner join " . DB_PREFIX . "category c on b.fkidcategory = c.idcategory inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where c.idcategoryparent = {$id} and c.status = 1 order by b.sort asc";

        $banners = Banner::find_by_sql($sql);

        if ($banners) {
            
            $cont = 0;
            foreach ($banners as $key => $value) {

                $arrayChild = self::recursive_category_banner($value->idcategory);
                //unset($banners->$key->idcategory);
                $return[$cont] = $value->to_array();
                $return[$cont]['child'] = $arrayChild;
                
                $cont++;
            }
        }

        return $return;
    }

    private static function select_category_parent($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Banner', 'Category', 'Module', 'Layer'));
            $return = array();
            $categories = array();
            $banner = array();
            $banner = Module::find_by_name('Banner');
            $layer = $attributes['layer'];
            $layer = Layer::find_by_name($layer);

            if ($id > 0) {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.idcategory = {$id} and c.fkidmodule = $banner->idmodule and c.status = 1 order by c.name asc";
            } else {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $banner->idmodule and c.status = 1 order by c.name asc";
            }

            $categories = Category::find_by_sql($sql);
            $return[] = array('label' => "", 'value' => "");

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
    private static function select_category($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->setDependencyModule(array('Banner', 'Category', 'Module', 'Layer'));
            $return = array();
            $categories = array();
            $banner = array();
            $banner = Module::find_by_name('Banner');
            $layer = $attributes['layer'];
            $layer = Layer::find_by_name($layer);

            if ($id > 0) {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.idcategory = {$id} and c.fkidmodule = $banner->idmodule and c.status = 1";
            } else {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $banner->idmodule and c.status = 1";
            }

            $categories = Category::find_by_sql($sql);
            $return[] = array('label' => "", 'value' => "");

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

    private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            $APP->CON->transaction();
            if (isset($_SESSION[CLIENT.LAYER])) {

                if (!empty($attributes)) {

                    $APP->setDependencyModule(array('Banner', 'Authentication','Module','Sitemap'));
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'banner','insert')){
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
                            if ($value->name === 'link') {
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

                    if (Authentication::check_token($token) === false) {
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    $find = Module::find_by_name('banner');
                    $array['fkidmodule'] = $find->idmodule;

                    $find = Banner::find('all', array('conditions' => "title = '{$array['title']}'"));

                    if($find) {
                        throw new Exception("MODULE.BANNER.ERROR.EXISTS.TITLE");
                    }
                    
                    //Verifica se existe a chave sort, se não existir é buscado o autoincrement da tabela a ser cadastrada
                    if(!isset($array['sort']) || empty($array['sort'])){
                        $id = false;
                        $id = $APP->CON->query_and_fetch_one("SELECT `AUTO_INCREMENT` as id FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_DATABASE."'AND   TABLE_NAME   = '".DB_PREFIX.strtolower(str_replace('Controller','',__CLASS__))."' ");
                        $array['sort'] = (int) $id;
                    }

                    Banner::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Banner");
                    SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("ERROR.NOTFOUND.VALUE");
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

            global $APP;
            $APP->setDependencyModule(array('Banner','Category'));
            $return = array();
            $banners = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    $return = Banner::find($id);
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $value) {
                            if ($key === 'url' && !empty($value)) {
                                if (substr($value, -1) != '/') {
                                    $value = $value . '/';
                                }
                            }
                            if (is_string($value)) {
                                $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.{$key} = '{$value}' AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach ($banners as $key2 => $value2) {
                                    $return[] = $value2->to_array();
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            } else {
                                $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.{$key} = {$value} AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach ($banners as $key2 => $value2) {
                                    $return[] = $value2->to_array();
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    } else {
                        $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        $cont = 0;
                        foreach ($banners as $key => $value) {
                            $return[$cont] = $value->to_array();
                            if(!empty($value->fkidcategory)){
                                $category = Category::find($value->fkidcategory);
                                $return[$cont]['category'] = $category->to_array();
                            }
                            $cont++;
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }else{
                if ($id > 0) {
                    $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.idbanner = {$id} AND b.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                    $cont = 0;
                    foreach($banners as $value){
                        $return = $value->to_array();
                        if(!empty($value->fkidcategory)){
                            $category = Category::find($value->fkidcategory);
                            $return[$cont]['category'] = $category->to_array();
                        }
                        $cont++;
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $value) {
                            if ($key === 'url' && !empty($value)) {
                                if (substr($value, -1) != '/') {
                                    $value = $value . '/';
                                }
                            }
                            if (is_string($value)) {
                                $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.{$key} = '{$value}' AND b.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach ($banners as $key2 => $value2) {
                                    $return[] = $value2->to_array();
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            } else {
                                $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.{$key} = {$value} AND b.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                                $cont = 0;
                                foreach ($banners as $key2 => $value2) {
                                    $return[] = $value2->to_array();
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    } else {
                        $banners = Banner::find_by_sql("SELECT b.idbanner,b.title,b.link,b.width,b.height,b.description,b.keywords,b.picture,b.sort,b.registrationdate,b.status,b.fkidcategory,b.fkidmodule FROM ".DB_PREFIX."banner b INNER JOIN ".DB_PREFIX."module m on b.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE b.status = 1 AND m.status = 1 AND lm.status = 1 AND l.status = 1 AND l.idlayer = {$layer->idlayer}");

                        $cont = 0;
                        foreach ($banners as $key => $value) {
                            $return[] = $value->to_array();
                            if(!empty($value->fkidcategory)){
                                $category = Category::find($value->fkidcategory);
                                $return[$cont]['category'] = $category->to_array();
                            }
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
                $APP->CON->transaction();
                $id = (int) $id;
                $APP->setDependencyModule(array('Banner','Sitemap'));
                $banner = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'banner','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $banner = Banner::find($id);
                $url = $banner->link;
                $banner->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Banner");
                SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
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
                $APP->setDependencyModule(array('Banner', 'Authentication', 'Sitemap'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idbanner = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'banner','update')){
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
                        if ($value->name === 'link') {
                            if(!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = Tools::formatUrl($value->value . '/');
                                }else{
                                    $value->value = Tools::formatUrl($value->value);
                                }
                            }
                        }
                        if($value->name === 'sort'){
                            if(!empty($value->value)){
                                $value->value = (int) $value->value;
                            }
                        }
                        if ($value->name === 'idbanner') {
                            $idbanner = $value->value;
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

                $find = Banner::find('all', array('conditions' => "title = '{$array['title']}' and idbanner <> {$idbanner}"));

                if ($find) {
                    throw new Exception("MODULE.BANNER.ERROR.EXISTS.TITLE");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Banner::find($id);
                
                //Verifica se existe a chave sort, se não existir é atribuído o id do produto
                if(!isset($update_attributes['sort']) || empty($update_attributes['sort'])){
                    $update_attributes['sort'] = (int) $find->{"id".strtolower(str_replace('Controller','',__CLASS__))};
                }
                
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Banner");
                SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
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

                if(!Authentication::check_access_control(CUR_LAYER,'banner','config')){
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
                        $APP->activityLog("Update Config Banner");
                        return $APP->response(true, 'success', 'MODULE.BANNER.SUCCESS.CONFIG', $json);
                    }
                }
                return $APP->response(true, 'success', 'MODULE.BANNER.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Banner', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'banner', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('banner');
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

    private static function sitemap($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {

            $thismodule = strtolower(str_replace('Controller','',__CLASS__));

            $return = array();
            $find = false;
            $url = '';
            $layer = Layer::find_by_name(LAYER);
            $module = Module::find_by_name($thismodule);
            $file = "sitemab.{$thismodule}.xml";
            $nick = substr($thismodule,0,1).'.';
            $thismoduleup = ucfirst($thismodule);
            $data = array();

            $APP->setDependencyModule(array('Layer', $thismoduleup, 'Module'));

            $sql = "SELECT {$nick}title,{$nick}picture,lm.filtermodel,lm.url as urlmodule,lm.urlcategory FROM ".DB_PREFIX."{$thismodule} b INNER JOIN ".DB_PREFIX."module m ON {$nick}fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE lm.idmodule = {$module->idmodule} and lm.idlayer = {$layer->idlayer} and m.name = '{$thismodule}' and m.status = 1 and lm.status = 1 and {$nick}status = 1";

            $find = $thismoduleup::find_by_sql($sql);

            if($find){
                foreach($find as $value){

                    $data[] = array('picture' => $value->picture,'title' => $value->title);
                }

                if(file_exists(DIR_SITEMAP.DS.$file)){
                    unlink(DIR_SITEMAP.DS.$file);
                }
                $sitemap = new NilPortugues\Sitemap\ImageSitemap(DIR_SITEMAP,$file);
                foreach($data as $value){
                    $item = new NilPortugues\Sitemap\Item\Image\ImageItem(BASE_URL.URL_UPLOADS_PATH.$value['picture']);
                    $item->setTitle($value['title']);

                    $sitemap->add($item,BASE_URL);
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

}

?>
