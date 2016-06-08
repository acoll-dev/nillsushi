<?php

class ModuleController {

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
                $idprofile = $APP->PROFILE['idprofile'];
                
                $APP->setDependencyModule(array('Module', 'Moduleprofile','Layer'));
                $return = array();
                $module = array();
                $modules = array();

                /*if (isset($attributes['layer'])) {
                    $layer = $attributes['layer'];
                    unset($attributes['layer']);
                }*/
                
                $layer = Layer::find_by_name(CUR_LAYER);

                if (isset($attributes['name'])) {
                    $modules = Moduleprofile::find_by_sql("SELECT mp.idmodule,mp.idprofile,mp.status FROM ".DB_PREFIX."moduleprofile mp INNER JOIN ".DB_PREFIX."profile p on mp.idprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile INNER JOIN ".DB_PREFIX."layer l on pl.idlayer = l.idlayer WHERE pl.idlayer = {$layer->idlayer} AND mp.idprofile = {$idprofile} AND mp.status = 1 AND pl.status = 1 AND l.status = 1");
                    $return[] = array('label' => "", 'value' => "");
                    foreach ($modules as $key => $value) {
                        $module = Module::find($value->idmodule);
                        if ($module->name === $attributes['name']) {
                            $return[] = array('label' => $module->name, 'value' => $module->idmodule, 'selected' => true, 'disabled' => true);
                            break;
                        }
                    }

                    return $APP->response($return, 'success', '', $json);
                } else {
                    $modules = Moduleprofile::find('all', array('conditions' => "idprofile = {$idprofile}"));
                    $return[] = array('label' => "", 'value' => "");
                    foreach ($modules as $key => $value) {
                        $module = Module::find($value->idmodule);
                        $return[] = array('label' => $module->name, 'value' => $module->idmodule);
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

    private static function get($attributes = array(), $id = 0, $json = false) {
        try {

            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Module','Layer'));
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////
                
                if(!Authentication::check_access_control(CUR_LAYER,'module','select')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                $return = array();
                $modules = array();
                if ($id > 0) {
                    $return = Module::find($id);
                    //$return->struct = ($return->struct === 1) ? 'Yes' : 'No' ;
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    
                    $modules = Module::find_by_sql("SELECT m.idmodule,m.name,m.struct,m.path,m.status FROM ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE l.name = '".LAYER."'");
                    foreach ($modules as $key => $value) {
                        //$value->struct = ($value->struct) ? 'Yes' : 'No' ;
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

    private static function insert($attributes = array(), $id = 0, $json = false) {
        try {
            global $APP;
            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                $APP->setDependencyModule(array('Module', 'Authentication'));

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'module','install')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'status') {
                            if ($value->value == true) {
                                $value->value = 1;
                            } else {
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'searchable') {
                            if ($value->value == true) {
                                $value->value = 1;
                            } else {
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'module') {
                            $module = $value->value;
                        }
                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                    }
                }

                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                $return = self::installModule($module);
                
                if(array_key_exists('error', $return)){
                    return $APP->response(false, 'danger', 'MODULE.MODULE.'.$return['error'], $json);
                }else{
                    return $APP->response(true, 'success', 'MODULE.MODULE.SUCCESS.INSTALL', $json);
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
                $module = false;
                $APP->setDependencyModule(array('Module', 'Route', 'Menu', 'Resource', 'Layermodule', 'Profilemenu', 'Profileresource', 'Moduleprofile'));
                $module = 0;
                $return = false;
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'module','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                $module = Module::find($id);
                $modulesDependency = array();
                if ($module) {
                    $nameModule = ucfirst(strtolower($module->name));
                    $APP->setDependencyModule(array($nameModule));
                    if ($module->struct === 0) {

                        if(isset($nameModule::$tables_parents)){
                            foreach($nameModule::$tables_parents as $value){

                                //Deleta esvazia a tabela dependente e excluí
                                $APP->CON->query("TRUNCATE TABLE " . $value['name']);
                                $APP->CON->query("DROP TABLE " . $value['name']);

                                //Delete a pasta caso exista
                                if(isset($value['path'])){
                                    self::deleteFolderModule(DIR_MODULE . $value['path']);
                                }
                            }
                        }
                        
                        $registers = false;
                        $registers = $nameModule::all();

                        if ($registers) {
                            foreach ($registers as $key => $value) {
                                $value->delete();
                            }
                        }

                        $APP->setDependencyModule(array('Category'));
                        
                        if (class_exists('Category') && class_exists('CategoryBase')) {
                            
                            $categories = false;
                            $categories = Category::find('all', array('conditions' => "fkidmodule = {$id}"));
                            if ($categories) {
                                foreach ($categories as $key => $value) {
                                    $categoriesparent = false;
                                    $categoriesparent = Category::find('all', array('conditions' => "idcategoryparent = {$value->idcategory}"));
                                    if ($categoriesparent) {
                                        foreach ($categoriesparent as $key2 => $value2) {
                                            $value2->delete();
                                        }
                                    }
                                    $value->delete();
                                }
                            }
                        }

                        if ($nameModule === 'Service') {
                            if (file_exists(DIR_MODULE_COMP . 'Budget')) {

                                $APP->setDependencyModule(array('Budget'));
                                $budgets = false;
                                $budget = Module::find_by_name('budget');
                                $modulesDependency[] = $budget->idmodule;
                            }
                        }

                        $menus = Menu::find('all', array('conditions' => "fkidmodule = {$id}"));

                        foreach ($menus as $key => $value) {
                            $profilemenus = false;
                            $profilemenus = Profilemenu::find('all', array('conditions' => "idmenu = {$value->idmenu}"));
                            if ($profilemenus) {
                                foreach ($profilemenus as $key2 => $value2) {
                                    $value2->delete();
                                }
                            }
                            $value->delete();
                        }

                        $resources = Resource::find('all', array('conditions' => "fkidmodule = {$id}"));

                        foreach ($resources as $key => $value) {
                            $profileresources = false;
                            $profileresources = Profileresource::find('all', array('conditions' => "idresource = {$value->idresource}"));
                            if ($profileresources) {
                                foreach ($profileresources as $key2 => $value2) {
                                    $value2->delete();
                                }
                            }
                            $value->delete();
                        }

                        $moduleprofiles = Moduleprofile::find('all', array('conditions' => "idmodule = {$id}"));

                        foreach ($moduleprofiles as $key => $value) {
                            $value->delete();
                        }

                        $layermodules = Layermodule::find('all', array('conditions' => "idmodule = {$id}"));

                        foreach ($layermodules as $key => $value) {
                            $value->delete();
                        }

                        //Deletas as páginas e blocos do módulo a ser deletado
                        
                        $APP->setDependencyModule(array('Page','Block'));
                        
                        $find = false;
                        $find = Page::find_by_fkidmodule($module->idmodule);

                        if($find){
                            $findblock = false;
                            $findblock = Block::find('all',array('conditions'=> "fkidpage = {$find->idpage}"));
                            
                            if($findblock){
                                foreach($findblock as $value){
                                    $value->delete();
                                }
                            }
                            $find->delete();
                        }
                        
                        $module->delete();
                        $APP->CON->commit();

                        $APP->DB->set_model_directory(DIR_MODULE . 'griffo' . DS . 'Authentication' . DS . "model");

                        if (!empty($modulesDependency)) {
                            foreach ($modulesDependency as $key => $value) {
                                $result = self::delete(array(), $value);
                                if (!$result['response']) {
                                    throw new Exception("MODULE.MODULE.ERROR.TRY.DELETE");
                                }
                            }
                        }
                        $APP->CON->transaction();
                        if (substr($module->path, -1) == '/' || substr($module->path, -1) == '\\') {
                            $module->path = substr($module->path, 0, -1);
                        }

                        $nameModule = strtolower($nameModule);
                        $APP->CON->query("SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;DROP TABLE " . DB_PREFIX . $nameModule);
                        $return = self::deleteFolderModule(DIR_MODULE . $module->path);
                        if ($return) {
                            $return = false;
                            $return = self::RemoveModuleUser($id);
                            if($return){
                                $APP->CON->commit();
                                $APP->activityLog("Delete Module {$nameModule}");
                                return $APP->response(true, 'success', 'MODULE.MODULE.SUCCESS.MODULE.DELETE', $json);
                            }else{
                                throw new Exception('MODULE.MODULE.ERROR.DELETE.ARRAYUSER');
                            }
                        } else {
                            throw new Exception('MODULE.MODULE.ERROR.DELETE.FOLDER');
                        }
                    } else {
                        throw new Exception('MODULE.MODULE.ERROR.DELETE.STRUCT');
                    }
                } else {
                    throw new Exception('MODULE.MODULE.ERROR.NOTFOUND');
                }
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
    }

    private static function downloadModule($file = "", $url = "", $dir = "") {
        if (!empty($url) && !empty($dir) && !empty($file)) {

            if (file_exists($dir)) {
                try {
                    set_time_limit(0);
                    $fp = fopen($dir . $file, 'w+'); //Este é o arquivo onde guardar as informações
                    $ch = curl_init(str_replace(" ", "%20", $url)); //Aqui está o arquivo que está baixando, substituir os espaços com %20
                    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
                    curl_setopt($ch, CURLOPT_FILE, $fp);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                    //echo 'Download completed successfully!';
                    return true;
                } catch (\Exception $e) {
                    global $APP;
                    $APP->writeLog($e);
                    //echo $e->getMessage();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            //echo 'URL or empty directory!';
            return false;
        }
    }

    private static function copyModule($curDir = "", $dirDst = "", $file = "", $module = "") {
        
        if (!empty($curDir) && !empty($dirDst) && !empty($file) && !empty($module)) {
            
            global $APP;
            
            if (!file_exists($dirDst . $module)) {
                
                if (file_exists($curDir . $file)) {
                    
                    try {
                        rename($curDir . $file, $dirDst . $file);
                        return true;
                    } catch (\Exception $e) {
                        $APP->writeLog($e);
                        //echo $e->getMessage();
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                
                self::deleteFileModule($curDir. $file);
                echo json_encode($APP->response(false, 'warning', 'MODULE.MODULE.WARNING.EXISTS', false));
                //return false;
                exit;
            }
        } else {
            return false;
        }
    }
    
    private static function loadXmlModule($file = "") {

        try {
            global $APP;
            $APP->CON->transaction();
            if (file_exists($file)) {

                $xml = json_decode(json_encode((array) simplexml_load_file($file, null, LIBXML_COMPACT)), 1);

                $arrayModules = array();
                $contMenu = 0;
                $resources = array();
                $arrayResources = array();
                $arrayMenus = array();
                $menus = array();
                $contResources = 0;
                $contMenus = 0;
                $arrayProfiles = array();
                if (!empty($xml) && count($xml) > 0 && isset($xml['griffo'])) {

                    foreach ($xml['griffo'] as $key => $value) {

                        $key = ucfirst(strtolower($key));
                        if (!file_exists(DIR_MODULE . $key)) {
                            $APP->includeModule($key);

                            if (!isset($value[0])) {

                                if (class_exists($key)) {
                                    $contResources = 0;
                                    $contMenus = 0;
                                    foreach ($value as $key2 => $value2) {
                                        if ($value2 === 'null') {
                                            $value[$key2] = null;
                                        }
                                        if (($key2 === 'fkidmodule' && isset($idmodule) && $value2 === '|current_module|') || ($key2 === 'idmodule' && isset($idmodule) && $value2 === '|current_module|')) {
                                            $value[$key2] = $idmodule;
                                        }
                                        /* if($key === 'Moduleprofile' && $key2 === 'idprofile'){
                                          $arrayProfiles[] = $value2;
                                          } */
                                        /* if($key2 === 'idresource' && $value2 === '|all|'){
                                          $value[$key2] = $resources[$contResources];
                                          $contResources++;
                                          }
                                          if($key2 === 'idmenu' && $value2 === '|all|'){
                                          $value[$key2] = $menus[$contMenus];
                                          $contMenus++;
                                          } */
                                    }

                                    $key::create($value);

                                    if ($key === 'Resource') {
                                        $aux = $key::last();
                                        $resources[] = (int) $aux->idresource;
                                    }
                                    if ($key === 'Menu') {
                                        if ($contMenu === 0) {
                                            $contMenu = 1;
                                            $menu = $key::last();
                                            $menus[] = $idmenu = (int) $menu->idmenu;
                                        } else {
                                            $menu = $key::last();
                                            $menus[] = (int) $menu->idmenu;
                                        }
                                    }
                                }
                            } else {

                                $contResources = 0;
                                $contMenus = 0;
                                foreach ($value as $key3 => $value3) {

                                    if (class_exists($key)) {
                                        foreach ($value3 as $key4 => $value4) {
                                            if ($value4 === 'null') {
                                                $value3[$key4] = null;
                                            }
                                            if (($key4 === 'fkidmodule' && isset($idmodule) && $value4 === '|current_module|') || ($key4 === 'idmodule' && isset($idmodule) && $value4 === '|current_module|')) {
                                                $value3[$key4] = $idmodule;
                                            }
                                            if ($key4 === 'idmenuparent' && $value4 === '|last|' && isset($idmenu)) {
                                                $value3[$key4] = $idmenu;
                                            }
                                            if ($key4 === 'idresource' && $value4 === '|all|') {
                                                $arrayResources[] = $resources[$key3];
                                                $value3[$key4] = $resources[$key3];
                                            }
                                            if ($key4 === 'idmenu' && $value4 === '|all|') {
                                                $arrayMenus[] = $menus[$key3];
                                                $value3[$key4] = $menus[$key3];
                                            }
                                            if ($key4 === 'idresource' && $value4 === '|current|') {
                                                $value3[$key4] = $arrayResources[$contResources];
                                                $contResources++;
                                            }
                                            if ($key4 === 'idmenu' && $value4 === '|current|') {
                                                $value3[$key4] = $arrayMenus[$contMenus];
                                                $contMenus++;
                                            }
                                            /* if($key === 'Moduleprofile' && $key4 === 'idprofile'){
                                              $arrayProfiles[] = $value4;
                                              } */
                                        }
                                        $key::create($value3);
                                        if ($key === 'Resource') {
                                            $aux = $key::last();
                                            $resources[] = (int) $aux->idresource;
                                        }
                                        if ($key === 'Menu') {
                                            if ($contMenu === 0) {
                                                $contMenu = 1;
                                                $menu = $key::last();
                                                $menus[] = $idmenu = (int) $menu->idmenu;
                                            } else {
                                                $menu = $key::last();
                                                $menus[] = (int) $menu->idmenu;
                                            }
                                        }
                                    }
                                }
                            }
                            if ($key === 'Module') {
                                $module = $key::last();
                                $idmodule = (int) $module->idmodule;
                            }
                        } else {
                            $APP->CON->rollback();
                            throw new Exception('MODULE.MODULE.ERROR.LOADXML');
                        }
                    }

                    /* if(count($arrayProfiles) > 0){
                      foreach($arrayProfiles as $value){
                      if(!self::addModuleUser((int)$value,$idmodule)){
                      return false;
                      }
                      }
                      } */

                    $APP->CON->commit();
                    return true;
                } else {
                    throw new Exception('MODULE.MODULE.ERROR.LOADXML');
                }
            } else {
                throw new Exception('MODULE.MODULE.ERROR.LOADXML');
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            //echo $e->getMessage();
            $APP->CON->rollback();
            return false;
        }
    }

    private static function deleteFileModule($file = "") {
        if (!empty($file)) {
            if (file_exists($file)) {
                try {
                    unlink($file);
                    return true;
                } catch (\Exception $e) {
                    global $APP;
                    $APP->writeLog($e);
                    //echo $e->getMessage();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private static function loadFileSql($module) {
        if (!empty($module)) {
            if (file_exists($module)) {
                try {
                    global $APP;
                    $sql = file_get_contents($module);
                    $APP->CON->transaction();
                    $APP->CON->query($sql);
                    $APP->CON->commit();
                    return true;
                } catch (\Exception $e) {
                    $APP->writeLog($e);
                    //echo $e->getMessage();
                    $APP->CON->rollback();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private static function installModule($url = "") {
        
        global $APP;
        
        if (!empty($url)) {
            
            $url = ((substr($url, -1) === '/')) ? substr($url, 0, -1) : $url;
            $file = explode('/', $url);
            $file = end($file);
            $res = false;
            $res = self::downloadModule($file, $url, DIR_TMP);
            
            if ($res) {
                $res = false;
                $array = array();
                $array['Download_Module'] = true;
                $module = explode('.', $file);
                $module = ucfirst(strtolower($module[0]));
                
                $res = self::copyModule(DIR_TMP, DIR_MODULE_COMP, $file, $module);
                
                if ($res) {
                    $res = false;
                    $array['Copy_Module'] = true;
                    
                    $res = Tools::unzip(DIR_MODULE_COMP . $file, DIR_MODULE_COMP);
                    if ($res) {
                        $res = false;
                        $array['Extract_Module'] = true;
                        
                        $res = self::deleteFileModule(DIR_MODULE_COMP . $file);
                        if ($res) {
                            $res = false;
                            $array['Delete_file_zip'] = true;
                            
                            $find = false;
                            
                            $find = $APP->CON->query_and_fetch_one("SELECT * FROM information_schema.tables WHERE table_schema = '".DB_DATABASE."' AND table_name = '".DB_PREFIX.strtolower($module)."' LIMIT 1");
                            
                            if (empty($find)){
                                
                                $res = self::loadFileSql(DIR_MODULE_COMP . $module . DS . $module . '.sql');
                                
                                if ($res) {
                                    $res = false;
                                    $array['Load_file_sql'] = true;

                                    $res = self::loadXmlModule(DIR_MODULE_COMP . $module . DS . $module . '.xml');
                                    
                                    if ($res) {
                                        
                                        $res = false;
                                        $idLastModule = Module::last();
                                        $idLastModule = $idLastModule->idmodule;
                                        $array['Add_user'] = true;
                                        
                                        $res = self::addModuleUser($idLastModule);
                                        if ($res) {
                                            $APP->activityLog("Installation Module {$module}");
                                            $array['Load_file_xml'] = true;
                                            //return true;
                                        } else {
                                            $array['error'] = "ERROR.ADD.USER";
                                            self::delete(array(), $idLastModule);
                                            self::deleteFolderModule(DIR_MODULE_COMP . $module);
                                            //return false;
                                        }
                                    } else {
                                        $array['error'] = "ERROR.LOAD.XML";
                                        self::deleteFolderModule(DIR_MODULE_COMP . $module);
                                        //return false;
                                    }
                                } else {
                                    $array['error'] = "ERROR.LOAD.SQL";
                                    self::deleteFolderModule(DIR_MODULE_COMP . $module);
                                    //return false;
                                }
                            } else{
                                $array['error'] = "ERROR.TABLE.EXISTS";
                                self::deleteFileModule(DIR_MODULE_COMP . $file);
                                self::deleteFolderModule(DIR_MODULE_COMP . $module);
                                //return false;
                            }
                        } else {
                            $array['error'] = "ERROR.DELETE.ZIP";
                            self::deleteFileModule(DIR_MODULE_COMP . $file);
                            self::deleteFolderModule(DIR_MODULE_COMP . $module);
                            //return false;
                        }
                    } else {
                        $array['error'] = "ERROR.UNZIP";
                        self::deleteFileModule(DIR_MODULE_COMP . $file);
                        //return false;
                    }
                } else {
                    $array['error'] = "ERROR.COPY.ZIP";
                    self::deleteFileModule(DIR_TMP . $file);
                    //return false;
                }
            } else {
                $array['error'] = "ERROR.DOWNLOAD";
                //return false;
            }
        } else {
            $array['error'] = "ERROR.EMPTY.ATTRIBUTES";
            //return $array;
        }
        
        return $array;
    }

    private static function deleteFolderModule($dir) {
        try {
            if(is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir . DS . $object) == "dir") {
                            self::deleteFolderModule($dir . DS . $object);
                        } else {
                            unlink($dir . DS . $object);
                        }
                    }
                }
                reset($objects);
                rmdir($dir);
                return true;
            }else{
                throw new Exception("MODULE.MODULE.ERROR.DIRECTORY.NOTFOUND");
            }
        } catch (\Exception $e) {
            global $APP;
            $APP->writeLog($e);
            //echo $e->getMessage();
            return false;
        }
    }

    private static function update_attributes($attributes = array(), $id = 0, $json = false) {

        if (!empty($attributes) && (is_numeric($id) && $id > 0)) {
            try {
                global $APP;
                $APP->CON->transaction();
                $APP->setDependencyModule(array('Module', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'module','update')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        if ($value->name === 'status') {
                            if ($value->value == true) {
                                $value->value = 1;
                            } else {
                                $value->value = 0;
                            }
                        }
                        if ($value->name === 'searchable') {
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

                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Module::find($id);
                $name = $find->name;
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Module {$name}");

                return $APP->response(true, 'success', 'SUCCESS.UPDATE.COMPLETED', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }
    
    public static function RemoveModuleUser($id = 0) {

        if (is_numeric($id) && $id > 0) {
            
            global $APP;
            
            try {
                
                $APP->setDependencyModule(array('User', 'Authentication', 'Module'));
                
                if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                    
                    $menuorder = unserialize($APP->USER['menuorder']);
                    
                    foreach($menuorder as $key => $value){
                        if($value == $id){
                            unset($menuorder[$key]);
                        }
                    }
                    
                    $iduser = IDUSER;

                    $user = User::find($iduser);

                    $user->menuorder = serialize($menuorder);
                    $user->save();
                    $APP->USER['menuorder'] = serialize($menuorder);
                    
                    //Retirando o módulo de todos os usuários
                    
                    $users = false;
                    $users = User::all();
                    
                    if($users){
                        foreach($users as $value){
                            
                            $menuorder = unserialize($value->menuorder);
                            
                            if(isset($menuorder[$id])){
                                unset($menuorder[$id]);
                                $value->menuorder = serialize($menuorder);
                                $value->save();
                            }
                        }
                    }

                    return true;
                } else {
                    throw new Exception('ERROR.NOTFOUND.SESSION');
                }
            } catch (\Exception $e) {
                //echo $e->getMessage();
                $APP->writeLog($e);
                return false;
            }
        } else {
            return false;
        }
    }
    
    public static function addModuleUser($id = 0) {

        if (is_numeric($id) && $id > 0) {
            
            global $APP;
            
            try {
                
                $APP->setDependencyModule(array('User', 'Authentication', 'Module'));
                
                if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                    $module = Module::find($id);
                    $menuorder = unserialize($APP->USER['menuorder']);
                    $menuorder[count($menuorder) + 1] = $id;
                    $iduser = IDUSER;

                    $user = User::find($iduser);

                    $user->menuorder = serialize($menuorder);
                    $user->save();
                    $APP->USER['menuorder'] = serialize($menuorder);
                    
                    $APP->activityLog("Added $module->name module in the $user->name user");

                    return true;
                } else {
                    throw new Exception('ERROR.NOTFOUND.SESSION');
                }
            } catch (\Exception $e) {
                //echo $e->getMessage();
                $APP->writeLog($e);
                return false;
            }
        } else {
            return false;
        }
    }

    private static function checkModule($attributes = array(), $id = 0, $json = false) {
        
        try {
            if (empty($attributes)) {
                throw new Exception('ERROR.NOTFOUND.PARAMETERS');
            }
            global $APP;
            $return = array();
            $APP->setDependencyModule(array('Page', 'Layer', 'Layermodule', 'Language', 'Module', 'Template'));
            
            $status = false;
            $url = $attributes['url'];
            $view = array();
            //$layer = $attributes['layer'];

            $APP->LAYER = Layer::find_by_name(LAYER);
            
            $modules = Layermodule::find('all',array('conditions' => "idlayer = {$APP->LAYER->idlayer}"));
            
            if(!empty($url)){
                
                $arrayUrl = explode('/', $url);
                
                foreach ($modules as $value) {
                    
                    $cont = 0;
                    foreach ($arrayUrl as $value2) {
                        if(!empty($value2)){
                            $tmp = "";
                            for ($i = 0; $i < count($arrayUrl) - $cont; $i++) {
                                if (!empty($arrayUrl[$i]))
                                    $tmp .= $arrayUrl[$i] . '/';
                            }
                            //echo "url = ".$tmp."<br>route = ".$value->url."<br><br>";

                            if (strcmp($tmp, $value->url) == 0) {

                                $status = true;
                                $url = str_replace($tmp, '', $url);
                                $template = Template::find($APP->LAYER->fkidtemplate);
                                $language = Language::find($APP->LAYER->fkidlanguage);
                                $APP->MODULE = Module::find($value->idmodule);

                                $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path);

                                break;
                            }
                            $cont++;
                        }
                    }
                    if ($status){
                        break;
                    }
                }

                //Se não for encontrado o MÓDULO na tabela layermodule, verifica-se se existe a url no módulo PAGE

                if (!$status) {
                    
                    $arrayUrl = explode('/', $url);
                    $page = Module::find_by_name('page');
                    //$pages = Page::find_by_sql("SELECT DISTINCT lm.custom,lm.filtermodel,p.idpage,p.url,p.filecss,p.filejs,p.fileview,p.fkidmodule FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idmodule = {$page->idmodule} and lm.idlayer = {$APP->LAYER->idlayer} and p.status = 1");
                    $pages = Page::find_by_sql("SELECT DISTINCT lm.custom,lm.filtermodel,p.idpage,p.url,p.filecss,p.filejs,p.fileview,p.fkidmodule FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idlayer = {$APP->LAYER->idlayer} and p.status = 1");
                    
                    foreach ($pages as $value) {
                    
                        $cont = 0;
                        foreach ($arrayUrl as $value2) {
                            if(!empty($value2)){
                                $tmp = "";
                                for ($i = 0; $i < count($arrayUrl) - $cont; $i++) {
                                    if (!empty($arrayUrl[$i]))
                                        $tmp .= $arrayUrl[$i] . '/';
                                }
                                //echo "url = ".$tmp."<br>route = ".$value->url."<br><br>";

                                if (strcmp($tmp, $value->url) == 0) {
                                    //Verifica se a url repassada é a página padrão, se for, redireciona para raiz
    
                                    
                                    if(!empty($value->custom)){
                                        $custom = unserialize($value->custom);
                                        if(isset($custom['default_page'])){
                                            
                                            $aux = THIS;
                                            if (substr($aux, -1) != '/') {
                                                $aux = $aux . '/';
                                            }
                                            
                                            if($value->idpage == $custom['default_page']){
                                                $APP->URL_REDIRECTION = str_replace($value->url,'',$aux);
                                            }
                                        }
                                    }
                                    
                                    $status = true;
                                    $url = str_replace($tmp, '', $url);
                                    $template = Template::find($APP->LAYER->fkidtemplate);
                                    $language = Language::find($APP->LAYER->fkidlanguage);
                                    $APP->MODULE = Module::find($page->idmodule);
                                    $APP->PAGE = Page::find($value->idpage);
                                    $APP->REQUIRE_AUTHENTICATION = (boolean) $APP->PAGE->authenticate;
                                    //$findpage = false;
                                    
                                    //$findpage = Layermodule::find_by_sql("SELECT DISTINCT lm.filtermodel,p.filecss,p.filejs,p.fileview FROM ".DB_PREFIX."page p INNER JOIN ".DB_PREFIX."module m on p.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idmodule = {$APP->MODULE->idmodule} and lm.idlayer = {$APP->LAYER->idlayer} and p.status = 1");
                                    //if($findpage){
                                        //foreach($findpage as $value3){
                                            //$filterModulepage = $value->filtermodel;
                                            $view = array('filecss' => $value->filecss,'filejs' => $value->filejs,'fileview' => $value->fileview.'.php');
                                        //}
                                    //}
                                    
                                    $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path, 'contentView' => $view);

                                    break;
                                }
                                $cont++;
                            }
                        }
                        if ($status){
                            break;
                        }
                    }
                }
                
                if(!$status){

                    if($APP->LAYER->name != 'admin'){
                        
                        $APP->MODULE = Module::find_by_name('page');
                        $pagemodule = Layermodule::find_by_idmodule($APP->MODULE->idmodule);
                        $custom = unserialize($pagemodule->custom);
                        if(isset($custom['default_page'])){
                           $status = true;
                           $template = Template::find($APP->LAYER->fkidtemplate);
                           $language = Language::find($APP->LAYER->fkidlanguage);

                           $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path);
                        }
                    }else{
                        $moduledefault = false;
                        $moduledefault = Layermodule::find_by_sql("SELECT lm.idmodule,lm.filtermodel from ".DB_PREFIX."layermodule lm WHERE lm.idlayer = {$APP->LAYER->idlayer} AND lm.default = 1");
                        
                        if($moduledefault){
                            foreach($moduledefault as $value){
                                $APP->MODULE = Module::find($value->idmodule);
                                $status = true;
                                $template = Template::find($APP->LAYER->fkidtemplate);
                                $language = Language::find($APP->LAYER->fkidlanguage);

                                $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path);
                            }
                        }
                    }
                }
            }else{
                
                if($APP->LAYER->name != 'admin'){
                    
                    /*$arrayUrl = explode('/', '/home');

                    foreach ($modules as $value) {

                        $cont = 0;
                        foreach ($arrayUrl as $value2) {
                            
                            if(!empty($value2)){
                                $tmp = "";
                                for ($i = 0; $i < count($arrayUrl) - $cont; $i++) {
                                    if (!empty($arrayUrl[$i]))
                                        $tmp .= $arrayUrl[$i] . '/';
                                }
                                //echo "url = ".$tmp."<br>route = ".$value->uri."<br><br>";

                                if (strcmp($tmp, $value->url) == 0) {

                                    $status = true;
                                    $url = str_replace($tmp, '', $url);
                                    $template = Template::find($APP->LAYER->fkidtemplate);
                                    $language = Language::find($APP->LAYER->fkidlanguage);
                                    $APP->MODULE = Module::find($value->idmodule);

                                    $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path);

                                    break;
                                }
                                $cont++;
                            }
                        }
                        if ($status){
                            break;
                        }
                    }*/
                    
                    
                    $find = false;
                    $APP->MODULE = Module::find_by_name('page');
                    
                    $find = Layermodule::find('all',array('conditions' => "idlayer = {$APP->LAYER->idlayer} AND idmodule = {$APP->MODULE->idmodule}"));
                    
                    if($find){
                        
                        foreach($find as $value){
                            $pagemodule = $value->to_array();
                        }
                        $custom = unserialize($pagemodule['custom']);
                        
                        if(is_array($custom)){
                            if(array_key_exists('default_page',$custom)){

                               $status = true;
                               $page = false;
                               $template = Template::find($APP->LAYER->fkidtemplate);
                               $language = Language::find($APP->LAYER->fkidlanguage);
                               
                               $contentsPage = array();
                               $page = Page::find($custom['default_page']);
                               
                               if($page){
                                   $APP->PAGE = $page;
                                   $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $pagemodule['filtermodel'], 'pathModule' => $APP->MODULE->path,'defaultPage' => substr($page->url,0,-1));
                               }else{
                                  $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $pagemodule['filtermodel'], 'pathModule' => $APP->MODULE->path); 
                               }
                               
                               
                            }
                        }
                    }
                    
                }else{
                    
                    $moduledefault = false;
                    $moduledefault = Layermodule::find_by_sql("SELECT lm.idmodule,lm.filtermodel from ".DB_PREFIX."layermodule lm WHERE lm.idlayer = {$APP->LAYER->idlayer} AND lm.default = 1");

                    if($moduledefault){
                        foreach($moduledefault as $value){
                            $APP->MODULE = Module::find($value->idmodule);
                            $status = true;
                            $template = Template::find($APP->LAYER->fkidtemplate);
                            $language = Language::find($APP->LAYER->fkidlanguage);

                            $return = array('url' => $url, 'template' => $template->path, 'languageLayer' => $language->name, 'module' => $APP->MODULE->name, 'filterModel' => $value->filtermodel, 'pathModule' => $APP->MODULE->path);
                        }
                    }
                }
            }
            
            return $APP->response($return, 'success', true, $json);
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function _checkFilter($attributes = array(), $id = 0, $json = false) {
        global $APP,$PAGE;
        if (empty($attributes)) {
            return $APP->response(false, 'danger', 'ERROR.NOTFOUND.PARAMETERS', $json);
        }
        try {

                $array = array();
                $arrayLayer = array();
                $return = array();
                $module = false;
                $filtermodule = false;
                $filterModel = false;
                $findfiltermodule = false;
                $arrayPage = array();
                $auxCategory = "";
                $parentpage = "";
                $view = "";
                $idmodule = $attributes['idmodule'];
                $APP->setDependencyModule(array('Module', 'Authentication', 'Filtermodel','Layermodule','Layer'));
                $layer = Layer::find_by_name($attributes['layer']);
                
                if(!empty($APP->PAGE->fkidmodule) && $APP->MODULE->name === 'page'){
                    
                    if($APP->PAGE->fkidmodule != $APP->MODULE->idmodule){
                        
                        $module = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$idmodule}"));
                        if($module){
                            foreach($module as $value){
                                $aux = explode('/',$value->filtermodel);
                            }
                            $findfiltermodule = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$APP->PAGE->fkidmodule}"));

                            if($findfiltermodule){
                                foreach($findfiltermodule as $value){
                                    $filterModuleAux = $value->filtermodel;
                                }
                            }

                            $filterModelAux = $aux[1].$filterModuleAux;
                            
                            $filterModel = array_filter((strstr($filterModelAux, '/')) ? explode('/', $filterModelAux) : $filterModelAux);
                        }
                    }
                }
                
                if(empty($filterModel)){
                    $module = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$idmodule}"));
                    if($module){
                        foreach($module as $value){
                            $filterModelAux = $value->filtermodel;
                        }
                    }
                    $filterModel = array_filter((strstr($filterModelAux, '/')) ? explode('/', $filterModelAux) : $filterModelAux);
                }
                
                $url = array_filter((strstr($attributes['url'], '/')) ? explode('/', $attributes['url']) : (array) $attributes['url']);
                
                $filters = Filtermodel::all();
                $pattern = '/(\%(.*)\%)/';
                
                foreach ($filterModel as $value) {
                    foreach ($filters as $value2) {
                        preg_match($pattern, $value2->filter, $matches);
                        if ($matches[1] === $value) {
                            $array[] = $matches[2];
                            break;
                        }
                    }
                }
                
                if (count($array) > 0 && count($url) > 0) {

                    $cont = 0;
                    $aux = array();
                    $idUrl = "";
                    foreach ($url as $value) {
                        $aux[$cont] = $value;
                        $cont++;
                    }
                    $filter = $aux;
                    $pathModule = "";
                    
                    foreach ($array as $key => $value) {
                        foreach ($filter as $key2 => $value2) {

                            $value2 = $APP->PURIFIER->purify($value2);
                            $regexFilter = Filtermodel::find('all', array('conditions' => "filter = '%{$value}%'"));
                            foreach ($regexFilter as $value3) {
                                $regexCurFilter = '/' . $value3->regex . '/';
                                break;
                            }

                            preg_match($regexCurFilter, $value2, $matches);

                            if ($matches[0] === $value2) {
                                if(isset($array[$key2])){
                                    switch ($array[$key2])
                                          {

                                              case 'layer': {
                                                  
                                                      $find = false;
                                                      $find = Layer::find_by_name($value2);
                                                      if ($find)
                                                      {
                                                          $return[$array[$key2]] = $value2;
                                                      }else
                                                      {
                                                          //require_once (DIR_CURTEMPLATE . '404.php');
                                                          //exit(0);
                                                      }
                                                      break;
                                                  }
                                              case 'module': {
                                                      $find = false;
                                                      $find = Module::find_by_name($value2);
                                                      if ($find)
                                                      {
                                                          $APP->MODULE = $find;
                                                          //$return['view'] = $find->name;
                                                          $return[$array[$key2]] = $value2;
                                                      }else
                                                      {
                                                          //require_once (DIR_CURTEMPLATE . '404.php');
                                                          //exit(0);
                                                      }
                                                      break;
                                                  }
                                              case 'view': {
                                                  
                                                    if($APP->LAYER->name === 'admin'){
                                                      if (array_key_exists('layer', $return))
                                                      {
                                                          if (array_key_exists('module', $return))
                                                          {
                                                            if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                            {
                                                                
                                                                $view = $value2;
                                                            } else
                                                            {
                                                                //require_once (DIR_CURTEMPLATE . '404.php');
                                                                //exit(0);
                                                            }
                                                          } else
                                                          {
                                                              //require_once (DIR_CURTEMPLATE . '404.php');
                                                              //exit(0);
                                                          }
                                                      } else
                                                      {
                                                          if (array_key_exists('module', $return))
                                                          {
                                                              if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                              {
                                                                  $view = $value2;
                                                              } else
                                                              {
                                                                  //require_once (DIR_CURTEMPLATE . '404.php');
                                                                  //exit(0);
                                                              }
                                                          } else
                                                          {

                                                             if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                              {
                                                                  $view = $value2;
                                                              } else
                                                              {
                                                                  //require_once (DIR_CURTEMPLATE . '404.php');
                                                                  //exit(0);
                                                              }
                                                          }
                                                      }
                                                    }else{
                                                        if (file_exists(DIR_CURTEMPLATE . 'views' . DS . $value2 . '.php'))
                                                        {
                                                            $view = $value2;
                                                        } else
                                                        {
                                                            //require_once (DIR_CURTEMPLATE . '404.php');
                                                            //exit(0);
                                                        }
                                                      }
                                                      break;
                                                    }    
                                              case 'id': {

                                                      if(is_numeric($value2)){
                                                        $module = ucfirst($APP->MODULE->name);
                                                        $APP->useModule($module);
                                                        $find = $module::find($value2);
                                                        if($find){
                                                            $return[$array[$key2]] = $value2;
                                                        }else{
                                                            //require_once (DIR_CURTEMPLATE . '404.php');
                                                            //exit(0);
                                                        }
                                                      }else{
                                                        //require_once (DIR_CURTEMPLATE . '404.php');
                                                        //exit(0);
                                                      }  
                                                  break;
                                              }
                                              case 'url': {
                                                    
                                                    $module = ucfirst($APP->MODULE->name);
                                                    $APP->useModule($module);
                                                    $url = $value2 . '/';
                                                    $find = false;
                                                    $find = Layermodule::find_by_sql("SELECT urlcategory FROM ".DB_PREFIX."layermodule WHERE idmodule = {$APP->PAGE->fkidmodule} and idlayer = {$APP->LAYER->idlayer} and status = 1 and urlcategory = '{$url}'");

                                                    if($find){
                                                      $return['urlcategory'] = $value2;
                                                    }else{
                                                        $find = false;
                                                        $find = $module::find_by_url($url);

                                                        if($find){

                                                          $return[$APP->MODULE->name] = $find->to_array();
                                                        }else{
                                                            $find = false;
                                                            $module = Module::find($APP->PAGE->fkidmodule);
                                                            $module = ucfirst($module->name);
                                                            $APP->setDependencyModule(array($module));
                                                            $url = $value2 . '/';
                                                            $find = $module::find_by_url($url);

                                                            if($find){

                                                              $return[$APP->MODULE->name] = $find->to_array();
                                                            }
                                                        }
                                                    }
                                                break;
                                              }
                                              case 'urlcategory': {
                                                  
                                                        
                                                        $APP->setDependencyModule(array('Category','Layermodule'));
                                                        $url = $value2 . '/';

                                                        $find = Layermodule::find_by_sql("SELECT urlcategory FROM ".DB_PREFIX."layermodule WHERE idmodule = {$APP->PAGE->fkidmodule} and idlayer = {$APP->LAYER->idlayer} and status = 1 and urlcategory = '{$url}'");

                                                        if($find){
                                                          $return[$array[$key2]] = $value2;
                                                        }
                                                      break;
                                              }
                                              case 'category': {
                                                      
                                                      $APP->setDependencyModule(array('Category'));
                                                      
                                                      if(empty($auxCategory)){
                                                        $auxCategory = $url = $value2 . '/';
                                                      }else{
                                                        $url = $auxCategory .= $value2 . '/';
                                                      }

                                                      $arrayCategory = array();
                                                      $find = Category::find_by_sql("SELECT idcategory,name,url,idcategoryparent,status,fkidmodule from ".DB_PREFIX."category where fkidmodule = {$APP->PAGE->fkidmodule} and url = '{$url}'");

                                                      if(!empty($find)){

                                                          foreach($find as $item){
                                                            $arrayCategory[] = $item->to_array();
                                                          }

                                                          if(!isset($return[$array[$key2]])){
                                                            $return[$array[$key2]] = $arrayCategory[0];
                                                          }else{
                                                            $return[$array[$key2]] = array_merge($return[$array[$key2]],$arrayCategory[0]);
                                                          }
                                                      }elseif(!empty($auxCategory)){
                                                          //require_once (DIR_CURTEMPLATE . '404.php');
                                                          //exit(0);
                                                      }

                                                    break;
                                              }
                                              case 'pageparent':{

                                                  $APP->setDependencyModule(array('Page'));
                                                  $APP->MODULE = Module::find_by_name('page');
                                                  
                                                  $find = false;
                                                  //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '{$value2}%' ");
                                                  /*if($find){
                                                      foreach($find as $value3){
                                                          $arrayPage[$array[$key2]] = $value3->to_array();
                                                      }
                                                  }*/
                                                  
                                                  $find = Page::find_by_url($value2.'/');
                                                  
                                                  if($find){
                                                      
                                                      if(empty($find->idpageparent)){
                                                          
                                                        //Se existir meta tags na página, incluí.

                                                        if(!empty($find->metatag)){

                                                              $metatags = preg_split("/(?<!\\\\);/",$find->metatag);

                                                              foreach($metatags as $value4){

                                                                  if(strpos($value4,'\;') !== false){
                                                                      $value4 = str_replace('\;',';',$value4);
                                                                  }

                                                                  if(strpos($value4,'\,') !== false){
                                                                      $value4 = str_replace('\,',',',$value4);
                                                                  }

                                                                  $aux = preg_split("/(?<!\\\\),/",$value4);
                                                                  $array = array($aux[0] => $aux[1]);
                                                                  $PAGE->setTag(array('meta' => $array));
                                                              }  
                                                          }

                                                        //Se existir título na página, incluí.  

                                                        if(!empty($find->title)){
                                                            $APP->TITLE = $find->title;
                                                        }

                                                        //Se existir descrição na página, incluí.  

                                                        if(!empty($find->description)){
                                                            $PAGE->METATAG_DESCRIPTION = $find->description;
                                                        }

                                                        //Se existir keywords na página, incluí.  

                                                        if(!empty($find->keywords)){
                                                            $PAGE->METATAG_KEYWORDS = $find->keywords;
                                                        }

                                                        $blocks = false;
                                                        $APP->setDependencyModule(array('Block'));
                                                        $blocks = Block::find('all',array('conditions' => "fkidpage = {$find->idpage}"));

                                                        if($blocks){
                                                            $arrayblocks = array();
                                                            foreach($blocks as $value4){
                                                                $arrayblocks[] = $value4->to_array();
                                                            }
                                                        }
                                                          //Verifica se existe arquivo js e css, se tiver incluí.
                                                          if(!empty($find->filecss)){
                                                              if(strpos($find->filecss,';') !== false){
                                                                  $filecss = explode(';',$find->filecss);
                                                                  foreach($filecss as $value3){

                                                                      //$PAGE->setTag(array('css' => PATH_TEMPLATE.$value3));
                                                                      $PAGE->arrayPageCss[] = PATH_TEMPLATE.$value3;
                                                                  }
                                                              }else{
                                                                  //$PAGE->setTag(array('css' => PATH_TEMPLATE.$find->filecss));
                                                                  $PAGE->arrayPageCss[] = PATH_TEMPLATE.$find->filecss;
                                                              }
                                                          }


                                                          if(!empty($find->filejs)){
                                                              if(strpos($find->filejs,';') !== false){
                                                                  $filesjs = explode(';',$find->filejs);
                                                                  foreach($filesjs as $value3){
                                                                      //$PAGE->setTag(array('js' => PATH_TEMPLATE.$value3),'footer');
                                                                      $PAGE->arrayPageJs[] = PATH_TEMPLATE.$value3;
                                                                  }
                                                              }else{
                                                                //$PAGE->setTag(array('js' => PATH_TEMPLATE.$find->filejs),'footer');
                                                                  $PAGE->arrayPageJs[] = PATH_TEMPLATE.$find->filejs;
                                                              }
                                                          }

                                                        $pageparent = $value2.'/';

                                                      if (file_exists(PATH_TEMPLATE . 'view/' . $find->fileview.'.php'))
                                                      {

                                                          $APP->VIEW = PATH_TEMPLATE . 'view/' . $find->fileview.'.php';

                                                          $arrayPage['page'] = $find->to_array();
                                                          if(!empty($arrayblocks)){
                                                              $arrayPage['page']['blocks'] = $arrayblocks;
                                                          }
                                                      }
                                                      $arraychild = array();
                                                      $arraychild = PageController::recursive_page_child($find->idpage);
                                                      
                                                      if(!empty($arraychild)){
                                                          $arrayPage['page']['child'] = $arraychild;
                                                      }else{
                                                          $arrayPage['page']['child'] = array();
                                                      }
                                                    }
                                                      
                                                  }
                                                  
                                                  break;
                                              }
                                              case 'pagechild':{
                                                  
                                                    /*$find = false;
                                                    //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '%{$value2}/%' and (idcategoryparent IS NOT NULL OR idcategoryparent > 0)");
                                                    
                                                    $find = Page::find_by_url($value2.'/');

                                                    if($find){
                                                        
                                                        if(!array_key_exists($array[$key2],$arrayPage)){
                                                            $arrayPage['page'] = $find->to_array();
                                                        }else{
                                                            $arrayPage['page'] = array($array[$key2] => $find->to_array());
                                                        }
                                                    }else{
                                                        $APP->VIEW = "";
                                                    }
                                                  
                                                  break;*/
                                                  
                                                  $APP->setDependencyModule(array('Page'));
                                                  $APP->MODULE = Module::find_by_name('page');
                                                  
                                                  $find = false;
                                                  //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '{$value2}%' ");
                                                  /*if($find){
                                                      foreach($find as $value3){
                                                          $arrayPage[$array[$key2]] = $value3->to_array();
                                                      }
                                                  }*/
                                                  
                                                  $find = Page::find_by_url($value2.'/');
                                                  
                                                  if($find){
                                                      
                                                      //Se existir meta tags na página, incluí.
                                                      
                                                      if(!empty($find->metatag)){
                                                            
                                                            $metatags = preg_split("/(?<!\\\\);/",$find->metatag);
                                                            
                                                            foreach($metatags as $value4){
                                                                
                                                                if(strpos($value4,'\;') !== false){
                                                                    $value4 = str_replace('\;',';',$value4);
                                                                }
                                                                
                                                                if(strpos($value4,'\,') !== false){
                                                                    $value4 = str_replace('\,',',',$value4);
                                                                }
                                                                
                                                                $aux = preg_split("/(?<!\\\\),/",$value4);
                                                                $array = array($aux[0] => $aux[1]);
                                                                $PAGE->setTag(array('meta' => $array));
                                                            }  
                                                        }
                                                        
                                                      //Se existir título na página, incluí.  
                                                        
                                                      if(!empty($find->title)){
                                                          $APP->TITLE = $find->title;
                                                      }
                                                      
                                                      //Se existir descrição na página, incluí.  
                                                        
                                                      if(!empty($find->description)){
                                                          $PAGE->METATAG_DESCRIPTION = $find->description;
                                                      }
                                                      
                                                      //Se existir keywords na página, incluí.  
                                                        
                                                      if(!empty($find->keywords)){
                                                          $PAGE->METATAG_KEYWORDS = $find->keywords;
                                                      }
                                                      
                                                      $blocks = false;
                                                      $APP->setDependencyModule(array('Block'));
                                                      $blocks = Block::find('all',array('conditions' => "fkidpage = {$find->idpage}"));
                                                          
                                                      if($blocks){
                                                          $arrayblocks = array();
                                                          foreach($blocks as $value4){
                                                              $arrayblocks[] = $value4->to_array();
                                                          }
                                                      }
                                                        //Verifica se existe arquivo js e css, se tiver incluí.
                                                        if(!empty($find->filecss)){
                                                            if(strpos($find->filecss,';') !== false){
                                                                $filecss = explode(';',$find->filecss);
                                                                foreach($filecss as $value3){
                                                                    //$PAGE->setTag(array('css' => PATH_TEMPLATE.$value3));
                                                                    $PAGE->arrayPageCss[] = PATH_TEMPLATE.$value3;
                                                                }
                                                            }else{
                                                                //$PAGE->setTag(array('css' => PATH_TEMPLATE.$find->filecss));
                                                                $PAGE->arrayPageCss[] = PATH_TEMPLATE.$find->filecss;
                                                            }
                                                        }


                                                        if(!empty($find->filejs)){
                                                            if(strpos($find->filejs,';') !== false){
                                                                $filesjs = explode(';',$find->filejs);
                                                                foreach($filesjs as $value3){
                                                                    //$PAGE->setTag(array('js' => PATH_TEMPLATE.$value3),'footer');
                                                                    $PAGE->arrayPageJs[] = PATH_TEMPLATE.$value3;
                                                                }
                                                            }else{
                                                                //$PAGE->setTag(array('js' => PATH_TEMPLATE.$find->filejs),'footer');
                                                                $PAGE->arrayPageJs[] = PATH_TEMPLATE.$find->filejs;
                                                            }
                                                        }
                                                      
                                                      $pageparent = $value2.'/';

                                                    if (file_exists(PATH_TEMPLATE . 'view/' . $find->fileview.'.php'))
                                                    {
                                                        
                                                        $APP->VIEW = PATH_TEMPLATE . 'view/' . $find->fileview.'.php';

                                                        $arrayPage['page'] = $find->to_array();
                                                        if(!empty($arrayblocks)){
                                                            $arrayPage['page']['blocks'] = $arrayblocks;
                                                        }
                                                    }
                                                    
                                                    $arraychild = array();
                                                      $arraychild = PageController::recursive_page_child($find->idpage);
                                                      
                                                      if(!empty($arraychild)){
                                                          $arrayPage['page']['child'] = $arraychild;
                                                      }else{
                                                          $arrayPage['page']['child'] = array();
                                                      }
                                                      
                                                  }
                                                  break;
                                              }
                                              
                                          }
                                }
                            } else {
                                return false;
                            }
                        }
                        break;
                    }
                }

                $return = array_merge($arrayPage,$return);
                
                if (!empty($return)) {
                    if (!defined('CUR_LAYER')) {
                        if(array_key_exists('layer', $return)){
                            define('CUR_LAYER', $return['layer']);
                        }else {
                            define('CUR_LAYER', LAYER);
                        }
                    }
                }else{
                    if (!defined('CUR_LAYER')) {
                        define('CUR_LAYER', LAYER);
                    }
                }

                if(!empty($view)){
                    $APP->VIEW = $view;
                }

                return $APP->response($return, 'success', true, $json);
            
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function checkFilter($attributes = array(), $id = 0, $json = false) {
        global $APP,$PAGE;
        if (empty($attributes)) {
            return $APP->response(false, 'danger', 'ERROR.NOTFOUND.PARAMETERS', $json);
        }
        try {

                $array = array();
                $arrayLayer = array();
                $return = array();
                $module = false;
                $filtermodule = false;
                $filterModel = false;
                $findfiltermodule = false;
                $arrayPage = array();
                $auxCategory = "";
                $parentpage = "";
                $view = "";
                $idmodule = $attributes['idmodule'];
                $APP->setDependencyModule(array('Module', 'Authentication', 'Filtermodel','Layermodule','Layer'));
                $layer = Layer::find_by_name($attributes['layer']);
                
                if(!empty($APP->PAGE->fkidmodule) && $APP->MODULE->name === 'page'){
                    
                    if($APP->PAGE->fkidmodule != $APP->MODULE->idmodule){
                        
                        $module = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$idmodule}"));
                        if($module){
                            foreach($module as $value){
                                $aux = explode('/',$value->filtermodel);
                            }
                            $findfiltermodule = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$APP->PAGE->fkidmodule}"));

                            if($findfiltermodule){
                                foreach($findfiltermodule as $value){
                                    $filterModuleAux = $value->filtermodel;
                                    $urlcategory = $value->urlcategory;
                                }
                            }
                            
                            $filterModelAux = $aux[1].$filterModuleAux;
                            
                            $filterModel = array_filter((strstr($filterModelAux, '/')) ? explode('/', $filterModelAux) : $filterModelAux);
                            
                        }
                    }
                }
                
                if(empty($filterModel)){
                    $module = Layermodule::find('all',array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$idmodule}"));
                    if($module){
                        foreach($module as $value){
                            $filterModelAux = $value->filtermodel;
                        }
                    }
                    $filterModel = array_filter((strstr($filterModelAux, '/')) ? explode('/', $filterModelAux) : $filterModelAux);
                }
                
                $url = array_filter((strstr($attributes['url'], '/')) ? explode('/', $attributes['url']) : (array) $attributes['url']);
                
                if($key = array_search('%urlcategory%',$filterModel) !== false){
                    if(!empty($urlcategory)){
                        if(array_search($urlcategory,$url) === false){
                            
                            unset($filterModel[$key+1]);
                            
                        }
                    }
                    
                }
                
                $filters = Filtermodel::all();
                $pattern = '/(\%(.*)\%)/';
                
                foreach ($filterModel as $value) {
                    foreach ($filters as $value2) {
                        preg_match($pattern, $value2->filter, $matches);
                        if ($matches[1] === $value) {
                            $array[] = $matches[2];
                            break;
                        }
                    }
                }
                
                if (count($array) > 0 && count($url) > 0) {

                    $cont = 0;
                    $aux = array();
                    $idUrl = "";
                    foreach ($url as $value) {
                        $aux[$cont] = $value;
                        $cont++;
                    }
                    $filter = $aux;
                    $pathModule = "";
                    
                    foreach ($array as $key => $value) {
                        foreach ($filter as $key2 => $value2) {

                            $value2 = $APP->PURIFIER->purify($value2);
                            $regexFilter = Filtermodel::find('all', array('conditions' => "filter = '%{$value}%'"));
                            foreach ($regexFilter as $value3) {
                                $regexCurFilter = '/' . $value3->regex . '/';
                                break;
                            }

                            preg_match($regexCurFilter, $value2, $matches);
                            
                            if(isset($matches[0]))
                            if ($matches[0] === $value2) {
                                if(isset($array[$key2])){
                                    switch ($array[$key2])
                                          {

                                              case 'layer': {
                                                  
                                                      $find = false;
                                                      $find = Layer::find_by_name($value2);
                                                      if ($find)
                                                      {
                                                          $return[$array[$key2]] = $value2;
                                                      }else
                                                      {
                                                          //require_once (DIR_CURTEMPLATE . '404.php');
                                                          //exit(0);
                                                      }
                                                      break;
                                                  }
                                              case 'module': {
                                                      $find = false;
                                                      $find = Module::find_by_name($value2);
                                                      if ($find)
                                                      {
                                                          $APP->MODULE = $find;
                                                          //$return['view'] = $find->name;
                                                          $return[$array[$key2]] = $value2;
                                                      }else
                                                      {
                                                          //require_once (DIR_CURTEMPLATE . '404.php');
                                                          //exit(0);
                                                      }
                                                      break;
                                                  }
                                              case 'view': {
                                                  
                                                    if($APP->LAYER->name === 'admin'){
                                                      if (array_key_exists('layer', $return))
                                                      {
                                                          if (array_key_exists('module', $return))
                                                          {
                                                            if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                            {
                                                                
                                                                $view = $value2;
                                                            } else
                                                            {
                                                                //require_once (DIR_CURTEMPLATE . '404.php');
                                                                //exit(0);
                                                            }
                                                          } else
                                                          {
                                                              //require_once (DIR_CURTEMPLATE . '404.php');
                                                              //exit(0);
                                                          }
                                                      } else
                                                      {
                                                          if (array_key_exists('module', $return))
                                                          {
                                                              if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                              {
                                                                  $view = $value2;
                                                              } else
                                                              {
                                                                  //require_once (DIR_CURTEMPLATE . '404.php');
                                                                  //exit(0);
                                                              }
                                                          } else
                                                          {

                                                             if (file_exists(DIR_MODULE . $APP->MODULE->path . 'view' . DS . $APP->LAYER->name . DS . $value2 . '.php'))
                                                              {
                                                                  $view = $value2;
                                                              } else
                                                              {
                                                                  //require_once (DIR_CURTEMPLATE . '404.php');
                                                                  //exit(0);
                                                              }
                                                          }
                                                      }
                                                    }else{
                                                        if (file_exists(DIR_CURTEMPLATE . 'views' . DS . $value2 . '.php'))
                                                        {
                                                            $view = $value2;
                                                        } else
                                                        {
                                                            //require_once (DIR_CURTEMPLATE . '404.php');
                                                            //exit(0);
                                                        }
                                                      }
                                                      break;
                                                    }    
                                              case 'id': {

                                                      if(is_numeric($value2)){
                                                        $module = ucfirst($APP->MODULE->name);
                                                        $APP->useModule($module);
                                                        $find = $module::find($value2);
                                                        if($find){
                                                            $return[$array[$key2]] = $value2;
                                                        }else{
                                                            //require_once (DIR_CURTEMPLATE . '404.php');
                                                            //exit(0);
                                                        }
                                                      }else{
                                                        //require_once (DIR_CURTEMPLATE . '404.php');
                                                        //exit(0);
                                                      }  
                                                  break;
                                              }
                                              case 'url': {
                                                    
                                                    $module = ucfirst($APP->MODULE->name);
                                                    $APP->useModule($module);
                                                    $url = $value2 . '/';
                                                    $find = false;
                                                    
                                                    $find = Layermodule::find_by_sql("SELECT urlcategory FROM ".DB_PREFIX."layermodule WHERE idmodule = {$APP->PAGE->fkidmodule} and idlayer = {$APP->LAYER->idlayer} and status = 1 and urlcategory = '{$url}'");

                                                    if($find){
                                                      $return['urlcategory'] = $value2;
                                                    }else{
                                                        $find = false;
                                                        $find = $module::find_by_url($url);

                                                        if($find){

                                                          $return['url'] = $value2;
                                                        }else{
                                                            $find = false;
                                                            $namemodule = Module::find($APP->PAGE->fkidmodule);
                                                            $module = ucfirst($namemodule->name);
                                                            $APP->setDependencyModule(array($module));
                                                            $url = $value2 . '/';
                                                            $find = $module::find_by_url($url);

                                                            if($find){
                                                              $return['url'] = $value2;
                                                              $return[$namemodule->name] = $find->to_array();
                                                            }else{
                                                                require_once ('client/404.php');
                                                                exit(0);
                                                            }
                                                        }
                                                    }
                                                break;
                                              }
                                              case 'urlcategory': {
                                                  
                                                        
                                                        $APP->setDependencyModule(array('Category','Layermodule'));
                                                        $url = $value2 . '/';

                                                        $find = Layermodule::find_by_sql("SELECT urlcategory FROM ".DB_PREFIX."layermodule WHERE idmodule = {$APP->PAGE->fkidmodule} and idlayer = {$APP->LAYER->idlayer} and status = 1 and urlcategory = '{$url}'");

                                                        if($find){
                                                          $return[$array[$key2]] = $value2;
                                                        }
                                                      break;
                                              }
                                              case 'category': {
                                                      
                                                      $APP->setDependencyModule(array('Category'));

                                                      /*if(empty($auxCategory)){
                                                        $auxCategory = $url = $value2 . '/';
                                                      }else{
                                                        $url = $auxCategory .= $value2 . '/';
                                                      }*/
                                                      
                                                      $url = $value2 . '/';

                                                      //$arrayCategory = array();
                                                      $find = false;
                                                      //$find = Category::find_by_sql("SELECT idcategory,name,url,idcategoryparent,status,fkidmodule from ".DB_PREFIX."category where fkidmodule = {$APP->PAGE->fkidmodule} and url = '{$url}'");
                                                      $find = Category::find_by_url($url);

                                                      if($find){

                                                          /*foreach($find as $item){
                                                            $arrayCategory[] = $item->to_array();
                                                          }

                                                          if(!isset($return[$array[$key2]])){
                                                            $return[$array[$key2]] = $arrayCategory[0];
                                                          }else{
                                                            $return[$array[$key2]] = array_merge($return[$array[$key2]],$arrayCategory[0]);
                                                          }*/
                                                          $return[$array[$key2]] = $find->to_array();
                                                      }else{
                                                          require_once (DIR_CURTEMPLATE . '404.php');
                                                          exit(0);
                                                      }

                                                    break;
                                              }
                                              case 'pageparent':{

                                                  $APP->setDependencyModule(array('Page'));
                                                  $APP->MODULE = Module::find_by_name('page');
                                                  
                                                  $find = false;
                                                  //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '{$value2}%' ");
                                                  /*if($find){
                                                      foreach($find as $value3){
                                                          $arrayPage[$array[$key2]] = $value3->to_array();
                                                      }
                                                  }*/
                                                  
                                                  $find = Page::find_by_url($value2.'/');
                                                  
                                                  if($find){
                                                      
                                                      if(empty($find->idpageparent)){
                                                          
                                                        //Se existir meta tags na página, incluí.

                                                        if(!empty($find->metatag)){

                                                              $metatags = preg_split("/(?<!\\\\);/",$find->metatag);

                                                              foreach($metatags as $value4){

                                                                  if(strpos($value4,'\;') !== false){
                                                                      $value4 = str_replace('\;',';',$value4);
                                                                  }

                                                                  if(strpos($value4,'\,') !== false){
                                                                      $value4 = str_replace('\,',',',$value4);
                                                                  }

                                                                  $aux = preg_split("/(?<!\\\\),/",$value4);
                                                                  $arrayAuxPage = array($aux[0] => $aux[1]);
                                                                  $PAGE->setTag(array('meta' => $arrayAuxPage));
                                                              }  
                                                          }

                                                        //Se existir título na página, incluí.  

                                                        if(!empty($find->title)){
                                                            $APP->TITLE = $find->title;
                                                        }

                                                        //Se existir descrição na página, incluí.  

                                                        if(!empty($find->description)){
                                                            $PAGE->METATAG_DESCRIPTION = $find->description;
                                                        }

                                                        //Se existir keywords na página, incluí.  

                                                        if(!empty($find->keywords)){
                                                            $PAGE->METATAG_KEYWORDS = $find->keywords;
                                                        }

                                                        $blocks = false;
                                                        $APP->setDependencyModule(array('Block'));
                                                        $blocks = Block::find('all',array('conditions' => "fkidpage = {$find->idpage}"));
                                                        
                                                        if($blocks){
                                                            $arrayblocks = array();
                                                            foreach($blocks as $value4){
                                                                $arrayblocks[] = $value4->to_array();
                                                            }
                                                        }
                                                          //Verifica se existe arquivo js e css, se tiver incluí.
                                                          if(!empty($find->filecss)){
                                                              if(strpos($find->filecss,';') !== false){
                                                                  $filecss = explode(';',$find->filecss);
                                                                  foreach($filecss as $value3){

                                                                      //$PAGE->setTag(array('css' => PATH_TEMPLATE.$value3));
                                                                      $PAGE->arrayPageCss[] = PATH_TEMPLATE.$value3;
                                                                  }
                                                              }else{
                                                                  //$PAGE->setTag(array('css' => PATH_TEMPLATE.$find->filecss));
                                                                  $PAGE->arrayPageCss[] = PATH_TEMPLATE.$find->filecss;
                                                              }
                                                          }


                                                          if(!empty($find->filejs)){
                                                              if(strpos($find->filejs,';') !== false){
                                                                  $filesjs = explode(';',$find->filejs);
                                                                  foreach($filesjs as $value3){
                                                                      //$PAGE->setTag(array('js' => PATH_TEMPLATE.$value3),'footer');
                                                                      $PAGE->arrayPageJs[] = PATH_TEMPLATE.$value3;
                                                                  }
                                                              }else{
                                                                //$PAGE->setTag(array('js' => PATH_TEMPLATE.$find->filejs),'footer');
                                                                  $PAGE->arrayPageJs[] = PATH_TEMPLATE.$find->filejs;
                                                              }
                                                          }

                                                        $pageparent = $value2.'/';

                                                      if (file_exists(PATH_TEMPLATE . 'view/' . $find->fileview.'.php'))
                                                      {

                                                          $APP->VIEW = PATH_TEMPLATE . 'view/' . $find->fileview.'.php';

                                                          //$arrayPage['page'] = $find->to_array();
                                                          
                                                      }
                                                      $arraychild = array();
                                                      $arraychild = PageController::recursive_page_child($find->idpage);                                                      
                                                    }
                                                    
                                                    $arrayPage = array();                                              
                                                    $arrayPage['page'] = $find->to_array();
                                                    
                                                    if(!empty($arrayblocks)){
                                                        $arrayPage['page']['blocks'] = $arrayblocks;
                                                    }
                                                    
                                                    if(!empty($arraychild)){
                                                        $arrayPage['page']['child'] = $arraychild;
                                                    }else{
                                                        $arrayPage['page']['child'] = array();
                                                    }
                                                  }
                                                  
                                                  break;
                                              }
                                              case 'pagechild':{
                                                  
                                                    /*$find = false;
                                                    //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '%{$value2}/%' and (idcategoryparent IS NOT NULL OR idcategoryparent > 0)");
                                                    
                                                    $find = Page::find_by_url($value2.'/');

                                                    if($find){
                                                        
                                                        if(!array_key_exists($array[$key2],$arrayPage)){
                                                            $arrayPage['page'] = $find->to_array();
                                                        }else{
                                                            $arrayPage['page'] = array($array[$key2] => $find->to_array());
                                                        }
                                                    }else{
                                                        $APP->VIEW = "";
                                                    }
                                                  
                                                  break;*/
                                                  
                                                  $APP->setDependencyModule(array('Page'));
                                                  $APP->MODULE = Module::find_by_name('page');
                                                  
                                                  $find = false;
                                                  //$find = Page::find_by_sql("SELECT idpage, title,description,metatag,url,idpageparent,keywords,status,fkidmodule from ".DB_PREFIX."page WHERE url LIKE '{$value2}%' ");
                                                  /*if($find){
                                                      foreach($find as $value3){
                                                          $arrayPage[$array[$key2]] = $value3->to_array();
                                                      }
                                                  }*/
                                                  
                                                  $find = Page::find_by_url($value2.'/');
                                                  
                                                  if($find){
                                                      
                                                      //Se existir meta tags na página, incluí.
                                                      
                                                      if(!empty($find->metatag)){
                                                            
                                                            $metatags = preg_split("/(?<!\\\\);/",$find->metatag);
                                                            
                                                            foreach($metatags as $value4){
                                                                
                                                                if(strpos($value4,'\;') !== false){
                                                                    $value4 = str_replace('\;',';',$value4);
                                                                }
                                                                
                                                                if(strpos($value4,'\,') !== false){
                                                                    $value4 = str_replace('\,',',',$value4);
                                                                }
                                                                
                                                                $aux = preg_split("/(?<!\\\\),/",$value4);
                                                                $array = array($aux[0] => $aux[1]);
                                                                $PAGE->setTag(array('meta' => $array));
                                                            }  
                                                        }
                                                        
                                                      //Se existir título na página, incluí.  
                                                        
                                                      if(!empty($find->title)){
                                                          $APP->TITLE = $find->title;
                                                      }
                                                      
                                                      //Se existir descrição na página, incluí.  
                                                        
                                                      if(!empty($find->description)){
                                                          $PAGE->METATAG_DESCRIPTION = $find->description;
                                                      }
                                                      
                                                      //Se existir keywords na página, incluí.  
                                                        
                                                      if(!empty($find->keywords)){
                                                          $PAGE->METATAG_KEYWORDS = $find->keywords;
                                                      }
                                                      
                                                      $blocks = false;
                                                      $APP->setDependencyModule(array('Block'));
                                                      $blocks = Block::find('all',array('conditions' => "fkidpage = {$find->idpage}"));
                                                          
                                                      if($blocks){
                                                          $arrayblocks = array();
                                                          foreach($blocks as $value4){
                                                              $arrayblocks[] = $value4->to_array();
                                                          }
                                                      }
                                                        //Verifica se existe arquivo js e css, se tiver incluí.
                                                        if(!empty($find->filecss)){
                                                            if(strpos($find->filecss,';') !== false){
                                                                $filecss = explode(';',$find->filecss);
                                                                foreach($filecss as $value3){
                                                                    //$PAGE->setTag(array('css' => PATH_TEMPLATE.$value3));
                                                                    $PAGE->arrayPageCss[] = PATH_TEMPLATE.$value3;
                                                                }
                                                            }else{
                                                                //$PAGE->setTag(array('css' => PATH_TEMPLATE.$find->filecss));
                                                                $PAGE->arrayPageCss[] = PATH_TEMPLATE.$find->filecss;
                                                            }
                                                        }


                                                        if(!empty($find->filejs)){
                                                            if(strpos($find->filejs,';') !== false){
                                                                $filesjs = explode(';',$find->filejs);
                                                                foreach($filesjs as $value3){
                                                                    //$PAGE->setTag(array('js' => PATH_TEMPLATE.$value3),'footer');
                                                                    $PAGE->arrayPageJs[] = PATH_TEMPLATE.$value3;
                                                                }
                                                            }else{
                                                                //$PAGE->setTag(array('js' => PATH_TEMPLATE.$find->filejs),'footer');
                                                                $PAGE->arrayPageJs[] = PATH_TEMPLATE.$find->filejs;
                                                            }
                                                        }
                                                      
                                                      $pageparent = $value2.'/';

                                                    if (file_exists(PATH_TEMPLATE . 'view/' . $find->fileview.'.php'))
                                                    {
                                                        
                                                        $APP->VIEW = PATH_TEMPLATE . 'view/' . $find->fileview.'.php';

                                                        $arrayPage['page'] = $find->to_array();
                                                        if(!empty($arrayblocks)){
                                                            $arrayPage['page']['blocks'] = $arrayblocks;
                                                        }
                                                    }
                                                    
                                                    $arraychild = array();
                                                      $arraychild = PageController::recursive_page_child($find->idpage);
                                                      
                                                      if(!empty($arraychild)){
                                                          $arrayPage['page']['child'] = $arraychild;
                                                      }else{
                                                          $arrayPage['page']['child'] = array();
                                                      }
                                                      
                                                  }
                                                  break;
                                              }
                                              
                                          }
                                }
                            } else {
                                return false;
                            }
                        }
                        break;
                    }

                }

                $return = array_merge($arrayPage,$return);
                
                if (!empty($return)) {
                    if (!defined('CUR_LAYER')) {
                        if(array_key_exists('layer', $return)){
                            define('CUR_LAYER', $return['layer']);
                        }else {
                            define('CUR_LAYER', LAYER);
                        }
                    }
                }else{
                    if (!defined('CUR_LAYER')) {
                        define('CUR_LAYER', LAYER);
                    }
                }

                if(!empty($view)){
                    $APP->VIEW = $view;
                }

                return $APP->response($return, 'success', true, $json);
            
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }
    
    private static function get_module_layer($attributes = array(), $id = 0, $json = false) {
        try {

            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {
                global $APP;
                $APP->setDependencyModule(array('Module','Layermodule','Layer','Authentication'));
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'module','select')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                $return = array();
                $modules = array();
                $layer = Layer::find_by_name(CUR_LAYER);
                $modules = Layermodule::find_by_sql("SELECT lm.idlayer,lm.idmodule,lm.filtermodel,lm.url,lm.custom,lm.default,lm.status FROM ".DB_PREFIX."layermodule lm INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idlayer = {$layer->idlayer} AND l.status = 1");
                $sql = '';
                
                foreach ($modules as $key => $value) {
                    
                    if(!Authentication::check_user_master()){
                        $modules = Module::find_by_sql("SELECT m.idmodule,m.name,m.struct,m.path,m.status FROM ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idlayer = {$layer->idlayer} AND lm.status = 1 AND l.status = 1 AND m.status = 1 AND m.struct = 0 AND m.idmodule = {$value->idmodule}");
                        $module = Module::find('all',array('conditions' => "idmodule = {$value->idmodule} and status = 1 and struct = 0"));
                    }else{
                        $modules = Module::find_by_sql("SELECT m.idmodule,m.name,m.struct,m.path,m.status FROM ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE lm.idlayer = {$layer->idlayer} AND lm.status = 1 AND l.status = 1 AND m.status = 1 AND m.struct = 0 AND m.idmodule = {$value->idmodule}");
                        $module = Module::find('all',array('conditions' => "idmodule = {$value->idmodule} and status = 1"));
                    }
                    
                    foreach($module as $value2){
                        
                        $return[] = array('idmodule' => $value2->idmodule,'name' => $value2->name,'struct' => $value2->struct,'path' => $value2->path,'default' => $value->default,'status' => ($value->status == 1) ? true : false);
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
}

?>
