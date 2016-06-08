<?php
    if(!file_exists('server/log')){
        mkdir('server/log',0755);
    }
    if(!file_exists('sitemaps')){
        mkdir('sitemaps',0755);
    }
    if(!file_exists('server/log/error.log')){
        fopen('server/log/error.log','w');
    }
    
    //var_dump(serialize(array(8,2,4,3,5,7,9,10,11,12,14,15)));exit;

    //ini_set('session.save_path',__DIR__.DIRECTORY_SEPARATOR.'server'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'session');
    //session_set_cookie_params(3600,__DIR__.DIRECTORY_SEPARATOR.'server'.DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'session');
    ini_set('session.use_trans_sid',false);
    session_start();
    ini_set("log_errors", 1);
    ini_set("error_log", "server/log/error.log");
    ini_set('default_charset','UTF-8');
    ini_set('session.use_only_cookies',true);
    //ini_set('session_cookie_lifetime',1200);
    setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
    date_default_timezone_set("America/Sao_Paulo");
    header('Content-Type:text/html;charset = UTF-8');

    if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300){
        die('É preciso ter uma versão 5.3 ou superior do PHP para o funcionamento do Griffo.');
    }

    //Se existir o arquivo config e ele não for vazio inicia-se a aplicação
    if((file_exists('client/config.json') && filesize('client/config.json')>0) && (file_exists('server/config.json') && filesize('server/config.json')>0)){

        define('INDEX_DIR', __DIR__);

        require_once 'server/ini.php';

        $APP->startApp();

    }else{
        header('location: install/index.php');
    }
?>
