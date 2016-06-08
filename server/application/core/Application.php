<?php

class Application {

    public $DB;
    public $CON;
    public $ROUTER;
    public $BROWSER;
    public $ACTIVITY;
    public $LAYERS;
    public $MODULE;
    public $ACCESSEDBY;
    public $USER;
    public $PROFILE;
    public $THEME;
    public $PURIFIER;
    public $CUSTOMER;
    public $LANGUAGE_LAYER;
    public $LANGUAGE_URL;
    public $LANGUAGE;
    public $ARRAY_FILTER = array();
    public $FILTER_MODEL;
    public $VIEW;
    public $MENU = array();
    public $RESOURCES;
    public $GALLERY;
    public $TITLE;
    public $CACHE;
    public $FILTER;
    public $variaveis = array();
    public $actions = array();
    public $SITEMAP;
    public $PAGE;
    public $URL_REDIRECTION;
    public $REQUIRE_AUTHENTICATION = false;
    public $REST = false;
    
    private static $_instance;
    private static $_instanceModules = array();
    

    public function includeModule($module, $instance = false) {

        if(file_exists(DIR_MODULE_COMP . $module)){
            return $this->useModule('complementary/' . $module, $instance);
        }elseif(file_exists(DIR_MODULE . 'griffo' . DS . $module)){
           return $this->useModule('griffo/' . $module, $instance);
        }

        //return (file_exists(DIR_MODULE . $module)) ? $this->useModule($module, $instance) : (file_exists(DIR_MODULE . 'griffo' . DS . $module) ? $this->useModule('griffo/' . $module, $instance) : '');
    }

    //Carregar classes do modulo e retornar uma instancia do modulo

    public function useModule($module, $instance = false) {
        $moduleTemp = explode('/', $module);

        if (sizeof($moduleTemp) > 0) {
            $pathModule = $module;
            $module = $moduleTemp[sizeof($moduleTemp) - 1];
        } else {
            $pathModule = $module;
        }

        //$path = DIR_MODULE . $pathModule . DS . "bootstrap.php";
        $arrayDir = array(
            str_replace('\\', '/', str_replace('\\', '\\', DIR_MODULE . $pathModule . DS . 'controller')),
            str_replace('\\', '/', str_replace('\\', '\\', DIR_MODULE . $pathModule . DS . 'model'))
        );
        if (!class_exists($module)) {
            $this->DB->set_model_directory(DIR_MODULE . $pathModule . DS . "model");
            Tools::loading($arrayDir);
            //return new $module();
        }
        if ($instance) {
            return self::singletonModule($module);
        }
    }

    public function setDependencyModule($array = array()) {

        if (!empty($array)) {
            foreach ($array as $value) {
                $this->includeModule($value);
            }
        }
    }

    //Controlar e exibir informações de erros de car

    public function showArray($array) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    //Conectar com a base de dados e iniciar roteamento

    public function startApp() {
        $this->DB->set_connections(array(ENVIRONMENT =>
            DB_BASE . "://" . DB_USER . ":" . DB_PASS . "@" . DB_HOST . "/" . DB_DATABASE . "?charset=utf8"));
        $this->CON = ActiveRecord\Connection::instance($this->DB->get_connection($this->DB->get_default_connection()));
        $this->ROUTER = Router::getInstance();
    }

    public function status($start) {
        echo '<div align="right" style="padding:4px 10px;margin:0;position:absolute;bottom:0;right:0;background:#5f7d77;z-index:999;color:#FFF">' .
        round(memory_get_usage(true) / 1024, 2) . 'KB | ' .
        round(memory_get_peak_usage(true) / 1024, 2) . 'KB | ' .
        round(microtime() - $start, 3) . 's</div>';
    }

