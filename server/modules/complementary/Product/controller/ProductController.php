<?php

class ProductController {

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
            $APP->setDependencyModule(array('Product', 'Module', 'Layer'));
            $return = array();
            $arraychild = array();
            $product = array();
            $products = false;
            $product = Module::find_by_name('Product');
            $layer = $attributes['layer'];
            unset($attributes['layer']);
            $layer = Layer::find_by_name($layer);
            
            $sql = "select p.idproduct,p.name,p.url,p.description,p.picture,p.shortdescription,p.sort,c.idcategory from " . DB_PREFIX . "product p inner join " . DB_PREFIX . "category c on p.fkidcategory = c.idcategory inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $product->idmodule and p.status = 1";
            
            if (is_numeric($id) && $id > 0) {
                $sql .= " and (c.idcategory = {$id} or c.idcategoryparent = {$id})";
            }
            
            if (count($attributes) > 0) {

                if(isset($attributes['param'])){
                    foreach ($attributes['param'] as $key => $value) {
                        
                        if($key[0] === '_'){
                            
                            $key = ltrim($key, '_');
                            
                            if (!is_numeric($value)) {
                                $sql .= " and p.{$key} = '{$value}'";
                            } else {
                                $sql .= " and p.{$key} = {$value}";
                            }
                        }else{
                            if (!is_numeric($value)) {
                                $sql .= " and c.{$key} = '{$value}'";
                            } else {
                                $sql .= " and c.{$key} = {$value}";
                            }
                        }
                    }
                }else{
                    foreach ($attributes as $key => $value) {
                        
                        if($key[0] === '_'){
                            
                            $key = ltrim($key, '_');
                            
                            if (!is_numeric($value)) {
                                $sql .= " and p.{$key} = '{$value}'";
                            } else {
                                $sql .= " and p.{$key} = {$value}";
                            }
                        }else{
                            if (!is_numeric($value)) {
                                $sql .= " and c.{$key} = '{$value}'";
                            } else {
                                $sql .= " and c.{$key} = {$value}";
                            }
                        }
                    }
                }
            }
            
            $sql .= " order by p.sort asc";
            
            $products = Product::find_by_sql($sql);

            if($products){
                $cont = 0;
                foreach ($products as $key => $value) {
                    
                    $arraychild = self::recursive_category_product($value->idcategory);
                    //unset($products->$key->idcategory);
                    
                    $return[$cont] = $value->to_array();
                    $return[$cont]['child'] = $arraychild;
                    
                    $cont++;
                }
            }

