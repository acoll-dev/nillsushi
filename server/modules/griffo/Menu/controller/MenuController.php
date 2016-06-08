<?php

class MenuController {

    private static $ACTIVE = false;

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
            $APP->setDependencyModule(array('Menu', 'Layer'));
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $menu = array();
            if ($id > 0) {
                //$menu = Menu::find('all', array('conditions' => "(idmenuparent IS NULL or idmenuparent = 0) and idmenu <> {$id} AND fkidlayer = {$layer->idlayer}"));
                $menu = Menu::find('all', array('conditions' => "idmenu <> {$id} AND fkidlayer = {$layer->idlayer}"));
                //$return[] = array('label' => "", 'value' => "");
                
                //foreach ($menu as $key => $value) {
                //    $return[] = array('label' => $value->label, 'value' => $value->idmenu);
                //}
            } else {
                //$menu = Menu::find('all', array('conditions' => "(idmenuparent IS NULL or idmenuparent = 0) AND fkidlayer = {$layer->idlayer}"));
                $menu = Menu::find('all', array('conditions' => "fkidlayer = {$layer->idlayer}"));
                //$return[] = array('label' => "", 'value' => "");
                
                
                //foreach ($menu as $key => $value) {
                //    $return[] = array('label' => $value->label, 'value' => $value->idmenu);
                //}
            }

            $menu = Tools::makeSelect($menu,'idmenu','idmenuparent','label');

            foreach ($menu as $key => $value) {

                ///////////////////////

                $ifen = '';
                for ($x = 1; $x <= $value['level']; $x++) {
                    $ifen .= '- ';
                }

                $return[] = array('label' => $ifen . $value['label'], 'value' => $value['idmenu']);

                ///////////////////////
            }
            
