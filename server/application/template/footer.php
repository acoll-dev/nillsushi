<?php
global $PAGE, $APP;

echo "<script src=\"" . PATH_LIBRARIES . "client/vendor/outdated-browser/outdatedbrowser/outdatedbrowser.js\"></script>";
echo "<script>function addLoadEvent(func) {var oldonload = window.onload;if (typeof window.onload != \"function\") { window.onload = func; } else {window.onload = function() {if (oldonload) {oldonload();}func();}}}addLoadEvent(function(){outdatedBrowser({bgColor: \"#f25648\", color: \"#ffffff\", lowerThan: \"transform\", languagePath: \"" . PATH_LIBRARIES . "client/outdatedbrowser/lang/br.html\"})}); </script>";

$PAGE->setTag(array("js" => array(
    PATH_LIBRARIES . "client/vendor/jquery/dist/jquery.js",
    PATH_LIBRARIES . "client/vendor/bootstrap/dist/js/bootstrap.js",
    PATH_LIBRARIES . "client/vendor/angular/angular.js",
    PATH_LIBRARIES . "client/vendor/angular-animate/angular-animate.js",
    PATH_LIBRARIES . "client/vendor/angular-cookies/angular-cookies.js",
    PATH_LIBRARIES . "client/vendor/angular-resource/angular-resource.js",
    // PATH_LIBRARIES . "client/vendor/angularjs/v1.4.7/angular-sanitize.min.js",
    PATH_LIBRARIES . "client/vendor/angular-bootstrap/ui-bootstrap-tpls.js",
    PATH_LIBRARIES . "client/vendor/angular-translate/angular-translate.js",
    PATH_LIBRARIES . "client/vendor/angular-translate-loader-static-files/angular-translate-loader-static-files.js",
    PATH_LIBRARIES . "client/vendor/angular-translate-storage-cookie/angular-translate-storage-cookie.js",
    PATH_LIBRARIES . "client/vendor/ngstorage/ngStorage.js",
    PATH_LIBRARIES . "client/ng-print/ng-print.js",
    PATH_LIBRARIES . "client/vendor/imagesloaded/imagesloaded.pkgd.js",
    PATH_LIBRARIES . "client/vendor/angular-images-loaded/angular-images-loaded.js",
    PATH_LIBRARIES . "client/vendor/file-saver/FileSaver.js",
    PATH_LIBRARIES . "client/vendor/jszip/dist/jszip.js",
    PATH_LIBRARIES . "client/vendor/ui-select/dist/select.js",
    PATH_LIBRARIES . "client/vendor/angular-file-upload/dist/angular-file-upload.js",
    PATH_LIBRARIES . 'client/vendor/lodash/lodash.js',
    PATH_LIBRARIES . "client/cidade-estado/cidade-estado.js",
    PATH_LIBRARIES . "client/restful.js",
    PATH_LIBRARIES . "client/gr-filemanager/js/gr-filemanager.js",
    PATH_LIBRARIES . "client/gr-ui/src/js/gr-ui.js",

    /* CODEMIRROR (CODE EDITOR) */

    PATH_LIBRARIES . "client/codemirror/lib/codemirror.js",
    PATH_LIBRARIES . "client/codemirror/addon/edit/closetag.js",
    PATH_LIBRARIES . "client/codemirror/addon/edit/matchtags.js",
    PATH_LIBRARIES . "client/codemirror/addon/edit/closebrackets.js",
    PATH_LIBRARIES . "client/codemirror/addon/edit/matchbrackets.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/foldcode.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/foldgutter.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/brace-fold.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/xml-fold.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/markdown-fold.js",
    PATH_LIBRARIES . "client/codemirror/addon/fold/comment-fold.js",
    PATH_LIBRARIES . "client/codemirror/addon/display/fullscreen.js",
    PATH_LIBRARIES . "client/codemirror/mode/xml/xml.js",
    PATH_LIBRARIES . "client/codemirror/mode/css/css.js",
    PATH_LIBRARIES . "client/codemirror/mode/javascript/javascript.js",
    PATH_LIBRARIES . "client/codemirror/mode/htmlmixed/htmlmixed.js",
    PATH_LIBRARIES . "client/vendor/angular-ui-codemirror/ui-codemirror.js",

    /* TEXTANGULAR (GR-AUTOFIELDS) */

    PATH_LIBRARIES . "client/textAngular/dist/textAngularSetup.js",
    PATH_LIBRARIES . "client/textAngular/dist/textAngular-rangy.min.js",
    PATH_LIBRARIES . "client/textAngular/dist/textAngular-sanitize.min.js",
    PATH_LIBRARIES . "client/textAngular/dist/textAngular.umd.js",

    PATH_LIBRARIES . "client/vendor/moment/moment.js",

    PATH_LIBRARIES . "client/vendor/requirejs/require.js"
)),"footer", "first");