    //FUNÇÕES DE GANCHOS DE FILTROS E AÇÕES
    //ADICIONA EM UM ARRAY DE FILTROS UM FILTRO
    public function add_filter($param = '', $function = '', $class = '', $module = '') {
        try{
            if (!empty($param) && !empty($function)) {
                if (!empty($module)) {
                    $this->setDependencyModule($module);
                }
                if (!empty($class)) {
                    if (class_exists($class)) {
                        if (method_exists($class, $function)) {
                            return $class::$function($param);
                        } else {
                            throw new Exception("A função '{$function}' para execução do filtro não existe");
                        }
                    } else {
                        throw new Exception("A classe '{$class}' para execução do filtro não existe");
                    }
                } else {
                    if (function_exists($function)) {
                        return $function($param);
                    } else {
                        throw new Exception("A função '{$function}' para execução do filtro não existe");
                    }
                }
            }
            return false;
        }catch(\Exception $e){
            $this->writeLog($e->getMessage());
            $this->CON->rollback();
            return false;
        }
    }

    //ADICIONA NO ARRAY DE AÇÕES UMA AÇÃO
    public function add_action($nameAction = '', $function = '', $class = '', $module = '', $priority = 10) {
        try{
            if (!empty($nameAction) && !empty($function)) {
                //if (!array_key_exists($nameAction, $this->actions)) {
                    //$this->actions = array_merge($this->actions,array($nameAction => array('function' => $function, 'class' => $class, 'module' => $module,'priority' => $priority)));
                    $this->actions[] = array('function' => $function, 'class' => $class, 'module' => $module,'priority' => $priority);
                //} else {
                    //throw new RuntimeException("A ação '{$nameAction}' já foi adicionada");
                //}
            } else {
                throw new Exception("A/s variáveis '{$nameAction}' e '{$function}' está ou estão vazia/s");
            }
        }catch(\Exception $e){
            $this->writeLog($e->getMessage());
            $this->CON->rollback();
            return false;
        }
    }

    //EXECUTA A FUNÇÃO DA AÇÃO REPASSADA, PODENDO EXECUTAR TODAS AS FUNÇÕES REGISTRADAS SE PASSADO O PARÂMETRO ALL
    public function apply_action($action = '') {
        try{
            if (!empty($action)) {
                if ($action === 'all') {
                    $this->exec_all_actions();
                } else {
                    if (array_key_exists($action, $this->actions)) {
                        $class = $this->actions[$action]['class'];
                        $function = $this->actions[$action]['function'];
                        $module = $this->actions[$action]['module'];
                        
                        if (!empty($module)) {
                            $this->setDependencyModule($module);
                        }
                        if (!empty($class)) {
                            if (class_exists($class)) {
                                if (method_exists($class, $function)) {
                                    $class::$function();
                                } else {
                                    throw new Exception("A função '{$function}' não existe");
                                    return false;
                                }
                            } else {
                                throw new Exception("A classe '{$class}' não existe");
                                return false;
                            }
                        } else {
                            if (function_exists($function)) {
                                $function();
                            } else {
                                throw new Exception("A função '{$function}' não existe");
                                return false;
                            }
                        }
                    } else {
                        throw new Exception("A ação '{$action}' não foi adicionada");
                        return false;
                    }
                }
            } else {
                throw new Exception("Não existe nenhum parâmetro para a função 'apply_action");
                return false;
            }
        }catch(\Exception $e){
            $this->writeLog($e->getMessage());
            $this->CON->rollback();
            return false;
        }
    }

