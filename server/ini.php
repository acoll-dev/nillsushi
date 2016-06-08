<?php
        
        if(!file_exists('client/config.json')){
            echo '<strong>FATAL ERROR!</strong>';
            exit;
        };

        $CONFIG = json_decode(file_get_contents('client/config.json'), true);

        define('ALIAS', serialize($CONFIG['alias']));
        define('DB_BASE', $CONFIG['database']['base']);
        define('DB_HOST', $CONFIG['database']['host']);
        define('DB_USER', $CONFIG['database']['user']);
        define('DB_PASS', $CONFIG['database']['password']);
        define('DB_DATABASE', $CONFIG['database']['name']);
        define('DB_PREFIX', $CONFIG['database']['table_prefix']);
        define('CLIENT', $CONFIG['client']['name']);
        define('CLIENT_WEBSITE', $CONFIG['client']['website']);
        define('THUMB_WIDTH',$CONFIG['thumb']['width']);
        define('THUMB_HEIGHT',$CONFIG['thumb']['height']);
        define('COMPRESS_IMAGE',$CONFIG['compress']);

        if(!file_exists('server/config.json')){
            echo '<strong>FATAL ERROR!</strong>';
            exit;
        }
            
        $CONFIG = json_decode(file_get_contents('server/config.json'), true);

        if($CONFIG['log']['send_email']['enable']){
            define('LOG_EMAIL', serialize($CONFIG['log']['send_email']));
        }else{
            define('LOG_EMAIL', NULL);
        }

        define('SESSION_NAME', $CONFIG['session_name']);
        define('DEBUG', $CONFIG['debug']);
        define('ENVIRONMENT', $CONFIG['environment']);
        define('ID_PROFILE_MASTER', $CONFIG['id_profile_master']);
                        
        define('DS', DIRECTORY_SEPARATOR);

        define('DIR', dirname(__DIR__) . DS);
        define('DIR_APPLICATION', DIR . 'server'.DS.'application' . DS);
        define('DIR_LIBRARIES', DIR . 'server'.DS.'libraries' . DS);
        define('DIR_BIN', DIR . 'server' . DS . 'bin' . DS);
        define('DIR_UPLOADS', DIR . 'client'.DS.'uploads');
        define('DIR_SITEMAP_PATH', 'sitemaps');
        define('DIR_SITEMAP', DIR . DIR_SITEMAP_PATH);
                
        //CACHE CONFIG
        define('CACHE', $CONFIG['cache']);
        define('DIR_CACHE', DIR . 'server'.DS.'tmp'.DS.'cache'.DS);
        define('TIME_CACHE',3600);
        /** Precisa? **/

//        define('DIR_UPLOADIMAGENS', DIR . 'client/uploads' . DS . 'images' . DS);
//        define('DIR_UPLOADAUDIO', DIR . 'client/uploads' . DS . 'audio' . DS);

        /** * **/

        define('DIR_MODULE_PATH', 'server'.DS.'modules' . DS);
        define('DIR_MODULE', DIR . DIR_MODULE_PATH);
        define('DIR_MODULE_COMP', DIR . DIR_MODULE_PATH . 'complementary' . DS);
        define('DIR_LAYER', DIR . 'client'.DS.'layers' . DS);
        define('DIR_LOG', DIR . 'server'.DS.'log' . DS);
        define('DIR_TMP', DIR . 'server'.DS.'tmp' . DS);

        define('URL_LAYER_PATH', 'client/layers/');
        define('URL_APPLICATION_PATH', 'server/application/');    
        define('URL_MODULE_PATH', 'server/modules/');
        define('URL_LIBRARIES_PATH', 'server/libraries/');
        define('URL_UPLOADS_PATH', 'client/uploads/');

        define('PROTOCOL', (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http://' : 'https://');
        define('PORT', ($_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : '');
        define('THIS', PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        define('URI', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
        define('IP', getenv("REMOTE_ADDR"));
        define('QUERY_STRING', $_SERVER['QUERY_STRING']);

        require_once(DIR_APPLICATION.'core'.DS.'Application.php');
        require_once(DIR_APPLICATION.'core'.DS.'Browser.php');
        require_once(DIR_APPLICATION.'core'.DS.'Pager.php');
        require_once(DIR_APPLICATION.'core'.DS.'Router.php');
        require_once(DIR_APPLICATION.'core'.DS.'Tools.php');
        require_once(DIR_APPLICATION.'core'.DS.'Rest.php');
        require_once(DIR_LIBRARIES.'autoload.php');

        error_reporting(0);

        if(CACHE){
            //desabilita armazenamento de cache
            
            //Causando erro no console do chrome,  erro: Failed to load resource: net::ERR_CACHE_MISS
             
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            
        }

        if(DEBUG){
            $whoops = new Whoops\Run();
            $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());
            $whoops->register();
            error_reporting(-1);
        }

    $PAGE = Pager::getInstance();
    $APP  = Application::getInstance();
    
    define('SYSTEM', serialize(array(
        'version' => $CONFIG['version'],
        'php' => phpversion(),
        //'mysql' => mysql_get_server_info() ? mysql_get_server_info() : 'undefined'
        'mysql' => PDO::ATTR_SERVER_INFO
    )));

?>