$PAGE->setTag(array("js" => array(PATH_LIBRARIES . "client/griffo.js")), "footer");

$PAGE->setTag(array("css" => $PAGE->arrayPageCss), "footer");

$PAGE->setTag(array("js" => $PAGE->arrayPageJs), "footer");

$tags = $PAGE->getTag();

$checkjs = false;
$checkcss = false;
$cssMin = "";
$jsMin = "";

foreach($tags as $key => $value){
    if($key === "footer"){
        if(DEBUG === false){
            foreach($value as $key2 => $value2){
                if($key2 === "js" && count($value2) > 0){
                    $checkjs = true;
                    foreach($value2 as $key3 => $value3){
                        if(strpos($value3,"https://") !== false || strpos($value3,"http://") !== false){
                            echo"<script src=\"" . $value3 . "\"></script>";
                        }else{
                            $jsMin .= BASE_COMPLEMENT . $value3.",";
                        }
                    }
                }
                if($key2 === "css" && count($value2) > 0){
                    $checkcss = true;
                    foreach($value2 as $key3 => $value3){
                        if(strpos($value3,"https://") !== false || strpos($value3,"http://") !== false){
                            echo "<link rel=\"stylesheet\" href=\"" . $value3 . "\" />";
                        }else{
                            $cssMin .= BASE_COMPLEMENT . $value3.",";
                        }
                    }
                }
            }
            if($checkjs === true && $jsMin !== ""){
                echo"<script src=\"min/?f=" . rtrim($jsMin,",") . "\"></script>";
            }
            if($checkcss === true && $cssMin !== ""){
                echo "<link rel=\"stylesheet\" href=\"min/?f=" . rtrim($cssMin,",") . "\" />";
            }
            break;
        }else{
            foreach($value as $key2 => $value2){
                if($key2 === "js" && count($value2) > 0){
                    foreach($value2 as $key3 => $value3){
                        echo"<script src=\"" . $value3 . "\"></script>";
                    }
                }
                if($key2 === "css" && count($value2) > 0){
                    foreach($value2 as $key3 => $value3){
                        echo "<link rel=\"stylesheet\" href=\"" . $value3 . "\" />";
                    }
                }
            }
            break;
        }
    }
}

if(!empty($APP->LANGUAGE)){
    echo "<script src=\"" . PATH_LIBRARIES . "client/vendor/angular-i18n/angular-locale_" . $APP->LANGUAGE . ".js\"></script>";
}

if(file_exists(DIR_MODULE . $APP->MODULE->path . "view/" . (LAYER === "admin" ? "admin" : "default") . "/js/" . $APP->VIEW . ".js") && isset($_SESSION[CLIENT.LAYER])){
    echo "<script src=\"" . URL_MODULE_PATH . $APP->MODULE->path . "view/" . (LAYER === "admin" ? "admin" : "default") . "/js/" . $APP->VIEW . ".js\"></script>";
}

if(!empty($ga)){
    echo "<script>(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+newDate;e=o.createElement(i);r=o.getElementsByTagName(i)[0];e.src=\"//www.google-analytics.com/analytics.js\";r.parentNode.insertBefore(e,r)}(window,document,\"script\",\"ga\"));ga(\"create\",\"".$ga."\");ga(\"send\",\"pageview\");</script>";
}

echo"</body></html>";
?>
