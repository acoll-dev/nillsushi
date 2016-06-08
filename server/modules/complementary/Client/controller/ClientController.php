<?php

class ClientController {

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
            $APP->setDependencyModule(array('Client'));
            $return = array();
            $client = array();
            $client = Client::find('all',array('order' => "name asc"));
            $return[] = array('label' => "", 'value' => "");
            foreach ($client as $key => $value) {
                $return[] = array('label' => $value->name, 'value' => $value->idclient);
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

                if (!empty($attributes)) {
                    
                    $APP->setDependencyModule(array('Client', 'Authentication','Module','Userauth','Applicationauth','Userauthapplication'));
                    $array = array();
                    $token = "";
                    $password = "";
                    $email = "";
                    $curlayer = "";
                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    //if(!Authentication::check_access_control(CUR_LAYER,'client','insert')){
                        //throw new Exception("ERROR.RECUSED.RESOURCE");
                    //}

                    ////////////////////////////////////////////////////////////
                    
                    if(isset($attributes['curlayer'])){    
                        $curlayer = $attributes['curlayer'];
                        unset($attributes['curlayer']);
                    }else{
                        $curlayer = LAYER;
                    }
                    
                    foreach ($attributes as $key => $value) {
                        if (isset($value->name)) {

                            if($value->name === 'status'){
                                if($value->value == true){
                                    $value->value = 1;
                                }else{
                                    $value->value = 0;
                                }
                            }
                            if($value->name === 'password'){
                                $password = Tools::myMD5($value->value);
                                unset($attributes[$key]);
                                continue;
                            }
                            if($value->name === 'email'){
                                
                                if(Tools::check_email($value->value) === false){
                                    unset($attributes[$key]);
                                    continue;
                                }else{
                                    $email = $value->value;
                                }
                            }if ($value->name === 'token') {
                                $token = $value->value;
                                unset($attributes[$key]);
                                continue;
                            }
                            if(empty($value->value)){
                                $value->value = null;
                            }
                            
                            $array = array_merge($array, array($value->name => $value->value));
                            
                        }
                    }
                    
                    $find = Client::find('all', array('conditions' => "name = '{$array['name']}'"));

                    if ($find) {
                        throw new Exception("MODULE.CLIENT.ERROR.EXISTS.NAME");
                    }
                    
                    /*if(!empty($token)){
                        if (Authentication::check_token($token) === false) {
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }
                    }*/

                    $array['created'] = date('Y-m-d H:i:s');
                    $find = Module::find_by_name('client');
                    $array['fkidmodule'] = $find->idmodule;
                    
                    Client::create($array);
                    
                    //Cadastra usuário
                    
                    if(!empty($email) && !empty($password)){
                        $application = Applicationauth::find_by_name($curlayer);
                        
                        Userauth::create(array('user' => $email,'password' => $password));
                        $userauth = Userauth::last();
                        Userauthapplication::create(array('iduserauth' => $userauth->iduserauth,'idapplicationauth' => $application->idapplicationauth,'status' => 1));
                    }
                    
                    $APP->CON->commit();
                    $APP->activityLog("Register Client");
                    //SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
                    return $APP->response(true, 'success', 'SUCCESS.CREATE.COMPLETED', $json);
                } else {
                    throw new Exception("There are no values​for registering!");
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
            $APP->setDependencyModule(array('Client'));
            $return = array();
            $clients = array();
            $layer = (LAYER === 'admin') ? CUR_LAYER : LAYER;
            $layer = Layer::find_by_name($layer);
            unset($attributes['layer']);
            if(LAYER === 'admin'){
                if ($id > 0) {
                    $return = Client::find($id);
                    return $APP->response($return->to_array(), 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(is_string($value)){
                                $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND b.{$key} = '{$value}'");
                                
                                foreach($clients as $key2 => $value2){
                                    $return = $value2->to_array();
                                }
                            }else{
                                $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND b.{$key} = {$value}");
                                
                                foreach($clients as $key2 => $value2){
                                    $return = $value2->to_array();
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status");
                        
                        foreach($clients as $key => $value){
                            $return[] = $value->to_array();
                        }
                        return $APP->response($return, 'success', '', $json);
                    }
                }
            }else{
                if ($id > 0) {
                    $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND c.idclient = {$id} AND c.status = 1");
                    
                    foreach($clients as $value){
                        $return = $value->to_array();
                    }
                    return $APP->response($return, 'success', '', $json);
                } else {
                    if(!empty($attributes)){
                        foreach($attributes as $key => $value){
                            if(is_string($value)){
                                $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND c.{$key} = '{$value}' AND c.status = 1");
                                
                                foreach($clients as $key2 => $value2){
                                    $return = $value2->to_array();
                                }
                            }else{
                                $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND c.{$key} = {$value} AND c.status = 1");

                                foreach($clients as $key2 => $value2){
                                    $return = $value2->to_array();
                                }
                            }
                        }
                        return $APP->response($return, 'success', '', $json);
                    }else{
                        $clients = Client::find_by_sql("SELECT DISTINCT c.idclient,c.name,c.email,c.address,c.number,c.complement,c.district,c.city,c.state,c.phone,c.mobilephone,c.preferredshop,c.created,c.updated,c.status,c.fkidmodule,c.fkidshop FROM ".DB_PREFIX."client c INNER JOIN ".DB_PREFIX."module m on c.fkidmodule = m.idmodule INNER JOIN ".DB_PREFIX."layermodule lm on m.idmodule = lm.idmodule INNER JOIN ".DB_PREFIX."layer l on lm.idlayer = l.idlayer WHERE m.status = 1 AND lm.status = 1 AND l.status AND c.status = 1");

                        foreach($clients as $key => $value){
                            $return[] = $value->to_array();
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
                $APP->setDependencyModule(array('Client'));
                $client = 0;
                $return = false;
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                if(!Authentication::check_access_control(CUR_LAYER,'client','delete')){
                    throw new Exception("ERROR.RECUSED.RESOURCE");
                }

                ////////////////////////////////////////////////////////////
                
                if(class_exists('Order')){
                    $APP->setDependendyModule(array('Order'));
                    $find = false;
                    $find = Order::find('all',array('conditions' => "fkidclient = {$id}"));
                    
                    if($find){
                        foreach($find as $value){
                            $value->delete();
                        }
                    }
                }
                
                $client = Client::find($id);
                $client->delete();
                $APP->CON->commit();
                $APP->activityLog("Delete Client");
                //SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
                return $APP->response(true, 'success', 'Registration deleted successfully!', $json);
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
                $APP->setDependencyModule(array('Client', 'Authentication'));
                $array = array();
                $update_attributes = array();
                $token = "";
                $idclient = 0;
                
                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                //if(!Authentication::check_access_control(CUR_LAYER,'client','update')){
                    //throw new Exception("ERROR.RECUSED.RESOURCE");
                //}

                ////////////////////////////////////////////////////////////
                
                foreach ($attributes as $key => $value) {
                    if (isset($value->name)) {
                        
                        if ($value->name === 'idclient') {
                            $idclient = $value->value;
                            unset($attributes[$key]);
                            continue;
                        }
                        if($value->name === 'status'){
                            if($value->value == true){
                                $value->value = 1;
                            }else{
                                $value->value = 0;
                            }
                        }
                        if($value->name === 'email'){

                            if(Tools::check_email($value->value) === false){
                                unset($attributes[$key]);
                                continue;
                            }else{
                                $email = $value->value;
                            }
                        }if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                            continue;
                        }
                        if(empty($value->value)){
                            $value->value = null;
                        }

                        $array = array_merge($array, array($value->name => $value->value));
                    }
                }

                $find = false;
                
                $find = Client::find('all', array('conditions' => "idclient <> {$idclient} and email = '{$array['email']}'"));
                
                if ($find) {
                    throw new Exception("MODULE.CLIENT.ERROR.EXISTS.EMAIL");
                }
                
                if (Authentication::check_token($token) === false) {
                    throw new Exception("ERROR.INVALID.TOKEN");
                }

                if(isset($array['created'])){
                    unset($array['created']);
                }
                
                $array['updated'] = date('Y-m-d H:i:s');
                
                if (!empty($array)) {
                    $update_attributes = $array;
                } else {
                    $update_attributes = $attributes;
                }

                $find = Client::find($id);
                
                $find->update_attributes($update_attributes);
                $APP->CON->commit();
                $APP->activityLog("Update Client");
                //SitemapController::controller('get',strtolower(str_replace('Controller','',__CLASS__)));
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

                if(!Authentication::check_access_control(CUR_LAYER,'client','config')){
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
                    throw new Exception("There is already url is registered for the current layer!");
                }
    
                $layermodule = Layermodule::find('all', array('conditions' => "idlayer = {$layer->idlayer} and idmodule = {$module->idmodule}"));
                
                if($layermodule){
                    $layermodule = false;
                    $layermodule = $APP->CON->query("UPDATE ".DB_PREFIX."layermodule SET url = '{$array['url']}',filtermodel = '{$array['filtermodel']}',custom = '{$array['custom']}' WHERE idlayer = {$layer->idlayer} AND idmodule = {$module->idmodule}");
                    
                    if($layermodule){
                        $APP->CON->commit();
                        $APP->activityLog("Update Config Client");
                        return $APP->response(true, 'success', 'MODULE.CLIENT.SUCCESS.CONFIG', $json);
                    }
                }
                
                return $APP->response(true, 'success', 'MODULE.CLIENT.SUCCESS.CONFIG', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }
    
    private static function get_config($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {
            $APP->setDependencyModule(array('Layer', 'Authentication', 'Layermodule', 'Client', 'Module'));

            ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

            if (!Authentication::check_access_control(CUR_LAYER, 'client', 'config')) {
                throw new Exception("ERROR.RECUSED.RESOURCE");
            }

            ////////////////////////////////////////////////////////////

            $layer = false;
            $layermodule = false;
            $module = false;
            $return = array();
            $layer = Layer::find_by_name(CUR_LAYER);
            $module = Module::find_by_name('client');
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
    
    /*private static function sitemap($attributes = array(), $id = 0, $json = false) {
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
    }*/

    private static function change_password($attributes = array(),$id = 0,$json = false){
        
            if(isset($_SESSION[CLIENT.LAYER])){

                if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                    try
                    {
                        global $APP;
                        $APP->setDependencyModule(array('Client','Authentication','Userauth'));
                        $client = false;
                        $password = "";
                        $userauth = false;
                        $token = "";

                        ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                        //if(!Authentication::check_access_control(CUR_LAYER,'client','update')){
                            //throw new Exception("ERROR.RECUSED.RESOURCE");
                        //}

                        ////////////////////////////////////////////////////////////

                        foreach($attributes as $key => $value){
                            if(isset($value->name)){
                                
                                if($value->name === 'password'){
                                   $password = Tools::myMD5($value->value);
                                }
                                if($value->name === 'token'){
                                   $token = $value->value;
                                }
                            }
                        }

                        if(Authentication::check_token($token) === false){
                            throw new Exception("ERROR.INVALID.TOKEN");
                        }

                        $APP->CON->transaction();
                        $client = Client::find($id);
                        
                        if($client){
                            $userauth = Userauth::find_by_user($client->email);
                            
                            if($userauth){
                                $userauth->update_attributes(array('password' => $password));
                                $APP->CON->commit();
                                $APP->activityLog("Update password Client {$client->name}");
                                
                                return $APP->response(true,'success','MODULE.CLIENT.SUCCESS.PASSWORD.UPDATE',$json);
                            }else{
                                $APP->CON->rollback();
                                return $APP->response(false,'danger','MODULE.CLIENT.ERROR.USER.EMPTY',$json);
                            }
                        }else{
                            $APP->CON->rollback();
                            return $APP->response(false,'danger','MODULE.CLIENT.EMPTY',$json);
                        }
                        
                        return $APP->response(true,'success','MODULE.USER.SUCCESS.PASSWORD.UPDATE',$json);
                    } catch (\Exception $e)
                    {
                        $APP->CON->rollback();
                        $APP->writeLog($e);
                        return $APP->response(false,'danger',$e->getMessage(),$json);
                    }
                }
                return $APP->response(false,'danger','ERROR.EMPTY.VALUE',$json);
            }
            return $APP->response(false,'danger','ERROR.NOTFOUND.SESSION',$json);
        }
}

?>
