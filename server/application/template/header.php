<?php

global $PAGE,$APP;

$htmlAttrs = "";
$bodyAttrs = "";
$title = "";
$meta = "";
$PAGE->setTag(array("title" => $APP->TITLE));

foreach($PAGE->getTag() as $key => $value){
    if($key === "header"){
        foreach($value as $key2 => $value2){
            if($key2 === "htmlAttr" && count($value2) > 0){
                foreach($value2 as $key3 => $value3){
                    $htmlAttrs .= $key3 . "=\"" . $value3 . "\" ";
                }
            }
            if($key2 === "bodyAttr" && count($value2) > 0){
                foreach($value2 as $key3 => $value3){
                    $bodyAttrs .= $key3 . "=\"" . $value3 . "\" ";
                }
            }
            if($key2 === "title"){
                $title = $value2;
            }
            if($key2 === "meta" && count($value2) > 0){
                //$meta .= "<meta ";
                    foreach($value2 as $key3 => $value3) {
                        $meta .= "<meta name = \"" . $key3 . "\" content=\"" . $value3 . "\" />";
                    }
                //$meta .= " />";
            }
        }
    }
    break;
}

echo "<!DOCTYPE html>";
echo "<html class=\"no-js\" {$htmlAttrs}>";
echo "<head>";
echo "<meta charset=\"utf-8\">";


if(!empty($PAGE->METATAG_DESCRIPTION)){
    echo (LAYER === "admin") ? "<title>Griffo | Admin Panel</title>" : "<title>".$title." | ".$PAGE->METATAG_DESCRIPTION."</title>" ;
    echo "<meta name=\"description\" content=\"{$PAGE->METATAG_DESCRIPTION}\" >";
}else{
    echo (LAYER === "admin") ? "<title>Griffo | Admin Panel</title>" : "<title>{$title}</title>" ;
}

if(!empty($PAGE->METATAG_KEYWORDS)){
    echo "<meta name=\"keywords\" content=\"{$PAGE->METATAG_KEYWORDS}\" >";
}

echo $meta;

echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" />";
echo "<meta name=\"fragment\" content=\"!\" />";
echo "<meta name=\"SKYPE_TOOLBAR\" content=\"SKYPE_TOOLBAR_PARSER_COMPATIBLE\" />";
echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />";
echo "<base href=\"" . BASE_COMPLEMENT . "\" />";
if(file_exists(DIR . PATH_TEMPLATE . "favicon.png")){
    echo "<link rel=\"shortcut icon\" href=\"" . PATH_TEMPLATE . "favicon.png\" />";
}
elseif(file_exists(DIR . PATH_TEMPLATE . "favicon.ico")){
    echo "<link rel=\"shortcut icon\" href=\"" . PATH_TEMPLATE . "favicon.ico\" />";
}

$PAGE->setTag(array("css" => array(
    PATH_LIBRARIES . "client/vendor/font-awesome/css/font-awesome.css",
    PATH_LIBRARIES . "client/vendor/outdated-browser/outdatedbrowser/outdatedbrowser.css",
    PATH_LIBRARIES . "client/vendor/ui-select/dist/select.css",
    PATH_LIBRARIES . "client/gr-filemanager/css/gr-filemanager.css",
    PATH_LIBRARIES . "client/codemirror/lib/codemirror.css",
    PATH_LIBRARIES . "client/codemirror/addon/fold/foldgutter.css",
    PATH_LIBRARIES . "client/codemirror/addon/display/fullscreen.css",
    PATH_LIBRARIES . "client/codemirror/theme/material.css",
    PATH_LIBRARIES . "client/textAngular/dist/textAngular.css",
    PATH_LIBRARIES . "client/acoll/icons/css/acoll.css"
)),"header", "first");

$PAGE->setTag(array("css" => array(
    PATH_LIBRARIES . "client/gr-ui/src/css/gr-ui.css"
)));

$tags = $PAGE->getTag();
$cssMin = "";

foreach($tags as $key => $value){
    if($key === "header"){
        if(DEBUG === false){
            foreach($value as $key2 => $value2){
                if($key2 === "css"){
                    foreach($value2 as $key3 => $value3){
                        if(strpos($value3,"https://") !== false || strpos($value3,"http://") !== false){
                            echo "<link rel=\"stylesheet\" href=\"{$value3}\" />";
                        }else{
                            $cssMin .= BASE_COMPLEMENT . $value3.",";
                        }
                    }
                }
            }
            echo "<link rel=\"stylesheet\" href=\"min/?f=" . rtrim($cssMin,",") . "\" />";
            break;
        }else{
            foreach($value as $key2 => $value2){
                if($key2 === "css"){
                    foreach($value2 as $key3 => $value3){
                        echo "<link rel=\"stylesheet\" href=\"{$value3}\" />";
                    }
                }
            }
            break;
        }
    }
}

if(file_exists(DIR_MODULE . $APP->MODULE->path . "view/" . (LAYER === "admin" ? "admin" : "default") . "/css/" . $APP->VIEW . ".css")){
    echo "<link rel=\"stylesheet\" href=\"" . URL_MODULE_PATH . $APP->MODULE->path . "view/" . (LAYER === "admin" ? "admin" : "default") . "/css/" . $APP->VIEW . ".css\" />";
}

echo "<script>this.GRIFFO = {$PAGE->getGriffo()};</script>";

$PAGE->setTag(array("js" => array(
    PATH_LIBRARIES . "client/modernizr/modernizr-2.6.2-respond-1.1.0.min.js"
)));

$jsMin = "";
$tags = $PAGE->getTag();

foreach($tags as $key => $value){
    if($key === "header"){
        if(DEBUG === false){
            foreach($value as $key2 => $value2){
                if($key2 === "js"){
                    foreach($value2 as $key3 => $value3){
                        if(strpos($value3,"https://") !== false || strpos($value3,"http://") !== false){
                            echo"<script src=\"{$value3}\"></script>";
                        }else{
                            $jsMin .= BASE_COMPLEMENT . $value3.",";
                        }
                    }
                }
            }
            echo"<script src=\"min/?f=" . rtrim($jsMin,",") . "\"></script>";
            break;
        }else{
            foreach($value as $key2 => $value2){
                if($key2 === "js"){
                    foreach($value2 as $key3 => $value3){
                        echo"<script src=\"{$value3}\"></script>";
                    }
                }
            }
            break;
        }
    }
}

echo "<style type=\"text/css\">[ng\:cloak],[ng-cloak],[data-ng-cloak],[x-ng-cloak],.ng-cloak,.x-ng-cloak{display: none !important;}</style>";

echo "</head><body {$bodyAttrs} ng-cloak><div id=\"outdated\"></div>";
?>