            return $APP->response($return, 'success', '', $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        
        global $APP;
        
        try {
            
            $APP->setDependencyModule(array('Menu', 'Layer'));
            $return = array();
            $menus = array();
            $layer = Layer::find_by_name($attributes['layer']);
            $idprofile = (isset($APP->PROFILE['idprofile'])) ? $APP->PROFILE['idprofile'] : '';
            if (LAYER === 'admin') {
                if ($id > 0) {
                    $return = Menu::find($id);

                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {

                    if(ID_PROFILE_MASTER === $idprofile){

                        $menus = Menu::all();
                        $cont = 0;
                        foreach ($menus as $key => $value) {
                            $layer = Layer::find($value->fkidlayer);
                            $return[$cont] = $value->to_array();
                            $return[$cont]['namelayer'] = $layer->label;
                            
                            if($value->idmenuparent > 0){
                                $menusparent = Menu::find($value->idmenuparent);
                                $return[$cont]['parent'] = $menusparent->to_array();
                            }
                            
                            $cont++;
                        }
                    }else{
                        $menus = Menu::find('all',array('conditions' => "fkidlayer = {$layer->idlayer}"));
                        $cont = 0;
                        foreach ($menus as $key => $value) {
                            $layer = Layer::find($value->fkidlayer);
                            $return[$cont] = $value->to_array();
                            $return[$cont]['namelayer'] = $layer->label;
                            
                            if($value->idmenuparent > 0){
                                $menusparent = Menu::find($value->idmenuparent);
                                $return[$cont]['parent'] = $menusparent->to_array();
                            }
                            
                            $cont++;
                        }
                    }
                        return $APP->response($return, 'success', '', $json);
                }
            } else {
                if ($id > 0) {
                    $menus = Menu::find('all', array('conditions' => "idmenu = {$id} and status = 1"));
                    foreach ($menus as $value) {
                        $return = $value->to_array();
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    $menus = Menu::find('all', array('conditions' => "status = 1 AND fkidlayer = {$layer->idlayer}"));
                    $cont = 0;
                    foreach ($menus as $key => $value) {
                        $layer = Layer::find($value->fkidlayer);
                        $return[$cont] = $value->to_array();
                        $return[$cont]['namelayer'] = $layer->label;
                        $cont++;
                    }
                    return $APP->response($return, 'success', '', $json);
                }
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function insert($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            
            $APP->CON->transaction();
            //if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
            if (LAYER === 'admin') {
                
                if (!empty($attributes)) {
                    
                    $APP->setDependencyModule(array('Menu', 'Authentication', 'Layer', 'Profile', 'Profilemenu'));
                    
                    $array = array();
                    $token = "";

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if (!Authentication::check_access_control(CUR_LAYER, 'menu', 'insert')) {
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////
                    
                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {
                            if (empty($value->value)) {
                                $value->value = null;
                            }
                            if ($value->name === 'path') {
                                if(!empty($value->value)){
                                    if (substr($value->value, -1) != '/') {
                                        $value->value = $value->value . '/';
                                    }
                                }
                            }
                            if ($value->name === 'status') {
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
                    
                    $attributes = Tools::objectToArray($attributes);
                    
                    if(isset($attributes['curlayer'])){
                        //$cur_layer = $attributes['curlayer'];
                        unset($attributes['curlayer']);
                       // $array['fkidlayer'] = $cur_layer;
                    }
                    
                    if (Authentication::check_token($token) === false) {
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    $find = false;
                    
                    //$find = Menu::find('all', array('conditions' => "(path = '{$array['path']}' OR label = '{$array['label']}') and fkidlayer = {$array['fkidlayer']}"));
                    
                    $conditions = "";
                    
                    if(isset($array['path'])){
                        if(!empty($array['path'])){
                            $conditions = "path = '{$array['path']}' and fkidlayer = {$array['fkidlayer']}";
                        }
                    }
                    
                    $layer = Layer::find($array['fkidlayer']);
                    
                    if($layer->name === 'admin'){
                        
                        $conditions = (!empty($conditions)) ? " and label = '{$array['label']}' and fkidlayer = {$array['fkidlayer']}" : "label = '{$array['label']}' and fkidlayer = {$array['fkidlayer']}";
                    }
                    
                    if(!empty($conditions)){
                        $find = Menu::find('all', array('conditions' => $conditions));

                        if ($find) {
                            throw new Exception("MODULE.MENU.ERROR.EXISTS.PATH");
                        }
                    }
                    
                    Menu::create($array);

                    if (isset($APP->PROFILE['idprofile'])) {

                        $last = Menu::last();
                        
                        $idprofile = $APP->PROFILE['idprofile'];

                        if ($layer->name === 'admin') {

                            if (Authentication::access_module($array['fkidmodule'], $idprofile) === true) {
                                Profilemenu::create(array('idmenu' => $last->idmenu, 'idprofile' => $idprofile, 'status' => 1));
                            }
                        }
                    }

                    $APP->CON->commit();
                    $APP->activityLog("Register Menu {$array['label']}");
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

    private static function update_attributes($attributes = array(), $id = 0, $json = false) {

        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Menu', 'Authentication', 'Layer', 'Profilemenu'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idmenu = 0;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'menu', 'update')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'path') {
                            if(!empty($value->value)){
                                if (substr($value->value, -1) != '/') {
                                    $value->value = $value->value . '/';
                                }
                            }
                        }
                        if ($value->name === 'status') {
                            if ($value->value == true) {
                                $value->value = 1;
                            } else {
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'idmenu') {
                            $idmenu = $value->value;
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

                $find = Menu::find('all', array('conditions' => "path = '{$array['path']}' and idmenu <> {$idmenu} and fkidlayer = {$array['fkidlayer']}"));

                if ($find) {
                    throw new Exception("MODULE.MENU.ERROR.EXISTS.PATHORLABEL");
                }

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Menu::find($id);
                $label = $find->label;
                $find->update_attributes($update_attributes);

                if (isset($APP->PROFILE['idprofile'])) {

                    $last = Menu::last();
                    $layer = Layer::find($array['fkidlayer']);
                    $idprofile = $APP->PROFILE['idprofile'];

                    if ($layer->name === 'admin') {

                        if (Authentication::access_module($update_attributes['fkidmodule'], $idprofile) === true) {
                            $find = false;
                            $find = Profilemenu::find('all',array('conditions' => "idmenu = $last->idmenu and idprofile = {$idprofile}"));

                            if(!$find){
                                Profilemenu::create(array('idmenu' => $last->idmenu, 'idprofile' => $idprofile, 'status' => 1));
                            }
                        }
                    }
                }

                $APP->CON->commit();
                $APP->activityLog("Update Menu {$label}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }
   
    private static function delete($attributes = array(), $id = 0, $json = false) {
        if (is_numeric($id) && $id > 0) {
            try {
                global $APP;
                $APP->CON->transaction();
                $id = (int) $id;
                $APP->setDependencyModule(array('Menu', 'Profilemenu'));
                $menu = 0;
                $return = false;

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if (!Authentication::check_access_control(CUR_LAYER, 'menu', 'delete')) {
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////

                $menu = Menu::find($id);
                $find = false;
                $find = Profilemenu::find_by_idmenu($id);

                if ($find) {
                    $find->delete();
                }
                
                $find = false;
                
                $find = Menu::find('all',array('conditions' => "idmenuparent = {$menu->idmenu}"));
                
                if($find){
                    
                    function deleteMenuParent($idmenuparent){

                        $findparent = false;
                        $findparent = Menu::find('all',array('conditions' => "idmenuparent = {$idmenuparent}"));

                        if($findparent){
                            foreach($findparent as $value2){
                                deleteMenuParent($value2->idmenu);
                            }
                        }
                        $menuparent = Menu::find($idmenuparent);
                        $menuparent->delete();
                    }
                    
                    foreach($find as $value){

                        deleteMenuParent($value->idmenu);
                    }
                }

                $label = $menu->label;
                $menu->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Menu {$label}");
                return $APP->response(true, 'success', 'SUCCESS.DELETE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function checkUrlMenu($pathMenu)
    {
        global $APP;

        if(!empty($pathMenu)){
            if(!empty($APP->FILTER)){

                return (stristr((substr($APP->FILTER, -1) === '/') ? $APP->FILTER : $APP->FILTER . '/', $pathMenu) !== false) ? self::$ACTIVE = true : false;
            }else{

                //Se o Filter estiver vazio, é verificado se a url é a da página padrão, se for o menu retorna active se houver a mesma url

                $APP->setDependencyModule(array('Layermodule','Module','Page'));
                $find = false;
                $page = Module::find_by_name('page');
                $find = Layermodule::find('all',array('conditions' => "idlayer = {$APP->LAYER->idlayer} and idmodule = {$page->idmodule} and status = 1 and (custom IS NOT NULL OR custom <> 0)"));

                if($find){
                    foreach($find as $value){
                        $custom = unserialize($value->custom);
                        if(isset($custom['default_page'])){
                            $idpagedefault = $custom['default_page'];
                        }
                    }
                    $page = Page::find($idpagedefault);
                    return (stristr((substr($page->url, -1) === '/') ? $page->url : $page->url . '/', $pathMenu) !== false) ? self::$ACTIVE = true : false;
                }

                return (stristr((substr($APP->FILTER, -1) === '/') ? $APP->FILTER : $APP->FILTER . '/', $pathMenu) !== false) ? self::$ACTIVE = true : false;
            }
        }
    }

    private static function orderMenu($arrayMenu = array())
    {
        global $APP;
        $return = array();
        if (is_array($arrayMenu) && !empty($arrayMenu))
        {
            
            if (isset($APP->USER['menuorder']))
            {
                $menuOrder = unserialize($APP->USER['menuorder']);
                $cont = 0;
                foreach ($menuOrder as $value)
                {
                    if (array_key_exists($value, $arrayMenu))
                    {
                        $return[$cont] = $arrayMenu[$value];
                        $cont++;
                    }
                }
            }
        }

        return $return;
    }

    public static function load_menu_admin($attributes = array(), $id = 0, $json = false) {

        global $APP;
        $APP->setDependencyModule(array('Module','Layer'));

        if(isset($APP->PROFILE['idprofile'])){

            $iduser = IDUSER;
            $idprofile = $APP->PROFILE['idprofile'];
            $arrayfilter = $attributes['array_filter'];
            $cur_layer = $attributes['cur_layer'];
            $arrayMenus = array();
            $activeSearch = false;
            $layer = Layer::find_by_name('admin');

            if (!empty($cur_layer) && $cur_layer != 'admin') {
                //busca menu do usuário atual
                $sql = "SELECT DISTINCT m.idmenu,ml.name,m.icon,m.idmenuparent,m.label,m.path,ml.struct,ml.idmodule FROM " . DB_PREFIX . "layermodule lm INNER JOIN " . DB_PREFIX . "module ml ON lm.idmodule = ml.idmodule INNER JOIN " . DB_PREFIX . "menu m on ml.idmodule = m.fkidmodule INNER JOIN " . DB_PREFIX . "profilemenu pm on m.idmenu = pm.idmenu WHERE pm.idprofile = {$idprofile} AND lm.status = 1 AND pm.status = 1 AND m.status = 1 AND (m.idmenuparent IS NULL or m.idmenuparent = 0) AND m.fkidlayer = {$layer->idlayer}";
                
                if(isset($attributes['search'])){
                    $aux = false;
                    $auxSQL = false;
                    $auxSQL = $sql;
                    $attributes['search'] = urldecode($attributes['search']);

                    //$sql .= $search = " AND MATCH(m.label,m.keywords) AGAINST('%{$attributes['search']}' IN BOOLEAN MODE)";
                    $sql .= $search = " AND m.keywords LIKE '%{$attributes['search']}%'";

                    $aux = Module::find_by_sql($sql);

                    if(!$aux){
                        //$sql = $auxSQL;
                        $aux = false;
                        $auxSearch = "";
                        $aux = Module::find_by_sql("SELECT DISTINCT m.idmenu,ml.name,m.icon,m.idmenuparent,m.label,m.path,ml.idmodule FROM " . DB_PREFIX . "module ml INNER JOIN " . DB_PREFIX . "menu m on ml.idmodule = m.fkidmodule INNER JOIN " . DB_PREFIX . "profilemenu pm on m.idmenu = pm.idmenu WHERE pm.status = 1 AND m.status = 1 AND (m.idmenuparent IS NOT NULL or m.idmenuparent = 0) AND m.fkidlayer = {$layer->idlayer}{$search}");
                        if($aux){
                            $auxSearch .= " AND (";
                            $cont = 1;
                            $activeSearch = true;
                            foreach($aux as $value){
                                if($cont > 1){
                                    $auxSearch .= " OR m.idmenu = {$value->idmenuparent}";
                                }else{
                                    $auxSearch .= " m.idmenu = {$value->idmenuparent}";
                                }
                                $cont++;
                            }
                            $auxSearch .= ")";

                            $sql = $auxSQL.$auxSearch;
                        }
                    }
                }
                
                $menu = Module::find_by_sql($sql);
                
                $arrayMenusParent = array();
                $dashboard = array();
                $menuDashboard = (array_key_exists('module', $arrayfilter)) ? false : true;
                
                foreach ($menu as $value) {
                    $active = false;
                    if(isset($attributes['search'])){
                        $arrayMenusParent = self::recursiveMenu($value->idmenu, $idprofile,$attributes['search']);
                    }else{
                        $arrayMenusParent = self::recursiveMenu($value->idmenu, $idprofile);
                    }
                    
                    if (!empty($arrayMenusParent)){
                        foreach ($arrayMenusParent as $sub) {
                            if ($sub['active'] === true) {
                                $active = true;
                                break;
                            }
                        }
                    }
                    $active = ($active === true) ? $active : (($activeSearch === true) ? $activeSearch : self::checkUrlMenu($value->path));


                    $arrayMenus[$value->idmodule] = array("idmenu" => (int) $value->idmenu, "idmodule" => (int) $value->idmodule, "name" => $value->name, "label" => $value->label, "href" => CUR_ALIAS . '/' . $cur_layer . '/' . $value->path, "icon" => $value->icon, "child" => $arrayMenusParent, "path" => $value->path, "active" => $active);

                }

                $arrayMenus = self::orderMenu($arrayMenus);
                
                if (self::$ACTIVE === false) {
                    foreach ($arrayMenus as $key => $value) {
                        if ($value['name'] === 'dashboard') {
                            $arrayMenus[$key]['active'] = true;
                            break;
                        }
                    }
                }
            }
            
            $APP->MENU = $arrayMenus;
        }
    }

    private static function recursiveMenu($id, $idprofile = 0,$search = "")
    {
        global $APP;
        $APP->setDependencyModule(array('Module'));

        if(LAYER === 'admin'){
            $sql = "SELECT DISTINCT m.idmenu,ml.name,m.icon,m.idmenuparent,m.label,m.path,ml.idmodule FROM " . DB_PREFIX . "module ml INNER JOIN " . DB_PREFIX . "menu m on ml.idmodule = m.fkidmodule INNER JOIN " . DB_PREFIX . "profilemenu pm on m.idmenu = pm.idmenu WHERE m.idmenuparent = {$id} AND pm.status = 1 AND m.status = 1";
          }else{
            $sql = "SELECT DISTINCT m.idmenu,ml.name,m.icon,m.idmenuparent,m.label,m.path,ml.idmodule FROM " . DB_PREFIX . "module ml INNER JOIN " . DB_PREFIX . "menu m on ml.idmodule = m.fkidmodule WHERE m.idmenuparent = {$id} AND m.status = 1";
          }

        if($idprofile > 0){
            $sql .= " AND pm.idprofile = {$idprofile}";
        }

        if(!empty($search)){
            //$sql .= " AND m.keywords LIKE '%{$search}%'";
            //$sql .= " AND MATCH(m.label,m.keywords) AGAINST('{$search}')";
        }

        $menuParent = Module::find_by_sql($sql);
        $cur_layer = CUR_LAYER;
        $cur_layer = ($cur_layer != 'admin') ? $cur_layer . '/' : '';
        $arrayMenusParent = array();
        $arrayReturn = array();
          foreach ($menuParent as $value)
          {
              $active = false;
              $arrayMenusParent = self::recursiveMenu($value->idmenu, $idprofile,$value->idmenuparent);
              if(!empty($arrayMenusParent)){
                  foreach ($arrayMenusParent as $sub){
                    if($sub['active'] === true){
                        $active = true;
                        break;
                    }
                  }
              }
              $active = ($active === true) ? $active : self::checkUrlMenu($value->path);

              $arrayReturn[] = array("idmenu" => (int)$value->idmenu,"idmodule" => (int)$value->idmodule,"name" => strtolower($value->label), "label" => $value->label, "href" => CUR_ALIAS . '/' . $cur_layer . $value->path, "icon" => $value->icon, "child" => $arrayMenusParent,"path" => $value->path, "active" => $active);
          }
        return $arrayReturn;
    }

    private static function loadMenu($attributes = array(), $id = 0, $json = false){

                  global $APP;
                  $APP->setDependencyModule(array('Menu','Layer'));
                  $layer = Layer::find_by_name(LAYER);
                    $menu = Menu::find('all',array('conditions' => "fkidlayer = {$layer->idlayer} and status = 1 and (idmenuparent IS NULL or idmenuparent = 0)"));
                    $arrayMenusParent = array();
                    $arrayMenus = array();
                    $cur_layer = (!empty($attributes['cur_layer'])) ? $attributes['cur_layer'] . '/' : '';

                    foreach ($menu as $value)
                    {
                        $active = false;

                        $arrayMenusParent = self::recursiveMenu($value->idmenu);
                          if(!empty($arrayMenusParent)){
                              foreach ($arrayMenusParent as $sub){
                                if($sub['active'] === true){
                                    $active = true;
                                    break;
                                }
                              }
                          }
                          $active = ($active === true) ? $active : self::checkUrlMenu($value->path);
                          $href = ($value->path === '/') ?  '' : $value->path;
                          $arrayMenus[] = array("idmenu" => $value->idmenu,"label" => $value->label, "href" => CUR_ALIAS . '/' . $href, "icon" => $value->icon, "child" => $arrayMenusParent,"path" => $value->path, "active" => $active);
                    }

                    $APP->MENU = $arrayMenus;
              }

}

?>
