<?php

class Router {

    private $STATUSURL = 404;
    private $MINIFY = false;
    private static $_instance;

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        global $APP,$PAGE;

        if (is_int(array_search('rest', explode('/', URI)))) {
            $APP->REST = true;
        }

        if (is_int(array_search('min', explode('/', URI))) && isset($_GET['f'])) {
            $this->MINIFY = true;
        }

        $this->identify();


        if (isset($_SESSION[CLIENT.LAYER])){
            $APP->ACCESSEDBY = ($APP->BROWSER->detect->isMobile() ? ( $APP->BROWSER->detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
        }

        if ($APP->REST === true) {

            new Rest();
        }elseif ($this->MINIFY === true) {

            $PAGE->minify();
        }else {
            $this->routeCore();
        }
    }

    private function routeCore() {

        global $APP;

        //if (MODULE === 'rest') {
           // new Rest();
        //} else {
            $APP->setDependencyModule(array('Authentication'));


            //if (LAYER === 'admin' ) {
                //$APP->admin();
            //} else {
            if(LAYER != 'admin' && $APP->REQUIRE_AUTHENTICATION === false){
                $APP->setDependencyModule(array('Menu'));
                MenuController::controller('loadMenu', array('cur_layer' => CUR_LAYER));
            }

            if (isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin') {

                if (defined('LANGUAGE_USER')){
                    $APP->LANGUAGE = LANGUAGE_USER;
                }else{
                    AuthenticationController::logout();
                }
            }else{
                $APP->LANGUAGE = $APP->LANGUAGE_LAYER;
            }

            Authentication::OAuth();

            $APP->redirect();

            $APP->setVarGriffo();

            if($APP->FILTER === '/'.$APP->LAYER->urllogin){

                if (file_exists(DIR_CURTEMPLATE . $APP->LAYER->urllogin.'.php')) {
                    require_once(DIR_CURTEMPLATE . $APP->LAYER->urllogin.'.php');
                }else{

                    require_once('client/404.php');
                }
            }else{
                require_once(DIR_CURTEMPLATE . 'index.php');
            }
        //}
    }

    private function identify() {

        global $APP;

        $APP->setDependencyModule(array('Language', 'Layer', 'Module', 'Authentication', 'Customer'));

        $tmp = "";
        $url = "";
        $cur_alias = "";
        $layer = "";
        $currentAlias = "";
        $template = "";
        $pathLayer = "";
        $module = "";
        $filterModel = "";
        $language_layer = "";
        $language_url = "";
        $language = "";
        $status = false;
        $statusLayer = false;
        $layers = "";

        //Separa url atual em array pelas barras (/) retirando a query string
        if ($APP->REST === true) {
            if(strpos(THIS,'/rest/website') !== false){
                $url = str_replace('/rest/website', '', THIS);
                $requestURI = explode('/', str_replace('?' . QUERY_STRING, '', str_replace(PROTOCOL, '', $url)));
            }else{
                $url = str_replace('/rest', '', THIS);
                $requestURI = explode('/', str_replace('?' . QUERY_STRING, '', str_replace(PROTOCOL, '', $url)));
            }
        } else {
            $url = THIS;
            $requestURI = explode('/', str_replace('?' . QUERY_STRING, '', str_replace(PROTOCOL, '', THIS)));
        }

        //arrays dos alias
        $alias = unserialize(ALIAS);

        $return = $this->checkAlias($alias, $requestURI, $url);

        $alias = $return['alias'];
        $url = $return['url'];
        $urlFilter = $return['url'];
        $currentAlias = $return['currentAlias'];
        $statusLayer = $return['statusLayer'];
        $layer = $return['layer'];
        $cur_alias = $return['curAlias'];

        define('LAYER', $layer);
        define('CUR_ALIAS', $cur_alias);
        define('CURRENT_ALIAS', $currentAlias);

        //Se não for encontrado a LAYER, é incluída a página de erro 404
        if (!$statusLayer) {
            //incluí arquivo de erro 404 padrão (sem o template)
            require_once ('404.php');
            exit(0);
        }

        //verificar o(s) filtro(s) da layer

        $return = LayerController::controller('checkFilter', array('layer' => $layer, 'url' => $url));

        if (!empty($return['response'])) {
            $APP->ARRAY_FILTER = array_merge($APP->ARRAY_FILTER, $return['response']);
        }

        //verifica o idioma na URL

        $return = LanguageController::controller('checkLanguage', array('url' => $url, 'language_url' => $language_url));

        //$url = $return['url'];
        $language_url = $return['languageUrl'];

        $filter = $return['url'];

        //Existindo a LAYER, é consultado o banco de dados para encontrar o MÓDULO atual

        $return = ModuleController::controller('checkModule', array('layer' => $layer, 'url' => $url));

        if ($this->MINIFY === false){
            if (isset($return['response']['error']) || empty($return['response'])) {
                require_once ('client/404.php');
                exit(0);
            }
        }

        if(array_key_exists('defaultPage', $return['response']) === true){
            $urlFilter .= '/'.$return['response']['defaultPage'];
        }

        $this->STATUSURL = 200;

        $url = $return['response']['url'];
        $template = $return['response']['template'];
        $language_layer = $return['response']['languageLayer'];
        $module = $return['response']['module'];
        $pathModule = $return['response']['pathModule'];
        $filterModel = $return['response']['filterModel'];

        define('TEMPLATE', $template);

        if(!file_exists(DIR_LAYER . LAYER . DS . 'templates' . DS . TEMPLATE)){
            $APP->writeLog("A pasta '{$template}' do template atual não existe!");
            require_once ('client/404.php');
            exit(0);
        }

        define('DIR_CURTEMPLATE', DIR_LAYER . LAYER . DS . 'templates' . DS . TEMPLATE . DS);
        define('PATH_TEMPLATE', str_replace('\\', '/', str_replace('\\', '\\', URL_LAYER_PATH . LAYER . DS . 'templates' . DS . TEMPLATE . DS)));
        define('PATH_LIBRARIES',URL_LIBRARIES_PATH);

        if($APP->REST === false){
            $return = ModuleController::controller('checkFilter', array('layer' => $layer, 'url' => $urlFilter, 'idmodule' => $APP->MODULE->idmodule));

        }

        if (is_array($return['response'])) {
            $APP->ARRAY_FILTER = array_merge($APP->ARRAY_FILTER, $return['response']);
        }

        if(LAYER != 'admin' && $filter != '/'.$APP->LAYER->urllogin){
            if($APP->REST === false && $this->MINIFY === false){
                if(empty($APP->VIEW)){
                    require_once ('client/404.php');
                    exit(0);
                }
            }
        }

        if($APP->MODULE->name === 'page' && isset($return['page']['url'])){
            $APP->setDependencyModule('page');
            $find = false;
            $find = Page::find_by_url($return['page']['url']);
            if($find){
                $APP->REQUIRE_AUTHENTICATION = true;
            }

        }

        //Define BASE_COMPLEMENT
        $dir = str_replace('/', DS, (substr(INDEX_DIR, 0, 6) == "/home/") ? substr(INDEX_DIR, 6) : INDEX_DIR);

        function checkURL($uri, $dir, $count){
            $newDir = explode($uri, $dir);
            if(count($newDir) > 1){
                $return = $uri;
            }else{
                $newUrl = explode('/', $uri);
                array_pop($newUrl);
                $newUrl = implode($newUrl, '/');
                if($count < 50){
                    if(!empty($newUrl)){
                        $return = checkURL($newUrl, $dir, $count++);
                    }else{
                        $return = '/';
                    }
                }else{
                    $return = URI;
                }
            }
            return $return;
        }

        if(URI != '/'){
            $tmpBase = checkURL(URI, $dir, 0);
            $baseComplement = ($tmpBase != '/') ? $tmpBase . '/' : '';
        }else {
            $baseComplement = '';
        }

        //Busca usuário com o token da layer
        if (isset($_SESSION[CLIENT.LAYER])){

            if(Authentication::Auth() === true){

                $APP->setDependencyModule(array('Usersession'));

                $usersession = false;
                $token = Tools::$salt_Pre.$_SESSION[CLIENT.LAYER].Tools::$salt_End;
                //$sql = "SELECT us.idusersession,u.iduser,u.name FROM ".DB_PREFIX."usersession us INNER JOIN ".DB_PREFIX."userauth au ON us.fkiduserauth = au.iduserauth INNER JOIN ".DB_PREFIX."user u ON au.iduserauth = u.fkiduserauth WHERE us.accesstoken = '{$token}' AND u.status = 1";

                $usersession = Usersession::find_by_accesstoken($token);

                if($usersession){

                    define("IDUSERSESSION",$usersession->idusersession);

                    if(LAYER === 'admin'){

                        $APP->setDependencyModule(array('Profilelayer','Theme'));

                        $find = false;
                        $sql = "SELECT p.idprofile,p.name as profile,p.label,us.idusersession,u.iduser,u.name,u.nickname,u.picture,u.email,u.menuorder,u.fkidlanguage,u.fkidtheme,u.fkidlayer FROM ".DB_PREFIX."usersession us INNER JOIN ".DB_PREFIX."userauth au ON us.fkiduserauth = au.iduserauth INNER JOIN ".DB_PREFIX."user u ON au.iduserauth = u.fkiduserauth INNER JOIN ".DB_PREFIX."profile p ON u.fkidprofile = p.idprofile WHERE us.accesstoken = '{$token}' AND u.status = 1";

                        $find = Usersession::find_by_sql($sql);

                        if($find){
                            foreach($find as $value){

                                define("IDPROFILE",$value->idprofile);
                                define("IDUSER",$value->iduser);

                                $array = array();
                                $array = Profilelayer::find('all', array('conditions' => "idprofile = {$value->idprofile}"));
                                $arrayLayers = array();

                                foreach ($array as $value2) {
                                    $arrayLayers[] = Layer::find($value2->idlayer);
                                }
                                //Cria um array de todas as layers cadastradas
                                $layers = array();
                                foreach ($arrayLayers as $value3) {
                                    //if ($value->name != 'admin') {
                                        $layers[] = array('name' => $value3->name, 'label' => $value3->label, 'url' => $cur_alias . '/' . $value3->name);
                                    //}
                                }

                                //Busca layer do usuario

                                $findlanguage = false;
                                $findlanguage = Language::find($value->fkidlanguage);

                                if($findlanguage){
                                    define("LANGUAGE_USER",$findlanguage->name);
                                }

                                //Busca tema do usuário

                                $findtheme = false;
                                $findtheme = Theme::find($value->fkidtheme);

                                if($findlanguage){
                                    $APP->THEME = $findtheme->name;
                                }

                                //Busca recursos de acesso do perfil atual

                                $accessControl = AuthenticationController::access_control(array('idprofile' => $value->idprofile));
                                //$APP->showArray($accessControl);exit;
                                $APP->RESOURCES = $accessControl['response'];

                                $APP->PROFILE = array('idprofile' => $value->idprofile, 'name' => $value->profile,'label' => $value->label,'accessControl' => $accessControl['response']);

                                $APP->USER = array('id' => $value->iduser, 'name' => $value->name,'nickname' => $value->nickname,'profile' => $APP->PROFILE,'picture' => $value->picture,'email' => $value->email,'theme' => $APP->THEME,'menuorder' => $value->menuorder,'token' => $token,'fkidlayer' => $value->fkidlayer);
                            }
                        }
                    }else{
                        $APP->setDependencyModule(array('Userauth','Client'));
                        $usersession = false;
                        $usersession = Usersession::find(IDUSERSESSION);

                        if($usersession){
                            $userauth = false;
                            $userauth = Userauth::find($usersession->fkiduserauth);

                            if($userauth){

                                $client = false;
                                $client = Client::find_by_email($userauth->user);

                                if($client){

                                    $APP->USER = array('id' => $client->idclient, 'name' => $client->name,'email' => $client->email,'address' => $client->address,'number' => $client->number,'district' => $client->district,'city' => $client->city,'state' => $client->state,'phone' => $client->phone,'mobilephone' => $client->mobilephone,'preferredshop' => $client->preferredshop,'fkidshop' => $client->fkidshop,'created' => $client->created,'token' => $_SESSION[CLIENT.LAYER]);
                                }else{
                                    AuthenticationController::logout();
                                }
                            }else{
                                AuthenticationController::logout();
                            }
                        }else{
                            AuthenticationController::logout();
                        }
                    }
                }
            }else{
                AuthenticationController::logout();
            }
        }else{
            $APP->USER = array('token' => Tools::tokenGenerator());
        }

        //Validação da linguagem
        $return = LanguageController::controller('validateLanguageUrl', array('alias' => $alias, 'language_url' => $language_url, 'language_layer' => $language_layer));

        $language = $return['language'];

        if ($return['status_url'] === 404) {
            $this->STATUSURL = 404;
        }

        //Se não for encontrado a uri da LAYER padrão no banco de dados, é incluída a página de erro 404

        if ($this->STATUSURL === 404) {
            require_once ('client/404.php');
            exit(0);
        }

        //Busca informações do cliente
        $customer = false;
        $customer = Customer::all();

        if ($customer) {
            foreach ($customer as $value) {
                $APP->CUSTOMER = array('logo' => $value->logo, 'name' => $value->name, 'phone' => unserialize($value->phone), 'email' => unserialize($value->email), 'address' => $value->address,'map' => $value->map,'social' => unserialize($value->social));
            }
        }

        //Define as constantes da URL base
        if(strlen($baseComplement) > 0){
            define('BASE_COMPLEMENT', ($baseComplement[0] === '/') ? $baseComplement : '/'.$baseComplement );
        }else{
            define('BASE_COMPLEMENT', '/');
        }

        $url = "";
        if (substr($_SERVER['HTTP_HOST'], -1) != '/') {
            $url = $_SERVER['HTTP_HOST'] . '/';
        } else {
            $url = $_SERVER['HTTP_HOST'];
        }

        if (substr(BASE_COMPLEMENT, 0, 1) === '/') {
            $url .= substr(BASE_COMPLEMENT, 1, -1);
        } else {
            $url .= BASE_COMPLEMENT;
        }

        if (substr($url, -1) != '/') {
            $url .= '/';
        }

        define('BASE_URL', PROTOCOL . $url);
        define('BASE_LAYER', BASE_URL . URL_LAYER_PATH);
        if(!defined('CUR_LAYER')){
            define('CUR_LAYER','');
        }
        define('BASE_DIR', BASE_LAYER . CUR_LAYER);
        define('BASE_APP', BASE_URL . URL_APPLICATION_PATH);
        define('BASE_MODULE', BASE_URL . URL_MODULE_PATH);
        define('BASE_UPLOAD', BASE_URL . URL_UPLOADS_PATH);
        define('BASE_CORE', BASE_APP . 'core/');
        define('BASE_LIBRARIES', BASE_URL . URL_LIBRARIES_PATH);

        //Define algumas variáveis da aplicação

        $APP->LAYERS = $layers;
        $APP->LANGUAGE = $language;
        $APP->LANGUAGE_LAYER = $language_layer;
        $APP->LANGUAGE_URL = $language_url;
        $APP->FILTER_MODEL = $filterModel;
        $APP->FILTER = $filter;

        define('PATH_LAYER', $pathLayer);
        define('MODULE', $module);
        define('PATH_MODULE', $pathModule);

        define('BASE_CURTEMPLATE', str_replace('\\', '/', str_replace('\\', '\\', BASE_LAYER . LAYER . DS . 'templates' . DS . TEMPLATE . DS)));

        define('HASH_ID', Tools::tokenGenerator());
        define('MINIFY_MIN_DIR', str_replace('\\', '/', str_replace('\\', '\\', DIR_LIBRARIES . 'mrclay' . DS . 'minify' . DS . 'min')));

    }

    private function checkAlias($alias = "", $requestURI = "", $curUrl) {

        if (!empty($alias) && !empty($requestURI)) {
            $return = array();
            $status = false;
            $cont = 0;
            if (count($alias) > 0) {
                //Verifica se a URL atual existe no array dos ALIAS e defini-se a LAYER da url
                for ($i = 0; $i < count($requestURI); $i++) {
                    $tmp = "";
                    for ($j = 0; $j < count($requestURI) - $cont; $j++) {
                        $tmp .= '/' . $requestURI[$j];
                    }

                    if ((substr($tmp, -1)) == '/')
                        $tmp = substr($tmp, 0, -1);

                    foreach ($alias as $key => $value) {
                        $tmpAlias = '/' . str_replace(PROTOCOL, '', $key);
                                            
                        if (strcmp($tmp, $tmpAlias) == 0) {
                            $cur_alias = $key;
                            $url = explode($tmp, str_replace('?' . QUERY_STRING, '', $curUrl));
                            $url = $url[1];
                            $alias = $tmpAlias;
                            $layer = $value;
                            $currentAlias = substr($alias, 1);
                            $status = true;
                            $statusLayer = true;
                            if ((substr($url, -1)) == '/')
                                $url = substr($url, 0, -1);

                            $return = array('alias' => $alias, 'url' => $url, 'currentAlias' => $currentAlias, 'statusLayer' => $statusLayer, 'layer' => $layer, 'curAlias' => $cur_alias);

                            break;
                        }
                    }
                    if ($status)
                        break;
                    $cont++;
                }
            }else {
                throw new RuntimeException("Não foram definidos os alias da aplicação.");
            }
        }
        return $return;
    }

}

?>