            return $APP->response((ONELEVEL) ? Tools::array_child_onelevel($return,'idproduct','child','sort') : $return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function recursive_category_product($id) {

        global $APP;
        $APP->setDependencyModule(array('Product'));
        $return = array();
        $arrayChild = array();
        $products = false;

        $sql = "select p.idproduct,p.name,p.url,p.description,p.picture,p.shortdescription,p.sort,c.idcategory from " . DB_PREFIX . "product p inner join " . DB_PREFIX . "category c on p.fkidcategory = c.idcategory inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where c.idcategoryparent = {$id} and c.status = 1 order by p.sort asc";

        $products = Product::find_by_sql($sql);

        if ($products) {
            
            $cont = 0;
            foreach ($products as $key => $value) {

                $arrayChild = self::recursive_category_product($value->idcategory);
                //unset($products->$key->idcategory);
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
            $APP->setDependencyModule(array('Product', 'Category', 'Module', 'Layer'));
            $return = array();
            $categories = array();
            $product = array();
            $product = Module::find_by_name('Product');
            $layer = $attributes['layer'];
            $layer = Layer::find_by_name($layer);

            if ($id > 0) {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.idcategory <> {$id} and c.fkidmodule = $product->idmodule and c.status = 1 order by c.name asc";
            } else {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $product->idmodule and c.status = 1 order by c.name asc";
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
            $APP->setDependencyModule(array('Product', 'Category', 'Module', 'Layer'));
            $return = array();
            $categories = array();
            $product = array();
            $product = Module::find_by_name('product');
            $layer = $attributes['layer'];
            $layer = Layer::find_by_name($layer);

            if ($id > 0) {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.idcategory = {$id} and c.fkidmodule = $product->idmodule and c.status = 1 order by c.name asc";
            } else {
                $sql = "select c.idcategory,c.name,c.url,c.idcategoryparent,c.sort,c.status,c.fkidmodule from " . DB_PREFIX . "category c inner join " . DB_PREFIX . "module m on c.fkidmodule = m.idmodule inner join " . DB_PREFIX . "layermodule lm on m.idmodule = lm.idmodule inner join " . DB_PREFIX . "layer l on lm.idlayer = l.idlayer where l.idlayer = {$layer->idlayer} and c.fkidmodule = $product->idmodule and c.status = 1 order by c.name asc";
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

                    $APP->setDependencyModule(array('Product', 'Authentication','Module','Sitemap'));
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'product','insert')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

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
                            if($value->name === 'highlight'){
                                if($value->value === true){
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

                    if (Authentication::check_token($token) === false) {
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    $find = Product::find('all', array('conditions' => "name = '{$array['name']}' OR url = '{$array['url']}'"));

                    if ($find) {
                        throw new Exception("MODULE.PRODUCT.ERROR.EXISTS.NAMEORURL");
                    }

                    $find = Module::find_by_name('product');
                    $array['fkidmodule'] = $find->idmodule;

                    //Verifica se existe a chave sort, se não existir é buscado o autoincrement da tabela a ser cadastrada
                    if(!isset($array['sort']) || empty($array['sort'])){
                        $id = false;
                        $id = $APP->CON->query_and_fetch_one("SELECT `AUTO_INCREMENT` as id FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".DB_DATABASE."'AND   TABLE_NAME   = '".DB_PREFIX.strtolower(str_replace('Controller','',__CLASS__))."' ");
                        $array['sort'] = (int) $id;
                    }
                    
                    Product::create($array);
                    $APP->CON->commit();
                    $APP->activityLog("Register Product");
                    SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
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

            global $APP;
            $APP->setDependencyModule(array('Product','Category'));
            $return = array();
            $products = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    $return = Product::find($id);
                    $return = $return->to_array();
                    $return['highlight'] = ($return['highlight'] == 1) ? true : false ;
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if (!empty($attributes)) {
                        foreach ($attributes as $key => $value) {
                            if ($key === 'url' && !empty($value)) {
                                if (substr($value, -1) != '/') {
                                    $value = $value . '/';
                                }
                            }
                            if (!is_numeric($value)) {
                                $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.{$key} = '{$value}' order by p.sort asc");
                                
                                $cont = 0;
                                foreach ($products as $key2 => $value2) {
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            } else {
                                $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.{$key} = {$value} order by p.sort asc");
                                
                                $cont = 0;
                                foreach ($products as $key2 => $value2) {
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
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
                        
                        $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 order by p.sort asc");
                        
                        $cont = 0;
                        
                        foreach ($products as $key => $value) {
                            $return[$cont] = $value->to_array();
                            $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
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
                    $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.idproduct = {$id} AND p.status = 1 order by p.sort asc");

                    $cont = 0;
                    foreach($products as $value){
                        $return[$cont] = $value->to_array();
                        $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
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
                            if (!is_numeric($value)) {
                                $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.{$key} = '{$value}' AND p.status = 1 order by p.sort asc");

                                $cont = 0;
                                foreach ($products as $key2 => $value2) {
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
                                    if(!empty($value2->fkidcategory)){
                                        $category = Category::find($value2->fkidcategory);
                                        $return[$cont]['category'] = $category->to_array();
                                    }
                                    $cont++;
                                }
                            } else {
                                $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.{$key} = {$value} AND p.status = 1 order by p.sort asc");

                                $cont = 0;
                                foreach ($products as $key2 => $value2) {
                                    $return[$cont] = $value2->to_array();
                                    $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
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
                        $products = Product::find_by_sql("SELECT DISTINCT p.idproduct,p.name,p.url,p.code,p.shortdescription,p.description,p.brand,p.manufacturer,p.supplier,p.unit,p.packaging,p.weight,p.stock,p.discount,p.unitvalue,p.amount,p.commission,p.observation,p.keywords,p.picture,p.pictures,p.highlight,p.sort,p.registrationdate,p.status,p.fkidcategory,p.fkidmodule FROM ".DB_PREFIX."product p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l ON lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status = 1 AND p.status = 1 order by p.sort asc");

                        $cont = 0;
                        foreach ($products as $key => $value) {
                            $return[$cont] = $value->to_array();
                            $return[$cont]['highlight'] = ($return[$cont]['highlight'] == 1) ? true : false ;
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
                $APP->setDependencyModule(array('Product','Sitemap'));
                $product = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'product','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                if(class_exists('Orderproduct')){
                    $APP->setDependencyModule(array('Orderproduct'));
                    $APP->CON->query("DELETE FROM ".DB_PREFIX."orderproduct WHERE fkidproduct = {$id}");
                }
                
                $product = Product::find($id);
                $url = $product->url;
                $product->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Product");
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
                $APP->setDependencyModule(array('Product', 'Authentication','Sitemap'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idproduct = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'product','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

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
                        if($value->name === 'sort'){
                            if(!empty($value->value)){
                                $value->value = (int) $value->value;
                            }
                        }
                        if($value->name === 'status'){
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if($value->name === 'highlight'){
                            if($value->value === true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'idproduct') {
                            $idproduct = $value->value;
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

                $find = Product::find('all', array('conditions' => "(name = '{$array['name']}' OR url = '{$array['url']}') and idproduct <> {$idproduct}"));

                if ($find) {
                    throw new Exception("MODULE.PRODUCT.ERROR.EXISTS.NAMEORURL");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Product::find($id);
                
                //Verifica se existe a chave sort, se não existir é atribuído o id do produto
                if(!isset($update_attributes['sort']) || empty($update_attributes['sort'])){
                    $update_attributes['sort'] = (int) $find->{"id".strtolower(str_replace('Controller','',__CLASS__))};
                }
                
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Product");
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

                if(!Authentication::check_access_control(CUR_LAYER,'product','config')){
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
                        $APP->activityLog("Update Config Product");
                        return $APP->response(true, 'success', 'MODULE.PRODUCT.SUCCESS.CONFIG', $json);
                    }
                }
                return $APP->response(true, 'success', 'MODULE.PRODUCT.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }

    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Product', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'product', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('product');
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
            if(isset($aux->link)){
                $columns .= $nick.'.link,';
            }

            $sql = "SELECT DISTINCT {$columns}lm.filtermodel,lm.url as urlmodule,lm.urlcategory,pg.url as pageparent FROM ".DB_PREFIX."{$thismodule} {$nick} INNER JOIN ".DB_PREFIX."module m ON {$nick}.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."page pg ON m.idmodule = pg.fkidmodule INNER JOIN ".DB_PREFIX."layermodule lm ON m.idmodule = lm.idmodule WHERE lm.idmodule = {$module->idmodule} and lm.idlayer = {$layer->idlayer} and m.name = '{$thismodule}' and m.status = 1 and lm.status = 1 and {$nick}.status = 1 GROUP BY ({$id})";

            $find = $thismoduleup::find_by_sql($sql);

            if($find){

                foreach($find as $value){

                    if(isset($value->link)){
                        $tempurl = $value->link;

                        if(Tools::check_url($tempurl) === true){
                            $urls[] = $tempurl;
                        }
                    }
                    if(isset($value->url)){
                        $aux = explode('/',$page['filtermodel']);

                        $tempurl = LayermoduleController::controller('generateUrl',array('module' => $module->name,'layer' => $layer->name,'filtermodel' => '/'.$aux[1].$value->filtermodel,'urlmodule' => $value->urlmodule,'url' => $value->url,'urlcategory' => '','category' => '','pageparent' => $value->pageparent));

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
