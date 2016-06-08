<?php

class Pager {

    public $METATAG_DESCRIPTION;
    public $METATAG_KEYWORDS;
    public $arrayPageCss = array();
    public $arrayPageJs = array();
    
    private $arrayHeaderCss = array();
    private $arrayHeaderJs = array();
    private $arrayFooterCss = array();
    private $arrayFooterJs = array();
    private $arrayMeta = array();
    private $arrayHtmlAttrs = array();
    private $arrayBodyAttrs = array();
    private $analyticsCode;
    private $griffo;
    private $title;
    
    private static $_instance;

    public function getGriffo() {
        return json_encode($this->griffo);
    }

    public function setGriffo($griffo) {
        $this->griffo = $griffo;
    }

    public function setTag($array, $localization = 'header', $position = 'last') {

        if (is_array($array)) {

            foreach ($array as $key => $value) {
                switch ($key) {

                    case 'css': {

                            if ($localization === 'header') {
                                $this->arrayHeaderCss = Tools::set_position_array($this->arrayHeaderCss, $value, $position);
                            } else {
                                $this->arrayFooterCss = Tools::set_position_array($this->arrayFooterCss, $value, $position);
                            }

                            break;
                        }
                    case 'js': {

                            if ($localization === 'header') {
                                $this->arrayHeaderJs = Tools::set_position_array($this->arrayHeaderJs, $value, $position);
                            } else {
                                $this->arrayFooterJs = Tools::set_position_array($this->arrayFooterJs, $value, $position);
                            }

                            break;
                        }
                    case 'meta': {

                            $this->arrayMeta = Tools::set_position_array($this->arrayMeta, $value, $position);

                            break;
                        }
                    case 'bodyAttr': {

                            $this->arrayBodyAttrs = Tools::set_position_array($this->arrayBodyAttrs, $value, $position);

                            break;
                        }
                    case 'htmlAttr': {

                            $this->arrayHtmlAttrs = Tools::set_position_array($this->arrayHtmlAttrs, $value, $position);
                            break;
                        }
                    case 'title': {

                            $this->title = $value;

                            break;
                        }
                    case 'analyticsCode': {

                            $this->analyticsCode = $value;

                            break;
                        }
                }
            }
        }
    }

    public function getTag() {

        $return = array(
            'header' => array(
                'css' => $this->arrayHeaderCss,
                'js' => $this->arrayHeaderJs,
                'meta' => $this->arrayMeta,
                'bodyAttr' => $this->arrayBodyAttrs,
                'htmlAttr' => $this->arrayHtmlAttrs,
                'title' => $this->title
            ),
            'footer' => array(
                'css' => $this->arrayFooterCss,
                'js' => $this->arrayFooterJs,
                'analyticsCode' => $this->analyticsCode
            )
        );

        return $return;
    }

    public function getHeader() {
        return require_once(DIR_APPLICATION . 'template' . DS . 'header.php');
    }

    public function getFooter() {
        return require_once(DIR_APPLICATION . 'template' . DS . 'footer.php');
    }

    public function minify() {
        //Definir padrões de configuração do caminho
        $min_configPaths = array(
            'base' => MINIFY_MIN_DIR . '/config.php',
            'test' => MINIFY_MIN_DIR . '/config-test.php',
            'groups' => MINIFY_MIN_DIR . '/groupsConfig.php'
        );

        //Verifique se os caminhos de configuração é personalizado
        if (!empty($min_customConfigPaths) && is_array($min_customConfigPaths)) {
            $min_configPaths = array_merge($min_configPaths, $min_customConfigPaths);
        }

        //Carrega configuração
        require $min_configPaths['base'];

        if (isset($_GET['test'])) {
            include $min_configPaths['test'];
        }

        Minify_Loader::register();

        Minify::$uploaderHoursBehind = $min_uploaderHoursBehind;
        Minify::setCache(
                isset($min_cachePath) ? $min_cachePath : ''
                , $min_cacheFileLocking
        );

        if ($min_documentRoot) {
            $_SERVER['DOCUMENT_ROOT'] = $min_documentRoot;
            Minify::$isDocRootSet = true;
        }

        $min_serveOptions['minifierOptions']['text/css']['symlinks'] = $min_symlinks;
        //Adiciona automático as metas de allowDirs
        foreach ($min_symlinks as $uri => $target) {
            $min_serveOptions['minApp']['allowDirs'][] = $target;
        }

        if ($min_allowDebugFlag) {
            $min_serveOptions['debug'] = Minify_DebugDetector::shouldDebugRequest($_COOKIE, $_GET, $_SERVER['REQUEST_URI']);
        }

        if ($min_errorLogger) {
            if (true === $min_errorLogger) {
                $min_errorLogger = FirePHP::getInstance(true);
            }
            Minify_Logger::setLogger($min_errorLogger);
        }

        //Verifica se existe URI de versionamento
        if (preg_match('/&\\d/', $_SERVER['QUERY_STRING']) || isset($_GET['v'])) {
            $min_serveOptions['maxAge'] = 31536000;
        }

        //Precisa de grupos de configuração?
        if (isset($_GET['g'])) {
            //precisa de grupos de configuração
            $min_serveOptions['minApp']['groups'] = (require $min_configPaths['groups']);
        }

        //Serve ou redireciona
        if (isset($_GET['f']) || isset($_GET['g'])) {
            if (!isset($min_serveController)) {
                $min_serveController = new Minify_Controller_MinApp();
            }

            Minify::serve($min_serveController, $min_serveOptions);
        }
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
        
    }

}

?>
