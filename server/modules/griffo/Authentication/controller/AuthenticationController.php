<?php

class AuthenticationController {

    public static function controller($function = "", $attributes = array(), $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $json);
        } else {
            return false;
        }
    }

    private static function send_email($array) {

        global $APP;
        try {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->isHTML(true);
            $mail->Host = "mail.acoll.com.br";
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            //$mail->SMTPSecure = "ssl";
            //$mail->SMTPSecure = "tls";
            $mail->Host = "mail.acoll.com.br";
            $mail->Port = 587;
            $mail->Username = "webmaster@acoll.com.br";
            $mail->Password = "_z9mx@-S]6]c";
            $mail->CharSet = 'utf-8';

            if (!empty($array['subject']) && !empty($array['address'])) {

                $body = '<html>';

                /* $body .= $mail->HeaderLine("Organization" , CLIENT);
                  $body .= $mail->HeaderLine("Content-Transfer-encoding" , "8bit");
                  $body .= $mail->HeaderLine("Message-ID" , "<".md5(uniqid(time()))."@{$_SERVER['SERVER_NAME']}>");
                  $body .= $mail->HeaderLine("X-MSmail-Priority" , "Normal");
                  $body .= $mail->HeaderLine("X-Mailer" , "Microsoft Office Outlook, Build 11.0.5510");
                  $body .= $mail->HeaderLine("X-MimeOLE" , "Produced By Microsoft MimeOLE V6.00.2800.1441");
                  $body .= $mail->HeaderLine("X-Sender" , $mail->Sender);
                  $body .= $mail->HeaderLine("X-AntiAbuse" , "This is a solicited email for - ".CLIENT." mailing list.");
                  $body .= $mail->HeaderLine("X-AntiAbuse" , "Servername - {$_SERVER['SERVER_NAME']}");
                  $body .= $mail->HeaderLine("X-AntiAbuse" , $mail->Sender);
                 *
                 */
                $body .= "<body><div>" . $array['body'];

                if (!empty($array['cc'])) {
                    if (is_array($array['cc'])) {
                        foreach ($array['cc'] as $value) {
                            if (!empty($value)) {
                                $mail->AddReplyTo($value);
                            }
                        }
                    } else {
                        $mail->AddReplyTo($array['cc']);
                    }
                }

                if (!empty($array['cco'])) {
                    if (is_array($array['cco'])) {
                        foreach ($array['cco'] as $value) {
                            if (!empty($value)) {
                                $mail->AddBCC($value);
                            }
                        }
                    } else {
                        $mail->AddBCC($array['cco']);
                    }
                }

                if (!empty($array['address'])) {
                    if (is_array($array['address'])) {
                        foreach ($array['address'] as $value) {
                            $mail->AddAddress($value);
                        }
                    } else {
                        $mail->AddAddress($array['address']);
                    }
                }

                if (!empty($array['attachment'])) {
                    if (is_array($array['attachment'])) {
                        foreach ($array['attachment'] as $value) {
                            if (!empty($value)) {
                                $mail->AddAttachment($value);
                            }
                        }
                    } else {
                        $mail->AddAttachment($array['attachment']);
                    }
                }

                if (empty($array['from'])) {
                    $mail->SetFrom('webmaster@acoll.com.br', 'Webmaster');
                } else {
                    $mail->SetFrom($array['from']->email, $array['from']->name);
                }

                $mail->Subject = $array['subject'];
                $mail->AltBody = "To view the message , please use a viewer e- mail compatible with HTML!";
                $body .= "</div></body></html>";
                $mail->msgHTML($body);
                $mail->Send();

                $mail->ClearAddresses();
                $mail->ClearCCs();
                $mail->ClearBCCs();
                $mail->ClearReplyTos();
                $mail->ClearAllRecipients();
                $mail->ClearAttachments();
                $mail->ClearCustomHeaders();
                $mail->smtpClose();

                return true;
            } else {
                throw new Exception("MODULE.AUTHENTICATION.ERROR.childJECTANDADDRESS.INVALID");
            }
        } catch (\Exception $e) {
            $APP->writeLog($e);
            return false;
        } catch (\phpmailerException $e) {
            $APP->writeLog($e);
            return false;
        }
    }

    public static function submit_email($attributes = array(), $json = false) {
        global $APP;

        if (!empty($attributes)) {
            try {
                $token = "";
                $APP->setDependencyModule(array('Authentication'));
                $array = array();

                foreach ($attributes as $key => $value) {

                    if (isset($value->name)) {

                        if ($value->name === 'token') {
                            $token = $value->value;
                            unset($attributes[$key]);
                        }
                        if ($value->name === 'body') {
                            $array['body'] = $value->value;
                        }
                        if ($value->name === 'cc') {
                            $array['cc'] = $value->value;
                        }
                        if ($value->name === 'subject') {
                            $array['subject'] = $value->value;
                        }
                        if ($value->name === 'address') {
                            $array['address'] = $value->value;
                        }
                        if ($value->name === 'cco') {
                            $array['cco'] = $value->value;
                        }
                        if ($value->name === 'attachment') {
                            $array['attachment'] = $value->value;
                        }
                        if ($value->name === 'from') {
                            $array['from'] = $value->value;
                        }
                    } else {
                        if ($key === 'token') {
                            $token = $value;
                            unset($attributes[$key]);
                        }
                        if ($key === 'body') {
                            $array['body'] = $value;
                        }
                        if ($key === 'cc') {
                            $array['cc'] = $value;
                        }
                        if ($key === 'subject') {
                            $array['subject'] = $value;
                        }
                        if ($key === 'address') {
                            $array['address'] = $value;
                        }
                        if ($key === 'cco') {
                            $array['cco'] = $value;
                        }
                        if ($key === 'attachment') {
                            $array['attachment'] = $value;
                        }
                        if ($key === 'from') {
                            $array['from'] = $value;
                        }
                    }
                }

                //if (!empty($token)) {
                //    if (Authentication::check_token($token) === false) {
                //        throw new Exception("ERROR.INVALID.TOKEN");
                //    }
                //}

                self::send_email($array);

                return $APP->response(true, 'success', 'MODULE.AUTHENTICATION.SUCCESS.SEND.MESSAGE', $json);
            } catch (\Exception $e) {
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        } else {
            return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
        }
    }

    private static function login($attributes = array(), $json = false) {

        global $APP;

        if (!empty($attributes)) {

            if(isset($attributes['curlayer'])){
                $curlayer = $attributes['curlayer'];
                unset($attributes['curlayer']);
            }

            foreach ($attributes as $value) {

                if ($value->name == 'user') {
                    $username = $value->value;
                }
                if ($value->name == 'password') {
                    $password = Tools::myMD5($value->value);
                }
            }

            $APP->setDependencyModule(array('Userauth','Usersession','Layer','User','Client'));

            $layer = false;

            $layer = Layer::find_by_name(LAYER);

            $find = false;
            $sql = "SELECT ua.iduserauth,ua.user,ua.password FROM ".DB_PREFIX."userauth ua INNER JOIN ".DB_PREFIX."userauthapplication uaa ON ua.iduserauth = uaa.iduserauth INNER JOIN ".DB_PREFIX."applicationauth app ON uaa.idapplicationauth = app.idapplicationauth WHERE app.name = '{$layer->name}' AND ua.user = '{$username}' AND ua.password = '{$password}' AND uaa.status = 1 ";

            $find = Userauth::find_by_sql($sql);

            if ($find) {
                try {
                    $APP->CON->transaction();
                    session_regenerate_id();
                    $idsession = session_id();
                    $accesstoken = Tools::tokenGenerator();
                    $datetimein = date('Y-m-d H:i');
                    $expiry = date('Y-m-d H:i', strtotime('+1 hour', strtotime($datetimein)));

                    foreach ($find as $value) {

                        $iduserauth = $value->iduserauth;

                        $attributes = array('idusersession' => null, 'ip' => IP, 'idsession' => $idsession, 'accesstoken' => Tools::$salt_Pre . $accesstoken . Tools::$salt_End, 'expiry' => $expiry,'datetimein' => $datetimein, 'datetimeout' => null, 'fkiduserauth' => (int) $iduserauth);

                        Usersession::create($attributes);

                        if(LAYER === 'admin'){
                            $user = false;
                            $user = User::find_by_fkiduserauth($iduserauth);
                            if(!$user){
                                return $APP->response(false, 'danger', 'MODULE.USER.ERROR.LOGIN.INCORRECT', $json);
                            }
                        }else{
                            $user = false;
                            $user = Client::find_by_email($value->user);
                            if(!$user){
                                return $APP->response(false, 'danger', 'MODULE.USER.ERROR.LOGIN.INCORRECT', $json);
                            }
                        }

                        break;
                    }
                    $APP->CON->commit();

                    LayerController::controller('generate_session',array('iduserauth' => $iduserauth,'accesstoken' => $accesstoken,'application' => $layer->name));

                    $APP->activityLog("Login user: {$username} password: {$password} application: ".LAYER);

                    return $APP->response(array('user' => $user->to_array()), 'success', 'MODULE.USER.SUCCESS.LOGIN.REDIRECT', $json);

                } catch (\Exception $e) {
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false, 'danger', $e->getMessage(), $json);
                }
            } else {
                return $APP->response(false, 'danger', 'MODULE.USER.ERROR.LOGIN.INCORRECT', $json);
            }
        }
        return $APP->response(false, 'danger', 'ERROR.EMPTY.VALUE', $json);
    }

    public static function logout($attributes = array(), $json = false) {

        global $APP;

        $APP->setDependencyModule(array('Usersession','Userauth'));
        if (isset($_SESSION[CLIENT.LAYER])) {
            try {
                $APP->CON->transaction();
                //$idusersession = IDUSERSESSION;
                $token = $_SESSION[CLIENT.LAYER];
                $usersession = false;
                $usersession = Usersession::find_by_accesstoken(Tools::$salt_Pre . $token . Tools::$salt_End);
                if($usersession){
                    $userauth = Userauth::find($usersession->fkiduserauth);
                    $usersession->update_attributes(array('datetimeout' => date('Y-m-d H:i')));
                    $APP->CON->commit();
                    $APP->activityLog('Logout layer: '.LAYER.' userauth: '.$userauth->user);
                    //session_destroy();
                }
                unset($_SESSION[CLIENT.LAYER]);
                return $APP->response(true, 'success', 'MODULE.USER.SUCCESS.LOGOUT.REDIRECT', $json);
            } catch (\Exception $e) {
                $APP->CON->rollback();
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
        }

        return $APP->response(false, 'danger', 'ERROR.NOTFOUND.SESSION', $json);
    }

    public static function access_control($attributes = array(), $json = false) {
        global $APP;
        try{
            $return = array();

            if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin'){

                $APP->setDependencyModule(array('Module','Moduleprofile','Resource'));

                $idprofile = (int) $attributes['idprofile'];

                ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                //if(!Authentication::check_access_control(CUR_LAYER,'profile','access-control',$idprofile)){
                //    throw new Exception("ERROR.RECUSED.RESOURCE");
                //}

                ////////////////////////////////////////////////////////////

                //Busca os recursos do perfil atual

                $modulesprofile = Moduleprofile::find_by_sql("SELECT DISTINCT m.name,m.idmodule,mp.visible,mp.status FROM " . DB_PREFIX . "module m INNER JOIN " . DB_PREFIX . "moduleprofile mp on mp.idmodule = m.idmodule INNER JOIN ".DB_PREFIX."profile p on mp.idprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile WHERE p.idprofile = {$idprofile} AND pl.status = 1 AND pl.idlayer = {$APP->LAYER->idlayer}");

                $modulesActive = array();
                $sub = array();
                foreach ($modulesprofile as $value) {

                    $resources = false;

                    //Busca módulo e recursos ativos

                    $sql = "SELECT DISTINCT r.idresource,r.resource,r.idresourceparent,r.fkidmodule,m.name,pr.status FROM " . DB_PREFIX . "profile p INNER JOIN " . DB_PREFIX . "profileresource pr ON p.idprofile = pr.idprofile INNER JOIN " . DB_PREFIX . "resource r ON pr.idresource = r.idresource INNER JOIN " . DB_PREFIX . "module m ON r.fkidmodule = m.idmodule WHERE r.fkidmodule = {$value->idmodule} AND (r.idresourceparent = 0 OR r.idresourceparent IS NULL) AND p.idprofile = {$idprofile}";

                    $resources = Resource::find_by_sql($sql);

                    if($resources){
                        $array = array();
                        $arrayResource = array();
                        $nameModule = "";

                        foreach ($resources as $value2) {
                            $nameModule = $value2->name;
                            $sub = self::recursiveResource($value2->idresourceparent, $idprofile, false);

                            $arrayResource[$value2->resource] = array('idresource' => $value2->idresource, 'name' => $value2->resource, 'idresourceparent' => $value2->idresourceparent, 'sub' => $sub, 'status' => ($value2->status == 1) ? true : false);

                        }
                        $array['resources'] = $arrayResource;
                        $array['idmodule'] = $value->idmodule;
                        $array['name'] = $value->name;
                        $array['visible'] = $value->visible;
                        $array['status'] = ($value->status == 1) ? true : false;
                        $modulesActive[$value->name] = $array;
                    }
                }
                //$return = $modulesActive;

                //Busca todos os modulos e todos os recursos


                $arrayResource = array();

                $sql = "";
                $modulesAll = false;

                if($idprofile != 1){
                    //$modulesAll = Module::find('all',array('conditions' => "struct = 0"));
                    $modulesAll = Module::find_by_sql("SELECT m.*,mp.visible FROM ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."moduleprofile mp ON m.idmodule = mp.idmodule");
                    //$modulesAll = $m->find_by_sql('all');
                }else{
                    $modulesAll = Module::find_by_sql("SELECT m.*,mp.visible FROM ".DB_PREFIX."module m INNER JOIN ".DB_PREFIX."moduleprofile mp ON m.idmodule = mp.idmodule");
                    //$modulesAll = $m->find_by_sql('all');
                }

                if($modulesAll){
                    foreach ($modulesAll as $value) {
                        $resources = false;
                        $resources = Resource::find_by_sql("SELECT DISTINCT r.idresource,r.resource,r.idresourceparent,r.fkidmodule FROM " . DB_PREFIX . "profile p INNER JOIN " . DB_PREFIX . "profileresource pr ON p.idprofile = pr.idprofile INNER JOIN " . DB_PREFIX . "resource r ON pr.idresource = r.idresource WHERE r.fkidmodule = {$value->idmodule} AND (r.idresourceparent = 0 OR r.idresourceparent IS NULL)");

                        if($resources){
                            $array = array();
                            foreach ($resources as $value2) {
                                $sub = self::recursiveResource($value2->idresourceparent, $idprofile, true);

                                $arrayResource[$value2->resource] = array('idresource' => $value2->idresource, 'name' => $value2->resource, 'idresourceparent' => $value2->idresourceparent, 'sub' => $sub, 'status' => false);
                            }
                            $array['resources'] = $arrayResource;
                            $array['idmodule'] = $value->idmodule;
                            $array['name'] = $value->name;
                            $array['visible'] = $value->visible;
                            $array['status'] = false;
                            $modules[$value->name] = $array;
                        }
                    }

                    $return = array_merge($modules, $modulesActive);
                }

            }

            //ksort($return);
            return $APP->response($return, 'success', true, $json);

        }catch(\Exception $e){

            $APP->writeLog($e);
            return $APP->response(false, 'danger', $e->getMessage(), $json);
        }
    }

    private static function recursiveResource($idresourceparent, $idprofile, $all) {
        global $APP;
        $APP->setDependencyModule(array('Module','Resource', 'Moduleprofile'));

        $return = array();

        if (!empty($idresourceparent)) {
            if ($all === false) {
                $modulesprofile = Moduleprofile::find_by_sql("SELECT m.name,m.idmodule,mp.visible,mp.status FROM " . DB_PREFIX . "module m INNER JOIN " . DB_PREFIX . "moduleprofile mp on mp.idmodule = m.idmodule WHERE mp.idprofile = {$idprofile}");
                $modulesActive = array();
                $sub = array();

                foreach ($modulesprofile as $value) {
                    //Busca módulo e recursos ativos
                    $resources = Resource::find_by_sql("SELECT DISTINCT r.idresource,r.resource,r.idresourceparent,r.fkidmodule,m.name,pr.status FROM " . DB_PREFIX . "profile p INNER JOIN " . DB_PREFIX . "profileresource pr ON p.idprofile = pr.idprofile INNER JOIN " . DB_PREFIX . "resource r ON pr.idresource = r.idresource INNER JOIN " . DB_PREFIX . "module m ON r.fkidmodule = m.idmodule WHERE r.fkidmodule = {$value->idmodule} AND r.idresourceparent = {$idresourceparent} AND p.idprofile = {$idprofile}");
                    $array = array();
                    $arrayResource = array();

                    foreach ($resources as $value2) {
                        $sub = self::recursiveResource($value->idmodule, $value2->idresourceparent, $idprofile, false);

                        $arrayResource[$value2->resource] = array('idresource' => $value2->idresource, 'name' => $value2->resource, 'idresourceparent' => $value2->idresourceparent, 'sub' => $sub, 'status' => ($value2->status == 1) ? true : false);
                    }
                    $array['resources'] = $arrayResource;
                    $array['idmodule'] = $value->idmodule;
                    $array['name'] = $value->name;
                    $array['status'] = ($value->status == 1) ? true : false;
                    $modulesActive[$value->name] = $array;
                }

                $return = $modulesActive;
            } else {
                $modulesAll = Module::all();

                foreach ($modulesAll as $value) {

                    $resources = Resource::find_by_sql("SELECT DISTINCT r.idresource,r.resource,r.idresourceparent,r.fkidmodule FROM " . DB_PREFIX . "profile p INNER JOIN " . DB_PREFIX . "profileresource pr ON p.idprofile = pr.idprofile INNER JOIN " . DB_PREFIX . "resource r ON pr.idresource = r.idresource WHERE r.fkidmodule = {$value->idmodule} AND r.idresourceparent = {$idresourceparent}");
                    $array = array();
                    foreach ($resources as $value2) {
                        $sub = self::recursiveResource($value->idmodule, $value2->idresourceparent, $idprofile, true);

                        $arrayResource[$value2->resource] = array('idresource' => $value2->idresource, 'name' => $value2->resource, 'idresourceparent' => $value2->idresourceparent, 'sub' => $sub, 'status' => false);
                    }
                    $array['resources'] = $arrayResource;
                    $array['idmodule'] = $value->idmodule;
                    $array['name'] = $value->name;
                    $array['status'] = true;
                    $array['status'] = false;
                    $modules[$value->name] = $array;
                }

                $return = $modules;
            }
        }
        return $return;
    }

    private static function update_access_control($attributes = array(),$id = 0,$json = false){

            if(!empty($attributes) && (is_numeric($id) && $id > 0)){
                try
                {
                    global $APP;

                    $APP->CON->transaction();
                    $APP->setDependencyModule(array('Authentication','Moduleprofile','Profileresource','Menu','Profilemenu','User'));
                    $array = array();
                    $update_attributes = array();
                    $token = "";
                    $idprofile = 0;
                    //$id = (int) $id;
                    $find = false;

                    ///////////////VERIFICA PERMISSÃO AO RECURSO////////////////

                    if(!Authentication::check_access_control(CUR_LAYER,'profile','access-control')){
                        throw new Exception("ERROR.RECUSED.RESOURCE");
                    }

                    ////////////////////////////////////////////////////////////

                    foreach($attributes as $key => $value){
                        if(isset($value->name)){

                            if($value->name === 'token'){
                                $token = $value->value;
                                unset($attributes[$key]);
                            }else{
                                $array = array_merge($array,array($value->name => $value->value));
                            }
                        }
                    }

                    if(Authentication::check_token($token) === false){
                        throw new Exception("ERROR.INVALID.TOKEN");
                    }

                    $array = Tools::objectToArray($array);

                    $user = false;

                    //Seleciona todos os usuários do perfil a ser alterado.

                    $user = User::find_by_sql("SELECT DISTINCT u.iduser FROM ".DB_PREFIX."user u INNER JOIN ".DB_PREFIX."profile p on u.fkidprofile = p.idprofile INNER JOIN ".DB_PREFIX."profilelayer pl on p.idprofile = pl.idprofile WHERE p.idprofile = {$id} and pl.idlayer = {$APP->LAYER->idlayer} and u.status = 1 and pl.status = 1 ");

                    if($user){
                        foreach($array as $key => $value){

                            //Se houver recursos, é alterado o seu status

                            if(count($value['resources']) > 0){

                                $value['status'] = ($value['status'] == true) ? 1 : 0;

                                foreach($value['resources'] as $key2 => $value2){

                                    $value2['status'] = ($value2['status'] == true) ? 1 : 0;

                                    $find = Profileresource::find_by_idprofile_and_idresource($id,$value2['idresource']);

                                    if($find){

                                        $find->update_attributes(array('status' => $value2['status']));

                                    }else{
                                        Profileresource::create(array('idprofile' => $id,'idresource' => $value2['idresource'],'status' => $value2['status']));
                                    }
                                }
                            }/*else{
                                Moduleprofile::create(array('idmodule' => $value['idmodule'],'idprofile' => $id,'status' => $value['status']));

                                $find = false;
                                $find = Menu::find('all',array('conditions' => "fkidlayer = {$APP->LAYER->idlayer} and fkidmodule = {$value['idmodule']}"));
                                if($find){
                                    foreach($find as $value3){
                                        Profilemenu::create(array('idmenu' => $value3->idmenu,'idprofile' => $id,'status' => 1));
                                    }
                                }

                                if($user){
                                    foreach($user as $value3){

                                        if($value['status'] === 1){
                                            UserController::update_menu_user($value3->iduser,$value['idmodule'],'add');
                                        }else{
                                            UserController::update_menu_user($value3->iduser,$value['idmodule'],'remove');
                                        }
                                    }
                                }
                            }
                            */

                            $find = false;
                            $find = Moduleprofile::find_by_idmodule_and_idprofile($value['idmodule'],$id);

                            if($find){
                                $find->update_attributes(array('status' => $value['status']));
                            }else{
                                Moduleprofile::create(array('idmodule' => $value['idmodule'],'idprofile' => $id,'status' => $value['status']));

                                $find = false;
                                $find = Menu::find('all',array('conditions' => "fkidlayer = {$APP->LAYER->idlayer} and fkidmodule = {$value['idmodule']}"));
                                if($find){
                                    foreach($find as $value3){
                                        Profilemenu::create(array('idmenu' => $value3->idmenu,'idprofile' => $id,'status' => 1));
                                    }
                                }
                            }

                            if($user){

                                $findVisible = false;
                                $findVisible = Moduleprofile::find('all',array('conditions' => "idmodule = {$value['idmodule']} and idprofile = {$id}"));

                                if($findVisible){
                                    foreach($findVisible as $value4){
                                        $value4->update_attributes(array('visible' => $value['visible']));
                                    }
                                }

                                foreach($user as $value3){

                                    if($value['status'] === 1 && $value['visible'] == 1){
                                        UserController::update_menu_user($value3->iduser,$value['idmodule'],'add');
                                    }else{
                                        UserController::update_menu_user($value3->iduser,$value['idmodule'],'remove');
                                    }
                                }
                            }
                        }
                    }else{
                        throw new Exception("ERROR.NOTEXISTS.USERBOND");
                    }    

                    $APP->CON->commit();
                    $APP->activityLog("Update Access Control");

                    return $APP->response(true,'success','MODULE.AUTHENTICATION.SUCCESS.UPDATE.ACCESSCONTROL',$json);
                } catch (\Exception $e)
                {
                    $APP->CON->rollback();
                    $APP->writeLog($e);
                    return $APP->response(false,'danger',$e->getMessage(),$json);
                }
            }
            return $APP->response(false,'danger','ERROR.EMPTY.VALUE',$json);
        }

}

?>
