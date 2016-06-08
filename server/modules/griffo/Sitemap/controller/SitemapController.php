<?php

class SitemapController {

    public static function controller($function = "", $attributes = array(), $id = 0, $json = false) {
        if (!empty($function)) {
            return self::$function($attributes, $id, $json);
        } else {
            return false;
        }
    }

    private static function get($attributes = array(), $id = 0, $json = false) {
        global $APP;
        try {                
                $APP->setDependencyModule(array('Module','Category','Page'));
                
                $modules = array();
                $find = false;
                $sql = (array_key_exists('module', $attributes) === false) ? "struct = 0" : "struct = 0 and name = '{$attributes['module']}'";
                $find = Module::find('all',array('conditions' => $sql));
                $fileindex = array();
                $file = 'sitemap.index.xml';

                //Obtem a url dos arquivos de sitemap dos módulos não estruturais

                foreach($find as $key => $value){
                    $APP->setDependencyModule(array(ucfirst($value->name)));
                    $module = ucfirst($value->name).'Controller';
                    if(method_exists($module,'sitemap')){
                        $return = $module::controller('sitemap');
                        if($return['status'] === 'success' && !empty($return['response'])){
                            $fileindex[] = $return['response'];
                        }
                    }
                }

                //Obtem a url do arquivo sitemap do módulo category
                
                if(method_exists('CategoryController','sitemap')){
                    $return = CategoryController::controller('sitemap');
                    
                    if($return['status'] === 'success' && !empty($return['response'])){
                        $fileindex[] = $return['response'];
                    }
                }

                //Obtem a url do arquivo sitemap do módulo page

                if(method_exists('PageController','sitemap')){
                    $return = PageController::controller('sitemap');
                    
                    if($return['status'] === 'success' && !empty($return['response'])){
                        $fileindex[] = $return['response'];
                    }
                }

                if(!empty($fileindex)){
                    if(file_exists(DIR.$file)){
                        unlink(DIR.$file);
                    }
                    
                    try{
                        $sitemap = new NilPortugues\Sitemap\IndexSitemap(DIR,$file);
                        
                        foreach($fileindex as $value){
                            if(!empty($value)){
                                $item = new NilPortugues\Sitemap\Item\Index\IndexItem($value);
                                $item->setLastMod(date(DATE_ATOM, mktime(date("h"), date("i"), date("s"), date("m"), date("d"), date("Y"))));
                                $sitemap->add($item);
                            }
                        }
                        
                        $sitemap->build();
                    }catch(NilPortugues\Sitemap\SitemapException $e){
                        $APP->writeLog($e);
                        return $APP->response(false, 'danger', $e->getMessage(), $json);
                    }    
                }

                return $APP->response(true, 'success', '', $json);
            }catch (\Exception $e) {
                $APP->writeLog($e);
                return $APP->response(false, 'danger', $e->getMessage(), $json);
            }
    }


}
?>