    public function exec_all_actions() {
        try{
            $this->actions = Tools::sortKeyArray($this->actions, 'priority');
            $this->showArray($this->actions);exit;
            foreach ($this->actions as $key => $value) {
                
                $class = $value['class'];
                $function = $value['function'];
                $module = $value['module'];

                if (!empty($module)) {
                    $this->setDependencyModule($module);
                }
                if (!empty($class)) {
                    if (class_exists($class)) {
                        if (method_exists($class, $function)) {
                            $class::$function();
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if (function_exists($function)) {
                        $function();
                    } else {
                        return false;
                    }
                }
            }
        }catch(\Exception $e){
            $this->writeLog($e->getMessage());
            $this->CON->rollback();
            return false;
        }
    }

    public function activityLog($activity = "") {

        $return = false;
        if (!empty($activity)) {
            try {
                $this->CON->transaction();
                if (isset($_SESSION[CLIENT.LAYER])) {
                    $this->setDependencyModule(array('Activitylog','Usersession'));
                    
                    $token = $_SESSION[CLIENT.LAYER];
                    $usersession = Usersession::find_by_accesstoken(Tools::$salt_Pre . $token . Tools::$salt_End);
                    $atributtes = array('idactivitylog' => null, 'date' => date('Y-m-d H:i:s'), 'activity' => $activity, 'url' => THIS, 'fkidusersession' => $usersession->idusersession);

                    Activitylog::create($atributtes);
                    $this->CON->commit();
                    return true;
                }
            } catch (\Exception $e) {
                $this->writeLog($e->getMessage());
                $this->CON->rollback();
                return false;
            }
        }
        return $return;
    }

    public function response($response, $status, $message, $json = true) {
        $return = ($json === true) ? json_encode(array('response' => $response, 'status' => $status, 'message' => $message)) : array('response' => $response, 'status' => $status, 'message' => $message);
        return $return;
    }

    public function writeLog($error) {
        if(is_object($error)){
            $log_email = unserialize(LOG_EMAIL);
            $system = unserialize(SYSTEM);
            $log_email['subject'] = "Erro griffo (" . CLIENT . ")";
            $log_email['body'] = "[" . date("d/m/Y - H:i:s") . "]<br /><br /><strong>Url: " . THIS . "</strong><br /><strong>Versão: " . $system['version'] . "</strong><br /><strong>Cliente: </strong>" . CLIENT . "<br /><strong>Endereço: </strong>" . CLIENT_WEBSITE . "<br /><strong>Mensagem do erro: </strong>" . $error->getMessage() . "<br /><br /><strong>Rota do erro:</strong><br /><pre>" . $error->getTraceAsString() . "</pre>";
            //Tools::submit_email($log_email);
            error_log($error->getMessage() . $error->getTraceAsString() . PHP_EOL);
        }else{
            error_log($error);
        }
    }

    public function redirect() {

        $url = $this->URL_REDIRECTION;
        
        if (!empty($url)) {
            if (Tools::check_url($url) === true) {
                header('location: ' . $url);
                exit(0);
            } else {
                require_once (DIR_CURTEMPLATE . '404.php');
                exit(0);
            }
        }else{
            global $APP;
            
            if(isset($_SESSION[CLIENT.LAYER]) && LAYER === 'admin' && empty($APP->FILTER) && !empty($APP->USER['fkidlayer'])){
                $APP->setDependencyModule(array('Layer'));
                
                $layer = Layer::find($APP->USER['fkidlayer']);
                
                header('location: ' . CUR_ALIAS . '/' . $layer->name);
                exit(0);
            }
        }
        
        /*
        $arrayMenus = array();
        $arrayFilter = array();

        $aux = explode(BASE_URL, THIS);

        if ((is_int(array_search($this->LANGUAGE_LAYER, explode('/', $aux[1])))) && $this->LANGUAGE_LAYER === $this->LANGUAGE) {

            header('location: ' . str_replace('/' . $this->LANGUAGE_LAYER, '', THIS));
        }

        $this->setDependencyModule(array('Authentication'));
        $auth = Authentication::Auth();

        if ($this->FILTER === '/login' && $auth === false) {

            $this->setVarGriffo();

            if (file_exists(DIR_CURTEMPLATE . 'login.php')) {
                require_once(DIR_CURTEMPLATE . 'login.php');
            }
            
            exit(0);
        }

        if ((LAYER === 'admin' && $this->FILTER === '/login' && $auth === false) || (LAYER === 'admin' && $auth === false)) {

            //$this->setVarGriffo();

            header('location: ' . CUR_ALIAS . ((!empty($this->LANGUAGE_LAYER)) ? '/' . $this->LANGUAGE_LAYER : '') . '/login');
            exit(0);
        }

        if (LAYER === 'website' && empty($this->FILTER) && $this->LANGUAGE_URL == $this->LANGUAGE_LAYER) {

            //$this->setVarGriffo();

            header('location: ' . CUR_ALIAS . ((!empty($this->LANGUAGE_LAYER)) ? '/' . $this->LANGUAGE_LAYER : '') . '/home/');
            exit(0);
        }*/
         
    }

    public function setVarGriffo() {

        global $PAGE,$APP;
        
        //$aux = (LAYER === 'admin') ? ((MODULE === 'dashboard') ? '/index.php' : ((!empty($this->VIEW)) ? '/' . $this->VIEW . '.php' : $this->VIEW)) : ((MODULE === 'page' && LAYER === 'website' && empty($this->VIEW)) ? '/home.php' : '/' . $this->VIEW . '.php');

        if(LAYER === 'admin'){

            if(MODULE === 'dashboard'){
                $aux = '/index.php';
                $this->VIEW = 'index';
            }else{
                if(!empty($this->VIEW)){
                    $aux = '/' . $this->VIEW . '.php';
                }else{

                    $aux = $this->VIEW;
                }
            }
        }else{
            $aux = $this->VIEW;
            /*if(MODULE === 'page' && LAYER === 'website' && empty($this->VIEW)){
                $aux = '/home.php';
            }else{
                $aux = '/' . $this->VIEW . '.php';
            }*/
        }

        $view = (LAYER === 'admin') ? BASE_MODULE . $this->MODULE->path . 'view/' . LAYER . $aux : BASE_URL . $aux;

        if(LAYER != 'admin' && empty($APP->MENU)){
            self::setDependencyModule(array('Menu'));
            MenuController::controller('loadMenu', array('array_filter' => $APP->ARRAY_FILTER, 'cur_layer' => CUR_LAYER));
        }

        $restPath = (LAYER === 'admin') ? 'rest/' . LAYER . '/' . CUR_LAYER . '/' : 'rest/' . LAYER . '/';

        $griffo = array('baseUrl' => BASE_URL, 'layer' => LAYER, 'module' => MODULE, 'modulePath' => URL_MODULE_PATH . PATH_MODULE, 'filter' => $this->ARRAY_FILTER, 'applicationPath' => URL_APPLICATION_PATH, 'templatePath' => PATH_TEMPLATE, 'restPath' => $restPath, 'uploadPath' => URL_UPLOADS_PATH, 'menu' => $this->MENU, 'user' => $this->USER, 'curAlias' => CUR_ALIAS, 'language' => $this->LANGUAGE, 'layers' => $this->LAYERS, 'customer' => $this->CUSTOMER, 'curView' => $view, 'librariesPath' => URL_LIBRARIES_PATH, 'system' => unserialize(SYSTEM));

        $PAGE->setGriffo($griffo);
    }

    /*public function admin() {
        
        $this->setDependencyModule(array('Authentication', 'Layer', 'Menu'));
        $cur_layer = CUR_LAYER;
        //Identifica Layer padrao do usuário

        if (isset($_SESSION[CLIENT.LAYER]['fkidlayer'])) {
            $idlayer = $_SESSION[CLIENT.LAYER]['fkidlayer'];
            if (is_numeric($idlayer)) {
                $layer = Layer::find($idlayer);

                if($cur_layer != $layer->name){

                    $this->URL_REDIRECTION = (CUR_ALIAS . '/' . $layer->name);
                }
            }
        }

        MenuController::controller('load_menu_admin', array('array_filter' => $this->ARRAY_FILTER, 'cur_layer' => $cur_layer));

        Authentication::update_session_user();
    }*/
    
    public function getVarGriffo() {
        global $PAGE;
        return json_decode($PAGE->getGriffo());
    }

    public static function &singletonModule($class) {
        if (class_exists($class)) {
            foreach (self::$_instanceModules as &$value) {
                if ($value instanceof $class) {
                    return $value;
                }
            }
            self::$_instanceModules[] = $return = new $class();
            return $return;
        }
        return false;
    }

    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    final private function __clone() {

    }

    final private function __construct() {

        $this->DB = ActiveRecord\Config::instance();
        //$this->DB->set_cache("memcache://localhost");
        //$this->DB->set_cache("memcached://localhost");
        //$this->DB->set_cache("memcached://localhost",array("expire" => 60));
        //$this->DB->set_cache('memcache://localhost:11211',array('namespace' => 'my_cool_app','expire' => 120                                                    ));
        $this->CACHE = new Gilbitron\Util\SimpleCache();
        $this->CACHE->cache_path = DIR_CACHE;
        $this->CACHE->cache_time = TIME_CACHE;
        $this->BROWSER = new Browser();
        $this->PURIFIER = new HTMLPurifier(HTMLPurifier_Config::CreateDefault());
    }

}
?>
